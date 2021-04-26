<?php
ob_start();
session_start();
date_default_timezone_set("America/Mexico_City");
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nueva Cuenta - AdmiCredit1.0</title>
    <link rel="stylesheet" href="./css/style.css" />
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
    <div class="container-fluid">
        <div style="padding-top:15px row">
            <div class="col-md-6 offset-md-3">
                <form action="nuevaCuenta.php" method="post">
                    <h4><span>Nueva Cuenta</span></h4>
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">CURP</span>
                        </div>
                        <input type="text" disabled class="form-control" aria-describedby="addon-wrapping"
                            value="<?php echo strtoupper($_SESSION['lifeId']) ?>">
                    </div>
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">Línea de Crédito</span>
                        </div>
                        <input type="number" name="lineaCredito" class="form-control" required min="0.01" step="0.01"
                            max="999999999.99" required>
                    </div>
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">Tasa de interés</span>
                        </div>
                        <input type="number" name="interes" class="form-control" required min="0.01" step="0.01"
                            max="999.99" required>
                    </div>
                    <select class="custom-select" name="mensualidades">
                        <option value="" selected>Mensualidades</option>
                        <option value="3">3 Meses</option>
                        <option value="6">6 Meses</option>
                        <option value="9">9 Meses</option>
                        <option value="12">12 Meses</option>
                    </select>
                    <select class="custom-select" name="periodicidad">
                        <option value="" selected>Periodicidad de pagos</option>
                        <option value="1">Pago mensual</option>
                        <option value="2">Pago quincenal</option>
                        <option value="4">Pago semanal</option>
                    </select>
                    <br>
                    <br>
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">Fecha de Solicitud</span>
                        </div>
                        <input type="date" class="form-control" aria-describedby="addon-wrapping"
                            value="<?php echo date('Y-m-d') ?>" min="<?php echo date('Y-m-d') ?>" disabled>
                    </div>
                    <div class="input-group flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">Fecha de Aprobación</span>
                        </div>
                        <input type="date" name="fechaAprobacion" class="form-control" aria-describedby="addon-wrapping"
                            required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" name="active" aria-label="Checkbox for following text input">
                            </div>
                        </div>
                        <span type="text" class="form-control" aria-label="Text input with checkbox">Activar</span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" name="agregar" class="btn btn-primary">Agregar</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-light">
                                <a href="inicio.php" target="_parent">Cancelar</a>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['agregar'])) {
        function generateCode($limit)
        {
            $code = '';
            for ($i = 0; $i < $limit; $i++) {
                $code .= mt_rand(0, 9);
            }
            return $code;
        }
        $curp = $_SESSION['lifeId'];
        $acct = strval(generateCode(10));
        $lineaCredito = $_POST['lineaCredito'];
        $interes = $_POST['interes'];
        $mensualidades = $_POST['mensualidades'];
        $periodicidad = $_POST['periodicidad'];
        $fechaSolicitud = date("Y-m-d");
        $fechaAprobacion = $_POST['fechaAprobacion'];
        //$active = $_POST['active'];

        $sqlAgregarCliente = "INSERT INTO cuentas (curp, id_cuenta, fecha_solicitud, fecha_aprobacion, linea_credito, interes, mensualidades, periodicidad) VALUES ('$curp','$acct', '$fechaSolicitud', '$fechaAprobacion', '$lineaCredito', '$interes', '$mensualidades', '$periodicidad')";
        if (!mysqli_query($conn, $sqlAgregarCliente)) {
            die('Error: ' . mysqli_error($conn));
        } else {
            echo "<div class='alert alert-success' role='alert'>¡La Cuenta# $acct se ha agregado correctamente al cliente con CURP $curp! Gracias</div>";
        }
    }
    ?>
</body>

</html>

<?php
ob_flush();
?>