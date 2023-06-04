<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Calculadora - SCORE</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    if ($_GET['key'] && $_GET['token']) {
        include "config.php";
        $email = $_GET['key'];
        $token = $_GET['token'];
        $query = mysqli_query(
            $conn,
            "SELECT * FROM `user` WHERE `email_verification_link`='" . $token . "' and `email`='" . $email . "';"
        );
        $d = date('Y-m-d H:i:s');
        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_array($query);
            if ($row['email_verified_at'] == NULL) {
                mysqli_query($conn, "UPDATE user set email_verified_at ='" . $d . "' WHERE email='" . $email . "'");
                $msg = "Parabéns, o seu email foi verificado.";
            } else {
                $msg = "O seu email já se encontra verificado.";
            }
        } else {
            $msg = "Este email não foi registado.";
        }
    } else {
        $msg = "Erro. Alguma coisa se passou.";
    }
    ?>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header text-center">
                Ativação do email.
            </div>
            <div class="card-body">
                <p><?php echo $msg; ?></p>
            </div>
            <div>
                <a href="index.php" style="margin-left: 42%;">Ir para a página principal</a>
            </div>
        </div>
    </div>
</body>

</html>