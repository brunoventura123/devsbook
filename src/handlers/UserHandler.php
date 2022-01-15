<?php
namespace src\handlers;

use \src\models\User;
use \src\models\UserRelation;
use \src\handlers\PostHandler;

class UserHandler {

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
                $loggedUser->city = $data['city'];
                $loggedUser->work = $data['work'];

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
    public static function idExists($id){
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }
    public static function emailExists($email){
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }
    public static function getUser($id, $full = false){
        $data = User::select()->where('id', $id)->one();
        if($data){
            $user = new User();
            $user->id = $data['id'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->birthdate = $data['birthdate'];
            $user->city = $data['city'];
            $user->work = $data['work'];
            $user->avatar = $data['avatar'];
            $user->cover = $data['cover'];

            if($full){
                $user->followers = [];
                $user->following = [];
                $user->photos = [];

                // followers
                $follwers = UserRelation::select()->where('userTo', $id)->get();
                foreach($follwers as $follower){
                    $userData = User::select()->where('id', $follower['userFrom'])->one();
                    $newUser = new User();
                    $newUser->id = $userData['id'];
                    $newUser->name = $userData['name'];
                    $newUser->avatar = $userData['avatar'];

                    $user->followers[] = $newUser;
                }
                // following
                $following = UserRelation::select()->where('userFrom', $id)->get();
                foreach($following as $follower){
                    $userData = User::select()->where('id', $follower['userTo'])->one();
                    $newUser = new User();
                    $newUser->id = $userData['id'];
                    $newUser->name = $userData['name'];
                    $newUser->avatar = $userData['avatar'];

                    $user->following[] = $newUser;
                }

                // photos
                $user->photos = PostHandler::getPhotosFrom($id);
            }

            return $user;
        }
    }
    public static function addUser($name, $email, $birthdate,$city, $work, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,999).time()); 

        User::insert([
            'name' => $name,
            'email' => $email,
            'birthdate' => $birthdate,
            'city' => $city,
            'work' => $work,
            'password' => $hash,
            'token' => $token
        ])->execute();

        return $token;
    }

    public static function isFollowing($from, $to){
        $data = UserRelation::select()
            ->where('userFrom', $from)
            ->where('userTo', $to)
        ->one();
        if($data){
            return true;
        } else{
            return false;
        }
    }

    public static function follow($from, $to){
        UserRelation::insert([
            'userFrom' => $from,
            'userTo' => $to
        ])->execute();
    }
    public static function unFollow($from, $to){
        UserRelation::delete()
            ->where('userFrom', $from)
            ->where('userTo', $to)
        ->execute();
    }

    public static function searchUser($term){
        $users = [];
        $data = User::select()->where('name', 'like', '%'.$term.'%')->get();
        if($data){
            foreach($data as $user){
                $newUser = new User();
                $newUser->id = $user['id'];
                $newUser->name = $user['name'];
                $newUser->avatar = $user['avatar'];

                $users[] = $newUser;
            }
        }
        return $users;
    }
    public static function updateUser($id ,$name, $birthdate, $email, $city, $work, $password) {
        User::update()
            ->set('name', $name)
            ->set('birthdate', $birthdate)
            ->set('email', $email)
            ->set('city', $city)
            ->set('work', $work)
            ->where('id', $id)
        ->execute();

        if(!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            User::update()
                ->set('password', $hash)
                ->where('id', $id)
            ->execute();
        }
       
    }
}