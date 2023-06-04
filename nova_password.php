<!doctype html>
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

    if (isset($_POST["nova_password_submit"])) {
        $selector = $_POST["selector"];
        $validator = $_POST["validator"];
        $password = md5($_POST["nova_password"]);
        $repassword = md5($_POST["re_nova_password"]);

        if (empty($password) || empty($repassword)) {
            echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#passwords-vazias').modal('show');
                                    });
                                    </script>";
        } else if ($password != $repassword) {
            echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#passwords-diferentes').modal('show');
                                    });
                                    </script>";
        }

        $currentDate = date("U");

        include "config.php";

        $sql = "SELECT * FROM pwd_reset WHERE pwdResetSelector=? AND pwdResetExpires >=?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#ocurreu-erro').modal('show');
                                    });
                                    </script>";
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
            mysqli_stmt_execute($stmt);
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$row = mysqli_fetch_assoc($result)) {
            echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#resubmete-pedido').modal('show');
            });
            </script>";
        } else {
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

            if ($tokenCheck === false) {
                echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#resubmete-pedido').modal('show');
            });
            </script>";
            } else if ($tokenCheck === true) {
                $tokenEmail = $row['pwdResetEmail'];

                $sql = "SELECT * FROM user WHERE email =?;";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#ocurreu-erro').modal('show');
                    });
                    </script>";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    if (!$row = mysqli_fetch_assoc($result)) {
                        echo "<script type='text/javascript'>
                        $(document).ready(function(){
                        $('#ocurreu-erro').modal('show');
                        });
                        </script>";
                    } else {
                        $sql = "UPDATE user SET password=? WHERE email=?";
                        $stmt = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>
                            $(document).ready(function(){
                            $('#ocurreu-erro').modal('show');
                            });
                            </script>";
                        } else {
                            mysqli_stmt_bind_param($stmt, "ss", $password, $tokenEmail);
                            mysqli_stmt_execute($stmt);


                            $sql = "DELETE FROM pwd_reset WHERE pwdResetEmail=?";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "<script type='text/javascript'>
                                $(document).ready(function(){
                                $('#ocurreu-erro').modal('show');
                                });
                                </script>";
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);


                                echo "<script type='text/javascript'>
                                $(document).ready(function(){
                                $('#sucesso').modal('show');
                                });
                                </script>";
                            }
                        }
                    }
                }
            }
        }
    }
    ?>

    <?php
    $selector = $_GET['selector'];
    $validator = $_GET['validator'];

    if (empty($selector) || empty($validator)) {
        echo "Não foi possivel validar o seu pedido";
    } else {
        if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
    ?>
            <div id="nova_password_form">
                <form id="nova_password_submit" action="" method="POST">

                    <input type="hidden" name="selector" value="<?php echo $selector ?>">
                    <input type="hidden" name="validator" value="<?php echo $validator ?>">

                    <div class="row mb-3">
                        <label for="nova_password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input name="nova_password" type="password" class="form-control" id="nova_password" value="<?php echo $_POST['nova_password']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="re_nova_password" class="col-sm-2 col-form-label">Confirmar Password</label>
                        <div class="col-sm-10">
                            <input name="re_nova_password" type="password" class="form-control" id="re_nova_password" value="<?php echo $_POST['re_nova_password']; ?>" required>
                        </div>
                    </div>

                    <button name="nova_password_submit" type="submit" class="btn btn-primary" id="nova_password_button" style="background-color: #003366;">Mudar Password</button>
                </form>
            </div>
    <?php

        }
    }

    ?>

    <div class='modal' tabindex='-1' id="sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Password alterada com sucesso!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>A palavra pass foi alterada com sucesso!</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="ocurreu-erro">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>A password não foi alterada.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Ocurreu um erro.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="resubmete-pedido">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>A password não foi alterada.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Ocurreu um erro. Resubmeta o pedido de novo.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="passwords-diferentes">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>A password não foi alterada.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='';"></button>
                </div>
                <div class='modal-body'>
                    <p>As passwords são diferentes.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="passwords-vazias">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>A password não foi alterada.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='nova_password.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>As passwords não podem ser vazias!</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='nova_password.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>