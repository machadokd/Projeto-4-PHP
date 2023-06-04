<?php

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}
if (time() - $_SESSION["login_time_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
$_SESSION["login_time_stamp"] = time();


include_once("../config.php");
$html = '<table border=1';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Data do Cálculo</th>';
$html .= '<th>Idade</th>';
$html .= '<th>Género</th>';
$html .= '<th>Colesterol Total</th>';
$html .= '<th>Colesterol LDL</th>';
$html .= '<th>Pressão Arterial Sistólica (Máxima)</th>';
$html .= '<th>Peso</th>';
$html .= '<th>Altura</th>';
$html .= '<th>Fumador</th>';
$html .= '<th>Resultado</th>';
$html .= '<th>Utilizador</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$result_transacoes = "SELECT * FROM calculos";
$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    foreach ($resultado_trasacoes as $row_transacoes) {

        $sql2 = "SELECT * FROM user WHERE id=?;";

        $stmt = mysqli_stmt_init($conn);


        if (!mysqli_stmt_prepare($stmt, $sql2)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $row_transacoes["id_user"]);
            mysqli_stmt_execute($stmt);
        }



        $result2 = mysqli_stmt_get_result($stmt);
        if (!$row2 = mysqli_fetch_assoc($result2)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            $html .= '<tr><td>' . $row_transacoes['data'] . "</td>";
            $html .= '<td>' . $row_transacoes['idade'] . "</td>";
            $html .= '<td>' . $row_transacoes['genero'] . "</td>";
            $html .= '<td>' . $row_transacoes['colestrol'] . "</td>";
            $html .= '<td>' . $row_transacoes['colestrol_ldl'] . "</td>";
            $html .= '<td>' . $row_transacoes['pas'] . "</td>";
            $html .= '<td>' . $row_transacoes['peso'] . "</td>";
            $html .= '<td>' . $row_transacoes['altura'] . "</td>";
            $html .= '<td>' . $row_transacoes['fumador'] . "</td>";
            $html .= '<td>' . $row_transacoes['resultado'] . "</td>";
            $html .= '<td>' . $row2['resultado'] . "</td></tr>";
        }
    }
}

$html .= '</tbody>';
$html .= '</table';


//referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("dompdf/autoload.inc.php");

//Criando a Instancia
$dompdf = new DOMPDF();

// Carrega seu HTML
$dompdf->load_html('
            <h1 style="text-align: center;"> Relatorio dos utilizadores</h1>
            ' . $html . '
        ');

//Renderizar o html
$dompdf->render();

//Exibibir a página
$dompdf->stream(
    "relatorio_celke.pdf",
    array(
        "Attachment" => false //Para realizar o download somente alterar para true
    )
);
