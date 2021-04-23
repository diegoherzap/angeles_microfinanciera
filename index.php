<?php
session_start();
require('dbConnection/config.php');
/*echo $_SESSION['uid'];
if (isset($_SESSION['uid'])) {
    header("location: inicio.php");
}*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - AdmiCredit 1.0</title>
    <link rel="stylesheet" href="/angeles_microfinanciera/css/style.css" />
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
        integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous">
    </script>
</head>

<body class="login-page">
    <!--
        Ingresar credenciales. Generar una tabla de usuarios y contraseñas para autenticar a los usuarios.
    -->
    <div class="container">
        <section class="main row">
            <div class="col-md-12">
                <header>
                    <h1 class="titulos">
                        <img title="AMF" src="/angeles_microfinanciera/images/amf_logo_main.png" alt="AMF">
                        Bienvenid@ a AdmiCredit
                    </h1>
                    <h6 class="descripcion">
                        Sistema de Administración Crediticia de Ángeles Microfinanciera
                    </h6>
                </header>
            </div>
        </section>
        <section class="row">
            <!--<div class="col-md-7"></div>-->
            <!--
          Formulario de credenciales
        -->
            <form class="col-md-5" method="POST" action="index.php" style='padding-top: 15px'>
                <div class="credenciales input-box">
                    <label class="h4 mb-3" for="" style="color:white;">Iniciar sesión</label>
                    <input type="text" class="entrada form-control" name="username" placeholder="Usuario" required />
                    <input type="password" class="entrada form-control" name="password" placeholder="Contraseña"
                        required />
                    <!--Implementar login con validación de usuario y contraseña-->
                    <button class="btn btn-primary" name="enviar" style="width: 100%" type="submit"
                        value="Enviar">Entrar</button>
                    <span>
                        <a href="/angeles_microfinanciera/recuperar-contrasena.html">¿Olvidaste tu contraseña?</a>
                    </span>
                </div>
            </form>
        </section>
    </div>
    <?php
    if (isset($_POST['enviar'])) {
        $unm = $_POST['username'];
        $pwd = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$unm' AND password = '$pwd'";
        echo $sql;
        $sql_run = mysqli_query($conn, $sql);

        if (mysqli_num_rows($sql_run) > 0) {
            $_SESSION['uid'] = $unm;
            echo $_SESSION['uid'];
            header("location:inicio.php");
        } else {
            echo "<div class='container' style='padding-left: 30px'>
            <section class='row'>
            <div class='col-md-3 alert alert-danger' role='alert'>
            Usuario o contraseña incorrecta, inténtalo de nuevo</div>
            </section>";
        }
    }
    ?>
</body>

</html>