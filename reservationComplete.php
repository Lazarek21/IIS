<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<title>Rezervace</title>
</head>
<body>
    <?php require_once('common.php')?>
    <?php 
    if(!isset($_SESSION['tmp_login']) && !isset($_SESSION['type_id'])){
        header("Location:index.php");
        die();
    }?>

    <div style="display:block; text-align:center; width:fit-content; position:absolute;left:50%;top:50%;transform: translate(-50%, -50%);" >         
        <h4>Rezervace proběhla úspěšně</h4>
        
        <button onClick='location.href="index.php";' style="margin-bottom:60px">Domů</button>

        <?php if(!isset($_SESSION['type_id'])):?>
            <h4>Dokončit registraci</h4>

            <form id="formRegister" method="post" action="javascript:void(0);">
                    <div class="horLayout">
                        <label for="email">E-mail</label>
                        <input id="registerEmail" type="text" name="email" class="newInput" value="<?php echo $_SESSION['tmp_login'];?>" readonly></input>
                    </div>
                    <div class="horLayout">
                        <label for="pwd">Heslo</label>
                        <input id="registerPwd0" type="password" name="pwdFirst"></input>
                    </div>
                    <div class="horLayout">
                        <label for="pwd">Heslo znovu</label>
                        <input id="registerPwd1" type="password" name="pwdSecond"></input>
                    </div>
                    <input type="submit" class="inputButton" value="Registrovat" ></input>
                    <div id="registerErrMsg" style="display:table-cell;font-size:20px;text-align:center;color:#f00">
                        <?php
                            if(isset($_SESSION['errRegister'])){
                                echo $_SESSION['errRegister'];
                                unset($_SESSION['errRegister']);                             
                            }
                        ?>
                    </div>
                </form>
                

        <?php  endif; 
        
        ?>
    </div>

<script>
var formRegister = document.getElementById("formRegister");
formRegister.addEventListener("submit", form_register);

function form_register(){
    var correct = true;
    var registerEmail = document.getElementById("registerEmail");
    var registerPwd0 = document.getElementById("registerPwd0");
    var registerPwd1 = document.getElementById("registerPwd1");


    if (registerEmail.value == ""){
        registerEmail.style.border = "2px solid red";
        correct = false;
    } else {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(String(registerEmail.value).toLowerCase())){
            registerEmail.style.border = "2px solid red";
            correct = false;
            document.getElementById("registerErrMsg").innerText = "Neplatný email";
        } else {
            registerEmail.style.border = "none";
        }
        
    }

    if (registerPwd0.value == ""){
        registerPwd0.style.border = "2px solid red";
        correct = false;
    } else {
        registerPwd0.style.border = "none";
    }

    if (registerPwd1.value == ""){
        registerPwd1.style.border = "2px solid red";
        correct = false;
    } else {
        registerPwd1.style.border = "none";
    }

    if (registerPwd0.value != registerPwd1.value){
        registerPwd0.style.border = "2px solid red";
        registerPwd1.style.border = "2px solid red";
        document.getElementById("registerErrMsg").innerText = "Hesla se neshodují";
        correct = false;
    } else {
        if(document.getElementById("registerErrMsg").innerText == "Hesla se neshodují")
            document.getElementById("registerErrMsg").innerText = "";
    }

    if(correct){
        formRegister.action = "verify_register.php";
        formRegister.submit();
    }
}
</script>
</body>
</html>
