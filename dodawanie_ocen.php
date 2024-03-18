<?php
    session_start();
    $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');

    $login = $_SESSION['luser'];
    $q = "SELECT `uprawnienia` FROM `login` WHERE `nazwa_uzytkownika` = '$login'";
    $row = mysqli_query($conn,$q);
    $permission = mysqli_fetch_row($row);

    if ($permission[0] != 2) {
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
    <title>E-dziennik - Oceny</title>
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
    <?php
                $parts = explode(" ",$_SESSION['luser']);
                $name = $parts[0];
                $surname = $parts[1];
                $q = "SELECT `id_przedmiot` FROM `nauczyciel` WHERE `imie` = '$name' AND `nazwisko` = '$surname'";
                $row = mysqli_query($conn,$q);
                $idclass = mysqli_fetch_row($row);

            $selected_class = $_SESSION['klasa'];
            $selected_class1 = $_SESSION['klasa1'];

            $q = "SELECT uczen.id,uczen.imie,uczen.nazwisko,lista_klas.nazwa_klasy FROM `uczen` INNER JOIN `lista_klas` ON uczen.id_klas = lista_klas.id WHERE lista_klas.id = '$selected_class[0]';";
            $row = mysqli_query($conn,$q);
            
            $table1=mysqli_fetch_row($row);
            $q = "SELECT `ocena` FROM `oceny` WHERE `id_uczen` = '$table1[0]'";
            $row2 = mysqli_query($conn,$q);
            
    echo"<form method=\"get\" onsubmit=\"return submitForm()\" action=\"dodawanie_ocen.php\";>";
            echo"<table>";
            echo"<h1>Nazwa klasy: $selected_class1</h1>";
            $id = 1;
         //   while($grade = mysqli_fetch_row($row2)) {
            echo"Podaj: id<input type=\"text\" name=\"idd\">
        <br>Podaj ocene: <td><select name=\"ocena\" id=\"ocena\">
            <option value='0' selected></option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
        </select>
        <button type=\"submit\">Dodaj ocene</button></td></tr>";
        echo"<tr><td>NR</td><td>Imie</td><td>Nazwisko</td></tr>";

            while($table=mysqli_fetch_row($row)) {
                echo
                "<tr><td>$id</td><td>$table[1]</td><td>$table[2]</td>";
                $id++; 
       //     }
        }


        
        if(isset($_GET['ocena'])) {
            $name = explode(" ",$_SESSION['luser']);
            $q = "SELECT `id_przedmiot` FROM `nauczyciel` WHERE `imie` = '$name[0]' AND `nazwisko` = '$name[1]'";
            $row = mysqli_query($conn,$q);
            $subject = mysqli_fetch_row($row);
            $grade = $_GET['ocena'];
            $idd = $_GET['idd'];
            
            $q = "INSERT INTO `oceny`(`id`, `id_uczen`, `id_przedmiot`, `ocena`) VALUES ('','$idd',$idclass[0],'$grade[0]')";
            $added = mysqli_query($conn,$q);
            if($added) {
               header(('Location: http://localhost/2tp1/E-dziennik/dodawanie_ocen.php'));
            }
        }
            echo"</table>";
            echo"</form>";
?>
    </aside>
    <aside class="Menu">
    <h2>Menu</h2>
        <?php
        $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
        $login = $_SESSION['luser'];
        $q = "SELECT `uprawnienia` FROM `login` WHERE `nazwa_uzytkownika` = '$login'";
        $row3 = mysqli_query($conn,$q);
        $permission = mysqli_fetch_row($row3);
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