<?php
    session_start();
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
                <td><input type="submit" value="Zaloguj się" name="submit"></td>
            </tr>
        </table>
    </form>
</aside>
</body>
</html>

<?php
    if (isset($_POST['submit'])) {
        $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');

        $form[0] = $_POST['login'];
        $form[1] = $_POST['pass'];

        $q = "SELECT `nazwa_uzytkownika`,`haslo`,COUNT(`nazwa_uzytkownika`) FROM `login` WHERE `nazwa_uzytkownika` = '$form[0]'";
        $row = mysqli_query($conn,$q);
        $login = mysqli_fetch_row($row);



if ($login[2] > 0) {
        if ($login[0] == $form[0] && $login[1] == $form[1]) {
            $_SESSION['luser'] = $login[0];
            $_SESSION['start'] = time();
            $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
            header('Location: http://localhost/2tp1/E-dziennik/index.php');
        } else {
            echo "Nie poprawny login lub hasło!";
        }
}
        mysqli_close($conn);
    }
?>