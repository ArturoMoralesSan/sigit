<?php

namespace Database\Seeders;

use App\Models\Permission;
use DB;

class DashboardTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate(['dashboard_sections', 'dashboard_submenus', 'dashboard_links']);

        $permissions = Permission::all('id', 'key_name')->pluck('id', 'key_name');

        foreach ($this->getData() as $i => $section) {
            $sectionId = DB::table('dashboard_sections')->insertGetId([
                'name'       => $section['name'],
                'tile'       => $section['tile'],
                'order'      => $i + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            foreach ($section['submenus'] as $j => $submenu) {
                $submenuId = DB::table('dashboard_submenus')->insertGetId([
                    'name'       => $submenu['name'],
                    'icon'       => $submenu['icon'],
                    'order'      => $j + 1,
                    'section_id' => $sectionId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                foreach ($submenu['links'] as $k => $link) {
                    DB::table('dashboard_links')->insert([
                        'name'          => $link['name'],
                        'route'         => $link['route'],
                        'order'         => $k + 1,
                        'submenu_id'    => $submenuId,
                        'permission_id' => $permissions[$link['permission']],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }



    /**
     * Return the data to populate tables.
     *
     * @return array
     */
    private function getData()
    {
        return [
            [
                'name' => 'ACL',
                'tile' => 'lock.svg',
                'submenus' => [
                    [
                        'name' => 'Permisos',
                        'icon' => 'permissions.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar permisos',
                                'route'      => 'agregar-permisos',
                                'permission' => 'create.permissions'
                            ],
                            [
                                'name'       => 'Lista de permisos',
                                'route'      => 'permisos',
                                'permission' => 'view.permissions'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Roles',
                        'icon' => 'role.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar roles',
                                'route'      => 'agregar-roles',
                                'permission' => 'create.roles'
                            ],
                            [
                                'name'       => 'Lista de roles',
                                'route'      => 'roles',
                                'permission' => 'view.roles'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Usuarios',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Lista de usuarios',
                                'route'      => 'usuarios',
                                'permission' => 'view.users'
                            ]
                        ],
                    ],
                    
                ]
            ],
            [
                'name' => 'Inventario',
                'tile' => 'inventory.svg',
                'submenus' => [
                    [
                        'name' => 'Equipo / material',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar equipo / material',
                                'route'      => 'agregar-equipo',
                                'permission' => 'create.equipment'
                            ],
                            [
                                'name'       => 'Lista de equipo / material',
                                'route'      => 'equipo',
                                'permission' => 'view.equipment'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Asignación de equipo',
                'tile' => 'assignment.svg',
                'submenus' => [
                    [
                        'name' => 'Asignación de equipo',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Asignar equipo / material',
                                'route'      => 'asignar-equipo',
                                'permission' => 'create.assign_equipment'
                            ],
                            [
                                'name'       => 'Lista de equipo / material',
                                'route'      => 'equipo',
                                'permission' => 'view.equipment'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Vales de material',
                'tile' => 'ticket.svg',
                'submenus' => [
                    [
                        'name' => 'Vales de material',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar vale de equipo / material',
                                'route'      => 'agregar-vale-material',
                                'permission' => 'create.vouchers'
                            ],
                            [
                                'name'       => 'Lista de vales de equipo / material',
                                'route'      => 'vales-equipo',
                                'permission' => 'view.vouchers'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Reportes de equipo dañado',
                'tile' => 'damaged-equipment.svg',
                'submenus' => [
                    [
                        'name' => 'Reportes de daño',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar un reporte',
                                'route'      => 'agregar-reporte',
                                'permission' => 'create.reports'
                            ],
                            [
                                'name'       => 'Lista de reportes',
                                'route'      => 'reportes',
                                'permission' => 'view.reports'
                            ],
                        ]
                    ],
                ]
            ]

        ];
    }
}

