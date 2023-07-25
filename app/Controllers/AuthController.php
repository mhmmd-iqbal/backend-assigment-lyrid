<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\JWTCI4;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
	use ResponseTrait;

    public function login() {
		$validated = $this->validate([
			'email' 	=> 'required',
			'password' 	=> 'required|min_length[6]',
		]);

		if (!$validated) {
			$message = \Config\Services::validation()->getErrors();
            $message = implode(" ", $message);

            return $this->respond([
				'data'		=> null,
				'message'	=> $message,
			], 422);
		}

		$db 	= new User;

		$user  	= $db->where('email', $this->request->getVar('email'))->first();
		
		if ( $user ) {
			if ( password_verify($this->request->getVar('password'), $user['password']) ) {
				$jwt 	 = new JWTCI4;
				$token 	 = $jwt->token();

				return $this->respond([
					'data'		=> ['token'=> $token ],
					'message'	=> 'success login'
				]);
			}
		} else {
			return $this->respond([
				'data'		=> null,
				'message'	=> 'User not found',
			], 401);
		}
	}
}
