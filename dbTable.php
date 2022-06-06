<!DOCTYPE html>
<html>
<?php 

if(!isset($_GET['db'])){
    header("Location: index.php");
    die();
}?>
<head>
<link rel="stylesheet" href="table.css">
<title>Tabulka</title>
</head>
<body>
    <?php 
    require_once('common.php');
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id']<3){
        header('Location:index.php');
        die();
    }
    $_SESSION['lastRefer'] = $_SERVER["REQUEST_URI"];
    ?>
    <div style="overflow:auto; margin-top:50px; margin-bottom:50px;" >
    <table id="styled-table" class="styled-table" style="overflow-x:scroll;">
        <?php require_once('parse_table.php');
        $sql = "SELECT * FROM ".$_GET['db'];
        if ($_GET['db'] == 'user' || $_GET['db'] == 'link' || $_GET['db'] == 'carrier')
        {
            if (isset($_SESSION['type_id']))
            {
                if ($_SESSION['type_id'] == 3)
                {
                    $sql = $sql." WHERE carrier_id=".$_SESSION['carrier_id'];
                } else if ($_SESSION['type_id'] < 3){
                    header("Location: index.php");
                }
            } else {
                header("Location: index.php");
            } 
        }

        parse_table($_GET['db'],$sql);
        ?>

    </table>
    </div>
    <form id="form" method="post" action="#" style="display:none">
        <input name="rowPK" id="rowPK" value=0></input>
        <input name="action" id="action" value=""></input>
        <input name="db" id="db" value=""></input>
    </form>


    <?php print_database_footer($_GET['db']);?>


    
<script src="databaseManip.js" type="text/javascript"></script>
<?php if($_GET['db'] == "link"):?>
<script>
document.getElementById("styled-table").addEventListener('dblclick', e => {
    if(e.target.parentNode.classList.contains("tableTr")){
        var target = e.target.parentNode.cells[0].innerText;
        location.href = "selectLinkEdit.php?linkPK="+target;
    }
});
</script>
<?php endif;?>
<?php if($_GET['db'] == "carrier" && $_SESSION['type_id'] < 4):?>
<script>
    document.getElementById('newButton').style.display = "none";
    const deleteB = document.getElementById('deleteButton');
    if(deleteB){
        deleteB.style.display = "none";
    }
</script>
<?php endif;?>
</body>
</html>
