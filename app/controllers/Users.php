<?php
    class Users extends Controller {
        public function __construct(){
            $this->userModel = $this->model("User");
        }

        public function register(){
            // Check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form
                // Sanitixe post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            /*
            * Init data
            * Handle partial data so user does not have to re-enter all
            */
            $data =[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email for duplicate
                if($this->userModel->findUserBYEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            // Validate password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) <6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Pleae confirm password';
              } else {
                if($data['password'] != $data['confirm_password']){
                  $data['confirm_password_err'] = 'Passwords do not match';
                }
              }

            // Verify errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                //Validated

                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register user
                if($this->userModel->register($data)){
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                    // Load view with errors
                    $this->view('users/register', $data);
                  }
            } else {
                // Init data
                $data =[
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                // Load view
                $this->view('users/register', $data);
            }
        }

        public function login(){
            // Checking for posts
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            /*
            * Init data
            * Handle partial data so user does not have to re-enter all
            */
            $data =[
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

             // Validate email
             if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

             // Validate password
             if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Verify errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                //Validated
                die('SUCCESS');
            } else {
                    // Load view with errors
                    $this->view('users/login', $data);
                  }


            } else {
                // Init data
                $data =[
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => ''
                ];

                // Load view
                $this->view('users/login', $data);
            }
        }
    }
