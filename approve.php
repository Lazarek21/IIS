<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="table.css">
<title>Schválení</title>
</head>
<body>
    <?php require_once('common.php');?>
    <?php $_SESSION['lastRefer'] = $_SERVER["REQUEST_URI"];?>
    
    <?php require_once('parse_table.php');
    if (isset($_SESSION['type_id']) && $_SESSION['type_id'] >= 2)
    {
        if ($_SESSION['type_id'] < 4){
            $sql = "SELECT * FROM link where link_id=? and carrier_id=?";
            try {
                $sth = $db->prepare($sql);
                $sth->execute(array($_GET['linkPK'],$_SESSION['carrier_id']));
            } catch (PDOException $e) {
                $_SESSION['dbMsg'] = $e->getMessage();
                header('Location: index.php');
                die();
            }
            if ($sth->fetch())
            {
                echo '<table id="styled-table-reservation" class="styled-table" style="overflow-x:scroll;">';
                echo '<h3>Čekající rezervace</h3>';
                parse_table('reservation',"SELECT reservation.reservation_id as 'číslo rezervace',user.email,COUNT(seat.seat_id) as 'počet sedadel',reservation.price as 'cena',seat.link_id as 'číslo linky' from user, reservation, seat where user.user_id=reservation.user_id and seat.reservation_id=reservation.reservation_id and reservation.status_id=1 and link_id=".$_GET['linkPK']." GROUP BY reservation.reservation_id;");
                echo '</table>';
                echo '<table id="styled-table-reservation-done" class="styled-table" style="overflow-x:scroll;">';
                echo '<h3>Vyřízené rezervace</h3>';
                parse_table('reservation',"SELECT reservation.reservation_id as 'číslo rezervace',user.email,COUNT(seat.seat_id) as 'počet sedadel',reservation.price as 'cena',seat.link_id as 'číslo linky' from user, reservation, seat where user.user_id=reservation.user_id and seat.reservation_id=reservation.reservation_id and reservation.status_id=2 and link_id=".$_GET['linkPK']." GROUP BY reservation.reservation_id;");
                echo '</table>';
            } else {
                if(isset($_GET['linkPK'])){
                    header("Location: selectLink.php");
                } else {
                    header("Location: index.php");
                }
            }
          
            
        } else {
           

            if (!isset($_GET['linkPK']))
            {
                echo '<table id="styled-table-reservation" class="styled-table" style="overflow-x:scroll;">';
                echo '<h3>Čekající rezervace</h3>';
                parse_table('reservation',"SELECT reservation.reservation_id as 'číslo rezervace',user.email,COUNT(seat.seat_id) as 'počet sedadel',reservation.price as 'cena',seat.link_id as 'číslo linky' from user, reservation, seat where user.user_id=reservation.user_id and seat.reservation_id=reservation.reservation_id and reservation.status_id=1 GROUP BY reservation.reservation_id;");
                echo '</table>';
                echo '<h3>Zastávky</h3>';
                echo '<table id="styled-table-stop" class="styled-table" style="overflow-x:scroll;">';
                parse_table('stop',"SELECT * FROM stop WHERE stop.status_id=1");
                echo '</table>';
                echo '<h3>Požadavky na změny</h3>';
                echo '<table id="styled-table-change" class="styled-table" style="overflow-x:scroll;">';
                parse_table('change_stop',"SELECT * FROM change_stop");
                echo '</table>';
            } else {
                echo '<table id="styled-table-reservation" class="styled-table" style="overflow-x:scroll;">';
                echo '<h3>Čekající rezervace</h3>';
                parse_table('reservation',"SELECT reservation.reservation_id as 'číslo rezervace',user.email,COUNT(seat.seat_id) as 'počet sedadel',reservation.price as 'cena',seat.link_id as 'číslo linky' from user, reservation, seat where user.user_id=reservation.user_id and seat.reservation_id=reservation.reservation_id and reservation.status_id=1 and link_id=".$_GET['linkPK']. " GROUP BY reservation.reservation_id;");
                echo '</table>';
                echo '<table id="styled-table-reservation-done" class="styled-table" style="overflow-x:scroll;">';
                echo '<h3>Vyřízené rezervace</h3>';
                parse_table('reservation',"SELECT reservation.reservation_id as 'číslo rezervace',user.email,COUNT(seat.seat_id) as 'počet sedadel',reservation.price as 'cena',seat.link_id as 'číslo linky' from user, reservation, seat where user.user_id=reservation.user_id and seat.reservation_id=reservation.reservation_id and reservation.status_id=2 and link_id=".$_GET['linkPK']. " GROUP BY reservation.reservation_id;");
                echo '</table>';
            }
        }

    } else {
        header("Location: index.php");
    }
    
    ?>

    <form id="form" method="post" action="#" style="display:none">
        <input name="rowPK" id="rowPK" value=0></input>
        <input name="db" id="db" value=""></input>
        <input name="action" id="action" value=""></input>
    </form>

    <div class="databaseFoot">
      <button id="approveButton" style="color: #0f0; visibility:hidden">Schválit</button>
      <button id="deleteButton" style="color: #f00 ;visibility:hidden">Odstranit</button>
    </div>

    <script type="text/javascript" src="approve.js"></script>

    <script type="text/javascript">
    var form = document.getElementById("form");

    document.getElementById("approveButton").onclick = function () {

        form.action = "processDbRequest.php";
        document.getElementById("rowPK").value = document.getElementsByClassName("selected")[0].cells[0].innerText;
        document.getElementById("rowPK").setAttribute("name", localStorage.getItem('db')+"_id");
        document.getElementById("db").value = localStorage.getItem('db');
        document.getElementById("action").value = "approve";
        form.submit();
    };
    
    document.getElementById("deleteButton").onclick = function () {
        form.action = "processDbRequest.php";
        document.getElementById("rowPK").value = document.getElementsByClassName("selected")[0].cells[0].innerText;
        document.getElementById("rowPK").setAttribute("name", localStorage.getItem('db')+"_id");
        document.getElementById("db").value = localStorage.getItem('db');
        document.getElementById("action").value = "delete";
        form.submit();
    };
    </script>
    
</body>
</html>
