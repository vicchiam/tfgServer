<?php
/*
    Create file
    php spark make:controller (name) --restful
*/

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

//use CodeIgniter\API\ResponseTrait;
//use Firebase\JWT\JWT;

use App\Models\User;

class UserController extends ResourceController
{

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $user = new User();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'User list',
			'data' => $user
                ->orderBy('username', 'asc')
                ->findAll()
		]);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $user = new User();

        $data = $user->find($id);

        if( empty($data) ){
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'User not found',
                'data' => []
            ]);          
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single User data',
            'data' => $data
        ]);

    }

    public function showByType($type)
    {
        $user = new User();

        return $this->respondCreated([
            'status' => 200,
            "error" => false,
            'messages' => 'User by type',
            'data' => $user
                ->where('type',$type)
                ->orderBy('username')
                ->findAll()
        ]);
    }

    /*
    use ResponseTrait;
    public function auth(){

        $key = getenv('JWT_TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if(!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, $key, ['HS256']);
            $response = [
                'id' => $decoded->uid,
                'email' => $decoded->email
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
    }
    */
    
}
