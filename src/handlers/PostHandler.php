<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\PostLike;
use \src\models\PostComment;
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

    public static function _portListToObject($postList, $loggedUserId){
        $posts = [];
        foreach($postList as $postItem){
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->createdAt = $postItem['createdAt'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;
    
            if($postItem['idUser'] == $loggedUserId){
                $newPost->mine = true;
            }
    
            $newUser = User::select()->where('id', $postItem['idUser'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];
            
            $likes = PostLike::select()->where('idPost', $postItem['id'])->get();
    
            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($postItem['id'], $loggedUserId);
    
    
            $newPost->comments = PostComment::select()->where('idPost', $postItem['id'])->get();
            foreach($newPost->comments as $key => $comment){
                $newPost->comments[$key]['user'] = User::select()->where('id', $comment['idUser'])->one();
            }
    
            $posts[] = $newPost;
        }
        return $posts;
    }

    public static function isLiked($id, $loggedUserId){
        $myLike = PostLike::select()
            ->where('idPost', $id)
            ->where('id', $loggedUserId)
        ->get();

        if(count($myLike) > 0 ){
            return true;
        } else{
            return false;
        }
    }

    public static function deleteLike($id, $loggedUserId){
        PostLike::delete()
            ->where('idPost', $id)
            ->where('idUser', $loggedUserId)
        ->execute();
    }
    public static function addLike($id, $loggedUserId){
        PostLike::insert([
            'idPost' => $id,
            'idUser' => $loggedUserId,
            'createdAt' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static function addComment($id, $txt, $loggedUserId){
        PostComment::insert([
            'idPost' => $id,
            'idUser' => $loggedUserId,
            'createdAt' => date('Y-m-d H:i:s'),
            'body' => $txt
        ])->execute();
    }

    public static function getUserFeed($idUser, $page, $loggedUserId){
        $perPage = 2;
        $postList = Post::select()
            ->where('idUser', $idUser)
            ->orderBy('createdAt', 'desc')
            ->page($page, $perPage) 
        ->get();

        $total = Post::select()
            ->where('idUser', $idUser)
        ->count();
        $pageCount = ceil($total / $perPage);

        $posts = self::_portListToObject($postList, $loggedUserId);

    return [
        'posts' => $posts,
        'pageCount' => $pageCount,
        'currentPage' => $page
    ];
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

    $posts = self::_portListToObject($postList, $idUser);

    return [
        'posts' => $posts,
        'pageCount' => $pageCount,
        'currentPage' => $page
    ];
}
    public static function getPhotosFrom($idUser){
        $photosData = Post::select()
            ->where('idUser', $idUser)
            ->where('type', 'photo')
        ->get();

        $photos = [];
        foreach($photosData as $photo){
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->createdAt = $photo['createdAt'];
            $newPost->body = $photo['body'];

            $photos[] = $newPost;
        }
        return $photos;
    }

    public static function delete($id, $loggedUserId){
        // verificar se o post existe e é seu
        $post = Post::select()
            ->where('id', $id)
            ->where('idUser', $loggedUserId)
            ->get();
            if(count($post) > 0){
                $post = $post[0];

                // deletar os likes e comments
                PostLike::delete()->where('idPost', $id)->execute();
                PostComment::delete()->where('idPost', $id)->execute();

                // se o post for type == photo deletar o arquivo
                if($post['type'] === 'photo'){
                    $img = __DIR__.'/../../media/uploads/'.$post['body'];
                    if(file_exists($img)){
                        unlink($img);
                    }
                }
                // deletar o post
                Post::delete()->where('id',$id)->execute();
            }
        
        

        
    }
}