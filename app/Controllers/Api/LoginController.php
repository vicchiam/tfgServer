<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\User;
use Firebase\JWT\JWT;

class LoginController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {

        helper(['form']);
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if(!$this->validate($rules)) 
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => join(' ',$this->validator->getErrors()),
                'data' => []
            ]);            

        $model = new User();
        $user = $model
            ->where("username", $this->request->getVar('username'))
            ->first();
        if(!$user) 
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'User not found',
                'data' => []
        ]);          
 
        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if(!$verify) 
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'Wrong password',
                'data' => []
            ]);
 
        $key = getenv('JWT_TOKEN_SECRET');
        $time = time();
        $payload = array(
            "iat" => $time,
            "exp" => $time + (8*60*60),
            "uid" => $user['id'],
            "email" => $user['email']
        );
 
        $token = JWT::encode($payload, $key);

        $user['password']='';

        $data = [
            'user' => $user,
            'token' => $token
        ];

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Login success',
			'data' => $data
		]);
    }

    
}
