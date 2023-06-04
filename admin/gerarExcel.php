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
$i = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

$items = array_reverse($items, true);




$arqexcel = "<meta charset='UTF-8'>";
$arqexcel .= "<table class='content-table'>
                <thead>
                    <tr>
                        <th scope='col'>Data do Cálculo</th>
                        <th scope='col'>Idade</th>
                        <th scope='col'>Género</th>
                        <th scope='col'>Colesterol Total</th>
                        <th scope='col'>Colesterol LDL</th>
                        <th scope='col'>Pressão Arterial Sistólica (Máxima)</th>
                        <th scope='col'>Peso</th>
                        <th scope='col'>Altura</th>
                        <th scope='col'>Fumador</th>
                        <th scope='col'>Resultado</th>
                    </tr>
                </thead>
                <tbody>";

foreach ($items as $row) {

    $arqexcel .= " <tr
                      <td>  " . $row['data'] . "</td>
                      <td>  " . $row['idade'] . "</td>
                      <td>  " . $row['genero'] . "</td>
                      <td>  " . $row['colestrol'] . " </td>
                      <td>  " . $row['colestrol_ldl'] . " </td>
                      <td>  " . $row['pas'] . "</td>
                      <td>  " . $row['peso'] . "</td>
                      <td>  " . $row['altura'] . " </td>
                      <td>  " . $row['fumador'] . " </td>
                      <td>  " . $row['resultado'] . "</td>
                      </tr>";
}



$arqexcel .= " </tbody>
            </table>";

header("Content-Type: application/xls");
header("Content-Disposition:attachment; filename = relatorio.xls");
echo $arqexcel;
