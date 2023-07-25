<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Employee;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use Faker\Provider\Uuid;

class EmployeeController extends BaseController
{
    use ResponseTrait;
    public function create() {
        $validated = $this->validate([
            'email'     => 'required|valid_email|max_length[31]|is_unique[users.email]',
            'name'      => 'required|max_length[50]',
            'password'  => 'required|min_length[5]|max_length[50]',
            'gender'    => 'required',
            'phone'     => 'required|max_length[20]',
            'photo'     => 'uploaded[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ]);

        if (!$validated) {
            $message = \Config\Services::validation()->getErrors();
            $message = implode(" ", $message);
            return $this->respond([
				'data'		=> null,
				'message'	=> $message,
			], 422);
        }

        if (!in_array($this->request->getVar('gender'), ['male', 'female'])) {
            return $this->respond([
				'data'		=> null,
				'message'	=> 'Gender only male or female',
			], 422);
        }

        // Move the uploaded file to a directory
        $file       = $this->request->getFile('photo');
        $newName    = $file->getRandomName();

        $file->move('./uploads/', $newName);

        $setData = [
            'email'     => $this->request->getVar('email'),
            'name'      => $this->request->getVar('name'),
            'gender'    => $this->request->getVar('gender'),
            'phone'     => $this->request->getVar('phone'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'      => 'employee',
            'photo'     => base_url('uploads/' . $newName),
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        
        $dbUser     = new User;
        $dbEmployee = new Employee;

        $dbUser->db->transStart();

        $dbUser->insert([
            'uid'       => Uuid::uuid(),
            'email'     => $setData['email'],
            'password'  => $setData['password'],
            'role'      => $setData['role'],
            'created_at'=> $setData['timestamp'],
            'updated_at'=> $setData['timestamp'],
        ]);

        $dbEmployee->insert([
            'uid'       => Uuid::uuid(),
            'email'     => $setData['email'],
            'name'      => $setData['name'],
            'gender'    => $setData['gender'],
            'phone'     => $setData['phone'],
            'photo'     => $setData['photo'],
            'created_at'=> $setData['timestamp'],
            'updated_at'=> $setData['timestamp'],
        ]);

        $dbUser->db->transComplete();

        if ($dbUser->db->transStatus() === false) {
            return $this->respond([
				'data'		=> null,
				'message'	=> 'error on create data',
			], 500);
        }

        return $this->respondCreated([
            'message'   => 'data created'
        ]);
    }

    public function index() {
        $db     = new Employee();
        $query  = $db->select('employees.email,
                                employees.name,
                                employees.gender,
                                employees.phone,
                                employees.photo,
                                employees.uid,
                                employees.created_at,
                                employees.updated_at')
                    ->where('users.role', 'employee')
                    ->join('users', 'users.email = employees.email')
                    ->findAll();

        return $this->respond([
            'data'		=> $query,
            'message'	=> 'Success retrived data'
        ]);
    }

    public function show(string $uid) {
        $db     = new Employee();
        $query  = $db->select('employees.email,
                    employees.name,
                    employees.gender,
                    employees.phone,
                    employees.photo,
                    employees.uid,
                    employees.created_at,
                    employees.updated_at')
                    ->join('users', 'users.email = employees.email')
                    ->where('employees.uid', $uid)
                    ->find();

        return $this->respond([
            'data'		=> $query ?? [],
            'message'	=> 'Success retrived data'
        ]);
    }

    public function updatePhoto(string $uid) {
        $validated = $this->validate([
            'photo'     => 'uploaded[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,1024]'
        ]);

        if (!$validated) {
            $message = \Config\Services::validation()->getErrors();
            $message = implode(" ", $message);
            return $this->respond([
				'data'		=> null,
				'message'	=> $message,
			], 422);
        }

        // Move the uploaded file to a directory
        $file       = $this->request->getFile('photo');
        $newName    = $file->getRandomName();

        $file->move('./uploads/', $newName);

        $data = [
            'photo'      => base_url('uploads/' . $newName),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db   = new Employee();
        $db->set($data);
        $db->where('uid', $uid);
        $db->update();

        return $this->respondUpdated([
            'message' => 'Photo updated'
        ]);
    }

    public function update(string $uid) {
        
    }

    public function changePhoto() {
        
    }
}
