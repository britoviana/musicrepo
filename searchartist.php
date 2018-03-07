<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search Artist</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap-select.min.css">

    <style>
    tr.clickableRow
    {cursor: pointer;}
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <header>
<?php
      require_once('connection.php');
      navBar();
?>
    </header>


  <div class="container">

  <h1>Artistas</h1>
  <div class="row">
  <div class="col-lg-7 col-md-7">
    <hr>
      <form action="searchartist.php?id=busca" method="get" accept-charset="utf8" role="search">
        <div class="input-group input-group-lg">
            <input type="text" class="form-control" placeholder="Buscar artista pelo nome" name="busca">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
      </br>

      <form action="searchartist.php?id=busca&ano_nasc&ano_morte" method="get" accept-charset="utf8" role="search">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="form-group" aria-describedby="helpBlock">
            <select name="andor_nasc" class="form-control selectpicker" data-style="btn-info" data-width="10%">
              <option value="OR">OU</option>
              <option value="AND">E</option>
            </select>
          <select id="ano_nasc" name="ano_nasc[]" class="form-control selectpicker" data-live-search="true" data-width="89%" data-none-selected-text="Buscar por ano de nascimento" multiple>';

              <?php showBirthYear("nasc"); ?>

          </select>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12">
            <div class="form-group" aria-describedby="helpBlock">
          <select name="andor_morte" class="form-control selectpicker" data-style="btn-info" data-width="10%">
            <option value="OR">OU</option>
            <option value="AND">E</option>
          </select>
          <select id="ano_morte" name="ano_morte[]" class="form-control selectpicker" data-live-search="true" data-width="89%" data-none-selected-text="Buscar por ano de morte" multiple>';

              <?php showBirthYear("morte"); ?>

          </select>
        </div>
        <span id="helpBlock" class="help-block">Faça as cominações que desejar.</span>
        <div class="input-group-btn">
            <button class="btn btn-default btn-sm btn-block" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </div>
      </form>
    </br>

  </div>


  <?php
  // Get a connection for the database
  date_default_timezone_set('America/Sao_Paulo');
  require_once('connection.php');

  $dbh = openConnection();

  //$id_album = $_GET['id'];

  if (!(empty($_GET['busca'])) || (!(isset($_GET['ano_nasc'])) && !(isset($_GET['ano_morte']))) ) {

    // Create a query for the database
    $query = "SELECT id_artista as id, nome, tipo from artista
              WHERE nome LIKE '%". $_GET['busca'] ."%' OR nome_completo LIKE '%". $_GET['busca'] . "%'
              ORDER BY nome";

    $sth = $dbh->prepare($query);

    // Get a response from the database by sending the connection
    // and the query
    $response = $sth->execute();

    $num_reg = $sth->rowCount();
    $itens_pp = 10;
    $pg = ($_GET['p']) * $itens_pp;

    $query .= " LIMIT $pg, $itens_pp";
    $sth = $dbh->prepare($query);
    $response = $sth->execute();

    $num_paginas = ceil($num_reg / $itens_pp);

    // If the query executed properly proceed
    if($response && ($num_reg > 0)){

    echo '<div class="col-lg-12 col-md-12">
    <table id="listaartista" class="table table-hover">
    <tr><td><b>Nome</b></td>
        <td></td>
    </tr></div>';

    // fetchAll will return a row of data from the query
    // until no further data is available
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);


    foreach($rows as $row){
      echo '<tr class="clickableRow" data-href="artistprofile.php?id='. $row['id'] .'"><td class="col-lg-12 col-md-12">' .
      $row['nome'] . '</td><td class="col-lg-1 col-md-1">';
      if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="updateartist.php?id='. $row['id'] .'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';
  ;
      echo '</tr>';
    }
      echo '</table>';

      if ($num_reg > 10) {
        echo '<nav class="text-center" aria-label="Page navigation">
    <ul class="pagination pagination-sm">
      <li>
        <a href="searchartist.php?busca='.$_GET['busca'].'&p=0" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>';
      for ($i = 0; $i < $num_paginas; $i++)
        echo' <li><a href="searchartist.php?busca='.$_GET['busca'].'&p='.$i.'">'.($i+1).'</a></li>';

      echo '<li>
        <a href="searchartist.php?busca='.$_GET['busca'].'&p='.($num_paginas-1).'"" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>';

      }

    }  else if ($count < 1){
      echo '<div class="col-lg-7 col-md-7"> <h2>Nenhuma ocorrência encontrada</h2>';
    } else {
      '<h2>Desculpe, houve algum erro na busca</h2>';
    }


  } else {

    $query = "SELECT id_artista as id, nome, tipo from ARTISTA";

    if (!empty($_GET['ano_nasc'])) {
      $ano_nasc_params = implode(', ', array_values($_GET['ano_nasc']));
      $ano_nasc_params = '('.$ano_nasc_params.')';

      if(stristr($query,'WHERE')) $query .= " " .$_GET['andor_nasc']. " year(data_nasc) IN " .$ano_nasc_params;
      else $query .= " WHERE year(data_nasc) IN " .$ano_nasc_params;
    }

    if (!empty($_GET['ano_morte'])){
      $ano_morte_params = implode(', ', array_values($_GET['ano_morte']));
      $ano_morte_params = '('.$ano_morte_params.')';

      if(stristr($query,'WHERE')) $query .= " " .$_GET['andor_morte']. " year(data_morte) IN " .$ano_morte_params;
      else $query .= " WHERE year(data_morte) IN " .$ano_morte_params;

    }

    if (!(stristr($query,'ORDER BY'))) $query .= " ORDER BY nome";

    $sth = $dbh->prepare($query);

    // Get a response from the database by sending the connection
    // and the query
    $response = $sth->execute();

    $num_reg = $sth->rowCount();
    $itens_pp = 10;
    $pg = ($_GET['p']) * $itens_pp;

    $query .= " LIMIT $pg, $itens_pp";
    $sth = $dbh->prepare($query);
    $response = $sth->execute();

    $num_paginas = ceil($num_reg / $itens_pp);

    // If the query executed properly proceed
    if($response && ($num_reg > 0)){

    echo '<div class="col-lg-12 col-md-12">
    <table id="listaartista" class="table table-hover">
    <tr><td><b>Nome</b></td>
        <td></td>
    </tr></div>';

    // fetchAll will return a row of data from the query
    // until no further data is available
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

    $nasc_query = http_build_query(array('ano_nasc' => $_GET['ano_nasc']));
    $nasc_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $nasc_query);
    $morte_query = http_build_query(array('ano_morte' => $_GET['ano_morte']));
    $morte_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $morte_query);


    foreach($rows as $row){
      echo '<tr class="clickableRow" data-href="artistprofile.php?id='. $row['id'] .'"><td class="col-lg-12 col-md-12">' .
      $row['nome'] . '</td><td class="col-lg-1 col-md-1">';
      if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="updateartist.php?id='. $row['id'] .'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';

  ;
      echo '</tr>';
    }
      echo '</table>';

      if ($num_reg > 10) {

        echo '<nav class="text-center" aria-label="Page navigation">
    <ul class="pagination pagination-sm">
      <li>
        <a href="searchartist.php?busca='.$_GET['busca'].'&'.$nasc_query.'&'.$morte_query.'&p=0" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>';
      for ($i = 0; $i < $num_paginas; $i++)
        echo' <li><a href="searchartist.php?busca='.$_GET['busca'].'&'.$nasc_query.'&'.$morte_query.'&p='.$i.'">'.($i+1).'</a></li>';

      echo '<li>
        <a href="searchartist.php?busca='.$_GET['busca'].'&'.$nasc_query.'&'.$morte_query.'&p='.($num_paginas-1).'"" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>';

      }


    }  else if ($count < 1){
      echo '<h2>Nenhuma ocorrência encontrada</h2>';
    } else {
      '<h2>Desculpe, houve algum erro na busca</h2>';
    }

  }

  // Close connection to the database
  $dbh = null;
  $response = null;

  ?>

</div></div>

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

 $('#framework').change(function(){
  $('#hidden_framework').val($('#framework').val());
 });

 $('#multiple_select_form').on('submit', function(event){
  event.preventDefault();
  if($('#framework').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     //console.log(data);
     $('#hidden_framework').val('');
     $('.selectpicker').selectpicker('val', '');
     alert(data);
    }
   })
  }
  else
  {
   alert("Please select framework");
   return false;
  }
 });
});

jQuery(document).ready(function($) {
    $(".clickableRow").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
