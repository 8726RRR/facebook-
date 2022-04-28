<?php
    // (C)
    require_once 'filters/login_filter.php';
    require_once 'filters/csrf_filter.php';
    require_once 'models/Post.php';
    
    // フォームで入力した値を取得
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    
    //そのidのユーザーインスタンスを取得
    $post = Post::find($id);
    
     // ログインユーザー以外の者が投稿をアップデートしようとした場合
    if($post === false) {
        header('Location: top.php');
        exit;
    } else if($login_user->id !== $post->user_id) {
        header('Location: top.php');
    }
    
    // Postインスタンスの個性を変更
    $post->title = $title;
    $post->content = $content;
    
    // タイトルや内容だけ変更したい場合もあり、エラーにより入力が手間になるため$imageを条件分岐
    if($image !== '') {
        $post->image = $image;
    }
    
    // エラーの検証のメソッド
    $errors = $post->validate();
    
    if(count($errors) === 0) {
        if($image !== '') {
            $post->upload();
        }
        // 更新した投稿を保存
        $flush = $post->save();
        if($flush === '') {
            $_SESSION['errors'] = array('何らかの理由で投稿に失敗しました');
            $_SESSION['post'] = $post;
            header('Location: posts_edit.php?id=' . $id);
            exit;
        } else {
            $_SESSION['flush'] = $flush;
            header('Location: posts_show.php?id=' . $id);
            exit;
        }
        
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['post'] = $post;
        header('Location: posts_edit.php?id=' . $id);
        exit;
    }