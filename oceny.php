            <script type="text/JavaScript">
                //brak refeshu strony po kliknieciu

            function submitFrom()
            {
                var textbox = document.getElementById("ocena");
                var url =
                    "http://localhost/2tp1/E-dziennik/oceny.php?klasa=" + encodeURIComponent(textbox.value);
            
                // get the URL
                http = new XMLHttpRequest(); 
                http.open("GET", url, true);
                http.send(null);
            
                // prevent form from submitting
                return false;
            }
            
            </script>
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
//Pobieranie wartości
            $parts = explode(" ",$_SESSION['luser']);
            $name = $parts[0];
            $surname = $parts[1];

            $q = "SELECT `id_przedmiot` FROM `nauczyciel` WHERE `imie` = '$name' AND `nazwisko` = '$surname'";
            $row = mysqli_query($conn,$q);
            $idclass = mysqli_fetch_row($row);
    
            $q = "SELECT `nazwa_klasy` FROM `lista_klas`;";
            $row1 = mysqli_query($conn,$q);

//Wyświetlanie tabeli
                echo"<form method=\"get\">";
                echo"<h3>Wybierz klase:</h3>";
                echo "<select name=\"klasa\">";
                 while ($class = mysqli_fetch_row($row1)) {         
                    echo "<option value='$class[0]'>";
                    echo "$class[0] <br>";
                    echo "</option>";
                 }
                echo "<input type=\"checkbox\" name=\"showGrades\">";
                echo "Pokaż oceny";
                echo "<br><input type=\"submit\" value=\"Potwierdź\">";
                if(isset($_GET['klasa'])) {
                $selected_class1 = $_GET['klasa'];
                $q="SELECT `id` FROM `lista_klas` WHERE `nazwa_klasy` = '$selected_class1'";
                $row=mysqli_query($conn,$q);
                $selected_class = mysqli_fetch_row($row);

                echo "</select>";
                echo "<button type=\"submit\">Wyświetl</button>";
                echo "</form>";
                if(isset($_GET['klasa']) && $_GET['showGrades']) {
                    $q = "SELECT oceny.id, uczen.imie, oceny.id_przedmiot, oceny.ocena FROM `oceny` INNER JOIN `uczen` ON oceny.id_uczen = uczen.id;";
                    $row = mysqli_query($conn,$q);
                    while($show = mysqli_fetch_row($row)) { 
                    echo"$show[0] | imie: $show[1] ocena: $show[3] <br>";
                    }
                } elseif (isset($_GET['klasa'])) {
                    $_SESSION['klasa'] = $selected_class;
                    $_SESSION['klasa1'] = $selected_class1;
                    header(('Location: http://localhost/2tp1/E-dziennik/dodawanie_ocen.php'));
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