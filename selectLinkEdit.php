<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<title>Úprava linky</title>
</head>

<body>
    <?php require_once('common.php')?>
    <?php
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id'] < 2){
        header("Location:selectLink.php");
        die();
    }
    ?>
    <div style="position:fixed; width:fit-content; margin:auto;top:50%;left:50%;transform: translate(-50%, -50%);text-align:center" >
        <h3>Linka <?php echo $_GET['linkPK'];?></h3>
        <?php if($_SESSION['type_id'] > 2):?>
        <button id="seatsButton" style="font-size:35px;">Sedadla</button>
        <button id="stopButton" style="font-size:35px;">Zastávky</button>
        <?php endif;?>
        <button id="positionButton" style="font-size:35px;">Pozice</button>
        <button id="reservationButton" style="font-size:35px;">Rezervace</button>
        
    </div>

<script type="text/javascript">
    <?php if($_SESSION['type_id'] > 2):?>
    document.getElementById("seatsButton").onclick = function () {
        location.href = "editPriceCategory.php?linkPK=<?php echo $_GET['linkPK'];?>";
    };

    document.getElementById("stopButton").onclick = function () {
        location.href = "editLinkStops.php?linkPK=<?php echo $_GET['linkPK'];?>";
    };
    <?php endif;?>
    
    document.getElementById("positionButton").onclick = function () {
        location.href = "position.php?linkPK=<?php echo $_GET['linkPK'];?>";
    };

    document.getElementById("reservationButton").onclick = function () {
        location.href = "approve.php?linkPK=<?php echo $_GET['linkPK'];?>";
    };

    
</script>
</body>
</html>