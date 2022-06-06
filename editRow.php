<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="table.css">
<title>IS DPMB</title>
</head>
<body>
    <?php 
        require_once('common.php'); 
        require_once('parse_table.php');
        echo '<form id="form" method="post" action="javascript:void(0);" ';
        if($_GET['action']=='delete'){
            echo 'style="display:none"';
        } else {
            echo 'style="display:table"';
        }
        echo '><br>';
        if(isset($_GET['rowPK'])){
            parse_row($_GET['db'], $_GET['rowPK']);
        } else {
            parse_row($_GET['db'], NULL);
        }
        echo '<div class="horLayout" style="text-align:right;column-span: all;">';
        echo '<input id="inputButton" type="submit" class="inputButton" value="Uložit"></input>';
        echo '</div>';
        echo '</form>';
    ?>

<script>
    var form = document.getElementById("form");
    console.log(form);
    <?php if ($_SESSION['type_id'] == 3 && $_GET['db'] == 'stop' && $_GET['action']=='update'):?>
        form[0].value = 'change_stop';  
        form[2].value = 'insert';
    <?php endif;?>

    <?php if($_GET['action'] == "delete"):?>
        formSubmit();
    <?php endif;?>

    attachFormSubmitEvent("form");
    
    function attachFormSubmitEvent(formId){
        document.getElementById(formId).addEventListener("submit", formSubmit);
    }

    function formSubmit(){
        form.action = "processDbRequest.php";
        form.submit();
    }

</script>
</body>
</html> 
