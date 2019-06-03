<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Adicionar faixa do álbum</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <!-- Bootstrap Formhelpers -->
    <link href="css/bootstrap-formhelpers.min.css" rel="stylesheet">


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

    <div class="modal confirma-exclusao" tabindex="-1" role="dialog" aria-labelledby="confirmaExclusaoModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title"><center>Confirma exclusão da faixa?</center></h3>
          </div>
          <div class="modal-body">
            <p>A faixa e todas suas referências serão removidas.&hellip;</p>
          </div>
          <form action="musicalbum.php?id=<?php echo $_GET['id'] ?>" method="post">
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" name="deletetrack" class="btn btn-danger">Excluir</button></form>
          </div>
        </div>
      </div>
    </div>
    </form>

    <div class="container">

        <?php

        if ((!isset($_SESSION['usuario'])) || !($_SESSION['nivel_acesso'] == '1')) {

					echo '<div class="col-md-12 alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong></strong> Voce não tem permissão para acessar esta pagina, faça o login!.
		</div>';

    session_destroy();

	} else {

        echo '<div class="col-lg-12">
              <h3>Adicionar faixa ao álbum</h3>
              <form class="form-inline" action="musicalbum.php?id='.$_GET['id'].'" method="post">';
        ?>

           <div class="form-group">
             <label class="sr-only" for="exampleInputEmail3">Faixa</label>
             <input type="text" class="form-control" data-buttons="false" name="num_faixa" id="num_faixa" data-min="1" data-max="99" value="<?php echo selectNextTrackNumByAlbumID($_GET['id']);?>">
           </div>

           <div class="form-group">
             <label class="sr-only" for="exampleInputPassword3">Nome</label>
             <input type="text" class="form-control" name="nome" id="nome" size="30" placeholder="Nome da faixa">
           </div>

           <div class="form-group">
             <select name="artista[]" id="artista" class="form-control selectpicker" data-buttons="false" data-width="auto" data-live-search="true" data-none-selected-text="Artista" multiple data-max-options="3" multiple>
               <?php
                 $artistas = selectArtistsByAlbumID($_GET['id']);
                 foreach ($artistas as $key => $value) $artistas[] = $key;
                 showArtists($artistas);
               ?>
             </select>
           </div>

         <div class="form-group">
           <select name="compositor[]" id="compositor" class="form-control selectpicker"  data-width="auto" data-live-search="true" data-none-selected-text="Compositor" multiple>
             <?php
              showArtists('all');
             ?>
           </select>
         </div>

       <div class="form-group">
         <select name="genero[]" id="genero" class="form-control selectpicker" data-width="auto" data-live-search="true" data-none-selected-text="Gênero" multiple>
           <?php
            $generos = selectGenresIdByAlbumID($_GET['id']);
            showGenres($generos);
           ?>
         </select>
       </div>

       <input type="hidden" name="id_album" value="<?php echo $_GET['id'] ?>">

     <div class="form-group">
       <button type="submit" name="addtrack" class="btn btn-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
     </div>

   </form>
 </div>


  <div class="container">

  <div class="row">
  <div class="col-lg-12 col-md-12" >

  <?php
  // Get a connection for the database
  date_default_timezone_set('America/Sao_Paulo');
  require_once('connection.php');

  $dbh = openConnection();

  $id_album = $_GET['id'];

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
      $id_album = $_POST['id_album'];

      $row = selectAlbumDetailsByAlbumID($id_album);
      $ano_lanc = $row['ano_lanc'];
      $versao = 'estudio';




      if(empty($data_missing)){


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

          $addrows = $sth->rowCount();


          if($addrows == 1){

              echo '<br><div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>SUCESSO!</strong> Faixa adicionada.
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

          echo '<div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      Preencha os campos obrigatórios:</br>';

          foreach($data_missing as $missing){

              echo "$missing </div><br />";
          }
      }
  }

/////////////////////////////////////////////updatetrack
  if (isset($_POST['updatetrack'])) {

    $data_missing = array();

    if (empty($_POST['num_faixa'])){

        // Adds name to array
        $data_missing[] = 'Número da faixa';

    } else {

        // Trim white space from the name and store the name
        $num_faixa = trim($_POST['num_faixa']);

    }
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

    if (empty($_POST['artista'])){

        // Adds name to array
        $data_missing[] = 'Artista';

    }

    $versao = trim($_POST['versao']);
    $id_musica = $_POST['id'];

    if(empty($data_missing)){

        $query = "UPDATE musica SET nome_musica = :nome , versao = :versao , ano_lanc = :ano_lanc
                  WHERE id_musica = :id";

        $query2 = "UPDATE album_musicas SET num_faixa = :num_faixa WHERE id_musica = :id";

        $query4 = "DELETE FROM musica_genero WHERE id_musica = :id_musica";
        $sth4 = $dbh->prepare($query4);
        $sth4->bindValue(':id_musica', $id_musica);
        $result4 = $sth4->execute();

        $query5 = "DELETE FROM musica_artista WHERE id_musica = :id_musica";
        $sth5 = $dbh->prepare($query5);
        $sth5->bindValue(':id_musica', $id_musica);
        $result5 = $sth5->execute();

        $query7 = "DELETE FROM musica_compositor WHERE id_musica = :id_musica";
        $sth7 = $dbh->prepare($query7);
        $sth7->bindValue(':id_musica', $id_musica);
        $result7 = $sth7->execute();


        if (isset($_POST['compositor']) && $result7){

          foreach ($_POST['compositor'] as $id_compositor){

              $query8 = "INSERT INTO musica_compositor (id_musica, id_compositor) VALUES (:id_musica, :compositor)";
              $sth8 = $dbh->prepare($query8);
              $sth8->bindValue(':id_musica', $id_musica);
              $sth8->bindValue(':compositor', $id_compositor);
              $result8 = $sth8->execute();
          }

        }

        if (isset($_POST['artista']) && $result5){

          foreach ($_POST['artista'] as $id_artista){

              $query6 = "INSERT INTO musica_artista (id_musica, id_artista) VALUES (:id_musica, :artista)";
              $sth6 = $dbh->prepare($query6);
              $sth6->bindValue(':id_musica', $id_musica);
              $sth6->bindValue(':artista', $id_artista);
              $result6 = $sth6->execute();
          }

        }

        if (isset($_POST['genero']) && $result4){

            foreach ($_POST['genero'] as $id_genero){

                $query3 = "INSERT INTO musica_genero (id_musica, genero) VALUES (:id_musica, :genero)";
                $sth3 = $dbh->prepare($query3);
                $sth3->bindValue(':id_musica', $id_musica);
                $sth3->bindValue(':genero', $id_genero);
                $result3 = $sth3->execute();

            }
        }

        $sth = $dbh->prepare($query);
        $sth2 = $dbh->prepare($query2);


        $sth->bindParam(':nome', $nome);
        $sth->bindParam(':versao', $versao);
        $sth->bindParam(':ano_lanc', $ano_lanc);
        $sth->bindParam(':id', $id_musica);

        $sth2->bindParam(':num_faixa', $num_faixa);
        $sth2->bindParam(':id', $id_musica);


        $result = $sth->execute();
        $result2 = $sth2->execute();


        $updaterows = $sth->rowCount();
        $updaterows2 = $sth2->rowCount();


        if($result && $result2 && $result4 && $result6){

          echo '<br><div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Sucesso!</strong> Faixa atualizada.
    </div>';

        } else {
          echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>ERRO!</strong> Faixa não atualizada.
    </div>';

        }
    } else {
        echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Faixa não atualizada!</strong> Preencha os campos obrigatórios:</br>';

        foreach($data_missing as $missing){

            echo "$missing<br/></div>";
        }
    }

}
/////////////////////////////////////////updatetrack
if (isset($_POST['deletetrack'])){

  $id_musica = $_POST['id'];

  $query = "DELETE FROM musica WHERE id_musica = :id";

  $sth = $dbh->prepare($query);

  $sth->bindParam(':id', $id_musica);

  $response = $sth->execute();

  $deleterows = $sth->rowCount();

  if($deleterows == 1){

        echo '
        <br><div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Sucesso!</strong> Faixa removida.
  </div>';

        $sth = null;
        $dbh = null;

    } else {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Erro! </strong>'. $sth->errorInfo().'</div>';

        $sth = null;
        $dbh = null;
      }
}
  // Create a query for the database
  $query = "SELECT m.id_musica AS id, am.num_faixa AS faixa, m.nome_musica AS musica, a.nome_album AS album, a.id_album as id_album FROM musica AS m
            JOIN album_musicas AS am ON am.id_musica = m.id_musica
            JOIN album AS a ON am.id_album = a.id_album WHERE a.id_album = :id_album
            ORDER BY am.num_faixa";

  $dbh = openConnection();
  $sth = $dbh->prepare($query);
  $sth->bindParam(':id_album', $id_album);

  // Get a response from the database by sending the connection
  // and the query
  $response = $sth->execute();


  // If the query executed properly proceed
  if($response){

  // fetchAll will return a row of data from the query
  // until no further data is available
  $row = $sth->fetchAll(PDO::FETCH_ASSOC);

  echo '<table id="listamusica" class="table table-hover table-responsive">

  <h2><a style="text-decoration:none;" href="editalbum.php?id='.$id_album.'">'.$row[0]['album'].'</a></h2>
  <thead>
  <th>#</th>
  <th>Nome da faixa</th>
  <th>Álbum</th>
  <th></th>
  </thead>';

  echo '<tbody>';
  foreach($row as $rows){
    echo '<tr><td>' .
    $rows['faixa'] . '</td><td class="col-lg-9 col-md-9">' .
    $rows['musica'] . '</td><td class="col-lg-11 col-md-11">'.
    $rows['album'] . '</td>'.
    '<td><a href="updatemusic.php?id='. $rows['id'] .'&album='.$rows['id_album'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></td>';
    //'<button type="button" name="deletetrack" class="btn btn-xs btn-danger" data-toggle="modal" data-target=".confirma-exclusao">Delete</button></td>';
    echo '</tr>';
  }
    echo '</tbody>
          </table>';

  // Close connection to the database
  $dbh = null;
  $response = null;
}
}
  ?>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Formhelpers -->
    <script src="js/bootstrap-formhelpers.js"></script>
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
</script>
