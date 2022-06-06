<?php
    session_start();
    if(!isset($_SERVER['HTTP_REFERER'])){
        $_SERVER['HTTP_REFERER'] = "login.php";
    }
    if(!isset($_SESSION['prevPage'])){
        $_SESSION['prevPage'] = "index.php";
    }

    require_once('parse_table.php');

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    try {
        $sql = 'SELECT * FROM user WHERE email = \''.$email.'\'';
        $result = $db->query($sql);
    } catch (PDOException $e) {
        header("Location:".$_SERVER['HTTP_REFERER']);
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }

    $tmp = 0;
    foreach ($result as $row) {
        if (password_verify($pwd, $row['password']))
        {
            $_SESSION['login'] = $row['email'];
            $_SESSION['type_id'] = $row['type_id'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['carrier_id'] = $row['carrier_id'];
            $tmp = 1;
        }
    }

    if($_SESSION['type_id'] > 1 && $_SESSION['type_id'] < 4){
        try {
            $sql = 'SELECT link_id FROM link WHERE carrier_id = \''.$_SESSION['carrier_id'].'\'';
            $result = $db->query($sql)->fetchAll();
        } catch (PDOException $e) {
            header("Location:".$_SERVER['HTTP_REFERER']);
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
        foreach($result as $link){
            $arr[] = $link['link_id'];
        }
        $_SESSION['links'] = $arr;
    }
    

    if ($tmp)
    {
        header("Location:".$_SESSION['prevPage']);
    }else{
        header("Location:".$_SERVER['HTTP_REFERER']);
        $_SESSION['errLogin'] = "Neplatné heslo nebo email";
    }
?>