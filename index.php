<?php

include 'config.php';

session_start();

if (isset($_SESSION['email'])) {
  if (time() - $_SESSION["login_time_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location: index.php");
  } else {
    header("Location: welcome.php");
  }
}

if (isset($_SESSION['admin'])) {

  if (time() - $_SESSION["login_time_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location: index.php");
  } else {
    header("Location: index_admin.php");
  }
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
  <title>Calculadora Framingham – SCORE – vrs1</title>
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
  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
      $row = mysqli_fetch_assoc($result);
      if (is_null($row['email_verified_at'])) {
        echo "<script type='text/javascript'>
              $(document).ready(function(){
              $('#email-verificacao').modal('show');
              });
              </script>";
      } else {
        $_SESSION['email'] = $row['email'];
        $_SESSION['id'] = $row['id'];
        $_SESSION["login_time_stamp"] = time();
        echo "<script type='text/javascript'>
              $(document).ready(function(){
              $('#pageContent').css({ opacity: 0.5 });
              $('#login-sucesso').modal('show');
              });
              </script>";
      }
    } else {
      $sql2 = $sql = "SELECT * FROM admin WHERE username='$email' AND password='$password'";
      $result2 = mysqli_query($conn, $sql);
      if ($result2->num_rows > 0) {
        $row2 = mysqli_fetch_assoc($result2);
        $_SESSION['id_admin'] = $row2['id'];
        $_SESSION['admin'] = $row2['username'];
        $_SESSION["login_time_stamp"] = time();
        echo "<script type='text/javascript'>
              $(document).ready(function(){
              $('#login-sucesso-admin').modal('show');
              });
              </script>";
      } else {
        echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#login-erro').modal('show');
            });
            </script>";
      }
    }
  }
  ?>


  <div class="row" id="menu">
    <div id="formula_selector">
      <ul class="tab">
        <li id="liFramingham" onclick="setFormula(this)" data-calc="calcu">
          <div>Calculadora Rápida</div>
          <span class="definition" data-lang-id="FraminghamCriteria">Não necessita de registo.</span>
        </li>
        <li onclick="setFormula(this)" data-calc="login">
          <div>Versão Completa</div>
          <span class="definition" data-lang-id="QriskCriteria">É necessário registo.</span>
        </li>
      </ul>
      <hr />
    </div>
  </div>

  <div class="row">
    <div class="col-md-4" id="calculadora">

      <h5 id="informação-variveis-principais">Variáveis para o cálculo do risco.</h5>

      <form name="formulario">

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



    <div id="login_form">
      <form id="login" action="" method="POST">
        <div class="row mb-3">
          <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input name="email" type="email" class="form-control" id="inputEmail3" value="<?php echo $email; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input name="password" type="password" class="form-control" id="inputPassword3" value="<?php echo $_POST['password']; ?>" required>
          </div>
        </div>

        <h6 id="frase_registo" class="links" onclick="mostraRegisto()">Ainda não se encontra registado? Clique aqui para se registar.</h6>
        <h6 id="frase_recuperacao" class="links" onclick="mostraRecuperacao()">Esqueceu-se da password? Clique aqui para a mudar.</h6>

        <button name="login" type="submit" class="btn btn-primary" id="login_button" style="background-color: #003366;">Login</button>
      </form>
    </div>

    <div id="registo_form">
      <form id="registo" action="register-send-email.php" method="POST">


        <div class="row mb-3">
          <label for="email_registo" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input name="email_registo" type="email" class="form-control" id="email_registo" value="<?php echo $email; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <label for="username_registo" class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-10">
            <input name="username_registo" type="text" class="form-control" id="username_registo" value="<?php echo $username; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <label for="password_registo" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input name="password_registo" type="password" class="form-control" id="password_registo" value="<?php echo $_POST['password_registo']; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <label for="re_password_registo" class="col-sm-2 col-form-label">Confirmar Password</label>
          <div class="col-sm-10">
            <input name="re_password_registo" type="password" class="form-control" id="re_password_registo" value="<?php echo $_POST['re_password_registo']; ?>" required>
          </div>
        </div>

        <h6 id="frase_login" class="links" onclick="mostraLogin()">Já se encontra registado? Clique aqui.</h6>

        <button name="registo" type="submit" class="btn btn-primary" id="registo_button" style="background-color: #003366;">Registo</button>
      </form>
    </div>


    <div id="recuperacao_form">
      <form id="recuperacao" action="recupera_password.php" method="POST">
        <div class="row mb-3">
          <label for="email-recuperacao" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input name="email-recuperacao" type="email" class="form-control" id="email-recuperacao" required>
          </div>
        </div>

        <h6 id="frase_login" class="links" onclick="mostraLogin()">Para voltar ao login, clique aqui.</h6>

        <button name="recuperacao" type="submit" class="btn btn-primary" id="login_button" style="background-color: #003366;">Recuperar Password</button>
      </form>
    </div>



    <div class='modal' tabindex='-1' id="email-verificacao">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Login Não Efetuado!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
          </div>
          <div class='modal-body'>
            <p>Precisa de verificar o email.</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366; " data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>

    <div class='modal' tabindex='-1' id="login-sucesso">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Login Efetuado com Sucesso!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='welcome.php';"></button>
          </div>
          <div class='modal-body'>
            <p>O login foi efetuado com sucesso.</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='welcome.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>

    <div class='modal' tabindex='-1' id="login-sucesso-admin">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Login Efetuado com Sucesso!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index_admin.php';"></button>
          </div>
          <div class='modal-body'>
            <p>O login foi efetuado com sucesso.</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366; " data-bs-dismiss='modal' onclick="window.location.href='index_admin.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>


    <div class='modal' tabindex='-1' id="login-erro">
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Login Não Efetuado!</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick="window.location.href='index.php';"></button>
          </div>
          <div class='modal-body'>
            <p>Dados Incorretos.</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' style="background-color: #003366;" data-bs-dismiss='modal' onclick="window.location.href='index.php';">Continuar</button>
          </div>
        </div>
      </div>
    </div>
</body>

</html>