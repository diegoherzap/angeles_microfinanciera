<?php
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
    <title>
        <?php
        echo $_SESSION['account'];
        ?> - Reporte de cuentas morosas - AdmiCredit2020
    </title>
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
    <?php
    function resultToArray($resultado)
        {
            $rows = array();
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
        
        ?>
    <div style="padding: 50px;">
        <div class="row">
            <h1>Reporte de cuentas morosas</h1>
        </div>
        <div class="row">
            <table class="col-md-12 table-bordered table">
                <thead>
                    <tr>
                        <th># Cuenta</th>
                        <th>CURP</th>
                        <th>Línea de Crédito</th>
                        <th>Tasa de interés</th>
                        <th>Mensualidades</th>
                        <th>Periodicidad</th>
                        <th>Monto total (Línea de crédito + tasa de interés)</th>
                        <th>Total de pagos a realizar</th>
                        <th>Pagos realizados</th>
                        <th>Pagos pendientes</th>
                        <th>Monto esperado hoy</th>
                        <th>Total pagado</th>
                        <th>Periodos vencidos</th>
                        <th>Saldo vencido</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sqlCuentasMorosasQuery = 
                "SELECT cuentas.*,
                CAST(linea_credito * (1+(interes/100)) AS DECIMAL(9,2)) AS 'monto_total',
                CAST(SUM(pagos.monto_del_pago) AS DECIMAL(9,2)) AS 'total_pagado',
                CAST(COUNT(pagos.id_cuenta) AS UNSIGNED) AS 'pagos_realizados',
                CAST(ROUND(DATEDIFF(CURRENT_DATE(), fecha_aprobacion)/30*periodicidad, 0) AS UNSIGNED) AS 'periodos_transcurridos',
                CAST(mensualidades * periodicidad AS UNSIGNED) AS 'total_de_pagos_a_realizar',
                IF(COUNT(pagos.id_cuenta) < ROUND(DATEDIFF(CURRENT_DATE(), fecha_aprobacion)/30*periodicidad, 0), ROUND(DATEDIFF(CURRENT_DATE(), fecha_aprobacion)/30*periodicidad, 0) - COUNT(pagos.id_cuenta), 0) as atraso
                FROM cuentas
                LEFT JOIN pagos
                ON cuentas.id_cuenta = pagos.id_cuenta
                GROUP BY cuentas.id_cuenta";
                $sqlCuentasMorosasResult = mysqli_query($conn, $sqlCuentasMorosasQuery);
                $sqlCuentasMorosasFilas = mysqli_num_rows($sqlCuentasMorosasResult);
                if($sqlCuentasMorosasFilas > 0){
                    echo "<tr>";
                    while($fila = mysqli_fetch_assoc($sqlCuentasMorosasResult))
                    {
                        echo "<tr>";
                        if($fila['pagos_realizados'] < $fila['periodos_transcurridos'] && $fila['pagos_realizados'] < $fila['total_de_pagos_a_realizar'])
                        {
                            $montoEsperadoHoy = $dinero->formatCurrency((strval($fila['monto_total']) /30 * $fila['periodicidad']) * (strval($fila['periodos_transcurridos'])), "USD");
                            echo "<td>" . $fila['id_cuenta'] . "</td>";
                            echo "<td>" . $fila['curp'] . "</td>";
                            echo "<td>" . $dinero->formatCurrency(strval($fila['linea_credito']), "USD") . "</td>";
                            echo "<td>" . $fila['interes'] . "%</td>";
                            echo "<td>" . $fila['mensualidades'] . "</td>";
                            echo "<td>" . $fila['periodicidad'] . "</td>";
                            echo "<td>" . $dinero->formatCurrency($fila['monto_total'], "USD") . "</td>";
                            $totalDePagosARealizar = $fila['mensualidades'] * $fila['periodicidad'];
                            $pagosRealizados = $fila['pagos_realizados'];
                            $totalPagado = $fila['total_pagado'];
                            if($fila['periodos_transcurridos'] <= $fila['total_de_pagos_a_realizar'])
                            {
                                echo "<td>" . $totalDePagosARealizar . "</td>";
                                echo "<td>" . $pagosRealizados . "</td>";
                                echo "<td>" . ($totalDePagosARealizar - $fila['pagos_realizados']) . "</td>";

                                $montoEsperadoHoy = strval($fila['monto_total'] / 30 * $fila['periodicidad']) * $fila['periodos_transcurridos'];
                                
                                $montoRestante = $montoEsperadoHoy - $totalPagado;

                                echo "<td>" . $dinero->formatCurrency($montoRestante, "USD") . "</td>";
                                echo "<td>" . $dinero->formatCurrency($totalPagado, "USD") . "</td>";
                                echo "<td>" . ($fila['total_de_pagos_a_realizar'] - $fila['pagos_realizados']) . "</td>";
                                echo "<td><span style='font-style: bold; color: red;'>" . $dinero->formatCurrency($totalPagado, "USD") . "</span></td>";
                            }
                            else{
                                echo "<td>" . $totalDePagosARealizar . "</td>";
                                echo "<td>" . $pagosRealizados . "</td>";
                                echo "<td>" . ($totalDePagosARealizar - $fila['pagos_realizados']) . "</td>";
                                $montoEsperadoHoy = $fila['monto_total'];
                                $montoRestante = $montoEsperadoHoy - $totalPagado;
                                echo "<td>" . $dinero->formatCurrency($montoRestante, "USD") . "</td>";
                                echo "<td>" . $dinero->formatCurrency($totalPagado, "USD") . "</td>";
                                echo "<td>" . ($fila['total_de_pagos_a_realizar'] - $fila['pagos_realizados']) . "</td>";
                                echo "<td> <span style='font-style: bold; color: red;'>" . $dinero->formatCurrency(($montoEsperadoHoy - $fila['total_pagado']), "USD") . "</span></td>";
                            }
                            
                        }
                        echo "</tr>";
                    }
                }?>
                    
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