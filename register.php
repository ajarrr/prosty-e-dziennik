<?php
    $conn = mysqli_connect('localhost','root','','e-dziennik2tp1');
    
    $login = $_SESSION['ruser'];
    $random = random_int(1,100);
    $password = sha1($random);

    $q = "INSERT INTO `login`(`id`,`nazwa_uzytkownika`, `haslo`, `uprawnienia`) VALUES ('','$login','$password','1')";
    $row = mysqli_query($conn,$q);

    echo "Login: $login <br>";
    echo "Hasło: $password <br>";

    mysqli_close($conn);

?>