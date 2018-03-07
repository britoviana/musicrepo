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

  <title>Edit Artist</title>

</head>

<body>

<?php

if (isset($_POST['submit'])){

  require_once('connection.php');
  $dbh = openConnectionPDO();

  $data_missing = array();
  $id_artista = $_GET['id'];


  if(!($_POST['submit'] == 'delete')){

      if ($_POST['submit'] == 'update_person') {

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

            // Adds nacionalidade to array
            $data_missing[] = 'Nacionalidade';

        } else {

            // Trim white space from the nacionalidade and store the name
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
        if(!(empty($_POST['local_morte']))) $local_morte = trim($_POST['local_morte']);
        if(!(empty($_POST['data_morte']))) $data_morte = trim($_POST['data_morte']);
        if(!(empty($_POST['bio'])))$bio = trim($_POST['bio']);
        if(!(empty($_POST['webpage']))) $webpage = trim($_POST['webpage']);




      if(empty($data_missing)){

          $query = "UPDATE artista SET nome = :nome, nome_completo = :nome_completo, nacionalidade = :nacionalidade,
                    data_nasc = :data_nasc, local_nasc = :local_nasc, data_morte = :data_morte, local_morte = :local_morte, bio = :bio, webpage = :webpage
                    WHERE id_artista = :id_artista";

          $sth = $dbh->prepare($query);

          $sth->bindParam(':nome', $nome);
          $sth->bindParam(':nome_completo', $nome_completo);
          $sth->bindParam(':nacionalidade', $nacionalidade);
          $sth->bindParam(':data_nasc', $data_nasc);
          $sth->bindParam(':local_nasc', $local_nasc);
          $sth->bindParam(':data_morte', $data_morte);
          $sth->bindParam(':local_morte', $local_morte);
          $sth->bindParam(':bio', $bio);
          $sth->bindParam(':webpage', $webpage);
          $sth->bindParam(':id_artista', $id_artista);

          $result = $sth->execute();

          if ($result || ($sth->rowCount) == 1) $affected_rows = 1;





      } else {

         echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <strong>Faixa não atualizada!</strong> Preencha os campos obrigatórios:</br>';

         foreach($data_missing as $missing){

             echo "$missing<br />";
         }
     }


   } else if ($_POST['submit'] == 'update_band') {

        if (empty($_POST['nome'])){

            // Adds nome to array
            $data_missing[] = 'Nome';

        } else {

            // Trim white space and escape the nome and store the nome
            $nome = trim($_POST['nome']);

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
            $data_missing[] = 'Data de início';

        } else {

            // Trim white space from the name and store the name
            $data_nasc = trim($_POST['data_nasc']);

        }

        if (empty($_POST['local_nasc'])){

            // Adds name to array
            $data_missing[] = 'Local de residencia';

        } else {

            // Trim white space from the name and store the name
            $local_nasc = trim($_POST['local_nasc']);

        }
        if(!(empty($_POST['nome_completo'])))$nome_completo = trim($_POST['nome_completo']);
        if(!(empty($_POST['local_morte']))) $local_morte = trim($_POST['local_morte']);
        if(!(empty($_POST['data_morte']))) $data_morte = trim($_POST['data_morte']);
        if(!(empty($_POST['bio'])))$bio = trim($_POST['bio']);
        if(!(empty($_POST['webpage']))) $webpage = trim($_POST['webpage']);


      if(empty($data_missing)){


          $query = "UPDATE artista SET nome = :nome, nome_completo = :nome_completo, nacionalidade = :nacionalidade,
                    data_nasc = :data_nasc, local_nasc = :local_nasc, data_morte = :data_morte, local_morte = :local_morte, bio = :bio, webpage = :webpage
                    WHERE id_artista = :id_artista";

          $sth = $dbh->prepare($query);

          $sth->bindParam(':nome', $nome);
          $sth->bindParam(':nome_completo', $nome_completo);
          $sth->bindParam(':nacionalidade', $nacionalidade);
          $sth->bindParam(':data_nasc', $data_nasc);
          $sth->bindParam(':local_nasc', $local_nasc);
          $sth->bindParam(':data_morte', $data_morte);
          $sth->bindParam(':local_morte', $local_morte);
          $sth->bindParam(':bio', $bio);
          $sth->bindParam(':webpage', $webpage);
          $sth->bindParam(':id_artista', $id_artista);


          $result = $sth->execute();

          $affected_rows = $sth->rowCount();

          $query2 = "DELETE FROM artista_componente WHERE id_artista = :id_artista";
          $sth2 = $dbh->prepare($query2);
          $sth2->bindValue(':id_artista', $id_artista);
          $result2 = $sth2->execute();

          if (isset($_POST['componentes']) && $result2){

            foreach ($_POST['componentes'] as $id_componente){

                $query3 = "INSERT INTO artista_componente (id_artista, id_componente) VALUES (:id_artista, :componente)";
                $sth3 = $dbh->prepare($query3);
                $sth3->bindValue(':id_artista', $id_artista);
                $sth3->bindValue(':componente', $id_componente);
                $result3 = $sth3->execute();
            }

          }

          if ($result && $result3) $affected_rows = 1;

      } else {

         echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <strong>Faixa não atualizada!</strong> Preencha os campos obrigatórios:</br>';

         foreach($data_missing as $missing){

             echo "$missing<br />";
         }

         echo "</div>";
     }



   }
 }  else if ($_POST['submit'] == 'delete'){

          var_dump($_POST);

          $query = "DELETE FROM artista WHERE id_artista = :id_artista";

          $sth = $dbh->prepare($query);

          $sth->bindParam(':id_artista', $id_artista);

          $result = $sth->execute();

          $affected_rows = $sth->rowCount();
        }

      if($affected_rows == 1){

            if ($_POST['submit'] == 'delete')
            echo '<div class="container" style=" margin-top: 20px;">
              <div class="alert alert-success col-md-6 col-md-offset-3 text-center" role="alert"><strong>Artista removido!</strong>
              </div>
              <a href="searchartist.php" class="btn btn-primary col-xs-12 col-xs-offset-0 col-md-2 col-md-offset-5">OK</a>
            </div>';

            if (!($_POST['submit'] == 'delete'))
            echo '<div class="container" style=" margin-top: 20px;">
              <div class="alert alert-success col-md-6 col-md-offset-3 text-center" role="alert"><strong>Artista atualizado!</strong>
              </div>
              <a href="searchartist.php" class="btn btn-primary col-xs-12 col-xs-offset-0 col-md-2 col-md-offset-5">OK</a>
            </div>';

            $sth = null;
            $dbh = null;

        } else {
            echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>ERRO!</strong> Artista não atualizado.<br>'. $sth->errorInfo() .'</div>';

          //  echo $sth->errorInfo();

            $sth = null;
            $dbh = null;
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
