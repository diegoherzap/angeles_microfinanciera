<?php
require "dbConnection/config.php";

session_start();
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$montoPagoPeriodico = $_SESSION['montoPagoPeriodico'];
$acct = $_SESSION['selectedAccount'];
date_default_timezone_set("America/Mexico_City");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?php
        echo $_SESSION['account'];
        ?>- Historial de pagos
    </title>
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
    <h1>Historial de pagos</h1>

    <div>
        <table class='table table-sm table-bordered' style='font-size: small'>
            <thead class='thead-light'>
                <th>Monto</th>
                <th>Fecha</th>
                <th>ID de la transacción</th>
            </thead>
            <tbody>
                <?php
                $sqlPagosQuery = "SELECT monto_del_pago, fecha_transaccion, transaction_id from pagos WHERE id_cuenta = '$acct';";
                $sqlPagosEnCuenta = mysqli_query($conn, $sqlPagosQuery);
                $pagosCheck = mysqli_num_rows($sqlPagosEnCuenta);
                if ($pagosCheck > 0) {
                    while ($row = mysqli_fetch_assoc($sqlPagosEnCuenta)) {
                        echo "<tr>";
                        echo "<td>" . $dinero->formatCurrency(strval($row['monto_del_pago']), "USD") . "</td>";
                        echo "<td>" . date("d/m/y", strtotime($row['fecha_transaccion'])) . "</td>";
                        echo "<td>" . $row['transaction_id'] . "</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <form action="historialPagos.php" method="post">
            <button type="submit" class="btn btn-dark" name="goBack">Regresar</button>
        </form>
        <?php
        if (isset($_POST['goBack'])) {
            header("location:cuenta.php");
        }
        ?>

</body>

</html>