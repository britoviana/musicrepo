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
  <link rel="stylesheet" href="css/bootstrap-select.min.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <title>Add Album</title>

</head>

<body>

  <header>
    <?php
    require_once('connection.php');
    navBar();
    ?>
  </header>

<?php

if (isset($_POST['submit'])){

    $data_missing = array();

    if (empty($_POST['nome'])){

        // Adds nome to array
        $data_missing[] = 'Nome';

    } else {

        // Trim white space and escape the nome and store the nome
        $nome = trim($_POST['nome']);

    }


    if (empty($_POST['artista'])){

        // Adds name to array
        $data_missing[] = 'Artista';

    } else {

        // Trim white space from the name and store the name
        //$artista = trim($_POST['artista']);

    }

    if (empty($_POST['num_faixas'])){

        // Adds name to array
        $data_missing[] = 'Número de faixas';

    } else {

        // Trim white space from the name and store the name
        $num_faixas = trim($_POST['num_faixas']);

    }

    if (empty($_POST['ano'])){

        // Adds name to array
        $data_missing[] = 'Ano de lançamento';

    } else {

        // Trim white space from the name and store the name
        $ano = trim($_POST['ano']);

    }

    if(empty($_POST['gravadora'])){

        // Adds name to array
        $data_missing[] = 'Gravadora';

    } else {

        // Trim white space from the name and store the name
        $gravadora = trim($_POST['gravadora']);

    }

    if(empty($_POST['formato'])){

        // Adds name to array
        $data_missing[] = 'Formato';

    } else {

        // Trim white space from the name and store the name
        $formato = trim($_POST['formato']);

    }

    if(empty($_POST['producao'])){

        // Adds name to array
        $data_missing[] = 'Produção';

    } else {

        // Trim white space from the name and store the name
        $producao = trim($_POST['producao']);

    }

    if(empty($data_missing)){

      require_once('connection.php');
      $dbh = openConnectionPDO();

      $query = "INSERT INTO album (id_artista, nome_album, num_faixas, gravadora, formato, ano_lanc, producao)
      VALUES (:id_artista, :nome, :faixas, :gravadora, :formato, :ano, :producao)";

      $sth = $dbh->prepare($query);

      // VER SE O CAMPO ID_ARTISTA CONTINUARA EM ALBUM
      $idd = 2;
      $sth->bindParam(':id_artista', $idd);
      ///////---------------------/////////////////////

      $sth->bindParam(':nome', $nome);
      $sth->bindParam(':faixas', $num_faixas);
      $sth->bindParam(':gravadora', $gravadora);
      $sth->bindParam(':formato', $formato);
      $sth->bindParam(':ano', $ano);
      $sth->bindParam(':producao', $producao);

      $result = $sth->execute();

      $id_album = $dbh->lastInsertId();

      if (isset($_POST['artista'])){
        foreach ($_POST['artista'] as $id_artista){

            $query = "INSERT INTO album_artista (id_album, id_artista) VALUES (:id_album, :id_artista)";
            $sth = $dbh->prepare($query);
            $sth->bindValue(':id_album', $id_album);
            $sth->bindValue(':id_artista', $id_artista);
            $result = $sth->execute();
        }
      }

      if (isset($_POST['genero'])){
        foreach ($_POST['genero'] as $id_genero){

            $query = "INSERT INTO album_genero (id_album, genero) VALUES (:id_album, :genero)";
            $sth = $dbh->prepare($query);
            $sth->bindValue(':id_album', $id_album);
            $sth->bindValue(':genero', $id_genero);
            $result = $sth->execute();
        }
      }

      $affected_rows = $sth->rowCount();

      if($affected_rows == 1){

            $sth = null;
            $dbh = null;

            echo '<br><div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>SUBMETERU!</strong> Artista adicionado.
      </div>'.
            '<form action="musicalbum.php?id=' . $id_album . '" method="post" accept-charset="uft8">
            <div class="form-group">
        			<div class="col-12">
        				<button type="submit" name="submit" class="btn btn-success">Cadastrar músicas</button>
        			</div>
        		</div>';


        } else {
            echo 'Error Occurred<br />';

            echo $sth->errorInfo();

            $sth = null;
            $dbh = null;
        }

    } else {

        echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Album não adicionado!</strong> Preencha os campos obrigatórios:</br>';

        foreach($data_missing as $missing){

            echo "$missing<br />";
        }
        echo "</div>";
    }
}

?>

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
