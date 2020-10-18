<?php
session_start();
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
        ?> - Reporte de cuentas morosas - AdmiCredit2020
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

    <div class="container">
        <div class="row">
            <h1>Reporte de cuentas morosas</h1>
        </div>
        <div class="row">
            <table class="col-md-12 table-borderless table-sm table">
                <thead>
                    <tr>
                        <th># Cuenta</th>
                        <th>CURP</th>
                        <th>Línea de Crédito</th>
                        <th>Tasa de interés</th>
                        <th>Mensualidades</th>
                        <th>Periodicidad</th>
                        <th>Monto esperado hoy</th>
                        <th>Total pagado</th>
                        <th>Saldo vencido</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2407127560
                        <td>DDBB890610HJCRPG07
                        </td>
                        </td>
                        <td>$100,000.00
                        </td>
                        <td>12.50%
                        </td>
                        <td>3
                        </td>
                        <td>2
                        </td>
                        <td>$56,250.00
                        </td>
                        <td>$18,750.00
                        </td>
                        <td style="font-weight:bold">$37,500.00
                        </td>
                    </tr>
                    <tr>
                        <td>3295775538
                        </td>
                        <td>CURP12131415161718
                        </td>
                        <td>$10,000.00</td>
                        <td>13.45%</td>
                        <td>3</td>
                        <td>1</td>
                        <td>$7,563.33
                        </td>
                        <td>$3,781.67

                        </td>
                        <td style="font-weight:bold">$3,781.67
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <button id='back' class='btn btn-dark'>Regresar</button>
            <script>
            var btn = document.getElementById('back');
            btn.addEventListener('click', function() {
                window.history.back();
            });
            </script>
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