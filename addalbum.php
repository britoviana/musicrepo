<?php if (!isset($_SESSION)) session_start();?>

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
    <!-- Bootstrap Formhelpers -->
    <link href="css/bootstrap-formhelpers.min.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		<title>Adicionar Album</title>

	</head>
	<body>

		<header>
			<?php
			require_once('connection.php');
			navBar();
			?>
		</header>

		<div class="container">
			<div class="row">


				<?php
				if ((!isset($_SESSION['usuario'])) || !($_SESSION['nivel_acesso'] == '1')) {

					echo '<div class="col-md-12 alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong></strong> Voce não tem permissão para acessar esta pagina, faça o login!.
		</div>';

		session_destroy();

				} else {

					echo '<div class="col-lg-5 col-md-5">';



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

								error_reporting( ~E_NOTICE ); // avoid notice
								$dbh = openConnection();
							  //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

								if (!empty($_FILES['cover']['name'])){

									$imgFile = $_FILES['cover']['name'];
									$tmp_file = $_FILES['cover']['tmp_name'];
									$fileSize = $_FILES['cover']['size'];

									// upload directory
									$upload_dir = uploadDir('album_files/',$_GET[id]);

									// get file extension
									$fileExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

									// valid file extensions
									$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

									// rename uploading file
									$userfile = rand(1000,1000000)."-".$imgFile;

									// allow valid file formats
									if(in_array($fileExt, $valid_extensions)){

									  if(array_key_exists('cover', $_FILES)){
									   if ($_FILES['cover']['error'] === UPLOAD_ERR_OK) {
									      //echo 'upload was successful';

									      // Check file size '5MB'
									      if ($fileSize < 5*1048576 && $fileSize > 0) $is_moved = move_uploaded_file($tmp_file,$upload_dir.$userfile);
									      else $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
									                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									                     <strong>Sorry, your file is too large. Max 5MB.</strong></div>';

									   } else {
									      die("Upload failed with error code " . $_FILES['cover']['error']);
									     }
									 }

									} else {
									 $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
									           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									           <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>';

									}

									// if no error occured, continue ....
									if(!isset($errMSG) && $is_moved) {


										$sth = fileUpload('album', $dbh, $userfile, $fileExt, $fileSize, 'cover');


									if ($sth->execute()) {

									 $successMSG = '<div class="alert alert-success alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<strong>Arquivo adicionado com sucesso!</strong></div>';
									//"new record succesfully inserted ...";

									 chmod($upload_dir, 0755);

									 //header("refresh:5;"); // redirects image view page after 5 seconds.

									 //echo "deu certo";

									} else {

									 $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<strong>Error while inserting!</strong></div>';

									}

									//echo $successMSG;
									//header("refresh:5;"); // redirects image view page after 5 seconds.


									} else {
									if (!$is_moved && !isset($errMSG)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<strong>Upload directory error!</strong></div>';
									//echo $errMSG;
									header("refresh:5;"); // redirects image view page after 5 seconds.
									}

									$query = "INSERT INTO album (id_artista, nome_album, num_faixas, gravadora, formato, ano_lanc, producao, cover)
						      VALUES (:id_artista, :nome, :faixas, :gravadora, :formato, :ano, :producao, LAST_INSERT_ID())";

								}else {
									$query = "INSERT INTO album (id_artista, nome_album, num_faixas, gravadora, formato, ano_lanc, producao)
								  VALUES (:id_artista, :nome, :faixas, :gravadora, :formato, :ano, :producao)";
								}



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

								$new_upload_dir = uploadDir("album_files/", $id_album);

								rename($upload_dir.$userfile,$new_upload_dir.$userfile);

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

					            echo '<br><form action="musicalbum.php?id=' . $id_album . '" method="post" accept-charset="utf8"><div class="col-12 alert alert-success alert-dismissible" role="alert">
					        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <strong>SUBMETERU!</strong> Album '.$nome.' adicionado. <button type="submit" name="submit" class="btn btn-success btn-sm">Cadastrar músicas</button>
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



		 echo '<form action="addalbum.php" method="post" accept-charset="uft8" enctype="multipart/form-data">

		<h1 class="col-10">Adicionar album</h1>


		<div class="form-group">
			<label for="nome" class="col-12 col-form-label">Nome do álbum</label>
			<div class="col-12">
				<input class="form-control" type="text" name="nome" id="nome" size="30">
			</div>
		</div>

    <div class="form-group">
      <label for="artista" class="col-12 col-form-label">Artistas do álbum</label>
      <div class="col-12">
      <select name="artista[]" id="artista" class="form-control selectpicker"  data-live-search="true" data-none-selected-text="Artistas" multiple data-max-options="5" multiple>';
          require_once('connection.php');
          showArtists('all');

      echo '</select>
    </div>
    </div>

    <div class="form-group">
      <div class="col-12">
      <select name="genero[]" id="genero" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Gênero" multiple>';

          require_once('connection.php');
          showGenres('all');

      echo '</select>
    </div>
    </div>

		<div class="form-group">
			<label for="num_faixas" class="col-12 col-form-label">Número de faixas</label>
			<div class="col-12">
				<input type="text" class="form-control bfh-number" name="num_faixas" id="num_faixas" data-min="1" data-max="99">
			</div>
		</div>

		<div class="form-group">
		  <label for="ano" class="col-12 col-form-label">Ano de lançamento</label>
      <div class="col-12">
        <input type="text" class="form-control bfh-number" name="ano" id="ano" data-min="1800" data-max="2017" value="2017">
      </div>
		</div>

		<div class="form-group">
			<label for="gravadora" class="col-12 col-form-label">Gravadora</label>
			<div class="col-12">
				<input class="form-control" type="text" name="gravadora" id="gravadora" size="30">
			</div>
		</div>

		<div class="form-group">
		  <label for="formato" class="col-12 col-form-label">Formato</label>
		  <div class="col-12">
		    <input class="form-control" type="text" value="" name="formato" id="formato">
		  </div>
		</div>

		<div class="form-group">
			<label for="producao" class="col-12 col-form-label">Produção</label>
			<div class="col-12">
				<input class="form-control" type="text" name="producao" id="producao" size="30">
			</div>
		</div>

		<div class="form-group">
		 <label class="control-label">Capa do album</label>
		 <div class="col-12">
				<input class="input-group" type="file" name="cover" accept="media_type" />
			</div>
		</div>


		<div class="form-group">
			<div class="col-12">
				<button type="submit" name="submit" class="btn btn-block btn-success">Cadastrar</button>
			</div>
		</div>

	</form>';
}
?>
	</div></div>


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

 $('#componentes').change(function(){
  $('#hidden_componentes').val($('#componentes').val());
 });

 $('#multiple_select_form').on('submit', function(event){
  event.preventDefault();
  if($('#componentes').val() != '')
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
   alert("Please select componentes");
   return false;
  }
 });
});
</script>
