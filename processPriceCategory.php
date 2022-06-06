<?php
    require_once('connect.php');

    try {
        $db->beginTransaction();
        $sql = 'UPDATE seat SET price_category_id = ? WHERE seat_id = ? AND link_id = ?;';
        foreach ($_GET['cond'] as $seat => $value) {
            $sth = $db->prepare($sql);
            $sth->execute(array($_GET['category'], $value, $_GET['linkPK']));
        }

        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['dbMsg'] = $e->getMessage();	
        header("Location: editPriceCategory.php?linkPK=".$_GET['linkPK']);  
        die();
    } 

    header("Location: editPriceCategory.php?linkPK=".$_GET['linkPK']); 
?>