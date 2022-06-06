<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="table.css">
<title>Moje Rezervace</title>
</head>
<body>
    <?php require_once('common.php')?>
    <?php $_SESSION['lastRefer'] = $_SERVER["REQUEST_URI"];?>
    <div style="overflow:auto; margin-top:50px; margin-bottom:50px;" >
    <table id="styled-table" class="styled-table" style="overflow-x:scroll;">
        <?php require_once('parse_table.php');
        $sql = "SELECT reservation.reservation_id as 'Číslo rezervace',COUNT(reservation.reservation_id) as 'Počet sedadel', reservation.price as 'Celková cena', seat.link_id as 'Číslo linky', reservation.status_id FROM reservation INNER JOIN user ON reservation.user_id = user.user_id INNER JOIN seat ON seat.reservation_id = reservation.reservation_id WHERE user.email='".$_SESSION['login']."' GROUP BY reservation.reservation_id";
        parse_table("reservation",$sql);
        ?>

    </table>
    </div>
    
    <form id="form" method="post" action="#" style="display:none">
        <input name="reservation_id" id="rowPK">
        <input name="action" value="delete"></input>
        <input name="db"value="reservation"></input>
    </form>

    <div class="databaseFoot">
        <button id="deleteButton">Stornovat</button>
    <div>

    
<script>
document.getElementById("deleteButton").onclick = function () {
    var form = document.getElementById("form");
    var sibling = document.getElementsByClassName("tableTr selected");
    if(sibling.length > 0){
        form.action = "processDbRequest.php";
        document.getElementById("rowPK").value = sibling[0].cells[0].innerText;
        form.submit();
    }
}
</script>

<script>
document.getElementById("styled-table").addEventListener('dblclick', e => {
    if(e.target.parentNode.classList.contains("tableTr")){
        var target = e.target.parentNode.cells[3].innerText;
        location.href = "position.php?linkPK="+target; 
    }
});
</script>

<script src="databaseManip.js" type="text/javascript"></script>
</body>
</html>
