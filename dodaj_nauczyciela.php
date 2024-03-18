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
    <title>E-dziennik - Dodaj nauczyciela</title>
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
            <h2>Dodaj nauczyciela do wybranej klasy</h2>
            Podaj imie nauczyciela<input type="text" name="imie"><br>
            Podaj nazwisko nauczyciela<input type="text" name="nazwisko"><br>
            <?php
                 $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
                 $q = "SELECT * FROM `lista_klas`;";

                 if ($result = mysqli_query($conn, $q)) {
                    $rowcount = mysqli_num_rows( $result );
                 }

                    $i = 1;
                    echo '<select name="klasa">';
                 while ($i<=$rowcount) {         
                    $q = "SELECT DISTINCT `nazwa_klasy`,COUNT(`id`) FROM `lista_klas` WHERE id = '$i';";
                    $row = mysqli_query($conn,$q);
                    $result = mysqli_fetch_row($row);

                    echo "<option value='$result[0]'>";
                    echo "$result[0] <br>";
                    $i++;
                 }

                 mysqli_close($conn);
            ?>
            <input type="submit" value="Dodaj nauczyciela">
        </form>
        <?php
            if(isset($_POST['klasa']) || isset($_POST['imie'])) {
                if ($_POST['klasa'] != "" && $_POST['nazwisko'] != "") {
                    $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
                    
                    $klasa = $_POST['klasa'];
                    $nazwisko = $_POST['nazwisko'];
                    $imie = $_POST['imie'];
                    $_SESSION['ruser'] = $imie . ' ' . $nazwisko;
                    
                    $q = "SELECT `id` FROM `lista_klas` WHERE `nazwa_klasy` = '$klasa'";
                    $row = mysqli_query($conn,$q);
                    $id = mysqli_fetch_array($row);
                    $IDD = $id[0];

                    $q = "INSERT INTO `nauczyciel`(`id`, `imie`, `nazwisko`, `id_klas`) VALUES ('','$imie','$nazwisko',$IDD)";
                    $result = mysqli_query($conn,$q);
                    //NIE DZIALA
                    include "register-teacher.php";
                    if ($row) {
                        echo "Nauczyciel poprawnie dodany do klasy";
                    }   
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