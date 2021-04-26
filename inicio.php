<?php
session_start();
/*$_SESSION['account'];
$_SESSION['lifeId'];*/
$dinero = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
require "dbConnection/config.php";
date_default_timezone_set("America/Mexico_City");

if (isset($_POST['agregarCliente'])) {
    header("location:nuevoCliente.php");
}
if (isset($_POST['reporteCuentasMorosas'])) {
    header("location:reporteCuentasMorosas.php");
}

function resultToArray($resultado)
{
    $rows = array();
    while ($row = $resultado->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio - AdmiCredit 1.0</title>
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

<body class="search-page">
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
        <div class="row" style="height: 100%;">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar"
                style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray">
                <div>
                    <label style="padding-top: 15px; font-size: larger;">Buscar por:</label>
                    <form action="inicio.php" method="POST">
                        <input type="text" class="form-control entrada text" name="cuentaCurp"
                            placeholder="CURP | #Cuenta" onkeyup="this.value = this.value.toUpperCase();" />
                        <button type="submit" class="enviar btn btn-primary" name="search_client">Enviar</button>
                    </form>
                    <form action="inicio.php" method="POST">
                        <button style="width:100%" type="submit" name="agregarCliente" class="btn btn-success">Agregar
                            Cliente</button>
                    </form>
                    <form action="inicio.php" method="POST">
                        <button style="width:100%" type="submit" name="reporteCuentasMorosas"
                            class="btn btn-success">Generar
                            Reporte</button>
                    </form>
                </div>
                <?php                    
                    $rowsTodasLasCuentas = array();

                    if (isset($_POST['search_client'])){
                        $cuentaCurp = $_POST['cuentaCurp'];
                        if (strlen($cuentaCurp) == 10) {
                            $_SESSION['account'] = $cuentaCurp;
                            $acct = $_SESSION['account'];
                            $sqlCuenta = "SELECT * FROM cuentas WHERE id_cuenta ='$acct'";

                            $sqlRunCuenta = mysqli_query($conn, $sqlCuenta);
                            $rowCuenta = mysqli_fetch_array($sqlRunCuenta);
                            if ($rowCuenta == 0) {
                                echo "<div class='container-fluid'>
                        <section class='row'>
                        <div class='alert alert-danger col-md-12' role='alert'>
                        El número de cuenta no existe, inténtalo de nuevo</div>
                        </section>";
                            } else {
                                $curp = $rowCuenta['curp'];
                                $_SESSION['lifeId'] = $curp;
                            }
                        } else if (strlen($cuentaCurp) == 18) {
                            $curp = $cuentaCurp;
                            $_SESSION['lifeId'] = $curp;
                        } else {
                            echo "<div class='container-fluid'>
                            <section class='row'>
                            <div class='alert alert-danger col-md-12' role='alert'>
                            Por favor ingrese un número de cuenta a 10 dígitos o una CURP de 18 caracteres</div>
                            </section>";
                        }
                        if (!empty($curp)) {
                            $sqlCliente = "SELECT * FROM clientes WHERE curp = '$curp'";
                            $sqlRunCliente = mysqli_query($conn, $sqlCliente);
                            if ($sqlRunCliente->num_rows == 0) {

                                echo "<div class='container-fluid'>
                                <section class='row'>
                                <div class='alert alert-danger col-md-12' role='alert'>
                                La CURP no existe, inténtalo de nuevo o registra a un nuevo cliente</div>
                                </section>";
                            } else {
                                $sqlCurp = "SELECT * FROM cuentas WHERE curp = '$curp'";
                                $sqlRunCurp = mysqli_query($conn, $sqlCurp);
                                $rowsTodasLasCuentas = resultToArray($sqlRunCurp);
                                if (!empty($rowsTodasLasCuentas)) {
                                    if (strlen($cuentaCurp) == 18) {
                                        $acct = $rowsTodasLasCuentas[0]['id_cuenta'];
                                        $_SESSION['account'] = $acct;
                                    }
                                    echo "</nav><main role='main' class='iframe-container col-md-9 ml-sm-auto col-lg-10' px-4>";
                                    echo "<div style='padding-left: 15px; height: 100%'>";
                                    echo "<iframe frameborder='0' style='padding: 15px; width: 100%; height: 100%' scrolling='no' src='cliente.php'>";
                                    echo "</iframe></div></main>";
                                } else {
                                    $_SESSION['lifeId'] = $curp;
                                    echo "</nav><main role='main' class='iframe-container col-md-9 ml-sm-auto col-lg-10 px-4'>";
                                    echo "<div class='results-box style='padding-left: 15px; width: 100%; min-height: 100%'>";
                                    echo "<iframe frameborder='0' style='padding: 15px; width: 100%; height:100%' scrolling='no' src='clienteSinCuenta.php'>";
                                    echo "</iframe></div></main>";
                                }
                            }
                        }
                        
                    }
                ?>
            </nav>
        </div>
    </div>
    </div>
    <div class="container-fluid">
        <section class>
            <nav class="navbar fixed-bottom col-md-12 footer">
                <footer class="custome-text">
                    EXOAL-ISOFT-044-001
                </footer>
            </nav>
        </section>
    </div>
</body>
</html>