<?php
require "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>login</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
</head>
<body>
    <header>
        <div class="container">
            <a href="<?=$base;?>"><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?=$base;?>/signup_action.php">
        <?php
        if(!empty($_SESSION['flash'])) {
            echo $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        ?>
            <input placeholder="Digite seu nome completo" class="input" type="text" name="name" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite uma senha" class="input" type="password" name="password" />

            <input placeholder="Digite sua data de nascimento" class="input" name="birthdate" id="birthdate" />

            <input class="button" type="submit" value="Fazer cadastro" />

            <a href="<?=$base;?>/login.php">Já possui uma conta? Acesse aqui</a>
        </form>
    </section>

    
    <script src="https://unpkg.com/imask@7.4.0/dist/imask.js"> </script>
    <script>
        IMask (
            document.getElementById("birthdate"),
            {mask: '00/00/0000'}
        );
    </script>
</body>
</html>