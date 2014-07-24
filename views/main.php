<section class="panel">
    <div class="panel-heading">
        <?php echo lang('users_management:title') ?>
        <a href="<?php echo site_url('users_management/create') ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Crear Usuario</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><?php echo lang('users_management:user_name') ?></th>
                                <th><?php echo lang('users_management:user_email') ?></th>
                                <th><?php echo lang('users_management:user_group') ?></th>
                                <th><?php echo lang('users_management:user_active') ?></th>
                                <th><?php echo lang('users_management:user_actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user->id ?></td>
                                    <td><?php echo $user->display_name ?></td>
                                    <td><?php echo $user->email ?></td>
                                    <td><?php echo $user->group_name ?></td>
                                    <td><?php echo ($user->active === "1") ? lang('users_management:active') : lang('users_management:not_active') ?></td>
                                    <td>
                                        <a href="<?php echo site_url('users_management/edit/'.$user->id) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo site_url('users_management/delete/'.$user->id) ?>" class="btn btn-danger delete-confirm"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="DeleteUser" tabindex="-1" role="dialog" aria-labelledby="Eliminar Usuario" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Usuario</h4>
      </div>
      <div class="modal-body">
          Esta seguro que desea eliminar este usuario?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteUser" data-url="0" data-loafing-text="Cargando">Eliminar</button>
      </div>
    </div>
  </div>
</div>