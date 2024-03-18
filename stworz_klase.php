<?php
    session_start();

    if (!isset($_SESSION['luser'])) {
        header('Location: http://localhost/2tp1/E-dziennik/login.php');
    }
    else {
        $now = time();

        if ($now > $_SESSION['expire']) {
            session_destroy();
            echo "Twoja sesja wygasła!!! Zaloguj się ponownie <a href='http://localhost/2tp1/E-dziennik/login.php'>Login here</a>";
        }
        else {
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>E-dziennik - Stwórz klase</title>
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
        <form method="post">
            <h2>Stwórz klase</h2>
            Podaj nazwe klasy<input type="text" name="klasa"><br>
            Podaj skrot klasy<input type="text" name="skrot"><br>
            Podaj rok rozpoczęcia nauki<input type="date" name="rok"><br>
            <input type="submit" value="Stworz klase">
        </form>
        <?php
            if(isset($_POST['klasa']) || isset($_POST['skrot'])) {
                if ($_POST['klasa'] != "") {
                $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
                    $klasa = $_POST['klasa'];
                    $skrot = $_POST['skrot'];
                    $rok = $_POST['rok'];

                    $q = "INSERT INTO `lista_klas`(`id`, `nazwa_klasy`, `rok_rozpoczecia`, `skrot`) VALUES ('','$klasa','$rok','$skrot')";
                    $result = mysqli_query($conn,$q);
                    if ($result) {
                    echo "<br>Poprawnie dodano klase do bazy danych";
                    }
                    mysqli_close($conn);
                }
        }
        ?>
    </aside>
    <aside class="Menu">
    <h2>Menu</h2>
        <?php
        $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
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
        mysqli_close($conn);
        ?>
    </aside>
</body>
</html>
<?php 
        }
    }
?>