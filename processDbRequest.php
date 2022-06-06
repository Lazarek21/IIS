<!DOCTYPE html>
<html>
<?php

    require_once("db_requests.php");
    require_once("common.php");
    foreach ($_POST as $key => $value) if ($value == "") unset($_POST[$key]);
    
    if (isset($_POST['action']))
    {
        $table = $_POST['db'];
        $action = $_POST['action'];
        unset($_POST['db']);
        unset($_POST['action']);
        switch ($action) {
            case 'insert':
                if(isset($_SESSION['lastRefer'])){
                    header('Location:'.$_SESSION['lastRefer']);
                    unset($_SESSION['lastRefer']);
                } else {
                    header('Location:'."dbTable.php?db=$table");
                }
                if ($table == 'link') 
                {
                    $num_of_seats = $_POST['number_of_seats'];
                    unset($_POST['number_of_seats']);
                    $link_id = insert_link($_POST,$num_of_seats);
                    if($_SESSION['type_id'] == 3 || $_SESSION['type_id'] == 2){
                        $_SESSION['links'][] = $link_id;
                    }
                } else if ($table == 'user') {
                    $pwd = generateRandomString();
                    $_POST['password'] =  password_hash($pwd, PASSWORD_DEFAULT);
                    if (insert('user',$_POST))
                    {
                        $message = "Dobry den,\n byl vam zalozen ucet s novym heslem.";
                        $message .= "Heslo si muzete kdykoliv zmenit.\n Heslo: ".$pwd."\n";
                        mail($_POST['email'],"New password",$message);
                    }
                } else {
                    insert($table,$_POST);
                }
                break;
            case 'update':
                if(isset($_SESSION['lastRefer'])){
                    header('Location:'.$_SESSION['lastRefer']);
                    unset($_SESSION['lastRefer']);
                } else {
                    header('Location:'."dbTable.php?db=$table");
                }
                $conditions = $_POST['cond'];
                unset($_POST['cond']);
                update($table,$conditions,$_POST);
                break;
            case 'approve':
                if(isset($_SESSION['lastRefer'])){
                    header('Location:'.$_SESSION['lastRefer']);
                    unset($_SESSION['lastRefer']);
                } else {
                    header('Location:approve.php');
                }

                if(isset($_POST[$table."_id"]))
                {
                    if ($table != 'change_stop'){
                        approve($table,$_POST[$table."_id"]);
                    } else {                    
                        approve_change_stop($_POST['change_stop_id']);
                    }
                } 
                break;
            case 'delete':
                if(isset($_SESSION['lastRefer'])){
                    header('Location:'.$_SESSION['lastRefer']);
                    unset($_SESSION['lastRefer']);
                } else {
                    header('Location:'."dbTable.php?db=$table");
                }
                delete($table,$_POST);
                break;
            default:
                break;
        }
    }
?>