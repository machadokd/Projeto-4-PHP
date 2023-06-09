<?php

include 'config.php';

session_start();

if (!isset($_SESSION['email'])) {
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
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



  <script type="text/javascript" src="js/toogle.js"></script>
</head>

<body>
  <?php
  if (isset($_POST['guardar_button'])) {
    $_SESSION["login_time_stamp"] = time();
    $age = $_POST['age'];
    $genero = $_POST['gender'];
    $colestrol = $_POST['colestrol_total'];
    $colestrolLDL = $_POST['colestrol_ldl'];
    $pressao = $_POST['pas'];
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $fumador = $_POST['fumador'];
    $resultado = $_POST['resultado_final'];

    $current_date = date("Y-m-d H:i:s");
    $user_id = $_SESSION['id'];

    $sql = "INSERT INTO calculos (idade, genero, colestrol, colestrol_ldl, pas, peso, altura, fumador, resultado, data, id_user)
    VALUES ('$age','$genero','$colestrol','$colestrolLDL','$pressao','$peso','$altura','$fumador', '$resultado', '$current_date','$user_id')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo "<script type='text/javascript'>
              $(document).ready(function(){
              $('#sucesso').modal('show');
              });
              </script>";
    } else {
      echo "<script>alert('Erro.')</script>";
    }
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top rounded" style="background-color: #003366;">
    <div class="container-fluid">
      <ul class="navbar-nav mr-auto">
        <a class="navbar-brand" href="welcome.php">
          <img src="imagens/title.png" width="160" height="40">
        </a>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="welcome.php">Calculadora
            <img src="imagens/calculadora.png" width="25" height="25">
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="historico.php">Histórico
            <img src="imagens/registo.png" width="25" height="25">
          </a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="muda_password.php">Mudar Password
            <img src="imagens/perfil.png" width="25" height="25">
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout
            <img src="imagens/Logout.png" width="25" height="25">
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="row" id="workspace">
    <div class="col-md-4" id="calculadora">
      <form name="formulario" id="guardar" action="" method="POST">

        <h5 id="informação-variveis-principais">Variáveis para o cálculo do risco.</h5>

        <div style="display:inline;">
          <p style="display: inline-block " class="categoria">Género</p>
          <span class="toggle">
            <span id="male" onclick="genero(this),calc2(),masc()" data-lang-id="Male">Homem</span>
            <!--
        --><span id="female" onclick="genero(this),calc2(),fem()" class="active" data-lang-id="Female">Mulher</span>
          </span>
          <input id="gen" type="hidden" name="gender" value="F">
        </div> <br>


        <p class="categoria">Idade
          &nbsp;<span data-lang-id="anos" class="slider-desc-anos">anos</span>
          <input name="age" id="age" type="number" class="slider-label-anos" min="40" max="65" maxlength="2" step="1" value="40" oninput="adjustSlider('idadeRange', 'age')" onchange="adjustSlider('idadeRange', 'age'), calc2()" />
          <input type="range" class="slider" id="idadeRange" oninput="adjustBox('idadeRange', 'age'), calc2()" onchange="adjustBox('idadeRange', 'age'), calc2()" min="40" max="65" value="40">
        </p>

        <p class="categoria">Colesterol total
          &nbsp;<span data-lang-id="mg/dL" class="slider-desc-mmol">mg/dL</span>
          <input name="colestrol_total" id="colestrolTotalValue" type="number" class="slider-label-colestrol" min="116" max="310" maxlength="2" step="1" value="116" oninput="adjustSlider('colestrolTotalRange', 'colestrolTotalValue'), calc2()" onchange="adjustSlider('colestrolTotalRange', 'colestrolTotalValue'), calc2()" />
          <input type="range" class="slider" id="colestrolTotalRange" oninput="adjustBox('colestrolTotalRange', 'colestrolTotalValue'), calc2()" onclick="adjustBox('colestrolTotalRange', 'colestrolTotalValue'), calc2()" min="116" max="310" value="116" step="1">
        </p>

        <p class="categoria">Pressão Arterial Sistólica (Máxima)
          &nbsp;<span data-lang-id="mmHg" class="slider-desc-mmhg">mmHg</span>
          <input name="pas" id="pressaoArterialSistolicaValue" type="number" class="slider-label-PAS" min="100" max="200" maxlength="2" step="1" value="100" oninput="adjustSlider('pressaoArterialSistolicaRange', 'pressaoArterialSistolicaValue'), calc2()" onchange="adjustSlider('pressaoArterialSistolicaRange', 'pressaoArterialSistolicaValue'), calc2()" />
          <input type="range" class="slider" id="pressaoArterialSistolicaRange" oninput="adjustBox('pressaoArterialSistolicaRange', 'pressaoArterialSistolicaValue'), calc2()" onclick="adjustBox('pressaoArterialSistolicaRange', 'pressaoArterialSistolicaValue'), calc2()" min="100" max="200" value="100">
        </p>

        <div style="display:inline;">
          <p style="display: inline-block" class="categoria">Fumador(a)</p>
          <span class="toggle">
            <span id="smoker_yes" onclick="smoker(this), calc2(),fuma()" data-lang-id="Yes">Sim</span>
            <!--
        --><span id="smoker_no" onclick="smoker(this), calc2(),nfuma()" class="active" data-lang-id="No">Não</span>
            <input id="fumador" type="hidden" name="fumador" value="NAO">
          </span>
        </div> <br>

        <h5 id="informação-variveis-secundarias">Variáveis para o cálculo das Recomendações.</h5>

        <p class="categoria">Colesterol LDL
          &nbsp;<span data-lang-id="mg/dL" class="slider-desc-mmol">mg/dL</span>
          <input name="colestrol_ldl" id="colestroLDLValue" type="number" class="slider-label-colestrolHDL" min="30" max="220" maxlength="2" step="1" value="30" oninput="adjustSlider('colestroLDLRange', 'colestroLDLValue'), calc2()" onchange="adjustSlider('colestroLDLRange', 'colestroLDLValue'), calc()" />
          <input type="range" class="slider" id="colestroLDLRange" oninput="adjustBox('colestroLDLRange', 'colestroLDLValue'), calc2()" onclick="adjustBox('colestroLDLRange', 'colestroLDLValue'), calc2()" min="30" max="220" value="30" step="1">
        </p>

        <p class="categoria">Peso
          <span data-lang-id="kg" class="slider-desc-kg">kg</span>
          <input name="peso" id="pesoValue" type="number" class="slider-label-Peso" min="40" max="150" maxlength="2" step="1" value="40" oninput="adjustSlider('pesoRange', 'pesoValue'), calc2()" onchange="adjustSlider('pesoRange', 'pesoValue'), calc2()" />&nbsp;
          <input type="range" class="slider" id="pesoRange" oninput="adjustBox('pesoRange', 'pesoValue'), calc2()" onclick="adjustBox('pesoRange', 'pesoValue'), calc2()" min="40" max="150" value="40">
        </p>

        <p class="categoria">Altura
          &nbsp;<span data-lang-id="cm" class="slider-desc-cm">cm</span>
          <input name="altura" id="alturaValue" type="number" class="slider-label-altura" min="100" max="220" maxlength="2" step="1" value="100" oninput="adjustSlider('alturaRange', 'alturaValue'), calc2()" onchange="adjustSlider('alturaRange', 'alturaValue'), calc2()" />
          <input type="range" class="slider" id="alturaRange" oninput="adjustBox('alturaRange', 'alturaValue'), calc2()" onclick="adjustBox('alturaRange', 'alturaValue'), calc2()" min="100" max="220" value="100">
        </p>

        <input id="resultado_final" type="hidden" name="resultado_final" value="0">

        <button id="botao_guardar" name="guardar_button" type="submit" class="btn btn-primary btn-lg" style="background-color: #003366;" disabled>Guardar cálculo.</button>
        </label>
      </form>

      <div id="background-nota" class="rounded">
        <p id="nota">Esta calculadora não deve ser usada por pessoas com as seguintes patologias associadas: diabetes (tipo 1 e 2), doença renal crónica, hipercolesterolemia familiar ou níveis muito elevados de fatores de risco individuais.</p>
      </div>
    </div>


    <div class="col-md-4" id="resultados">
      <h5 style="display: inline-block;" id="resultadoFramingham">Risco de Doença Cardivascular Total (Fatal e Não Fatal): </h5>

      <div id="faces"></div>

      <h5 style="display: inline-block; margin-top: 20px">Resultado Score:</h5>
      <h5 id="resultado"> X </h5>

      <div id="matriz_score">
        <img id="imagem_matriz" src="imagens/m40.png" style="opacity: 0.25;">
      </div>

      <div id="descricao_score">
        <p>O valor de X% significa que a 10 anos, cerca de X pessoas em cada 100 com este valor de risco, sofrerão uma doença cardiovascular fatal. Este risco foi calculado com base nos fatores idade, sexo, tabagismo, pressão arterial sistólica e colesterol total. Poderão existir outros fatores que aumentem este risco. </p>
      </div>

    </div>

    <div class="col-md-4" id="div_recomendacoes">

      <h5>Recomendações</h5>
      <div id="titulo-recomendacoes" class="row rounded"></div>
      <div id="recomendacoes" class="row rounded"></div>

    </div>




    <div class='modal' tabindex='-1' id="sucesso">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Cálculo guardado com sucesso!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='welcome.php';"></button>
          </div>
          <div class='modal-body'>
            <p>O seu cálculo foi guardado com sucesso!</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='welcome.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>
    <div class='modal' tabindex='-1' id="insucesso">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Erro!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='welcome.php';"></button>
          </div>
          <div class='modal-body'>
            <p>O seu cálculo não foi guardado com sucesso!</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='welcome.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>
</body>

</html>