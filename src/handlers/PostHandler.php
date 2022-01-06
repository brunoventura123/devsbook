<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler {
    public static function addPost($idUser, $type, $body){
        $body = trim($body);
        if(!empty($idUser) && !empty($body)){
            Post::insert([
                'idUser' => $idUser,
                'type' => $type,
                'createdAt' => date('Y-m-d H:i:s'),
                'body' => $body
            ])->execute();
        }
    }

    public static function getHomeFeed($idUser, $page){
        $perPage = 2;

        $userList = UserRelation::select()->where('userFrom', $idUser)->get();
        $users = [];
        foreach($userList as $userItem){
            $users[] = $userItem['userTo'];
        }
        $users[] = $idUser;

        $postList = Post::select()
            ->where('idUser', 'in', $users)
            ->orderBy('createdAt', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('idUser', 'in', $users)
        ->count();
        $pageCount = ceil($total / $perPage);

    $posts = [];
    foreach($postList as $postItem){
        $newPost = new Post();
        $newPost->id = $postItem['id'];
        $newPost->type = $postItem['type'];
        $newPost->createdAt = $postItem['createdAt'];
        $newPost->body = $postItem['body'];
        $newPost->mine = false;

        if($postItem['idUser'] == $idUser){
            $newPost->mine = true;
        }

        $newUser = User::select()->where('id', $postItem['idUser'])->one();
        $newPost->user = new User();
        $newPost->user->id = $newUser['id'];
        $newPost->user->name = $newUser['name'];
        $newPost->user->avatar = $newUser['avatar'];

        $newPost->likeCount = 0;
        $newPost->liked = false;


        $newPost->comments = [];

        $posts[] = $newPost;
    }

    return [
        'posts' => $posts,
        'pageCount' => $pageCount
    ];
}
}