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

    if (isset($_POST['registo'])) {

        include "config.php";

        $username = $_POST['username_registo'];
        $email = $_POST['email_registo'];
        $password = md5($_POST['password_registo']);
        $cpassword = md5($_POST['re_password_registo']);

        $result = mysqli_query($conn, "SELECT * FROM user WHERE email='" . $email . "' AND email_verified_at IS NULL");
        $row = mysqli_num_rows($result);

        $result2 = mysqli_query($conn, "SELECT * FROM user WHERE username='" . $username . "'");
        $row2 = mysqli_num_rows($result2);

        $result3 = mysqli_query($conn, "SELECT * FROM user WHERE email='" . $email . "' AND email_verified_at IS NOT NULL");
        $row3 = mysqli_num_rows($result3);

        $result4 = mysqli_query($conn, "SELECT * FROM admin WHERE username='" . $email . "'");
        $row4 = mysqli_num_rows($result4);

        if ($row == 0) {
            if ($row2 == 0) {
                if ($row3 == 0) {
                    if (!$row4 > 0) {
                        if ($password == $cpassword) {
                            try {
                                $token = md5($email) . rand(10, 9999);
                                mysqli_query($conn, "INSERT INTO user (username, email, email_verification_link ,password) 
                        VALUES('" . $username . "', '" . $email . "', '" . $token . "', '" . $password . "')");
                                //$link = '<a href="$aux"> Click and Verify Email </a>';

                                $mail = new PHPMailer(true);

                                $mail->CharSet =  "utf-8";
                                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                                $mail->isSMTP();                                            //Send using SMTP
                                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                $mail->Username   = 'projeto4.calculadora.score@gmail.com';                     //SMTP username
                                $mail->Password   = '.Testeplataforma1';                               //SMTP password
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                                $mail->Port       = 465;


                                $mail->From = 'projeto4.calculadora.score@gmail.com';
                                $mail->FromName = 'Calculadora Risco Score';
                                $mail->AddAddress($email);
                                $mail->Subject  =  'Verificação Email.';
                                $mail->IsHTML(true);
                                $mail->Body    = "<p>Clique no link em baixo para verificar o email. <br> " .
                                    "<a href='http://www.localhost:8080/projeto4/verify-email.php" .
                                    "?key={$email}&token={$token}'>Verificar Email.</a>" .
                                    "</p>";

                                if ($mail->Send()) {
                                    echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#registo-sucesso').modal('show');
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
                                    $('#passwords-diferentes').modal('show');
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
                                    $('#email-em-uso').modal('show');
                                    });
                                    </script>";
                }
            } else {
                echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#username-em-uso').modal('show');
                                    });
                                    </script>";
            }
        } else {
            echo "<script type='text/javascript'>
                                    $(document).ready(function(){
                                    $('#email-ja-registado').modal('show');
                                    });
                                    </script>";
        }
    }
    ?>

    <div class='modal' tabindex='-1' id="registo-sucesso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo utilizador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Verifique o seu email e clique no link que lhe foi enviado.</p>
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
                    <h5 class='modal-title'>Registo utilizador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>As passwords inseridas não correspondem uma com a outra.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="email-em-uso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo utilizador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O email já se encontra em uso.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="username-em-uso">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo utilizador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>O username já se encontra em uso.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <div class='modal' tabindex='-1' id="email-ja-registado">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Registo utilizador</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
                </div>
                <div class='modal-body'>
                    <p>Já se encontra registado, veja no seu email e verifique a sua conta.</p>
                    <p>O email enviado pode-se encontrar na pasta 'spam'.</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>