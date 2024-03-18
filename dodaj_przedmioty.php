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
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>E-dziennik - Dodaj przedmioty</title>
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
            <h2>Dodaj przedmiot</h2>
            Podaj nazwe przedmiotu<input type="text" name="przedmiot"><br>
            
            <?php
                 $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
                 if(isset($_POST['przedmiot'])) {   
                 $przedmiot = $_POST['przedmiot'];
                    $q = "INSERT INTO `przedmioty`(`id`, `nazwa_przedmiotu`) VALUES ('','$przedmiot')";
                    $row = mysqli_query($conn,$q);
                    echo "Poprawnie dodano przedmiot";
                 }
                 mysqli_close($conn);
            ?>
            <input type="submit" value="Dodaj przedmiot">
        </form>
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