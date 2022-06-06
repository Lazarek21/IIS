<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="table.css">
<title>IS DPMB</title>
</head>
<body>
    
<?php require_once('common.php')?>
<div class="verLayout" style="text-align:center;margin-top:100px">
<button id="searchButton" class="submit-button" style="font-size:35px;">Vyhledat spoj</button>
<button id="selectLinkButton" class="submit-button" 
<?php 
    if((isset($_SESSION['type_id']) && $_SESSION['type_id'] == 0) || !isset($_SESSION['type_id'])){
        echo 'style="text-align:center;display:none"';
    } else {
        echo 'style="text-align:center;"';
    }
?>
>Vyhledat linku</button>
</div>


<div class="verLayout" 
<?php 
    if(isset($_SESSION['type_id']) && $_SESSION['type_id'] >2){
        echo 'style="text-align:center;"';
    } else {
        echo 'style="text-align:center;display:none"';
    }
?>
>
<button id="userDbButton" class="submit-button" style="font-size:35px;">Uživatelé</button>
<button id="linkDbButton" class="submit-button" style="font-size:35px;">Linky</button>
<button id="carrierDbButton" class="submit-button" style="font-size:35px;">Společnosti</button>
<button id="stopDbButton" class="submit-button" style="font-size:35px;">Zastávky</button>
</div>

<div class="verLayout" 
<?php 
    if(isset($_SESSION['type_id']) && $_SESSION['type_id'] >3){
        echo 'style="text-align:center;"';
    } else {
        echo 'style="text-align:center;display:none"';
    }
?>
>
    <button id="approveButton" class="submit-button" style="font-size:35px;">Schválení</button>
</div>

<script type="text/javascript">
    document.getElementById("searchButton").onclick = function () {
        location.href = "search.php";
    };

    document.getElementById("userDbButton").onclick = function () {
        location.href = "dbTable.php?db=user";
    };

    document.getElementById("linkDbButton").onclick = function () {
        location.href = "dbTable.php?db=link";
    };

    document.getElementById("carrierDbButton").onclick = function () {
        location.href = "dbTable.php?db=carrier";
    };

    document.getElementById("stopDbButton").onclick = function () {
        location.href = "dbTable.php?db=stop";
    };

    document.getElementById("selectLinkButton").onclick = function () {
        location.href = "selectLink.php";
    };

    document.getElementById("approveButton").onclick = function () {
        location.href = "approve.php";
    };

</script>
</body>
</html>
