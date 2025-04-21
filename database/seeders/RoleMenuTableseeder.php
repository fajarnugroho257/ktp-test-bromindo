<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role_menu;

class RoleMenuTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role_menu::create([
            'role_menu_id' => 'RM00001',
            'menu_id' => 'M0001',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00002',
            'menu_id' => 'M0002',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00003',
            'menu_id' => 'M0003',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00004',
            'menu_id' => 'M0004',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00005',
            'menu_id' => 'M0005',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00006',
            'menu_id' => 'M0000',
            'role_id' => 'R0001',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00007',
            'menu_id' => 'M0000',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00008',
            'menu_id' => 'M0000',
            'role_id' => 'R0003',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00011',
            'menu_id' => 'M0008',
            'role_id' => 'R0003',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00016',
            'menu_id' => 'M0008',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00017',
            'menu_id' => 'M0009',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00018',
            'menu_id' => 'M0010',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00019',
            'menu_id' => 'M0011',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00020',
            'menu_id' => 'M0012',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00021',
            'menu_id' => 'M0013',
            'role_id' => 'R0002',
        ]);
        Role_menu::create([
            'role_menu_id' => 'RM00022',
            'menu_id' => 'M0014',
            'role_id' => 'R0002',
        ]);
    }
}
