<?php

    session_start();

    if(isset($_POST['email']))
    {
        //Udana walidacja? TAK!
        $wszystko_OK=true;

        //Nick - sprawdzenie
        $nick = $_POST['nick'];

        if((strlen($nick)<3) || (strlen($nick)>20))
        {
            $wszystko_OK=false;
            $_SESSION['e_nick']="Nick musi podiadać od 3 do 20 znaków!";
        }

        if(ctype_alnum($nick)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_nick']="Nick może się składać tylko z liter i cyfr (bez polskich znaków)!";
        }

        //E-mail - sprawdzenie
        $email=$_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
        {
            $wszystko_OK=false;
            $_SESSION['e_email']="Podaj poprawny adres e-mail!";
        }

        //Hasło - sprawdzenie
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];

        if((strlen($haslo1)<8) || (strlen($haslo1)>20))
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo']="Hasło musi posiqadać od 8 do 20 znaków!";
        }

        if($haslo1!=$haslo2)
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
        }

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

        //Czy zaakceptowano regulamin
        if(!isset($_POST['regulamin']))
        {
            $wszystko_OK=false;
            $_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
        }

        //Bot or not?
        $sekret = "6Ldtpa4bAAAAAFfyNW4i2OJ5wJCjyqJIFibhpvq3";

        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

        $odpowiedz = json_decode($sprawdz);

        if($odpowiedz->success==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_bot']="Potwierdź, że nie jestes botem!";
        }

        //Zapamiętaj wprowadzone dane
        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;
        if(isset($_POST['regulamin']))$_SESSION['fr_regulamin'] = true;

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
           $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
           if($polaczenie->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            }else 
            {
                //czy email już istmieje?
                $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

                if(!$rezultat) throw new Exception($polaczenie->error);

                $ile_takich_maili = $rezultat->num_rows;
                if($ile_takich_maili>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_email']="Istnieje już konto przypisane do tego adresu email!";
                }

                if($wszystko_OK==true)
                {
                    //Udało się!
                    if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, now() + INTERVAL 14 DAY)"))
                    {
                        $_SESSION['udanarejestracja']=true;
                        header('Location: witamy.php');
                    }else 
                    {
                        throw new Exception($polaczenie->error);
                    }
                }

                $polaczenie->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestracje w innym terminie!</span>';
            echo '<br />Informacja developerska: '.$e;
        }

    }

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Rejestracja</title>
    <link rel="shortcut icon" href="favicon1.ico"  type="image/x-icon">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <link rel="stylesheet" href="reg.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
        .error
        {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div id="container">

        <form method="post">

            <br/> <input type="text" placeholder="Nickname" value="<?php
                if(isset($_SESSION['fr_nick']))
                {
                    echo $_SESSION['fr_nick'];
                    unset($_SESSION['fr_nick']);
                }
            ?>" name="nick" /> 

            <?php

                if(isset($_SESSION['e_nick']))
                {
                    echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                    unset($_SESSION['e_nick']);
                }

            ?>

             <input type="text" placeholder="E-mail" value="<?php
                if(isset($_SESSION['fr_email']))
                {
                    echo $_SESSION['fr_email'];
                    unset($_SESSION['fr_email']);
                }
            ?>" name="email" /> 

            <?php

                if(isset($_SESSION['e_email']))
                {
                    echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                }

            ?>

            <input type="Password" placeholder="Hasło" value="<?php
                if(isset($_SESSION['fr_haslo1']))
                {
                    echo $_SESSION['fr_haslo1'];
                    unset($_SESSION['fr_haslo1']);
                }
            ?>" name="haslo1" /> 

            <?php

                if(isset($_SESSION['e_haslo']))
                {
                    echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
                    unset($_SESSION['e_haslo']);
                }

            ?>

            <input type="Password" placeholder="Powtórz hasło" value="<?php
                if(isset($_SESSION['fr_haslo2']))
                {
                    echo $_SESSION['fr_haslo2'];
                    unset($_SESSION['fr_haslo2']);
                }
            ?>" name="haslo2" /> 

            <br/><br/>
            <div class="guzik">
                <input type="checkbox" id="check" <?php
                if(isset($_SESSION['fr_regulamin']))
                {
                    echo "checked";
                    unset($_SESSION['fr_regulamin']);
                }
            ?> name="regulamin" /> 
            <label for="check">Akceptuję regulamin</label>
            </div>
            <?php

                if(isset($_SESSION['e_regulamin']))
                {
                    echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                    unset($_SESSION['e_regulamin']);
                }

            ?>

            <br/>

            <div class="g-recaptcha" data-sitekey="6Ldtpa4bAAAAACccbpvb21xlqXyP9VEWG4p-MZRl"></div>

            <?php

                if(isset($_SESSION['e_bot']))
                {
                    echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                    unset($_SESSION['e_bot']);
                }

            ?>

            

            <input type="submit" value="Zarejestruj się"/>

        </form>
    </div> 

</body>
</html>