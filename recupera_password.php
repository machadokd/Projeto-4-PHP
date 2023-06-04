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

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    require 'vendor/autoload.php';

    if (isset($_POST['recuperacao'])) {
        include "config.php";
        $email = $_POST['email-recuperacao'];

        $result = mysqli_query($conn, "SELECT email FROM user WHERE email='" . $email . "'");
        $row = mysqli_num_rows($result);

        if ($row > 0) {
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);

            $binToken = bin2hex($token);

            $expires = date("U") + 1800;


            $sql = "DELETE FROM pwd_reset WHERE pwdResetEmail=?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#ocurreu-erro').modal('show');
                                    });
                                    </script>";
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
            }


            $sql = "INSERT INTO pwd_reset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#ocurreu-erro').modal('show');
                                    });
                                    </script>";
                exit();
            } else {
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
                mysqli_stmt_execute($stmt);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);


            try {




                $mail = new PHPMailer(true);

                $mail->CharSet =  "utf-8";
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'testesrsmachado@gmail.com';                     //SMTP username
                $mail->Password   = '.Axelghn2lgmt213';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;


                $mail->From = 'testesrsmachado@gmail.com';
                $mail->FromName = 'Calculadora Risco Score';
                $mail->AddAddress($email);
                $mail->Subject  =  'Alterar password.';
                $mail->IsHTML(true);
                $mail->Body    = "<p>Clique no link em baixo para inserir uma nova password. <br> " .
                    "<a href='http://www.localhost:8080/projeto4/nova_password.php" .
                    "?selector={$selector}&validator={$binToken}'>Nova password.</a>" .
                    "</p>";

                if ($mail->Send()) {
                    echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#enviar-email-pass-sucesso').modal('show');
                                    });
                                    </script>";
                } else {
                    echo "Mail Error - >" . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#nao-existe-email').modal('show');
                                    });
                                    </script>";
        }
    }

    ?>


    <div class='modal' tabindex='-1' id="enviar-email-pass-sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Email enviado com sucesso.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Foi enviado para o seu email um link para alteração da password.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="nao-existe-email">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>O email não foi enviado.</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O email inserido não se encontra registado.</p>
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
                    <h5 class='modal-title'>O email não foi enviado.</h5>
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


</body>

</html>