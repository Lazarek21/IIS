<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<title>Vybrat linku</title>
</head>
<body>
    <?php require_once('common.php')?>
    <?php 
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id'] == 0){
        header("Location:index.php");
        die();
    }?>
    <div style="overflow:auto; width:fit-content; position:absolute;left:50%;top:30%;transform: translate(-50%, -50%);" >
    <form id="form" method="get" action="<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']>1){echo "selectLinkEdit.php";}else{echo "position.php";}?>">
        <select name="linkPK">
            <?php
                require_once("connect.php");
                try {
                    $sql = 'SELECT * FROM link';
                    if ($_SESSION['type_id'] == 3 || $_SESSION['type_id'] == 2)
                    {
                        $sql = $sql . ' WHERE carrier_id=' . $_SESSION['carrier_id'];
                    }

                    $result = $db->query($sql);
                    $tmp = 1;
                    foreach ($result as $row) {
                        if($tmp){
                            echo '<option value="'.$row['link_id'].'" selected>Linka '.$row['link_id'].'</option>';
                            $tmp = 0;
                        } else {
                            echo '<option value="'.$row['link_id'].'" >Linka '.$row['link_id'].'</option>';
                        }
                    }
                } catch (PDOException $e) {
                    $_SESSION['dbMsg'] = $e->getMessage();
                    header("Location: index.php");
                    die();
                }
            ?>

            
        </select>
        <input type="submit" value="Vyhledat"></input>
    </form>
    </div>

</body>
</html>