<section>
    <div class="section-header">
        
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-8">
                <?php if ($this->method === 'create'): ?>
                    <?php echo form_open_multipart(uri_string(), 'class="crud" class="form-horizontal" autocomplete="off"') ?>
                <?php else: ?>
                    <?php echo form_open_multipart(uri_string(), 'class="crud form-horizontal"') ?>
                    <?php echo form_hidden('row_edit_id', isset($member->row_edit_id) ? $member->row_edit_id : $member->profile_id); ?>
                <?php endif ?>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="email" class="control-label"><?php echo lang('global:email') ?> <span>*</span></label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php echo form_input('email', $member->email, 'id="email" class="form-control" placeholder="Email"') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="username" class="control-label"><?php echo lang('user:username') ?> <span>*</span></label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php echo form_input('username', $member->username, 'id="username" class="form-control" placeholder="Email"') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="display_name" class="control-label"><?php echo lang('profile_display_name') ?></label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php echo form_input('display_name', $display_name, 'id="display_name" class="form-control"') ?>
                    </div>
                </div>
                <hr>
                <?php foreach ($profile_fields as $field): ?>
                    <div class="form-group">
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <label for="<?php echo $field['field_slug'] ?>">
                                <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name']; ?>
                                <?php if ($field['required']) { ?> <span>*</span><?php } ?>
                            </label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-9">
                            <?php echo $field['input'] ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <hr>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="group_id" class="control-label"><?php echo lang('user:group_label') ?> <span>*</span></label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php echo form_dropdown('group_id', array(0 => lang('global:select-pick')) + $groups_select, $member->group_id, 'id="group_id" class="form-control"') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="active" class="control-label"><?php echo lang('user:activate_label') ?> <span>*</span></label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php $options = array(0 => lang('user:do_not_activate'), 1 => lang('user:active'), 2 => lang('user:send_activation_email')) ?>
                        <?php echo form_dropdown('active', $options, $member->active, 'id="active" class="form-control"') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <label for="password" class="control-label"><?php echo lang('global:password') ?> 
                            <?php if ($this->method == 'create'): ?> <span>*</span><?php endif ?>
                        </label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                        <?php echo form_password('password', '', 'id="password" autocomplete="off" class="form-control"') ?>
                    </div>
                </div>
                <div class="form-footer col-lg-offset-1 col-md-offset-2 col-sm-offset-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="col-lg-4">
                <div class="alert alert-info">
                    <h2><i class="fa fa-warning"></i> Importante!</h2>
                    <p>Por favor ingrese todos los datos del formulario</p>
                </div>
            </div>
        </div>
    </div>
</section>