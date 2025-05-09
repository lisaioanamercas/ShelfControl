<?php 
 
 namespace App\Controllers;
 use App\Models\UserModel;
 use App\Views\BaseView;
 use App\Controllers\BaseController;

class LoginController{

    public function login()
    {
        require __DIR__ . '/../dbConnection.php'; 


        if($_SERVER['REQUEST_METHOD']=='POST')
        { 
            $email=$_POST['email'];
            $password=$_POST['password'];

            if (empty($email) || empty($email) || empty($password)) {
                $error = 'All fields are required.';
            }

            if(empty($error))
            {
                $userModel = new UserModel($conn);

                if($userModel->loginUser($email,$password)==0)
                {
                    $error='Account not found or password incorrect.';
                }
                else
                {
                    $success='Login succesful!';
                    $jwt=new BaseController;
                    $token=$jwt->generateJWT($email);
                    setcookie('jwt', $token, time() + 3600, '/', '', false, true);
                    
                }
            }
        }

        $data = [
            'heading' => 'Login',
            'message' => !empty($error) ? "<span style='color: red;'>{$error}</span>" : (!empty($success) ? "<span style='color: green;'>{$success}</span>" : ''),
        ];
    
        $view = new BaseView();
        $view->renderTemplate('login',$data);

    }


    
}
