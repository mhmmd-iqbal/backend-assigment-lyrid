<?php

namespace App\Database\Seeds;

use App\Models\Employee;
use App\Models\User;
use CodeIgniter\Database\Seeder;
use Faker\Provider\Uuid;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'     => 'admin@mail.com',
            'password'  => password_hash('admin123', PASSWORD_DEFAULT),
            'uid'       => Uuid::uuid(),
            'role'      => 'admin',
            'created_at'=> date('Y-m-d H:i:s')
        ];

		$dbUser     = new User;
		$dbUser->insert($data);

        $data = [
            'email'     => 'admin@mail.com',
            'name'      => 'admin',
            'gender'    => 'male',
            'phone'     => '0811111111',
            'uid'       => Uuid::uuid(),
            'created_at'=> date('Y-m-d H:i:s')
        ];

        $dbEmployee = new Employee;
        $dbEmployee->insert($data);
        
    }
}
