<?php
session_start();
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
date_default_timezone_set("America/Mexico_City");
if (isset($_POST['agregarCuenta']))
    header("location:nuevaCuenta.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cliente Sin Cuenta - AdmiCredit 1.0</title>
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
    <?php
    $lifeId = $_SESSION['lifeId'];
    $sqlCliente = "SELECT * FROM clientes WHERE curp = '$lifeId'";
    $sqlRunCliente = mysqli_query($conn, $sqlCliente);
    $rowCliente = mysqli_fetch_array($sqlRunCliente);
    $nombre = strtoupper($rowCliente['nombre']);
    $apellidoPat = strtoupper($rowCliente['apellido_paterno']);
    $apellidoMat = strtoupper($rowCliente['apellido_materno']);
    $domicilio = strtoupper($rowCliente['domicilio']);
    $cp = $rowCliente['cp'];
    $colonia = strtoupper($rowCliente['colonia']);
    $ciudad = strtoupper($rowCliente['ciudad']);
    $estado = strtoupper($rowCliente['estado']);
    $telefono = $rowCliente['telefono_1'];
    $telefono2 = $rowCliente['telefono_2'];
    $dob = $rowCliente['fecha_nacimiento'];
    $curpCAPS = strtoupper($lifeId);
    ?>
    <!-- Tabla con los datos del cliente-->
    <div style="padding-top: 15px; padding-bottom: 15px">
        <table class="table-sm" style="width: 100%">
            <tbody>
                <tr>
                    <td><span style="font-size: small; padding-right:5px">Nombre(s):</span><input class="dp" disabled
                            value=<?php echo $nombre; ?>></td>

                    <td><span style="font-size: small; padding-right:5px">Apellido Paterno:</span><input class="dp"
                            disabled value="<?php echo $apellidoPat; ?>"></td>

                    <td><span style="font-size: small; padding-right:5px">Apellido Materno:</span><input class="dp"
                            disabled value="<?php echo $apellidoMat; ?>"></td>
                </tr>
                <tr>
                    <td><span style="font-size: small; padding-right:5px">Domicilio:</span><input class="dp" disabled
                            value="<?php echo $domicilio; ?>"></td>

                    <td><span style="font-size: small; padding-right:5px">Colonia:</span><input class="dp" disabled
                            value="<?php echo $colonia; ?>"></td>

                    <td>
                        <span style="font-size: small; padding-right:5px">C.P.:</span><input class="dp" disabled
                            value="<?php echo $cp; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span style="font-size: small; padding-right:5px">Ciudad:</span><input class="dp" disabled
                            value="<?php echo $ciudad; ?>"></td>

                    <td>
                        <span style="font-size: small; padding-right:5px">Estado:</span><input class="dp" disabled
                            value="<?php echo $estado; ?>">
                    </td>

                    <td>
                        <span style="font-size: small; padding-right:5px">Teléfono principal:</span><input class="dp"
                            disabled value="<?php echo $telefono; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: small; padding-right:5px">Teléfono secundario:</span><input class="dp"
                            disabled value="<?php echo $telefono2; ?>">
                    </td>

                    <td>
                        <span style="font-size: small; padding-right:5px">CURP:</span><input class="dp" disabled
                            value="<?php echo $curpCAPS; ?>">
                    </td>
                    <td>
                        <span style="font-size: small; padding-right:5px">Fecha de nacimiento:</span><input class="dp"
                            disabled value="<?php echo $dob ?>">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No hay cuentas asociadas con este cliente
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="clienteSinCuenta.php" method="post">
            <button name="agregarCuenta" type="submit" class="btn btn-primary">Agregar Cuenta</button>
        </form>
    </div>
</body>

</html>