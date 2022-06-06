<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<script type="text/javascript" src="swap_rows.js"></script>
<title>Přihlášení</title>
</head>
<body>

    <?php
        require_once("common.php");
        if (basename($_SERVER['HTTP_REFERER']) <> 'login.php'){
            $_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
        }
    ?>
    <div style="margin: auto;">
        <div class="horLayout" >
            <div class="verLayout" style="float:left;">
                Přihlášení
                <form id="formLogin" method="post" action="javascript:void(0);" style="display:table;">
                    <div class="horLayout">
                        <label for="email">E-mail</label>
                        <input id="loginEmail" type="text" name="email" class="newInput"></input>
                    </div>
                    <div class="horLayout">
                        <label for="pwd">Heslo</label>
                        <input id="loginPwd" type="password" name="pwd"></input>
                    </div>
                    <input type="submit" class="inputButton" value="Přihlásit"></input>
                    <div id="loginErrMsg" style="display:table-cell;font-size:20px;text-align:center;color:#f00">
                        <?php
                        if(isset($_SESSION['errLogin'])){
                            echo $_SESSION['errLogin'];
                            unset($_SESSION['errLogin']);
                        }
                        ?>
                    </div>
                </form>

                
            </div>
            <div class="verLayout" style="float:left;">
                Registrace
                <form id="formRegister" method="post" action="javascript:void(0);">
                    <div class="horLayout">
                        <label for="email">E-mail</label>
                        <input id="registerEmail" type="text" name="email" class="newInput"></input>
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
            </div>
        </div>
    </div>


<script>

var formLogin = document.getElementById("formLogin");
var formRegister = document.getElementById("formRegister");

formLogin.addEventListener("submit", form_login);
formRegister.addEventListener("submit", form_register);

function form_login(){
    var correct = true;
    var loginEmail = document.getElementById("loginEmail");
    var loginPwd = document.getElementById("loginPwd");

    if (loginEmail.value == ""){
        loginEmail.style.border = "2px solid red";
        correct = false;
    } else {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(String(loginEmail.value).toLowerCase())){
            loginEmail.style.border = "2px solid red";
            correct = false;
            document.getElementById("loginErrMsg").innerText = "Neplatný email";
        } else {
            loginEmail.style.border = "none";
        }
    }

    if (loginPwd.value == ""){
        loginPwd.style.border = "2px solid red";
        correct = false;
    } else {
        loginPwd.style.border = "none";
    }

    console.log(correct);

    if(correct){
        formLogin.action = "verify_login.php";
        formLogin.submit();
    }
}

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