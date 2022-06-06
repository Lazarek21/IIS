<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="searchResults.css">
<title>Výsledky</title>
</head>
<body>
<?php require_once('common.php');?>
<?php require_once('parse_table.php');?>
<div>
<?php print_links(find_connections($_GET['from'], $_GET['whereto'], $_GET['time']));?>
</div>

<script>

    document.getElementById("linkTable").addEventListener('click', e => {
        var redirect = "seat_reservation.php?";
        console.log(e.target);
        if(e.target.classList.contains("linkButton")){
            console.log(e.target.children[0].children[0].children[0]);
            location.href = redirect.concat("linkPK=",e.target.children[0].children[0].children[0].innerText);

        } else if (e.target.parentNode.classList.contains("linkButton")){
            console.log(e.target.parentNode.children[0].children[0].children[0]);
            location.href = redirect.concat("linkPK=",e.target.parentNode.children[0].children[0].children[0].innerText);

        } else if (e.target.parentNode.parentNode.classList.contains("linkButton")){
            console.log(e.target.parentNode.parentNode.children[0].children[0].children[0]);
            location.href = redirect.concat("linkPK=",e.target.parentNode.parentNode.children[0].children[0].children[0].innerText);

        } else if (e.target.classList.contains("linkPK")){
            location.href = redirect.concat("linkPK=",e.target.innerText);
        }
    });

</script>

</body>
</html>
