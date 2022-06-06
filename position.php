<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="position.css">
<script type="text/javascript" src="search.js"></script>
<title>Pozice</title>
</head>
<body>
    <?php require_once('common.php');?>
    <?php 
    if(!isset($_SESSION['type_id']) || $_SESSION['type_id'] == 0){
        header("Location:index.php");
        die();
    }?>
    <div style=";width:0;border-left: 25px solid transparent;border-right: 25px solid transparent;border-bottom: 50px solid #fff;margin-left:auto;margin-right:auto;margin-top:40px;"></div>
    <?php
        if((!isset($_SESSION['type_id']) || $_SESSION['type_id'] == 3 || $_SESSION['type_id'] == 2) && !in_array($_GET['linkPK'], array_values($_SESSION["links"]))){
            header("Location:selectLink.php");
            die();
        }
        $_SESSION['link_id'] = $_GET['linkPK'];
        require_once('parse_table.php');
        print_stops();
    ?>

<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']>1):?>
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <form id="form" method="post" action="processDbRequest.php" style="display:none" target="dummyframe">
    <input name="action" value="update">
    <input name="db" value="link">
    <input name="cond[link_id]" value="<?php echo $_GET['linkPK']?>">
    <input id="stop_id" name="stop_id" value="">
    </form>
    <div style="margin-left:auto;margin-right:auto;text-align:center;position:fixed;bottom:0;left: 50%;transform: translate(-50%, 0);">
        <button id="upStop" onClick="return upStop()"><i class="arrow up"></i></button>
        <button id="DownStop" onClick="return downStop()"><i class="arrow down"></i></button>
    <div>
    
<?php endif;?>   
    

<script>
    var stops = document.getElementsByClassName("stopRow");
    var stop = document.getElementsByClassName('selected')[0];
    const stop_id = document.getElementById("stop_id");
    const form = document.getElementById("form");
    
    function downStop(){
        if(stop == null){
            console.log(stops);
            stop = stops[stops.length-1].children[0];
            stop.classList.toggle("selected");
        } else {
            var breakCon = false;
            var prevStop = stop;
            for (let item of stops) {
                stop = item.children[0];
                if(breakCon){
                    prevStop.classList.toggle("selected");
                    stop.classList.toggle("selected");
                    break;
                }
                if(stop.classList.contains("selected")){
                    breakCon = true;
                }
                prevStop = stop;  
            }
        }
        console.log(stop.id);
        stop_id.value = stop.id;
        form.submit();
    }

    function upStop(){
        if(stop == null){
            stop = stops[0].children[0];
            stop.classList.toggle("selected");
        } else {
            var breakCon = false;
            var prevStop = null;
            for (let item of stops) {
                stop = item.children[0];
                if(prevStop == null){
                    prevStop = stop;
                }
                if(breakCon){
                    
                    break;
                }
                if(stop.classList.contains("selected")){
                    if(prevStop != stop){
                        stop.classList.toggle("selected");
                        prevStop.classList.toggle("selected");                    
                        breakCon = true;
                        stop=prevStop;
                    }
                    
                }  
                prevStop = stop;
            }
        }
        console.log(stop.id);
        stop_id.value = stop.id;
        form.submit();
    }

</script>

</body>
</html>
