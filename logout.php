<?php
    session_start();
    session_destroy();
    header('Location:http://localhost/2tp1/E-dziennik/login.php');
?>