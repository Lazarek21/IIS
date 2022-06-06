<?php

session_start();
print_login_head();

if(!isset($_SERVER['HTTP_REFERER'])){
    $_SERVER['HTTP_REFERER'] = "index.php";
}

function print_login_head(){
    if(isset($_SESSION['dbMsg'])){
    echo '<script>alert("'.$_SESSION['dbMsg'].'");</script>';
    unset($_SESSION['dbMsg']);
    }

    echo '<div class="loginHead">';
    if(basename($_SERVER['PHP_SELF']) != 'login.php'){
        if(isset($_SESSION['login'])){
            echo '<button onclick="myFunction()" class="dropbtn">'.$_SESSION['login'].'</button>';
            echo '<div id="myDropdown" class="dropdown-content">';
            echo '<div class="dropdownRow"><a href="userReservations.php">Moje rezervace</a></div>';
            echo '<div class="dropdownRow"><a href="logout.php">Odhlásit</a></div>';
            if (basename($_SERVER["PHP_SELF"]) == "changePwd.php"){
                echo '<div class="dropdownRow"><a href="';
                echo basename($_SESSION['prevPage']);
                echo '">Zpět</a></div>';
            } else {
                echo '<div class="dropdownRow"><a href="changePwd.php">Změnit heslo</a></div>';
            }
            echo '</div>';
            echo '<script type="text/javascript">';
            echo 'function myFunction() {';
            echo '    console.log("haha");';
            echo '    document.getElementById("myDropdown").classList.toggle("show");';
            echo '  }';  
            echo '  window.onclick = function(event) {';
            echo '    if (!event.target.matches(".dropbtn")) {';
            echo '      var dropdowns = document.getElementsByClassName("dropdown-content");';
            echo '      var i;';
            echo '      for (i = 0; i < dropdowns.length; i++) {';
            echo '        var openDropdown = dropdowns[i];';
            echo '        if (openDropdown.classList.contains("show")) {';
            echo '          openDropdown.classList.remove("show");';
            echo '        }';
            echo '      }';
            echo '    }';
            echo '  }'; 

            echo '</script>';
        } else {
            echo '<button id="loginButton">Přihlásit se</button>';
            echo '<script type="text/javascript">
            document.getElementById("loginButton").onclick = function () {
                location.href = "login.php";
            };';
            echo '</script>';
        }
    } else {
        echo '<button id="backButton">Zpět</button>';
        echo '<script type="text/javascript">
        document.getElementById("backButton").onclick = function () {
            location.href = "';
        echo $_SESSION['prevPage'];
        echo ' ";
        };
        </script>';
    }
    echo '<button id="homeButton" class="HomeButton"><img width="25" alt="Home Icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Home_Icon.svg/32px-Home_Icon.svg.png"></button>';
    echo '<script type="text/javascript">
        document.getElementById("homeButton").onclick = function () {
            location.href = "index.php";
        };
        </script>';
    echo '</div>';
}

function print_database_footer($dbTable){
    echo '<div class="databaseFoot">';
    echo '  <button id="editButton">Upravit</button>';
    echo '  <button id="deleteButton">Smazat</button>';
    echo '  <button id="newButton">Přidat</button>';
    echo '</div>';

    echo '<script type="text/javascript">';
    echo 'document.getElementById("deleteButton").onclick = function () {';
    echo '    var sibling = document.getElementsByClassName("tableTr selected");';
    echo '    var form = document.getElementById("form");';
    echo '    if(sibling.length > 0){';
    echo '        form.action = "processDbRequest.php";';
    if(isset($_GET["linkPK"])){
        echo '    document.getElementById("link_id").value = "'.$_GET['linkPK'].'";';
        echo '    document.getElementById("time").value = sibling[0].cells[2].innerText;';
        echo '    console.log(document.getElementById("time").value);';
        echo '    document.getElementById("stop_id").value = sibling[0].cells[0].innerText;';
    }else{    
        echo '    var pk = document.getElementById("rowPK");';
        echo '    pk.setAttribute("name", "'.$dbTable.'_id");';
        echo '    pk.value = sibling[0].cells[0].innerText;';
        echo '    document.getElementById("action").value = "delete";';
        echo '    document.getElementById("db").value = "'.$dbTable.'";';
    }
    echo '        form.submit();';
    echo '    }';
    echo '};';
    echo '</script>';

    echo '<script type="text/javascript">';
    echo 'document.getElementById("newButton").onclick = function () {';
    echo '    localStorage.removeItem("tablePK");';
    echo 'var redirect_string = "editRow.php?action=insert&db='.$dbTable.'";';
    if(isset($_GET["linkPK"])){
        echo 'redirect_string += "&rowPK='.$_GET["linkPK"].'";';
    }
    echo '    location.href = redirect_string;';
    echo '};';
    echo '</script>';

    echo '<script type="text/javascript">';
    echo 'if(document.getElementById("editButton") != null){';
    echo '  document.getElementById("editButton").onclick = function () {';
    echo '      var redirect_string = "editRow.php?";';
    echo '  redirect_string += "action=update&db='.$dbTable.'";';
    echo '      var sibling = document.getElementsByClassName("tableTr selected");';
    echo '      if(sibling.length > 0){';
        if(isset($_GET["linkPK"])){
            echo 'redirect_string += "&rowPK='.$_GET["linkPK"].'";';
        }else{
            echo '        redirect_string = redirect_string.concat("&rowPK=", sibling[0].cells[0].innerText);';
        }
    echo '          console.log(redirect_string);';
    echo '      }';
    echo '          console.log(redirect_string);';
    echo '      location.href = redirect_string;';
    echo '  };';
    echo '}';
    echo '</script>';

    
}

 function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }