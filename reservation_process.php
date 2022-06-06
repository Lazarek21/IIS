<?php
    require_once('common.php');
    require_once('connect.php');
    $email = "";
    $user_id = 0;

    $transaction_began = false;
    $price = $_GET['price'];
    $newUFlag = 0;

    if(isset($_GET['email'])){
        $email = $_GET['email'];
        
        if ($email == ""){
            $_SESSION['reservationErr'] = "Neplatný email";
            header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);
            return;
        } else {
            try {
                $sql = 'SELECT * FROM user WHERE email = ?';
                $stmt = $db->prepare($sql);
                $stmt->execute(array($email));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row)
                {
                    if($row['type_id'] == 0){
                        $_SESSION['tmp_login'] = $email;
                        $user_id = $row['user_id']; 
                    } else {
                        $_SESSION['reservationErr'] = "Email je již zaregistrovaný";
                        header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);
                        return;
                    }

                } else {
                    $sql = "INSERT INTO user (email, type_id) VALUES (?,0);";
                    $db->beginTransaction();
                    $transaction_began = true;
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array($email));
                    $user_id = $db->lastInsertId();
                    $_SESSION['tmp_login'] = $email;
                }

            } catch (PDOException $e) {
                if ($transaction_began)
                {
                    $db->rollBack();
                }
                $_SESSION['dbMsg'] = $e->getMessage();
                header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);
                die();
            } 
        }
    } else {
        if(isset($_SESSION['login'])){
            $email = $_SESSION['login'];
            $user_id = $_SESSION['user_id'];
        } else {
            $_SESSION['reservationErr'] = "Zadejte email";
            header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);
            return;
        }
    }

    if($_GET["count"] > 0 and isset($_GET["seats"])){
        try {
            print_r( $_GET['seats']);
            if (!$transaction_began)
            {
                $db->beginTransaction();
            }
            $sql = 'INSERT INTO reservation (user_id, status_id, price) VALUES (? ,1, ?);';
            $sth = $db->prepare($sql);
            $sth->execute(array($user_id, $price));
            $res_id = $db->lastInsertId();
            $found = false;
            foreach ($_GET['seats'] as $seat => $value) {
                $stmt = $db->prepare('SELECT * FROM seat WHERE seat_id = ? AND link_id = ? AND reservation_id IS NOT NULL');
                $stmt->execute(array($value, $_SESSION['link_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $found = $found || $row; 
                $sql = 'UPDATE seat SET reservation_id = ? WHERE seat_id = ? AND link_id = ?';
                $sth = $db->prepare($sql);
                $sth->execute(array($res_id,$value,$_SESSION['link_id']));
            }
            if($found){
                $db->rollBack();
                $_SESSION['reservationErr'] = "Místa již nejsou dostupná.";
                header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);
                return;
            } else {
                $db->commit();
            }
           
        } catch (PDOException $e) {
            $db->rollBack();
            $_SESSION['dbMsg'] = $e->getMessage();
            header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id']);  
            die();
        } 
        
        //korektni pruchod
       header("Location: reservationComplete.php");
    } else {
        if ($transaction_began)
            $db->rollBack();
        $_SESSION['reservationErr'] = "Vyberte sedadla";
        header("Location: seat_reservation.php?linkPK=".$_SESSION['link_id'])."";
        return;
    }

?>