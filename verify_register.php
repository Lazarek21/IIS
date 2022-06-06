<?php
    session_start();
    require_once("connect.php");

    if(isset($_SESSION['tmp_login'])){
        try {
            $pwd = password_hash($_POST['pwdFirst'], PASSWORD_DEFAULT);

            $sql = 'UPDATE user SET password = "'.$pwd.'"'.', type_id = 1 WHERE email = "'.$_SESSION['tmp_login'].'";';
            $sth = $db->prepare($sql);
            $sth->execute(array());
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['type_id'] = 1;
            $_SESSION['login'] = $_SESSION['tmp_login'];
            unset($_SESSION['tmp_login']);
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            header("Location: logout.php");
            die();
        } 
        header("Location: index.php");  
    } else {
        if(!isset($_SERVER['HTTP_REFERER'])){
            $_SERVER['HTTP_REFERER'] = "login.php";
        }
        if(!isset($_SESSION['prevPage'])){
            $_SESSION['prevPage'] = "index.php";
        }
        
        $email = $_POST['email'];
        $pwd = $_POST['pwdFirst'];
        $tmpPwd = $_POST['pwdSecond'];

        if ($pwd <> $tmpPwd){    
            header("Location:".$_SERVER['HTTP_REFERER']);
            $_SESSION['errRegister'] = "Špatné heslo";
        } else {

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
                $tmp = 1;
            }

            if ($tmp)
            {   //email was used
                header("Location:".$_SERVER['HTTP_REFERER']);
                $_SESSION['errRegister'] = "Tento email již někdo používá";
            }else{  //new reg
                $pwd = password_hash($_POST['pwdFirst'], PASSWORD_DEFAULT);
                try {
                    $sql = 'INSERT INTO user (password, email, type_id) VALUES (\''.$pwd.'\', \''.$email.'\', 1)';
                    $db->exec($sql);

                    $sql = 'SELECT * FROM user WHERE email = \''.$email.'\'';
                    $result = $db->query($sql);
                    foreach ($result as $row) {
                        $_SESSION['user_id'] = $row['user_id'];
                    }

                    $_SESSION['login'] = $email;
                    $_SESSION['type_id'] = 1;
                } catch (PDOException $e) {
                    header("Location:".$_SERVER['HTTP_REFERER']);
                    $_SESSION['dbMsg'] = $e->getMessage();
                    die();
                }
                
                header("Location:".$_SESSION['prevPage']);
            }
        }
    }
?>