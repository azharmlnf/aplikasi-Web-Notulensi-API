<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;


class AuthController extends ResourceController
{
    //post
    public function register()
    {
        $rules = [
            "username" => "required|is_unique[users.username]",
            "email" => "required|valid_email|is_unique[auth_identities.secret]",

            "password" => "required",
        ];

        if (!$this->validate($rules)) {
            $response = [
                "status" => false,
                "message" => $this->validator->getErrors(),
                "data" => []
            ];
        } else {
            //user model
            $userObject = new UserModel();
            //user Entity
            $userEntityObject = new User([
                "username" => $this->request->getVar("username"),
                "email" => $this->request->getVar("email"),
                "password" => $this->request->getVar("password")
            ]);
            $userObject->save($userEntityObject);

            $response = [
                "status" => true,
                "message" => "user saved succesfully",
                "data" => []
            ];
        }
        return $this->respondCreated($response);
    }

    //post
    public function login()
    {

        if(auth()->loggedIn()){
            auth()->logout();
        }
        $rules = [
            "email" => "required|valid_email",
            "password" => "required"
        ];

        if (!$this->validate($rules)) {
            $response = [
                "status" => false,
                "message" => $this->validator->getErrors(),
                "data" => []

            ];
        } else {
            //succes
            $credentials = [
                "email" => $this->request->getVar("email"),
                "password" => $this->request->getVar("password")
            ];

            $loginAttempt = auth()->attempt($credentials);

            if (!$loginAttempt->isOK()) {
                $response = [
                    "status" => false,
                    "message" => "invalid Login details",
                    "data" => []
                ];
            } else {
                //jika punya valid data
                $userObject = new UserModel();
                $userData = $userObject->findById(auth()->id());
                $token = $userData->generateAccessToken("magangindustri2024");

                $auth_token = $token->raw_token;

                $response = [
                    "status" => true,
                    "message" => "user logged in succesfully",
                    "data" => [
                        "token" => $auth_token
                    ]
                ];
            }
        }
        return $this->respondCreated($response);
    }
    //get
    public function profile()
    {
        $userId = auth()->id();
        $userObject = new UserModel();
        $userData = $userObject->findById($userId);
       return  $this->respondCreated([
            "status"=>true,
            "message"=>"profile Information of logged in user",
            "data"=>[
                "user"=>$userData
            ]
       ]);
    }

    //get
    public function logout()
    {
        auth()->logout();
        auth()->user()->revokeAllAccessTokens();
        return $this->respondCreated([
            "status"=>true,
            "message"=>"User logged out successfully",
            "data"=>[]
        ]);
    }

    public function accesDenied()
    {
        return $this->respondCreated([
            "status" => false,
            "messag" => "invalid acces",
            "data" => []
        ]);
    }
}
