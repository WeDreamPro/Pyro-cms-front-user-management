<?php

/**
 * Description of Menu_Users_management
 *
 * @author desarrollo
 */
class Menu_Users_management {

    public function setMenu() {
        return [
            'sidebar' => [
                'Users' => [
                    'link' => [
                        'link' => '#',
                        'text' => '<i class="fa fa-fw fa-user"></i> Usuarios',
                    ],
                    'permissions' => ['_EditarPerfiles'],
                    'submenus' => [
                        'Admin' => [
                            'link' => [
                                'link' => "#",
                                'text' => 'Administrador',
                            ],
                            'permissions' => ['_EditarPerfiles'],
                            'submenus' => [
                                'AdminUsers' => [
                                    'link' => [
                                        'link' => site_url('users_management'),
                                        'text' => 'Administrar Usuarios',
                                    ],
                                    'permissions' => ['_EditarPerfiles']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'user' => [
                'Profile' => [
                    'link' => [
                        'link' => site_url('me/edit'),
                        'text' => '<i class="fa fa-fw fa-user"></i> Editar Perfil'
                    ],
                    'permissions' => ['_EditarPerfilPropio']
                ]
            ]
        ];
    }

}
