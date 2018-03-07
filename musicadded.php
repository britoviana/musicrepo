<!DOCTYPE HTML>
<html lang="pt_BR">
  <head>
    <title>Add Musica</title>
    <!-- Required meta tags -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  </head>
<body>

<?php

if (isset($_POST['submit'])){

    $estudio_aovivo = trim($_POST['estudio_aovivo']);
    $artista = trim($_POST['artista']);

    $data_missing = array();

    if (empty($_POST['nome'])){

        // Adds name to array
        $data_missing[] = 'Nome da música';

    } else {

        // Trim white space from the name and store the name
        $nome = trim($_POST['nome']);

    }

    if (empty($_POST['ano_lanc'])){

        // Adds name to array
        $data_missing[] = 'Ano de lançamento';

    } else{

        // Trim white space from the name and store the name
        $ano_lanc = trim($_POST['ano_lanc']);
    }


    if(empty($data_missing)){

        require_once('connection.php');
        $link = openConnection();

        $query = "INSERT INTO musica (nome_musica, estudio_ao_vivo, ano_lancamento) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($link, $query);

        mysqli_stmt_bind_param($stmt, 'sss', $nome, $estudio_aovivo, $ano_lanc);

        mysqli_stmt_execute($stmt);

        //Return ID_MUSICA from the recent added music
        $id_musica = mysqli_insert_id($link);
        var_dump($id_musica);

        $query2 = "INSERT INTO musica_artista (id_musica, id_artista) VALUES (?, ?)";

        $stmt2 = mysqli_prepare($link, $query2);

        mysqli_stmt_bind_param($stmt2, 'ii', $id_musica, $_POST['artista']);

        mysqli_stmt_execute($stmt2);


        $affected_rows = mysqli_stmt_affected_rows($stmt);
        $affected_rows2 = mysqli_stmt_affected_rows($stmt2);


        if( $affected_rows == 1 && $affected_rows2 == 1 ){

            echo 'Música cadastrada!';

            mysqli_stmt_close($stmt);

            mysqli_stmt_close($stmt2);

        } else {
            echo 'Error Occurred<br />';

            echo mysqli_error($link);

            mysqli_stmt_close($stmt);

            mysqli_stmt_close($stmt2);

            mysqli_close($link);
        }
    } else {

        echo 'Preencha os campos obrigatórios:<br />';

        foreach( $data_missing as $missing ){

            echo "$missing<br />";
        }
    }
}

?>

<header>
  <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="#">Musicrepo</a>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
  <li class="nav-item active">
    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="getartistifno.php">Artistas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="addmusic.php">Músicas</a>
  </li>
</ul>
</div>
</nav>
</header>

<div class="container-fluid">
  <div class="row" style="margin-top: 20px;">
  <div class="col-md-10 col-md-offset-1" >

<h1>Add new music</h1>

<form action="musicadded.php" method="post" accept-charset="utf8">

  <div class="form-group">
    <label for="nome" class="col-2 col-form-label">Nome da música</label>
    <div class="col-10">
      <input class="form-control" type="text" name="nome" id="nome" maxlength="60">
    </div>
  </div>

  <div class="form-group">
    <div class="col-2">
      <label for="versao">Versão</label>
      <select class="form-control" name="estudio_aovivo" id="estudio_aovivo">
        <option value="estudio">Estúdio</option>
        <option value="aovivo">Ao Vivo</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <div class="col-10">
      <label for="ano_lanc">Ano de lançamento</label>
      <input class="form-control" type="number" min="1900" max="2017" name="ano_lanc" size="4" maxlength="4" value="" name="ano_lanc" id="ano_lanc">
    </div>
  </div>

  <div class="form-group">
    <div class="col-3">
      <label for="artista">Artista</label>
      <select class="form-control" id="artista" name="artista">
        <?php
        require_once('connection.php');
        showArtists();
        ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <div class="col-10">
      <input type="submit" name="submit" value="Cadastrar"/>
    </div>
  </div>

</form>
</div>
</div>
</div>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"
    </body>
</html>
