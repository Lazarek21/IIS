<?php
    try {
        $db = new PDO("mysql:host=localhost;dbname=xbrazd21;port=/var/run/mysql/mysql.sock", 'xbrazd21', 'bazeran8', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        Header('Location: index.php');
        die();
    }
?>