<?php
session_start();
ob_start();
date_default_timezone_set("America/Mexico_City");
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo Cliente - AdmiCredit1.0</title>
    <link rel="stylesheet" href="/html/css/style.css" />
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

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0 justify-content-between">
        <header class="cabecera">
            <span href="inicio.php" class="navbar-brand titulos col-md-3">AdmiCredit 2020</span>
        </header>

        <li class="navbar-nav nav-item dropdown text-no-wrap">
            <div class="container">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" style="text-align:right">
                    Hola!,
                    <?php
                    if (isset($_SESSION['uid'])) {
                        echo $_SESSION['uid'];
                    } else {
                        echo "No se ha guardado el UID";
                    }
                    ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="box-sizing:border-box">
                    <a class="dropdown-item" href="cerrarSesion.php" style="text-align:center">Cerrar Sesión</a>
                </div>
            </div>
        </li>
    </nav>

    <div class="container-fluid">
        <div style="padding-top:15px row">
            <div class="col-md-6 offset-md-3">
                <form action="nuevoCliente.php" method="post">
                    <h4><span>Nuevo Cliente</span></h4>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre(s)"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="apellidoPat" class="form-control" placeholder="Apellido Paterno"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="apellidoMat" class="form-control" placeholder="Apellido Materno"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="curp" class="form-control" placeholder="CURP"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="domicilio" class="form-control" placeholder="Domicilio"
                        onkeyup="this.value = this.value.toUpperCase();" required>
                    <input type="text" name="cp" class="form-control" placeholder="C.P."
                        onkeyup="this.value = this.value.toUpperCase();" required>
                    <input type="text" name="colonia" class="form-control" placeholder="Colonia"
                        onkeyup="this.value = this.value.toUpperCase();" required>
                    <input type="text" name="ciudad" class="form-control" placeholder="Ciudad"
                        onkeyup="this.value = this.value.toUpperCase();" required>
                    <input type="text" name="estado" class="form-control" placeholder="Estado"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="telefono1" class="form-control" placeholder="Teléfono principal"
                        onkeyup="this.value = this.value.toUpperCase();" required>

                    <input type="text" name="telefono2" class="form-control" placeholder="Teléfono secundario"
                        onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">Fecha de Nacimiento</span>
                        </div>
                        <input type="date" name="fechaNacimiento" class="form-control" aria-describedby="addon-wrapping"
                            required>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <button type="submit" name="agregar" class="btn btn-primary">Agregar</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-light">
                                <a href="inicio.php">Cancelar</a>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $apellidoPat = $_POST['apellidoPat'];
        $apellidoMat = $_POST['apellidoMat'];
        $curp = $_POST['curp'];
        $domicilio = $_POST['domicilio'];
        $cp = $_POST['cp'];
        $colonia = $_POST['colonia'];
        $ciudad = $_POST['ciudad'];
        $estado = $_POST['estado'];
        $telefono1 = $_POST['telefono1'];
        $telefono2 = $_POST['telefono2'];
        $fechaNacimiento = $_POST['fechaNacimiento'];
        /*$lineaCredito = $_POST['lineaCredito'];
        $mensualidades = $_POST['mensualidades'];
        $periodicidad = $_POST['periodicidad'];
        $fechaSolicitud = $_POST['fechaSolicitud'];
        $fechaAprobacion = $_POST['fechaAprobacion'];
        $active = $_POST['active'];*/

        $sqlAgregarCliente = "INSERT INTO clientes (curp, nombre, apellido_paterno, apellido_materno, domicilio, cp, colonia, ciudad, estado, telefono1, telefono2, fecha_nacimiento) VALUES ('$curp','$nombre','$apellidoPat','$apellidoMat','$domicilio','$cp','$colonia','$ciudad','$estado','$telefono1','$telefono2','$fechaNacimiento')";
        if (!mysqli_query($conn, $sqlAgregarCliente)) {
            die('Error: ' . mysqli_error($conn));
        } else {
            echo "<div class='alert alert-success' role='alert'>¡El cliente se ha agregado exitosamente! Gracias</div>";
            $_SESSION['lifeId'] = $curp;
            header("location:clienteSinCuenta.php");
        }
    }
    ?>
</body>

</html>

<?php
ob_flush();
?>