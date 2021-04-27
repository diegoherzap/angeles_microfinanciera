<?php
session_start();
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
date_default_timezone_set("America/Mexico_City");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cuenta - AdmiCredit 1.0</title>
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
    <?php
    if (isset($_SESSION['selectedAccount'])) {
        $_SESSION['selectedAccount'];
    } else echo "Not set";
    $acct = $_SESSION['selectedAccount'];
    $sqlCuenta = "SELECT * FROM cuentas WHERE id_cuenta = '$acct'";
    $sqlRunCuenta = mysqli_query($conn, $sqlCuenta);
    $rowCuenta = mysqli_fetch_array($sqlRunCuenta);
    echo "<div>
            <table class='table table-sm table-bordered' style='font-size: small'>
            <thead class='thead-light'>
            <th>Línea de Crédito</th>
            <th>Tasa de Interés</th>
            <th>Mensualidades</th>
            <th>Periodicidad</th>
            </thead>
            <tbody>";

    $credito = $dinero->formatCurrency(strval($rowCuenta['linea_credito']), "USD");
    $interes = $rowCuenta['interes'];
    $mensualidad = $rowCuenta['mensualidades'];
    $period = $rowCuenta['periodicidad'];
    $fecha_aprobacion = date_create($rowCuenta['fecha_aprobacion']);
    echo "<td>$credito</td>";
    echo "<td>$interes%</td>";
    echo "<td>$mensualidad</td>";
    switch ($period) {
        case 1:
            $periodicidad = "Mensual";
            $periodos = "Meses";
            break;

        case 2:
            $periodicidad = "Quincenal";
            $periodos = "Quincenas";
            break;

        case 4:
            $periodicidad = "Semanal";
            $periodos = "Semanas";
            break;
    }
    echo "<td>$periodicidad</td>";
    ?>
    </tbody>
    </table>
    </div>
    <div>
        <?php
        $sqlDays = "SELECT DATEDIFF(CURDATE(), fecha_aprobacion) AS dayCount FROM cuentas WHERE id_cuenta = '$acct'";
        $sqlRunDays = mysqli_query($conn, $sqlDays);
        $rowDays = mysqli_fetch_array($sqlRunDays);
        $diasHastaHoy = $rowDays['dayCount'];
        $mesesHastaHoy = round(($rowDays['dayCount'] / 30), 0);
        $periodosTranscurridos = round($mesesHastaHoy * $period, 0);
        $totalAPagar = round($rowCuenta['linea_credito'] * (1 + $rowCuenta['interes'] / 100), 2);
        $montoMensual = round($totalAPagar / $rowCuenta['mensualidades'], 2);
        $montoPagoPeriodico = round(($montoMensual / $rowCuenta['periodicidad']), 2);
        if($periodosTranscurridos > $mensualidad * $periodicidad)
            $montoEsperadoHoy = $mensualidad * $periodicidad * $montoPagoPeriodico;
        else $montoEsperadoHoy = $periodosTranscurridos * $montoPagoPeriodico;
        $_SESSION['montoPagoPeriodico'] = $montoPagoPeriodico;
        $montoEsperadoHoy = round($montoEsperadoHoy, 2);

        $sqlTotalPagado = "SELECT SUM(monto_del_pago) as totalPagado, COUNT(id_cuenta) as pagosRealizados from pagos WHERE id_cuenta = '$acct'";

        $sqlRunTotalPagado = mysqli_query($conn, $sqlTotalPagado);
        $rowPagos = mysqli_fetch_array($sqlRunTotalPagado);
        $totalPagado = $rowPagos['totalPagado'];
        $saldo = $totalAPagar - $totalPagado;
        $pagosRealizados = $rowPagos['pagosRealizados'];
        $pagosTotales = $rowCuenta['mensualidades'] * $rowCuenta['periodicidad'];
        $pagosFaltantes = $pagosTotales - $pagosRealizados;
        $estatus = "";
        if ($pagosRealizados >= $periodosTranscurridos) {
            $estatus = "AL CORRIENTE";
            $saldoMoroso = 0.00;
        } else {
            $estatus = "ATRASADO";
            $saldoMoroso = $montoEsperadoHoy - $totalPagado;
        }
        ?>
        <table class="table-borderless table-sm table" style="font-size:small">
            <tbody>
                <tr>
                    <td>
                        <label style="font-weight: bold">
                            Fecha de solicitud
                        </label>
                        <span> <?php echo date("d/m/y", strtotime($rowCuenta['fecha_solicitud'])) ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Fecha de aprobación
                        </label>
                        <span> <?php echo date("d/m/y", strtotime($rowCuenta['fecha_aprobacion'])) ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            <?php echo $periodos ?> transcurridas
                        </label>
                        <span> <?php echo $periodosTranscurridos ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label style="font-weight: bold">
                            Costo total (linea de credito + interés)
                        </label>
                        <span>
                            <?php echo $dinero->formatCurrency($totalAPagar, "USD") ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Monto mensual a pagar
                        </label>
                        <span>
                            <?php echo $dinero->formatCurrency($montoMensual, "USD") ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Monto <?php echo $periodicidad ?> a pagar
                        </label>
                        <span>
                            <?php echo $dinero->formatCurrency($montoPagoPeriodico, "USD") ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label style="font-weight: bold">
                            Saldo
                        </label>
                        <span> <?php echo $dinero->formatCurrency($saldo, "USD") ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Monto esperado al día
                        </label>
                        <span>
                            <?php echo $dinero->formatCurrency($montoEsperadoHoy, "USD") ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Total Pagado
                        </label>
                        <span> <?php echo $dinero->formatCurrency($totalPagado, "USD") ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label style="font-weight: bold">
                            Cantidad de pagos <?php echo $periodicidad ?>es requeridos hoy
                        </label>
                        <span> <?php echo $periodosTranscurridos ?></span>
                    </td>

                    <td>
                        <label style="font-weight: bold">
                            Cantidad de pagos <?php echo $periodicidad ?>es realizados
                        </label>
                        <span>
                            <?php echo $pagosRealizados ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Cantidad de pagos restantes
                        </label>
                        <span> <?php echo $pagosFaltantes ?></span>
                    </td>

                </tr>
                <tr>
                    <td>
                        <label style="font-weight: bold">
                            Cantidad total de pagos requeridos
                        </label>
                        <span> <?php echo $pagosTotales ?></span>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Estatus
                        </label>
                        <?php
                        if ($estatus == "ATRASADO") {
                            echo "<span class='badge badge-danger'>", $estatus, "</span>";
                        } else {
                            echo "<span class='badge badge-success'>", $estatus, "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <label style="font-weight: bold">
                            Saldo moroso
                        </label>
                        <span> <?php echo $dinero->formatCurrency($saldoMoroso, "USD") ?></span>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
    <div class='row container-fluid'>
        <section class>
            <button id='paymentHist' class='btn btn-info'>Ir al historial de pagos</button>
            <script>
            var btn = document.getElementById('paymentHist');
            btn.addEventListener('click', function() {
                document.location.href = 'historialPagos.php';
            });
            </script>
            <button id='registerPayment' class='btn btn-success'>Registrar un nuevo pago</button>
            <script>
            var btn = document.getElementById('registerPayment');
            btn.addEventListener('click', function() {
                document.location.href = 'realizarPago.php';
            });
            </script>
        </section>
</body>

</html>