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
    <title>Calculadora - SCORE – vrs3</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
    <?php

    $id = $_GET['id'];

    $sql = "DELETE FROM calculos WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<script type='text/javascript'>
                                 $(document).ready(function(){
                                 $('#insucesso').modal('show');
                                 });
                                 </script>";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        echo "<script type='text/javascript'>
                                 $(document).ready(function(){
                                 $('#sucesso').modal('show');
                                 });
                                 </script>";
    }

    ?>

    <div class='modal' tabindex='-1' id="sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Cálculo apagado com sucesso!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='historico.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O seu cálculo foi apagado com sucesso!</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='historico.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>
    <div class='modal' tabindex='-1' id="insucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Erro!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='historico.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O seu cálculo não foi apagado!</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='historico.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>