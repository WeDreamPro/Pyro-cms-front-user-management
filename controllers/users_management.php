<?php

/**
 * Description of users_management
 *
 * @author desarrollo
 */
class users_management extends Public_Controller {

    /**
     * Validation for basic profile
     * data. The rest of the validation is
     * built by streams.
     *
     * @var array
     */
    private $validation_rules = array(
        'email' => array(
            'field' => 'email',
            'label' => 'lang:global:email',
            'rules' => 'required|max_length[60]|valid_email'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'lang:global:password',
            'rules' => 'min_length[6]|max_length[20]'
        ),
        'username' => array(
            'field' => 'username',
            'label' => 'lang:user_username',
            'rules' => 'required|alpha_dot_dash|min_length[3]|max_length[20]'
        ),
        array(
            'field' => 'group_id',
            'label' => 'lang:user_group_label',
            'rules' => 'required|callback__group_check'
        ),
        array(
            'field' => 'active',
            'label' => 'lang:user_active_label',
            'rules' => ''
        ),
        array(
            'field' => 'display_name',
            'label' => 'lang:profile_display_name',
            'rules' => 'required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->lang->load('users_management');
        $this->load->model('users/user_m');
        $this->load->model('users/profile_m');
        $this->load->model('groups/group_m');
        $this->load->helper('user');
        $this->load->library('form_validation');
        $this->lang->load('user');

        if ($this->current_user->group != 'admin') {
            $this->template->groups = $this->group_m->where_not_in('name', 'admin')->get_all();
        } else {
            $this->template->groups = $this->group_m->get_all();
        }

        $this->template->groups_select = array_for_select($this->template->groups, 'id', 'description');
        $this->template->append_js('module::users_management.js', null, 'modules');
    }

    public function index() {
        /** get users * */
        $users = $this->user_m->get_all();
        $this->template->set('users', $users);
        $this->template->build('main');
    }

    public function edit($id) {
        if (empty($id)) {
            show_404();
        }
        if (!($member = $this->ion_auth->get_user($id))) {
            $this->session->set_flashdata('error', lang('user:edit_user_not_found_error'));
            redirect('users_management');
        }
        // Check to see if we are changing usernames
        if ($member->username != $this->input->post('username')) {
            $this->validation_rules['username']['rules'] .= '|callback__username_check';
        }
        // Check to see if we are changing emails
        if ($member->email != $this->input->post('email')) {
            $this->validation_rules['email']['rules'] .= '|callback__email_check';
        }
        // Get the profile fields validation array from streams
        $this->load->driver('Streams');
        $profile_validation = $this->streams->streams->validation_array('profiles', 'users', 'edit', array(), $id);
        // Set the validation rules
        $this->form_validation->set_rules(array_merge($this->validation_rules, $profile_validation));
        // Get user profile data. This will be passed to our
        // streams insert_entry data in the model.
        $assignments = $this->streams->streams->get_assignments('profiles', 'users');
        $profile_data = array();
        foreach ($assignments as $assign) {
            if (isset($_POST[$assign->field_slug])) {
                $profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
            } elseif (isset($member->{$assign->field_slug})) {
                $profile_data[$assign->field_slug] = $member->{$assign->field_slug};
            }
        }
        if ($this->form_validation->run() === true) {
            // Get the POST data
            $update_data['email'] = $this->input->post('email');
            $update_data['active'] = $this->input->post('active');
            $update_data['username'] = $this->input->post('username');
            // allow them to update their one group but keep users with user editing privileges from escalating their accounts to admin
            $update_data['group_id'] = ($this->current_user->group !== 'admin' and $this->input->post('group_id') == 1) ? $member->group_id : $this->input->post('group_id');
            if ($update_data['active'] === '2') {
                $this->ion_auth->activation_email($id);
                unset($update_data['active']);
            } else {
                $update_data['active'] = (bool) $update_data['active'];
            }
            $profile_data = array();
            // Grab the profile data
            foreach ($assignments as $assign) {
                $profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
            }
            // Some stream fields need $_POST as well.
            $profile_data = array_merge($profile_data, $_POST);
            // We need to manually do display_name
            $profile_data['display_name'] = $this->input->post('display_name');
            // Password provided, hash it for storage
            if ($this->input->post('password')) {
                $update_data['password'] = $this->input->post('password');
            }
            if ($this->ion_auth->update_user($id, $update_data, $profile_data)) {
                // Fire an event. A user has been updated. 
                Events::trigger('user_updated', $id);
                $this->session->set_flashdata('success', $this->ion_auth->messages());
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
            }
            // Redirect back to the form or main page
            $this->input->post('btnAction') === 'save_exit' ? redirect('users_management') : redirect('users_management/edit/' . $id);
        } else {
            // Dirty hack that fixes the issue of having to re-add all data upon an error
            if ($_POST) {
                $member = (object) $_POST;
            }
        }
        // Loop through each validation rule
        foreach ($this->validation_rules as $rule) {
            if ($this->input->post($rule['field']) !== null) {
                $member->{$rule['field']} = set_value($rule['field']);
            }
        }
        // We need the profile ID to pass to get_stream_fields.
        // This theoretically could be different from the actual user id.
        if ($id) {
            $profile_id = $this->db->limit(1)->select('id')->where('user_id', $id)->get('profiles')->row()->id;
        } else {
            $profile_id = null;
        }
        $stream_fields = $this->streams_m->get_stream_fields($this->streams_m->get_stream_id_from_slug('profiles', 'users'));
        $profile = $this->db->limit(1)->where('user_id', $id)->get('profiles')->row();
        // Set Values
        $values = $this->fields->set_values($stream_fields, $profile, 'edit');
        // Run stream field events
        $this->fields->run_field_events($stream_fields, array(), $values);
        $this->template
                ->title($this->module_details['name'], sprintf(lang('user:edit_title'), $member->username))
                ->set('display_name', $member->display_name)
                ->set('profile_fields', $this->streams->fields->get_stream_fields('profiles', 'users', $values, $profile_id))
                ->set('member', $member)
                ->build('form');
    }

    /**
     * Username check
     *
     * @author Ben Edmunds
     *
     * @param string $username The username.
     *
     * @return bool
     */
    public function _username_check() {
        if ($this->ion_auth->username_check($this->input->post('username'))) {
            $this->form_validation->set_message('_username_check', lang('user:error_username'));
            return false;
        }
        return true;
    }

    /**
     * Email check
     *
     * @author Ben Edmunds
     *
     * @param string $email The email.
     *
     * @return bool
     */
    public function _email_check() {
        if ($this->ion_auth->email_check($this->input->post('email'))) {
            $this->form_validation->set_message('_email_check', lang('user:error_email'));
            return false;
        }

        return true;
    }

    /**
     * Check that a proper group has been selected
     *
     * @author Stephen Cozart
     *
     * @param int $group
     *
     * @return bool
     */
    public function _group_check($group) {
        if (!$this->group_m->get($group)) {
            $this->form_validation->set_message('_group_check', lang('regex_match'));
            return false;
        }
        return true;
    }

}
