<?php
    session_start();
    $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');

    $login = $_SESSION['luser'];
    $q = "SELECT `uprawnienia` FROM `login` WHERE `nazwa_uzytkownika` = '$login'";
    $row = mysqli_query($conn,$q);
    $permission = mysqli_fetch_row($row);

    if ($permission[0] != 3) {
        header('Location: http://localhost/2tp1/E-dziennik/login.php');;
    }
    else {
        $now = time();

        if ($now > $_SESSION['expire']) {
            session_destroy();
            echo "Twoja sesja wygasła!!! Zaloguj się ponownie <a href='http://localhost/2tp1/E-dziennik/login.php'>Login here</a>";
        }
        else {
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>E-dziennik - Login</title>
</head>
<body>
<header class="Banner">
        <h1>Dziennik</h1>
        <?php
               echo "Witaj ";
               echo $_SESSION['luser'];
               echo "<br>";
               echo "<a href='http://localhost/2tp1/E-dziennik/logout.php'> Wyloguj się</a>";       
        ?>
</header>
<aside class="Main">
    <form name="form1" method="post">
        <table>
            <tr>
                <td>Nazwa użytkownika</td>
                <td><input type="text" name="login"></td>
            </tr>
            <tr>
                <td>Hasło</td>
                <td><input type="password" name="pass"></td>
            </tr>
            <tr>
                <td>Powtórz hasło</td>
                <td><input type="password" name="re_pass"></td>
            </tr>
            <tr>
                <td><input type="radio" name="uprawnienia" value="Uczeń">Uczeń</input></td>
                <td><input type="radio" name="uprawnienia" value="Nauczyciel">Nauczyciel</input></td>
                <td><input type="radio" name="uprawnienia" value="Admin">Admin</input></td>
            </tr>
            <tr>
                <td><input type="submit" value="Zarejestruj się" name="submit"></td>
            </tr>
        </table>
    </form>
</aside>
<aside class="Menu">
        <h2>Menu</h2>
        <?php
        $login = $_SESSION['luser'];
        $q = "SELECT `uprawnienia` FROM `login` WHERE `nazwa_uzytkownika` = '$login'";
        $row = mysqli_query($conn,$q);
        $permission = mysqli_fetch_row($row);
        if ($permission[0] == 0) {
            include "menu-user.php";
        }
        if ($permission[0] == 2) {
            include "menu-teacher.php";
        }
        if ($permission[0] == 3) {
            include "menu-admin.php";
        }
        ?>
    </aside>
</body>
</html>

<?php
    if (isset($_POST['submit'])) {
        if ($_POST['re_pass'] == $_POST['pass'])
        $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
        
        $q = "SELECT `nazwa_uzytkownika`,`haslo` FROM `login`";
        $row = mysqli_query($conn,$q);
        $login = mysqli_fetch_row($row);

        $form[0] = $_POST['login'];
        $form[1] = $_POST['pass'];
        $form[3] = $_POST['uprawnienia'];
        
        if ($form[3] == "Uczeń") {
            $permission = 1;
        }
        if ($form[3] == "Nauczyciel") {
            $permission = 2;
        }
        if ($form[3] == "Admin") {
            $permission = 3;
        }


        if ($login[0] != $form[0]) {
            $q = "INSERT INTO `login`(`id`,`nazwa_uzytkownika`, `haslo`, `uprawnienia`) VALUES ('','$form[0]','$form[1]','$permission')";
            $register = mysqli_query($conn,$q);
            echo "Poprawnie dodano użytkownika";
        } else {
            echo "Nie poprawny login lub hasło!";
        }
    }
?>
<?php
        }
    }
    mysqli_close($conn);
?>