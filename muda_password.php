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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>

<body>


    <?php


    if (isset($_POST['muda-pass'])) {
        $user_id = $_SESSION['id'];
        $password = md5($_POST['muda-password']);
        $repassword = md5($_POST['re_muda-password']);
        $passwordantiga = md5($_POST['password-antiga']);

        $sql = "SELECT * FROM user WHERE id='$user_id' AND password='$passwordantiga'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($passwordantiga == $password) {
                echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#mudar-pass-insucesso-3').modal('show');
                });
                </script>";
            } else {
                if ($password == $repassword) {
                    $sql2 = "UPDATE user SET password='$password' WHERE id='$user_id'";
                    $result2 = mysqli_query($conn, $sql2);
                    echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#mudar-pass-sucesso').modal('show');
                    });
                    </script>";
                } else {
                    echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#mudar-pass-insucesso-2').modal('show');
                    });
                    </script>";
                }
            }
        } else {
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#mudar-pass-insucesso').modal('show');
                });
                </script>";
        }
    }

    ?>

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
                    <a class="nav-link" href="historico.php">Histórico
                        <img src="imagens/registo.png" width="25" height="25">
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="muda_password.php">Mudar Password
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

    <div class="row">

        <div id="muda-pass">
            <form id="muda-pass-form" action="" method="POST">

                <div class="row mb-3">
                    <label for="password-antiga" class="col-sm-2 col-form-label">Password antiga</label>
                    <div class="col-sm-10">
                        <input name="password-antiga" type="password" class="form-control" id="password-antiga" value="<?php echo $_POST['password-antiga']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="muda-password" class="col-sm-2 col-form-label">Password Nova</label>
                    <div class="col-sm-10">
                        <input name="muda-password" type="password" class="form-control" id="muda-password" value="<?php echo $_POST['muda-password']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="re_muda-password" class="col-sm-2 col-form-label">Confirmar Password</label>
                    <div class="col-sm-10">
                        <input name="re_muda-password" type="password" class="form-control" id="re_muda-password" value="<?php echo $_POST['re_muda-password']; ?>" required>
                    </div>
                </div>

                <button name="muda-pass" type="submit" style="background-color: #003366;" class="btn btn-primary" id="muda-pass-button" style="background-color: #003366;">Mudar password</button>
            </form>

        </div>

    </div>

    <div class='modal' tabindex='-1' id="mudar-pass-sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Alteração efetuada com sucesso!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='welcome.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>A sua password foi alterada com sucesso.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='welcome.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="mudar-pass-insucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Alteração não efetuada!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='muda_password.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>A sua password que inseriu está incorreta.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='muda_password.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="mudar-pass-insucesso-2">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Alteração não efetuada!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='muda_password.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>As passwords que inseriu não coicidem.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='muda_password.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="mudar-pass-insucesso-3">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Alteração não efetuada!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='muda_password.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>A nova password não pode ser a mesma que a anterior.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='muda_password.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>