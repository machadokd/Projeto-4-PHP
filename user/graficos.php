<?php

include '../config.php';

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
}
$user_id = $_SESSION['id'];

if (time() - $_SESSION["login_time_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
$_SESSION["login_time_stamp"] = time();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>Calculadora - SCORE – vrs3</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top rounded" style="background-color: #003366;">
        <div class="container-fluid">
            <ul class="navbar-nav mr-auto">
                <a class="navbar-brand" href="../welcome.php">
                    <img src="../imagens/title.png" width="160" height="40">
                </a>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../welcome.php">Calculadora
                        <img src="../imagens/calculadora.png" width="25" height="25">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../historico.php">Histórico
                        <img src="../imagens/registo.png" width="25" height="25">
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../muda_password.php">Mudar Password
                        <img src="../imagens/perfil.png" width="25" height="25">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout
                        <img src="../imagens/Logout.png" width="25" height="25">
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <main>
        <script>
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Colesterol', 'Resultado'],

                    <?php

                    $sql = "SELECT * FROM calculos WHERE id_user = $user_id";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $idade = (float)$row['resultado'];
                            $peso  = (int)$row['colestrol'];

                            echo "[" . $idade . ", " . $peso . "],";
                        }
                    }
                    ?>
                ]);
                var options = {
                    title: 'Resultado vs Colesterol',
                    hAxis: {
                        title: 'Resultado',
                        minValue: 0,
                        maxValue: 100
                    },
                    vAxis: {
                        title: 'Colesterol',
                        minValue: 40,
                        maxValue: 65
                    },
                    legend: 'none'
                };

                var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

                chart.draw(data, options);
            }
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart2() {
                var data = google.visualization.arrayToDataTable([
                    ['Resultado', 'Pressão Arterial Sistólica (Máxima)'],

                    <?php

                    $sql = "SELECT * FROM calculos WHERE id_user = $user_id";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {

                            $idade = (float)$row['resultado'];
                            $peso  = (int)$row['pas'];

                            echo "[" . $idade . ", " . $peso . "],";
                        }
                    }
                    ?>
                ]);
                var options = {
                    title: 'Resultado vs Pressão Arterial Sistólica (Máxima)',
                    hAxis: {
                        title: 'Resultado',
                        minValue: 0,
                        maxValue: 100
                    },
                    vAxis: {
                        title: 'Pressão Arterial Sistólica (Máxima)',
                        minValue: 100,
                        maxValue: 200
                    },
                    legend: 'none'
                };

                var chart = new google.visualization.ScatterChart(document.getElementById('chart_div2'));

                chart.draw(data, options);
            }
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart2);


            google.charts.load("current", {
                packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart3);

            function drawChart5() {
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['Work', 11],
                    ['Eat', 2],
                    ['Commute', 2],
                    ['Watch TV', 2],
                    ['Sleep', 7]
                ]);

                var options = {
                    title: 'My Daily Activities',
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
                chart.draw(data, options);
            }

            function drawChart3() {
                var data = google.visualization.arrayToDataTable([
                    ['Intervalo de Resultados', 'Contagem de Resultados nesse intervalo'],

                    <?php

                    $sql = "SELECT * FROM calculos WHERE id_user = $user_id";
                    $result = mysqli_query($conn, $sql);

                    $zero = 0;
                    $um = 0;
                    $dois = 0;
                    $tres_quatro = 0;
                    $cinco_nove = 0;
                    $dez = 0;


                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {

                            if ($row['resultado'] >= 0 and  $row['resultado'] < 1) {
                                $zero++;
                            }
                            if ($row['resultado'] >= 1  and  $row['resultado'] < 2) {
                                $um++;
                            }
                            if ($row['resultado'] >= 2 and  $row['resultado'] < 3) {
                                $dois++;
                            }
                            if ($row['resultado'] >= 3 and  $row['resultado'] <= 4) {
                                $tres_quatro++;
                            }
                            if ($row['resultado'] >= 5 and  $row['resultado'] <= 9) {
                                $cinco_nove++;
                            }
                            if ($row['resultado'] >= 10) {
                                $dez++;
                            }
                        }
                    }
                    echo "[ '0-1' ,   $zero  ],";
                    echo "[ '1-2' ,  $um ],";
                    echo "[ '2-3' ,   $dois  ],";
                    echo "[ '3-5' ,  $tres_quatro ],";
                    echo "[ '5-9' ,  $cinco_nove ],";
                    echo "[ '>10' ,  $dez ],";
                    ?>
                ]);

                var options = {
                    title: 'Quantidade de cálculos por Resultado Score',
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
                chart.draw(data, options);
            }

            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart4);

            function drawChart4() {

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Resultado Score', 'Colesterol', 'Género', 'Pressão Arterial Sistólica (Máxima)'],
                    <?php

                    $sql = "SELECT * FROM calculos WHERE id_user = $user_id";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $id = (int)$row['id'];

                            if ($row['genero'] == 'M') {
                                $genero = 'M';
                            } else {
                                $genero = 'F';
                            }

                            $resultado = (float)$row['resultado'];
                            $colestrol  = (int)$row['colestrol'];
                            $pas  = (int)$row['pas'];
                            $idade  = (int)$row['idade'];

                            echo "['ID: $id', " . $resultado . ", " . $colestrol . ", '" . $genero . "', " . $pas . "],";
                        }
                    }
                    ?>
                ]);

                var options = {
                    title: 'Colesterol vs Pressão Arterial Sistólica (Máxima) vs Resultado Score',
                    hAxis: {
                        title: 'Colesterol'
                    },
                    vAxis: {
                        title: 'Pressão Arterial Sistólica (Máxima)'
                    },
                    bubble: {
                        textStyle: {
                            fontSize: 11
                        }
                    }
                };

                var chart = new google.visualization.BubbleChart(document.getElementById('chart_div4'));
                chart.draw(data, options);
            }
        </script>

        <div id="chart_div" style="width: 1700px; height: 500px; z-index: 1; position:relative; margin-left: 0%"></div>
        <div id="chart_div2" style="width: 1700px; height: 500px; z-index: 1; position:relative; margin-left: 0%"></div>
        <div id="chart_div3" style="width: 1700px; height: 500px; z-index: 1; position:relative; margin-left: 0%"></div>
        <div id="chart_div4" style="width: 1700px; height: 500px; z-index: 1; position:relative; margin-left: 0%"></div>

    </main>



</body>

</html>