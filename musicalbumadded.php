<!DOCTYPE html>
<html lang="pt_BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Add Musica Album</title>

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
  </head>
<body>

<?php

if (isset($_POST['addtrack'])){

    $data_missing = array();

    if (empty($_POST['nome'])){

        // Adds name to array
        $data_missing[] = 'Nome da música';

    } else {

        // Trim white space from the name and store the name
        $nome = trim($_POST['nome']);

    }

    if (empty($_POST['num_faixa'])){

        // Adds name to array
        $data_missing[] = 'Número da faixa';

    } else{

        // Trim white space from the name and store the name
        $num_faixa = trim($_POST['num_faixa']);
    }

    //For test purposes
    $ano_lanc = '1976';
    $versao = 'estudio';
    $id_album = $_POST['id_album'];


    if(empty($data_missing)){

        require_once('connection.php');
        $dbh = openConnectionPDO();

        $query = "INSERT INTO musica (nome_musica, versao, ano_lanc) VALUES (:nome, :versao, :ano);
                  INSERT INTO album_musicas (id_album, id_musica, num_faixa) VALUES (:id_album, LAST_INSERT_ID(), :faixa)";

        $sth = $dbh->prepare($query);

        $sth->bindParam(':nome', $nome);
        $sth->bindParam(':versao', $versao);
        $sth->bindParam(':ano', $ano_lanc);
        $sth->bindParam(':id_album', $id_album);
        $sth->bindParam(':faixa', $num_faixa);

        $result = $sth->execute();

        //Return ID_MUSICA from the recent added music
        $id_musica = $dbh->lastInsertId();

        //Add row to MUSICA_ARTISTA
        if (isset($_POST['artista'])){
          foreach ($_POST['artista'] as $id_artista){

              $query = "INSERT INTO musica_artista (id_musica, id_artista) VALUES (:id_musica, :id_artista)";
              $sth = $dbh->prepare($query);
              $sth->bindValue(':id_artista', $id_artista);
              $sth->bindValue(':id_musica', $id_musica);
              $result = $sth->execute();
          }
        }

        //Add row to MUSICA_COMPOSITOR
        if (isset($_POST['compositor'])){
          foreach ($_POST['compositor'] as $id_compositor){

              $query = "INSERT INTO musica_compositor (id_musica, id_compositor) VALUES (:id_musica, :id_compositor)";
              $sth = $dbh->prepare($query);
              $sth->bindValue(':id_musica', $id_musica);
              $sth->bindValue(':id_compositor', $id_compositor);
              $result = $sth->execute();
          }
        }

        //Add row to MUSICA_GENERO
        if (isset($_POST['genero'])){
          foreach ($_POST['genero'] as $id_genero){

              $query = "INSERT INTO musica_genero (id_musica, genero) VALUES (:id_musica, :genero)";
              $sth = $dbh->prepare($query);
              $sth->bindValue(':id_musica', $id_musica);
              $sth->bindValue(':genero', $id_genero);
              $result = $sth->execute();
          }
        }

        $affected_rows = $sth->rowCount();


        if($affected_rows == 1){

            echo '<div class="container" style=" margin-top: 20px;">
              <div class="alert alert-success col-md-6 col-md-offset-3 text-center" role="alert"><strong>Música cadastrada!</strong>
              </div>
              <a href="musicalbum.php?id=' . $id_album . '" class="btn btn-primary col-xs-12 col-xs-offset-0 col-md-2 col-md-offset-5" role="button">Voltar</a>
            </div>
            ';

            $sth = null;
            $dbh = null;


        } else {
            echo '<div class="alert alert-danger" role="alert">Error Occurred</div><br />';

            $sth = null;
            $dbh = null;
        }
    } else {

        echo 'Preencha os campos obrigatórios:<br />';

        foreach($data_missing as $missing){

            echo "$missing<br />";
        }
    }
}

?>


    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    </body>
</html>
