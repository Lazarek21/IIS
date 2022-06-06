<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="table.css">
<title>Tabulka</title>
</head>
<body>
    <?php require_once('common.php')?>
    <?php 
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id'] < 3 || !isset($_GET['linkPK'])){
        header("Location:selectLink.php");
        die();
    }
    if($_SESSION['type_id'] == 3 && !in_array($_GET['linkPK'], array_values($_SESSION["links"]))){
        header("Location:selectLink.php");
        die();
    }
    $_SESSION['lastRefer'] = $_SERVER["REQUEST_URI"];
    ?>
    <div style="overflow:auto; margin-top:50px; margin-bottom:50px;" >
    <table id="styled-table" class="styled-table" style="overflow-x:scroll;">
        <?php require_once('parse_table.php');
        $sel_link = "SELECT DISTINCT stop.stop_id,stop.position, link_stops.time from link, link_stops, stop where link.link_id=link_stops.link_id and stop.stop_id=link_stops.stop_id and stop.status_id=2 and link.link_id='".$_GET['linkPK']."' ORDER BY link.description;";
        if (isset($_SESSION['carrier_id']))
        {
            $sql = "SELECT * FROM link where link_id=? and carrier_id=?";
            try {
            $sth = $db->prepare($sql);
            $sth->execute(array($_GET['linkPK'],$_SESSION['carrier_id']));
            } catch (PDOException $e){
                $_SESSION['dbMsg'] = $e->getMessage();
                die();
            }
            if ($sth->fetch())
            {
                parse_table('link_stops',$sel_link);
            } else {
                header("Location: selectLink.php");
            }    
        } else {
            parse_table('link_stops',$sel_link);
        }
        ?>
    </table>
    </div>

    <form id="form" method="post" action="#" style="display:none">
        <input name="link_id" id="link_id" value=0></input>
        <input name="time" id="time" value=""></input>
        <input name="stop_id" id="stop_id" value=0></input>
        <input name="action" value="delete"></input>
        <input name="db"value="link_stops"></input>
    </form>

    <?php print_database_footer('link_stops');?>

    <script>
        child = document.getElementById('editButton');
        child.parentNode.removeChild(child);
    </script>
    
<script src="databaseManip.js" type="text/javascript"></script>
</body>
</html>