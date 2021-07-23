<?php

    session_start();

    if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        header('Location: gra.php');
        exit();
    }

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Logowanie</title>
    <link rel="shortcut icon" href="favicon1.ico"  type="image/x-icon">
    <link rel="stylesheet" href="ind.css" type="text/css" />
</head>

<body>
    

    <div class="cytat"> 

        Tylko martwi ujrzeli koniec wojny - Platon
    
        <br/><br/>
        
    </div>

    <br/><br/>

    <div id="container">

        <form action="zaloguj.php" method="post">

            <br/><input type="text" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'" name="login"/><br/>
            <input type="password" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'" name="haslo"/><br/>
            <input type="submit" id="zaloguj" value="Zaloguj się"/> 
            
        </form>

        <div class="register">

            <br/>

            <a href="rejestracja.php" class="reglink">Rejestracja - załóż darmowe konto!</a>
        
        </div>
    </div>

<?php

    if(isset($_SESSION['blad'])) echo $_SESSION['blad'];
    
?>

</body>
</html>