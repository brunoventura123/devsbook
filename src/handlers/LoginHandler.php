<?php
namespace src\handlers;

use \src\models\User;

class LoginHandler {

    public static function checkLogin(){
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];
            $data = User::select()->where('token', $token)->one();

            if(!empty($data) > 0){
                $loggedUser = new User();
                $loggedUser->id =$data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name'];
                $loggedUser->avatar = $data['avatar'];

                return $loggedUser;

            }
        }   
             return false;
    }
    public static function verifyLogin($email, $password){
        $user = User::select()->where('email', $email)->one();

        
            if($user && password_verify($password, $user['password'])){
                $token = md5(time().rand(0,999).time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                ->execute();

                return $token;
            }
    }
    public static function emailExists($email){
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }
    public static function addUser($name, $email, $birthdate, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,999).time()); 

        User::insert([
            'name' => $name,
            'email' => $email,
            'birthdate' => $birthdate,
            'password' => $hash,
            'token' => $token
        ])->execute();

        return $token;
    }
}