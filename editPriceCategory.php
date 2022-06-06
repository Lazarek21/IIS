<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="seat.css">
<title>Cenové kategorie</title>
</head>
<body>
    <?php 
    require_once('common.php');
    require_once('connect.php');
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id'] < 3 || !isset($_GET['linkPK'])){
        header("Location:selectLink.php");
    }
    if($_SESSION['type_id'] == 3 && !in_array($_GET['linkPK'], array_values($_SESSION["links"]))){
        header("Location:selectLink.php");
    }
    try{
        $sql = "SELECT * FROM link where link_id=?";
        $sth = $db->prepare($sql);
        $sth->execute(array($_GET['linkPK']));
        if (!$sth->fetch())
        {
            header("Location: selectLink.php");
        }    
        
        $linkPK = $_GET["linkPK"];
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    ?>
    <div style="position:fixed;top:50%;left:50%;transform: translate(-50%, -40%);text-align:center">
    <div class="range-slider">
        <form  action="javascript:void(0);" id="form">
        <select id="category" name="category">
            <?php
                try {
                    $sql = 'SELECT * FROM price_category';
                    $result = $db->query($sql);
                    foreach ($result as $value) {
                        echo "<option value=".$value['price_category_id'].">".$value['description']."</option>";
                    }
                } catch (PDOException $e) {
                    $_SESSION['dbMsg'] = $e->getMessage();
                    header("Location: editPriceCategory.php?linkPK=".$_SESSION['link_id']);  
                    die();
                }
            ?>
        </select>
        <button id="save">Uložit</button>
        </form>
    </div>
    <div class="bus" style="padding-top:5px;width:fit-content;position:relative;left:40%;transform: translate(-50%, 0%)">
    <div id="link" style="text-align:center">
        <text><?php echo $_GET["linkPK"];?></text>
    </div>
          <?php
            require_once('parse_table.php');
            update_state_seat($linkPK);
          ?>
    </div>
    
    </div>


    
    
<script src="editPriceCategory.js" type="text/javascript"></script>
</body>
</html>