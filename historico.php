<?php

include 'config.php';

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
}
if (time() - $_SESSION["login_time_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
$_SESSION["login_time_stamp"] = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>Calculadora – SCORE – vrs3</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/toogle.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top rounded" style="background-color: #003366;">
        <div class="container-fluid">

            <ul class="navbar-nav mr-auto">
                <a class="navbar-brand" href="welcome.php">
                    <img src="imagens/title.png" width="160" height="40">
                </a>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="welcome.php">Calculadora
                        <img src="imagens/calculadora.png" width="25" height="25">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="historico.php">Histórico
                        <img src="imagens/registo.png" width="25" height="25">
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="muda_password.php">Mudar Password
                        <img src="imagens/perfil.png" width="25" height="25">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout
                        <img src="imagens/Logout.png" width="25" height="25">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="row" id="fila_botoes">
        <a class="botoes" href="./user/gerarExcel.php" target="_blank">Gerar relatório Excel</a>
        <a class="botoes" href="./user/graficos.php">Ir para os gráficos</a>
        <a class="botoes" href="./user/gerarPdf2.php" target="_blank">Gerar relatório em PDF</a>
        <a class="botoes" href="./user/gerarCSV.php" target="_blank">Gerar relatório em CSV</a>
    </div>

    <div class="row" id="tabela">
        <table class="table table-bordered table-success table-striped">
            <thead>
                <tr class="table-active">
                    <th scope="col">Data do Cálculo</th>
                    <th scope="col">Idade</th>
                    <th scope="col">Género</th>
                    <th scope="col">Colesterol Total</th>
                    <th scope="col">Colesterol LDL</th>
                    <th scope="col">Pressão Arterial Sistólica (Máxima)</th>
                    <th scope="col">Peso</th>
                    <th scope="col">Altura</th>
                    <th scope="col">Fumador</th>
                    <th scope="col">Resultado</th>
                    <th scope="col">Apagar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['id'];

                $sql = "SELECT * FROM calculos WHERE id_user = $user_id";
                $result = mysqli_query($conn, $sql);

                $i = 0;

                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }

                $items = array_reverse($items, true);

                foreach ($items as $row) {
                    $i++;

                ?>

                    <tr>
                        <th scope='row'><?php echo $row["data"]; ?></th>
                        <td><?php echo $row["idade"]; ?></td>
                        <td><?php echo $row["genero"]; ?></td>
                        <td><?php echo $row["colestrol"]; ?></td>
                        <td><?php echo $row["colestrol_ldl"]; ?></td>
                        <td><?php echo $row["pas"]; ?></td>
                        <td><?php echo $row["peso"]; ?></td>
                        <td><?php echo $row["altura"]; ?></td>
                        <td><?php echo $row["fumador"]; ?></td>


                        <?php

                        if ($row["resultado"] >= 0 && $row["resultado"] < 1) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/0.png' width='35' height='35'> </td>";
                        }
                        if ($row["resultado"] >= 1 && $row["resultado"] < 2) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/1.png' width='35' height='35'> </td>";
                        }
                        if ($row["resultado"] >= 2 && $row["resultado"] < 3) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/2.png' width='35' height='35'> </td>";
                        }
                        if ($row["resultado"] >= 3 && $row["resultado"] < 5) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/34.png' width='35' height='35'> </td>";
                        }
                        if ($row["resultado"] >= 5 && $row["resultado"] <= 9) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/59.png' width='35' height='35'> </td>";
                        }
                        if ($row["resultado"] >= 10) {
                            echo "<td>" . $row["resultado"] . "  <img class='coracao-imagem' src='imagens/10.png' width='35' height='35'> </td>";
                        }
                        ?>

                        <td><button type='button' class='btn btn-danger' onclick='setId("<?php echo $row["id"]; ?>")' data-toggle="modal" data-target="#certeza">Apagar</button> </td>
                    </tr>

                <?php
                }

                ?>
            </tbody>
        </table>
        <p id="id" style="visibility: hidden;"></p>
    </div>

    <div class='modal' tabindex='-1' id='certeza' role="dialog">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Atenção!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='historico.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Tem a certeza que quer apagar este cálculo?</p>
                </div>
                <div class='modal-footer'>
                    <a href="delete_row_historico.php?id=" onclick="location.href=this.href+getId();return false;"><button type='button' class='btn btn-danger'>Sim</button></a>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='historico.php';">Não</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>