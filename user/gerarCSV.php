 <?php

    include '../config.php';

    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
    }
    if (time() - $_SESSION["login_time_stamp"] > 600) {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    }
    $_SESSION["login_time_stamp"] = time();

    $user_id = $_SESSION['id'];

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('ID', 'Idade', 'Genero', 'Colestrol', 'colestrol_ldl', 'pas', 'peso', 'altura', 'fumador', 'resultado', 'data'));


    $sql = "SELECT * FROM calculos WHERE id_user=$user_id ORDER BY data DESC;";
    $result = mysqli_query($conn, $sql);


    while ($row = mysqli_fetch_assoc($result)) {
        $stmt = mysqli_stmt_init($conn);



        fputcsv($output, $row);
    }
    fclose($output);

    ?> 