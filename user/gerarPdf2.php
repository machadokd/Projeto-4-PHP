<?php

include_once('../config.php');

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
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$result_transacoes = "SELECT * FROM calculos WHERE id_user = $user_id ORDER BY data DESC";
$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    foreach ($resultado_trasacoes as $row_transacoes) {
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
    }
}

$html .= '</tbody>';
$html .= '</table';


//referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("../dompdf/autoload.inc.php");

//Criando a Instancia
$dompdf = new DOMPDF();

// Carrega seu HTML
$dompdf->load_html('
            <h1 style="text-align: center;"> Relatorio dos seu calculos</h1>
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
