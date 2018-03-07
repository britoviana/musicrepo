<!DOCTYPE HTML>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <title>Add Artist Solo</title>

</head>

<body>

  <header>
    <?php
    require_once('connection.php');
    navBar();
    ?>
  </header>

  <div class="container">
    <div class="row" style="margin-top: 20px;">
    <div class="col-md-5">

  <?php

  require_once 'connection.php';

  if (isset($_POST['submit'])){

      $data_missing = array();

      if (empty($_POST['nome'])){

          // Adds nome to array
          $data_missing[] = 'Nome';

      } else {

          // Trim white space and escape the nome and store the nome
          $nome = trim($_POST['nome']);

      }

      if (empty($_POST['nome_completo'])){

          // Adds nome_completo to array
          $data_missing[] = 'Nome completo';

      } else{

          // Trim white space from the name and store the name
          $nome_completo = trim($_POST['nome_completo']);

      }

      if (empty($_POST['nacionalidade'])){

          // Adds name to array
          $data_missing[] = 'Nacionalidade';

      } else {

          // Trim white space from the name and store the name
          $nacionalidade = trim($_POST['nacionalidade']);

      }

      if (empty($_POST['data_nasc'])){

          // Adds name to array
          $data_missing[] = 'Data de nascimento';

      } else {

          // Trim white space from the name and store the name
          $data_nasc = trim($_POST['data_nasc']);

      }

      if (empty($_POST['local_nasc'])){

          // Adds name to array
          $data_missing[] = 'Local de nascimento';

      } else {

          // Trim white space from the name and store the name
          $local_nasc = trim($_POST['local_nasc']);

      }
      /*
      if(empty($_POST['data_morte'])){

          // Adds name to array
          $data_missing[] = 'Data de morte';

      } else {

          // Trim white space from the name and store the name
          $data_morte = trim($_POST['data_morte']);

      }

      if(empty($_POST['local_morte'])){

          // Adds name to array
          $data_missing[] = 'Local de morte';

      } else {

          // Trim white space from the name and store the name
          $local_morte = trim($_POST['local_morte']);

      }

      if(empty($_POST['bio'])){

          // Adds name to array
          $data_missing[] = 'Biografia';

      } else {

          // Trim white space from the name and store the name
          $bio = trim($_POST['bio']);

      }

      if(empty($_POST['webpage'])){

          // Adds name to array
          $data_missing[] = 'Webpage';

      } else {

          // Trim white space from the name and store the name
          $webpage = trim($_POST['webpage']);

      }*/
      if(!(empty($_POST['nome_completo'])))$nome_completo = trim($_POST['nome_completo']);
      if(!(empty($_POST['local_morte']))) $local_morte = trim($_POST['local_morte']);
      if(!(empty($_POST['data_morte']))) $data_morte = trim($_POST['data_morte']);
      if(!(empty($_POST['bio'])))$bio = trim($_POST['bio']);
      if(!(empty($_POST['webpage']))) $webpage = trim($_POST['webpage']);
      $tipo = "pessoa";


      if(empty($data_missing)){

        require_once('connection.php');
        $link = openConnectionPDO();

        $query = "INSERT INTO artista (nome, nome_completo, nacionalidade,
        data_nasc, local_nasc, data_morte, local_morte, bio, webpage, tipo) VALUES (:nome, :nome_completo, :nacionalidade,
        :data_nasc, :local_nasc, :data_morte, :local_morte, :bio, :webpage, :tipo)";

        $stmt = $link->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':nome_completo', $nome_completo);
        $stmt->bindParam(':nacionalidade', $nacionalidade);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':local_nasc', $local_nasc);
        $stmt->bindParam(':data_morte', $data_morte);
        $stmt->bindParam(':local_morte', $local_morte);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':webpage', $webpage);
        $stmt->bindParam(':tipo', $tipo);


        $result = $stmt->execute();

        $affected_rows = $stmt->rowCount();

        if($affected_rows == 1){

              echo '<br><div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>SUBMETERU!</strong> Artista adicionado.
        </div>';

              $stmt = null;
              $link = null;

          } else {
              echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>ERRO!</strong> Artista não adicionado.
        </div>';

              echo $stmt->errorInfo();

              $stmt = null;
              $link = null;
          }
      } else {

          echo '
          <br><div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Artista não adicionado!</strong> Preencha os campos obrigatórios:</br>';

          foreach($data_missing as $missing){

              echo "$missing<br />";

          }
          echo "</div>";
      }

  }

  ?>


  <form action="artistsoloadded.php" method="post" accept-charset="uft8">

  <h1 class="col-10">Add new solo artist</h1>

  <div class="form-group">
    <label for="nome" class="col-12 col-form-label">Nome</label>
    <div class="col-12">
      <input class="form-control" type="text" name="nome" id="nome" size="30">
    </div>
  </div>

  <div class="form-group">
    <label for="nome" class="col-12 col-form-label">Nome completo</label>
    <div class="col-12">
      <input class="form-control" type="text" name="nome_completo"id="nome_completo" size="30">
    </div>
  </div>

  <div class="form-group">
    <div class="col-12">
      <label for="nacionalidade">Nacionalidade</label>
      <select name="nacionalidade" id="nacionalidade" class="form-control selectpicker" data-live-search="true" multiple data-max-options="1" multiple>
        <?php
        require_once('connection.php');
        showCountries(); ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="data_nasc" class="col-12 col-form-label">Data de nascimento</label>
    <div class="col-12">
      <input class="form-control" type="date" value="" name="data_nasc" id="data_nasc">
    </div>
  </div>

  <div class="form-group">
    <label for="local_nasc" class="col-12 col-form-label">Local de nascimento</label>
    <div class="col-12">
      <input class="form-control" type="text" name="local_nasc" id="local_nasc" size="30">
    </div>
  </div>

  <div class="form-group">
    <label for="data_morte" class="col-12 col-form-label">Data de morte</label>
    <div class="col-12">
      <input class="form-control" type="date" value="" name="data_morte" id="data_morte">
    </div>
  </div>

  <div class="form-group">
    <label for="local_morte" class="col-12 col-form-label">Local de morte</label>
    <div class="col-12">
      <input class="form-control" type="text" name="local_morte" id="local_morte" size="30">
    </div>
  </div>

  <div class="form-group">
    <div class="col-12">
      <label for="exampleTextarea">Biografia</label>
      <textarea class="form-control" name ="bio" id="bio" rows="4"></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="webpage" class="col-3 col-form-label">Site Oficial</label>
    <div class="col-12">
      <input class="form-control" type="url" name ="webpage" id="webpage" size="30">
    </div>
  </div>


  <div class="form-group">
    <div class="col-12">
      <button type="submit" name="submit" class="btn btn-success">Cadastrar</button>
    </div>
  </div>

  </form>
  </div>
  </div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="js/i18n/defaults-*.min.js"></script>

</body>
</html>

<script>
$(document).ready(function(){
 $('.selectpicker').selectpicker();

 $('#componente').change(function(){
  $('#hidden_componente').val($('#componente').val());
 });

 $('#multiple_select_form').on('submit', function(event){
  event.preventDefault();
  if($('#componente').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     //console.log(data);
     $('#hidden_componentes').val('');
     $('.selectpicker').selectpicker('val', '');
     alert(data);
    }
   })
  }
  else
  {
   alert("Please select componente");
   return false;
  }
 });
});
</script>
