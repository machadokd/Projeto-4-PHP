 <?php

    include '../config.php';
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php");
    }
    if (time() - $_SESSION["login_time_stamp"] > 600) {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    }
    $_SESSION["login_time_stamp"] = time();

    $sql = "SELECT * FROM calculos";
    $result = mysqli_query($conn, $sql);
    //$sql = "SELECT * FROM calculos";
    //$result = mysqli_query($conn, $sql);
    //$i = 0;
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $items = array_reverse($items, true);

    // $connect = mysqli_connect("localhost", "root", "", "testing");  
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('ID', 'Idade', 'Genero', 'Colestrol', 'colestrol_ldl', 'pas', 'peso', 'altura', 'fumador', 'resultado', 'data', 'id_user'));
    // $query = "SELECT * from tbl_employee ORDER BY id DESC";  
    $sql = "SELECT * FROM calculos";
    $result = mysqli_query($conn, $sql);
    //$result = mysqli_query($connect, $query);  
    while ($row = mysqli_fetch_assoc($result)) {
        foreach ($items as $row) {
            $sql2 = "SELECT * FROM user WHERE id=?;";

            $stmt = mysqli_stmt_init($conn);



            fputcsv($output, $row);
        }
    }
    fclose($output);

    ?> 