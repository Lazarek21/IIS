<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="common.css">
<title>Změna hesla</title>
</head>
<body>
    <?php 
        session_start();
        require_once("connect.php");

        $email = $_POST['email'];
        $oldPwd = $_POST['pwdOld'];
        $newPwd = $_POST['pwdFirst'];

        try {
            $sql = 'SELECT * FROM user WHERE email = \''.$email.'\'';
            $result = $db->query($sql);
            foreach ($result as $row) {
                if (password_verify($oldPwd, $row['password'])){
                    //zmenime heslo
                    $sql = 'UPDATE user 
                    SET password = "'.password_hash($newPwd, PASSWORD_DEFAULT).'"
                    WHERE email = "'.$email.'";';
                    $db->exec($sql);
                } else {
                    //nezadal spravne stare heslo
                    header("Location:".$_SERVER['HTTP_REFERER']);
                    $_SESSION['errChange'] = "Chybne aktuální heslo!";
                    die();
                }
            }
        } catch (PDOException $e) {
            header("Location:".$_SERVER['HTTP_REFERER']);
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
    ?>
    <div style="position:fixed;top:50%;left:50%;transform: translate(-50%, -50%);">
        <p>Úspěšně změněno</p>
        <p id="waitingText"></p>
    </div>

    <script>
        function myFunction(){
            location.href = "index.php";
        }
        function printOne(){
            document.getElementById('waitingText').innerText = "Probíhá přesměrování."
        }
        function printTwo(){
            document.getElementById('waitingText').innerText = "Probíhá přesměrování.."
        }
        function printThree(){
            document.getElementById('waitingText').innerText = "Probíhá přesměrování..."
        }

        setTimeout(myFunction, 3000);
        for (let index = 1; index < 11; index++) {
            setTimeout(printOne, index*900-900);
            setTimeout(printTwo, index*900-600);
            setTimeout(printThree, index*900-300);
        }
    </script>
</body>
</html>