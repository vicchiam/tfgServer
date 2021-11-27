<?php
/*
    Create file
    php spark make:controller (name) --restful
*/

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

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
    
}
