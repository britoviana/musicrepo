<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search Albums</title>

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
  <h1>Albums</h1>
  <div class="row">
  <div class="col-md-7" >
    <hr>
    <form action="searchalbum.php?id=busca" method="get" accept-charset="utf8" role="search">
      <div class="input-group input-group-lg">
          <input type="text" class="form-control" placeholder="Buscar por nome do álbum ou do artista" name="busca">
          <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
          </div>
      </div>
      </form>
    </br>
      <form action="searchalbum.php?busca&genero&ano_lanc&compositor" method="get" accept-charset="utf8" role="search">
        <div class="row">
          <div class="col-lg-12 col-md-12">
          <div class="form-group">
            <select class="form-control selectpicker" data-style="btn-info" data-width="10%">
              <option value="or">OU</option>
              <option value="and">E</option>
            </select>
          <select id="genero" name="genero[]" class="form-control selectpicker" data-width="89%" data-live-search="true" data-none-selected-text="Buscar por gêneros" multiple>';

              <?php showGenres('all'); ?>

          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12">
      <div class="form-group">
        <select class="form-control selectpicker" data-style="btn-info" data-width="10%">
          <option value="or">OU</option>
          <option value="and">E</option>
        </select>
          <select id="ano_lanc" name="ano_lanc[]" class="form-control selectpicker" data-width="89%" data-live-search="true" data-none-selected-text="Buscar por ano de lançamento" multiple>';

              <?php showReleasedYear(); ?>

          </select>
        </div>
      </div>
    </div>

    <span id="helpBlock" class="help-block">Faça as cominações que desejar.</span>
        <div class="input-group-btn">
            <button class="btn btn-default btn-sm btn-block" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </div>
      </form>

    </br>
  <?php
  // Get a connection for the database
  date_default_timezone_set('America/Sao_Paulo');
  require_once('connection.php');

  $dbh = openConnection();

  $id_album = $_GET['id'];

  $qr = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
  $sth = $dbh->prepare($qr);
  $sth->execute();

    if (!(empty($_GET['busca'])) || (!(isset($_GET['genero'])) && !(isset($_GET['ano_lanc']))) ) {

      // Create a query for the database
      $query = "SELECT a.id_artista, a.nome, a.tipo, al.nome_album, al.id_album from artista as a
                JOIN album_artista as aa ON a.id_artista = aa.id_artista
                JOIN album as al ON aa.id_album = al.id_album
                WHERE a.nome LIKE '%". $_GET['busca'] ."%' OR al.nome_album LIKE '%". $_GET['busca'] . "%'
                GROUP BY al.id_album, al.id_artista
                ORDER BY al.nome_album, a.nome";

      $query2 = "SELECT count(aa.id_artista) as artistas, al.id_album, al.nome_album from artista as a
                JOIN album_artista as aa ON a.id_artista = aa.id_artista
                JOIN album as al ON aa.id_album = al.id_album
                GROUP BY al.id_album, al.id_artista
                HAVING artistas > 1
                ORDER BY al.nome_album";

      $sth = $dbh->prepare($query);
      $sth2 = $dbh->prepare($query2);

      // Get a response from the database by sending the connection
      // and the query
      $response = $sth->execute();
      $response2 = $sth2->execute();


      // Prepares pagination
      $num_reg = $sth->rowCount();
      $itens_pp = 20;
      $pg = ($_GET['p']) * $itens_pp;

      $query .= " LIMIT $pg, $itens_pp";
      $sth = $dbh->prepare($query);
      $response = $sth->execute();

      $num_paginas = ceil($num_reg / $itens_pp);

      // If the query executed properly proceed
      if($response && $response2 && ($num_reg > 0) ){

      echo '<table id="listaalbum" class="table table-hover">
      <tr><th><b>Album</b></th>
      <th><b>Artista</b></th>
      <th></th>';

      // fetchAll will return a row of data from the query
      // until no further data is available
      $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
      $rows2 = $sth2->fetchAll(PDO::FETCH_ASSOC);

      // Inicia o um array id_musica com 0 caso não haja músicas com mais de um artista
      $id_album[] = 0;

      // Cria um array com todos os id_musica das músicas com mais de um artista
      foreach ($rows2 as $key => $value) $id_album[] = $value['id_album'];

      //Cria um array com id_musica e os artistas relacionados
      foreach ($id_album as $key => $id) $artists[$id] = selectArtistsByAlbumID($id);

      foreach($rows as $row){
        echo '<tr class="clickableRow" data-href="album.php?id='. $row['id_album'] .'"><td class="col-lg-7 col-md-7">' .
        $row['nome_album'] . '</td><td>';
        if (in_array ($row['id_album'], $id_album)) echo implode(', ', $artists[$row['id_album']]) .  '</td>';
        else echo $row['nome'] . '</td>';
        echo '<td class="col-lg-1 col-md-1 col-offset-1">';
        if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="editalbum.php?id='. $row['id_album'] .'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';
        echo '</tr>';
      }
        echo '</table>';

        if ($num_reg > 10) {
          echo '<nav class="text-center" aria-label="Page navigation">
      <ul class="pagination pagination-sm">
        <li>
          <a href="searchalbum.php?busca='.$_GET['busca'].'&p=0" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>';
        for ($i = 0; $i < $num_paginas; $i++)
          echo' <li><a href="searchalbum.php?busca='.$_GET['busca'].'&p='.$i.'">'.($i+1).'</a></li>';

        echo '<li>
          <a href="searchalbum.php?busca='.$_GET['busca'].'&p='.($num_paginas-1).'"" aria-label="Next">
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

      // Close connection to the database
      $dbh = null;
      $response = null;

    } else {

      if (isset($_GET['genero'])) {
        $id_genero_params = implode(', ', array_values($_GET['genero']));
        $id_genero_params = '('.$id_genero_params.')';

        if(stristr($query,'WHERE')) $query .= " OR g.id_genero IN " .$id_genero_params;
        else $query .= " WHERE g.id_genero IN " .$id_genero_params;
      }

      if (isset($_GET['ano_lanc'])){
        $ano_lanc_params = implode(', ', array_values($_GET['ano_lanc']));
        $ano_lanc_params = '('.$ano_lanc_params.')';

        if(stristr($query,'WHERE')) $query .= " OR al.ano_lanc IN " .$ano_lanc_params;
        else $query .= " WHERE al.ano_lanc IN " .$ano_lanc_params;

      }

      if (!(stristr($query,'ORDER BY'))) $query .= " GROUP BY al.id_album ORDER BY al.nome_album";

      $query = "SELECT al.id_album, al.nome_album, a.nome, g.id_genero from album as al
                JOIN album_genero as ag ON al.id_album = ag.id_album
                JOIN genero as g ON ag.genero = g.id_genero
                JOIN album_artista AS aa ON aa.id_album = al.id_album
                JOIN artista AS a ON a.id_artista = aa.id_artista";

      $query2 = "SELECT count(aa.id_artista) as artistas, al.id_album, al.nome_album from artista as a
                 JOIN album_artista as aa ON a.id_artista = aa.id_artista
                 JOIN album as al ON aa.id_album = al.id_album
                 GROUP BY al.id_album, al.id_artista
                 HAVING artistas > 1
                 ORDER BY al.nome_album";

      $sth = $dbh->prepare($query);
      $sth2 = $dbh->prepare($query2);
      $response = $sth->execute();
      $response2 = $sth->execute();

      // Prepares pagination
      $num_reg = $sth->rowCount();
      $itens_pp = 20;
      $pg = ($_GET['p']) * $itens_pp;

      $query .= " LIMIT $pg, $itens_pp";
      $sth = $dbh->prepare($query);
      $response = $sth->execute();

      $num_paginas = ceil($num_reg / $itens_pp);


      // If the query executed properly proceed
      if($response && ($num_reg > 0)) {

      echo '<table id="listaalbum" class="table table-hover">
      <tr><th><b>Album</b></th>
      <th><b>Artista</b></th>
      <th></th>';

      // fetchAll will return a row of data from the query
      // until no further data is available
      $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
      $rows2 = $sth->fetchAll(PDO::FETCH_ASSOC);

      // Inicia um array id_album com 0 caso não haja albuns com mais de um artista
      $id_album[] = 0;

      // Cria um array com todos os id_album dos albuns com mais de um artista
      foreach ($rows2 as $key => $value) $id_album[] = $value['id_album'];

      //Cria um array com id_album e os artistas relacionados
      foreach ($id_album as $key => $id) $artists[$id] = selectArtistsByAlbumID($id);

      $lanc_query = http_build_query(array('ano_lanc' => $_GET['ano_lanc']));
      $lanc_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $lanc_query);
      $genero_query = http_build_query(array('genero' => $_GET['genero']));
      $genero_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $genero_query);

      foreach($rows as $row){
        echo '<tr class="clickableRow" data-href="album.php?id='. $row['id_album'] .'"><td class="col-lg-7 col-md-7">' .
        $row['nome_album'] . '</td><td>';
        if (in_array ($row['id_album'], $id_album)) echo implode(', ', $artists[$row['id_album']]) .  '</td>';
        else echo $row['nome'] . '</td>';
        echo '<td class="col-lg-1 col-md-1">';
        if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="editalbum.php?id='. $row['id_album'] .'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';
        echo '</tr>';
      }
        echo '</table>';
        if ($num_reg > 10) {
          echo '<nav class="text-center" aria-label="Page navigation">
      <ul class="pagination pagination-sm">
        <li>
          <a href="searchalbum.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&p=0" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>';
        for ($i = 0; $i < $num_paginas; $i++)
          echo' <li><a href="searchalbum.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&p='.$i.'">'.($i+1).'</a></li>';

        echo '<li>
          <a href="searchalbum.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&p='.($num_paginas-1).'"" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>';

        }

      } else if ($count < 1){
        echo '<h2>Nenhuma ocorrência encontrada</h2>';
      } else {
        '<h2>Desculpe, houve algum erro na busca</h2>';
      }

      // Close connection to the database
      $dbh = null;
      $response = null;
    }

  ?>
  </div>
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
