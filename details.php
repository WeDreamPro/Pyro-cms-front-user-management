<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Front User Management
 *
 * @author We Dream Pro, Jose Luis Fonseca
 */
class Module_users_management extends Module {

    public $version = '1.0.0';

    public function info() {
        return array(
            'name' => array(
                'en' => 'Users Front Management',
                'es' => 'Administrador de usuarios para el Front',
            ),
            'description' => array(
                'en' => 'Manage users in the frontend',
                'es' => 'Administrador de usuarios en el frontend',
            ),
            'frontend' => TRUE,
            'backend' => FALSE
        );
    }

    public function install() {
        $q = $this->db->where('name', 'site-admin-front')->get('groups');
        if ($q->num_rows() == 0) {
            $this->db->insert('groups', array(
                'name' => 'site-admin-front',
                'description' => 'Administrador de usuarios front'
            ));
        }
        return true;
    }

    public function uninstall() {
        return true;
    }

    public function upgrade($old_version) {
        return true;
    }

}
