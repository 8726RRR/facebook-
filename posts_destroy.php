<?php
    //(C)
    require_once 'filters/login_filter.php';
    require_once 'filters/csrf_filter.php';
    
    $id = $_POST['id'];
    // モデルを使ってPostインスタンスを取得
    $post = Post::find($id);
    
    // ログインユーザー以外の者が投稿を削除しようとした場合
    if($post === false) {
        header('Location: top.php');
        exit;
    } else if($login_user->id !== $post->user_id) {
        header('Location: top.php');
    }
    
    // そのインスタンスをMySQLから削除
    $flush = $post->delete();
    
    // flushメッセージをセッションに保存
    $_SESSION['flush'] = $flush;
    
    // リダイレクト
    header('Location: top.php');
    exit;
    