<?php

session_start();

if (!isset($_SESSION['admin'])) {
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

    if (isset($_POST['registo_admin'])) {

        include "config.php";

        $email = $_POST['email_registo_admin'];
        $password = md5($_POST['password_registo_admin']);
        $cpassword = md5($_POST['re_password_registo_admin']);

        $result = mysqli_query($conn, "SELECT * FROM admin WHERE username='" . $email . "'");
        $row = mysqli_num_rows($result);

        $result2 = mysqli_query($conn, "SELECT * FROM user WHERE email='" . $email . "'");
        $row2 = mysqli_num_rows($result2);

        if ($row == 0) {
            if ($row2 == 0) {
                if ($password == $cpassword) {
                    mysqli_query($conn, "INSERT INTO admin (username, password) 
            VALUES('" . $email . "', '" . $password . "')");

                    $result3 = mysqli_query($conn, "SELECT username FROM admin WHERE username='" . $email . "'");
                    $row3 = mysqli_num_rows($result3);
                    if ($row3 >= 1) {
                        echo "<script type='text/javascript'>
                        $(document).ready(function(){
                        $('#registo-sucesso').modal('show');
                        });
                        </script>";
                    }
                } else {
                    echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#pass-nao-coicide').modal('show');
                    });
                    </script>";
                }
            } else {
                echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#email-em-uso').modal('show');
                    });
                    </script>";
            }
        } else {
            echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    $('#email-em-uso-user').modal('show');
                    });
                    </script>";
        }
    }


    ?>
    <div class='modal' tabindex='-1' id="registo-sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo Administrador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index_admin.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Administrador registado com sucesso.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index_admin.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="pass-nao-coicide">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo Administrador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='adicionar_administrador.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>As passwords não coicidem.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='adicionar_administrador.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>
    <div class='modal' tabindex='-1' id="email-em-uso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo Administrador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='adicionar_administrador.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O email já se encontra em uso.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='adicionar_administrador.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>
    <div class='modal' tabindex='-1' id="email-em-uso-user">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo Administrador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='adicionar_administrador.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O email já se encontra em uso por um utilizador. Não pode usar o mesmo email para o Administrador!</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='adicionar_administrador.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>




</body>

</html>