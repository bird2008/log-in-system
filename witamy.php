<?php

    session_start();

    if(!isset($_SESSION['udanarejestracja']))
    {
        header('Location: index.php');
        exit();
    }else
    {
        unset($_SESSION['udanarejestracja']);
    }

    //Usuwanie zmiennych pamiętających wartości wpisane do formularza
    if(isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
    if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if(isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
    if(isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
    if(isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

    //Usuwanie błędów rejestracji
    if(isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
    if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if(isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
    if(isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo2']);
    if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Witamy!</title>
    <link rel="shortcut icon" href="favicon1.ico"  type="image/x-icon">
    <link rel="stylesheet" href="wit.css" type="text/css" />
</head>

<body>

    Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!<br/><br/>

    <a href="index.php">Zaloguj się na swoje konto!</a>
    <br/><br/>

</body>
</html>