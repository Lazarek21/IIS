<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<title>Změna hesla</title>
</head>
<body>

    <?php
        require_once("common.php");
        if (basename($_SERVER['HTTP_REFERER']) <> 'login.php'){
            $_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
        }
    ?>
    <div style="margin: auto;">
            <div class="verLayout" style="float:left;">
                Změna hesla
                <form id="formChange" method="post" action="javascript:void(0);">
                    <input name="email" value="<?php echo $_SESSION['login'];?>" hidden></input>
                    <div class="horLayout">
                        <label for="pwdOld">Aktuální heslo</label>
                        <input id="oldPwd" type="password" name="pwdOld"></input>
                    </div>
                    <div class="horLayout">
                        <label for="pwdFirst">Nové heslo</label>
                        <input id="changePwd0" type="password" name="pwdFirst"></input>
                    </div>
                    <div class="horLayout">
                        <label for="pwdSecond" style="padding-right:10px">Nové heslo znovu</label>
                        <input id="changePwd1" type="password" name="pwdSecond"></input>
                    </div>
                    <input type="submit" class="inputButton" value="Změnit" ></input>
                    <div id="changeErrMsg" style="display:table-cell;font-size:20px;text-align:center;color:#f00">
                    <?php
                            if(isset($_SESSION['errChange'])){
                                echo '<p>'.$_SESSION['errChange'].'</p';
                                unset($_SESSION['errChange']);
                            }
                        ?>
                    </div>
                </form>
            </div>
    </div>


<script>

var formLogin = document.getElementById("formChange");

formChange.addEventListener("submit", form_change);

function form_change(){
    var correct = true;
    var oldPwd = document.getElementById("oldPwd");
    var changePwd0 = document.getElementById("changePwd0");
    var changePwd1 = document.getElementById("changePwd1");

    if (oldPwd.value == ""){
        oldPwd.style.border = "2px solid red";
        correct = false;
    } else {
        oldPwd.style.border = "none";
    }

    if (changePwd0.value == ""){
        changePwd0.style.border = "2px solid red";
        correct = false;
    } else {
        changePwd0.style.border = "none";
    }

    if (changePwd1.value == ""){
        changePwd1.style.border = "2px solid red";
        correct = false;
    } else {
        changePwd1.style.border = "none";
    }

    if (changePwd0.value != changePwd1.value){
        changePwd0.style.border = "2px solid red";
        changePwd1.style.border = "2px solid red";
        document.getElementById("changeErrMsg").innerText = "Hesla se neshodují";
        correct = false;
    } else {
        if(document.getElementById("changeErrMsg").innerText == "Hesla se neshodují")
            document.getElementById("changeErrMsg").innerText = "";
    }

    if(correct){
        formChange.action = "verify_change.php";
        formChange.submit();
    }
}

</script>
</body>
</html>