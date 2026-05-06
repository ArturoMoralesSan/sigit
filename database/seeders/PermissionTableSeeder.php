<?php
namespace Database\Seeders;

use DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate(['permissions']);

        foreach ($this->getData() as $keyName => $name) {
            DB::table('permissions')->insert([
                'key_name'   => $keyName,
                'name'       => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }


    /**
     * Return the data to populate table.
     *
     * @return array
     */
    private function getData()
    {
        return [
             /*
             * Profile
             */
            'update.password' => 'Cambiar contraseña',
            'update.profile'  => 'Cambiar Perfil',

            /*
             * equipment
             */
            'view.equipment'   => 'Ver material / equipo',
            'create.equipment' => 'Agregar material / equipo',
            'edit.equipment'   => 'Editar material / equipo',
            'delete.equipment' => 'Eliminar material / equipo',

            /*
             * equipment
             */
            'view.assign_equipment'   => 'Ver material / equipo asignado',
            'create.assign_equipment' => 'asignar material / equipo',
            'edit.assign_equipment'   => 'Editar material / equipo asignado',
            'delete.assign_equipment' => 'Eliminar material / equipo asignado',

            /*
             * permission
             */
            'view.permissions'   => 'Ver permisos',
            'create.permissions' => 'Agregar permisos',
            'edit.permissions'   => 'Editar permisos',
            'delete.permissions' => 'Eliminar permisos',

            /*
             * permission
             */
            'view.roles'   => 'Ver roles',
            'create.roles' => 'Agregar roles',
            'edit.roles'   => 'Editar roles',
            'delete.roles' => 'Eliminar roles',

            /*
             * vouchers
             */
            'view.vouchers'   => 'Ver vales',
            'create.vouchers' => 'Agregar vales',
            'edit.vouchers'   => 'Editar vales',
            'delete.vouchers' => 'Eliminar vales',

            /*
             * reports
             */
            'view.reports'   => 'Ver Reportes de daño',
            'create.reports' => 'Agregar Reportes de daño',
            'edit.reports'   => 'Editar Reportes de daño',
            'delete.reports' => 'Eliminar Reportes de daño',

            /*
             * Users
             */
            'view.users'   => 'Ver Usuarios',
            'create.users' => 'Agregar usuarios',
        ];
    }
}
