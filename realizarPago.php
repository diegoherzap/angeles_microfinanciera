<?php
session_start();
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
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
        ?>- Registrar un nuevo pago
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
    <h1>Realizar Pago</h1>
    <?php
    if (isset($_POST['ejecutarPago'])) {
        $transactionId = "TRID" . time();
        echo $transactionId;
        $sqlInsertPayment = "INSERT INTO pagos (monto_del_pago, fecha_transaccion, id_cuenta, transaction_id) VALUES
        ('$montoPagoPeriodico',CURDATE(),'$acct','$transactionId')";
        if (!mysqli_query($conn, $sqlInsertPayment)) {
            die('Error: ' . mysqli_error($conn));
        } else {
            echo "<div class='alert alert-success' role='alert'>¡El pago se ha realizado exitosamente! Gracias</div>";
        }
        $_SESSION['seletectAccount'] = $acct;
    }
    ?>
    <div>
        <form action="realizarPago.php" method="post">
            <div class="input-group mb-3" name="pagar">
                <input type="text" disabled class="form-control"
                    value="<?php echo $dinero->formatCurrency(strval($montoPagoPeriodico), "USD"); ?>"
                    aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" name="ejecutarPago" type="submit"
                        id="button-addon2">Pagar</button>
                </div>
            </div>
        </form>
    </div>
    <form action="realizarPago.php" method="post">
        <button type="submit" class="btn btn-dark" name="goBack">Regresar</button>
    </form>
    <?php
    if (isset($_POST['goBack'])) {
        header("location:cuenta.php");
    }
    ?>

</body>

</html>