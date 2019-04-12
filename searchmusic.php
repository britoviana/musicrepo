<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search Musics</title>

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
  <h1>Músicas</h1>
  <div class="row">
  <div class="col-lg-12 col-md-12">
    <hr>
    <form action="searchmusic.php?busca&genero&ano_lanc&compositor" method="get" accept-charset="utf8" role="search">
      <div class="input-group input-group-lg">
          <input type="text" class="form-control" placeholder="Nome da música ou artista" name="busca">
          <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
          </div>
      </div>
    </form>
    <br>
    <form action="searchmusic.php?busca&genero&ano_lanc&compositor" method="get" accept-charset="utf8" role="search">
      <div class="row">
        <div class="col-lg-12 col-md-12">
      <div class="form-group">
        <select name="andor_genero" class="form-control selectpicker" data-style="btn-info" data-width="17%">
          <option value="OR">OU</option>
          <option value="AND">E</option>
        </select>
        <select id="genero" name="genero[]" class="form-control selectpicker" data-live-search="true" data-width="81%" data-none-selected-text="Selecionar gênero" multiple>';

            <?php showGenres('all'); ?>

        </select>
      </div>
    </div>
    </div>
      <div class="row">
        <div class="col-lg-12 col-md-12">
      <div class="form-group">
        <select name="andor_ano_lanc"class="form-control selectpicker" data-style="btn-info" data-width="17%">
          <option value="OR">OU</option>
          <option value="AND">E</option>
        </select>
        <select id="ano_lanc" name="ano_lanc[]" class="form-control selectpicker" data-live-search="true" data-width="81%" data-none-selected-text="Selecionar ano de lançamento" multiple>';

            <?php showReleasedYear(); ?>

        </select>
      </div>
    </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12">
      <div class="form-group">
        <select name="andor_compositor" class="form-control selectpicker" data-style="btn-info" data-width="17%">
          <option value="OR">OU</option>
          <option value="AND">E</option>
        </select>
        <select id="compositor" name="compositor[]" class="form-control selectpicker" data-actions-box="true" data-live-search="true" data-width="81%" data-none-selected-text="Selecionar compositor" multiple>';

            <?php showComposers('all'); ?>

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


  // Se o a busca estiver vazia e os outros campos não forem setados exibe todas as músicas
  if (!(empty($_GET['busca'])) || (!(isset($_GET['compositor'])) && !(isset($_GET['genero'])) && !(isset($_GET['ano_lanc']))) ) {


    // Create a query for the database
    $query = "SELECT a.id_artista, a.nome, a.tipo, m.nome_musica, m.id_musica, al.nome_album, al.id_album from artista AS a
              JOIN musica_artista AS ma ON a.id_artista = ma.id_artista
              JOIN musica as m ON ma.id_musica = m.id_musica
              JOIN album_musicas as am ON m.id_musica = am.id_musica
              JOIN album as al ON am.id_album = al.id_album
              WHERE a.nome LIKE '%". $_GET['busca'] ."%' OR m.nome_musica LIKE '%". $_GET['busca'] . "%'
              GROUP BY m.id_musica
              ORDER BY m.nome_musica, a.nome";

              // Conta quantos artista uma música tem
    $query2 = "SELECT count(m.id_musica) as artistas, m.id_musica, m.nome_musica FROM artista AS a
              JOIN musica_artista as ma ON a.id_artista = ma.id_artista
              JOIN musica as m ON ma.id_musica = m.id_musica
              WHERE a.nome LIKE '%". $_GET['busca'] ."%' OR m.nome_musica LIKE '%". $_GET['busca'] . "%'
  			      GROUP BY m.id_musica
              HAVING artistas > 1";

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

    if($response && ($num_reg > 0)){

    echo '<table id="listamusicas" class="table table-hover">
    <tr><th><b>Música</b></th>
    <th><b>Artista</b></th>
    <th></th>';

    // fetchAll will return a row of data from the query
    // until no further data is available
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    $rows2 = $sth2->fetchAll(PDO::FETCH_ASSOC);

    // Inicia o um array id_musica com 0 caso não haja músicas com mais de um artista
    $id_musica[] = 0;

    // Cria um array com todos os id_musica das músicas com mais de um artista
    foreach ($rows2 as $key => $value) $id_musica[] = $value['id_musica'];

    //Cria um array com id_musica e os artistas relacionados
    foreach ($id_musica as $key => $id) $artists[$id] = selectArtistByMusicID($id);



    foreach($rows as $row){
      echo '<tr class="clickableRow" data-href="music.php?id='. $row['id_musica'] .'"><td class="col-lg-7 col-md-7">' .
      $row['nome_musica'] . '</td><td>';
      if (in_array ($row['id_musica'], $id_musica)) echo implode(', ', $artists[$row['id_musica']]) .  '</td>';
      else echo $row['nome'] . '</td>';
      echo '<td class="col-lg-1 col-md-1">';
      if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="updatemusic.php?id='. $row['id_musica'] .'&album='.$row['id_album'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';

      echo '</tr>';
    }
      echo '</table>';

      if ($num_reg > 20) {
        echo '<nav class="text-center" aria-label="Page navigation">
    <ul class="pagination pagination-sm">
      <li>
        <a href="searchmusic.php?busca='.$_GET['busca'].'&p=0" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>';
      for ($i = 0; $i < $num_paginas; $i++)
        echo' <li><a href="searchmusic.php?busca='.$_GET['busca'].'&p='.$i.'">'.($i+1).'</a></li>';

      echo '<li>
        <a href="searchmusic.php?busca='.$_GET['busca'].'&p='.($num_paginas-1).'"" aria-label="Next">
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

  } else {
    $query = "SELECT m.id_musica, m.nome_musica, a.nome, g.id_genero, mc.id_compositor from musica as m
              LEFT JOIN musica_genero as mg ON m.id_musica = mg.id_musica
              LEFT JOIN genero as g ON mg.genero = g.id_genero
              LEFT JOIN musica_compositor as mc ON m.id_musica = mc.id_musica
              LEFT JOIN artista AS a ON mc.id_compositor = a.id_artista";

    if (isset($_GET['compositor'])) {

      $id_compositor_params = implode(', ', array_values($_GET['compositor']));
      $id_compositor_params = '('.$id_compositor_params.')';

      if(stristr($query,'WHERE')) $query .= " " .$_GET['andor_compositor']. " mc.id_compositor in " .$id_compositor_params;
      else $query .= " WHERE mc.id_compositor in " .$id_compositor_params;
    }

    if (isset($_GET['genero'])) {
      $id_genero_params = implode(', ', array_values($_GET['genero']));
      $id_genero_params = '('.$id_genero_params.')';

      if(stristr($query,'WHERE')) $query .= " " .$_GET['andor_genero']. " g.id_genero in " .$id_genero_params;
      else $query .= " WHERE g.id_genero in " .$id_genero_params;
    }

    if (isset($_GET['ano_lanc'])){
      $ano_lanc_params = implode(', ', array_values($_GET['ano_lanc']));
      $ano_lanc_params = '('.$ano_lanc_params.')';

      if(stristr($query,'WHERE')) $query .= " " .$_GET['andor_ano_lanc']. " m.ano_lanc in " .$ano_lanc_params;
      else $query .= " WHERE m.ano_lanc in " .$ano_lanc_params;

    }

    if (!(stristr($query,'ORDER BY'))) $query .= " GROUP BY m.id_musica ORDER BY m.nome_musica";

    // conta quantos compositores uma música tem
    $query2 = "SELECT count(m.id_musica) as compositores, m.id_musica, m.nome_musica FROM artista AS a
               JOIN musica_compositor as mc ON a.id_artista = mc.id_compositor
               JOIN musica as m ON mc.id_musica = m.id_musica
               GROUP BY m.id_musica
               HAVING compositores > 1";


    $sth = $dbh->prepare($query);
    $sth2 = $dbh->prepare($query2);


    $response = $sth->execute();
    $response2 = $sth2->execute();

    $num_reg = $sth->rowCount();
    $itens_pp = 20;
    $pg = ($_GET['p']) * $itens_pp;

    $query .= " LIMIT $pg, $itens_pp";
    $sth = $dbh->prepare($query);
    $response = $sth->execute();

    $num_paginas = ceil($num_reg / $itens_pp);


// If the query executed properly proceed
if($response && $response2 && ($num_reg > 0)){

echo '<table id="listaalbum" class="table table-hover">
<tr><th><b>musica</b></th>
<th><b>compositor</b></th>
<th></th>';

// fetchAll will return a row of data from the query
// until no further data is available
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$rows2 = $sth2->fetchAll(PDO::FETCH_ASSOC);


    // Inicia o um array id_musica com 0 caso não haja músicas com mais de um compositor
    $id_musica[] = 0;

    // Cria um array com todos os id_musica das músicas com mais de um compositor
    foreach ($rows2 as $key => $value) $id_musica[] = $value['id_musica'];

    //Cria um array com id_musica e os artistas relacionados
    foreach ($id_musica as $key => $id) $composers[$id] = selectComposersByMusicID($id);

    $lanc_query = http_build_query(array('ano_lanc' => $_GET['ano_lanc']));
    $lanc_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $lanc_query);
    $genero_query = http_build_query(array('genero' => $_GET['genero']));
    $genero_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $genero_query);
    $compositor_query = http_build_query(array('compositor' => $_GET['compositor']));
    $compositor_query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $compositor_query);


foreach($rows as $row){
  echo '<tr class="clickableRow" data-href="music.php?id='. $row['id_musica'] .'"><td class="col-lg-6 col-md-6">' .
  $row['nome_musica'] . '</td><td>';
  if (in_array ($row[id_musica], $id_musica)) echo implode(', ', $composers[$row[id_musica]]) .  '</td>';
  else echo $row['nome'] . '</td>';
  echo '<td class="col-lg-1 col-md-1">';
  if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="updatemusic.php?id='. $row[id_musica] .'&album='.$row[id_album].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';
  echo '</tr>';
}
  echo '</table>';

  if ($num_reg > 20) {
    echo '<nav class="text-center" aria-label="Page navigation">
<ul class="pagination pagination-sm">
  <li>
    <a href="searchmusic.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&'.$compositor_query.'&p=0" aria-label="Previous">
      <span aria-hidden="true">&laquo;</span>
    </a>
  </li>';
  for ($i = 0; $i < $num_paginas; $i++)
    echo' <li><a href="searchmusic.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&'.$compositor_query.'&p='.$i.'">'.($i+1).'</a></li>';

  echo '<li>
    <a href="searchmusic.php?busca='.$_GET['busca'].'&'.$genero_query.'&'.$lanc_query.'&'.$compositor_query.'&p='.($num_paginas-1).'"" aria-label="Next">
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
