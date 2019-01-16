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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		<title>Adicionar Artista Solo</title>

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
			      if(!(empty($_POST['nome_completo']))) $nome_completo = trim($_POST['nome_completo']);
			      if(!(empty($_POST['local_morte']))) $local_morte = trim($_POST['local_morte']);
			      if(!(empty($_POST['data_morte']))) $data_morte = trim($_POST['data_morte']);
			      if(!(empty($_POST['bio']))) $bio = trim($_POST['bio']);
			      if(!(empty($_POST['webpage']))) $webpage = trim($_POST['webpage']);
			      $tipo = "pessoa";


			      if(empty($data_missing)){

							error_reporting( ~E_NOTICE ); // avoid notice
							$dbh = openConnection();
						  //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							if (!empty($_FILES['avatar']['name'])){

								$imgFile = $_FILES['avatar']['name'];
								$tmp_file = $_FILES['avatar']['tmp_name'];
								$fileSize = $_FILES['avatar']['size'];

								// upload directory
								$upload_dir = uploadDir('artist_files/', $_GET[id]);

								// get file extension
								$fileExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

								// valid file extensions
								$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

								// rename uploading file
								$userfile = rand(1000,1000000)."-".$imgFile;

								// allow valid file formats
								if(in_array($fileExt, $valid_extensions)){

								  if(array_key_exists('avatar', $_FILES)){
								   if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
								      //echo 'upload was successful';

								      // Check file size '5MB'
								      if ($fileSize < 5*1048576 && $fileSize > 0) $is_moved = move_uploaded_file($tmp_file,$upload_dir.$userfile);
								      else $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
								                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								                     <strong>Desculpe, arquivo muito grande. Máximo 5Mb.</strong></div>';

								   } else {
								      die("Upload failed with error code " . $_FILES['avatar']['error']);
								     }
								 }

								} else {
								 $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
								           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								           <strong>Desculpe, apenas arquivos JPG, JPEG, PNG & GIF são permitidos.</strong></div>';

								}

								// if no error occured, continue ....
								if(!isset($errMSG) && $is_moved) {


									$sth = fileUpload('artist', $dbh, $userfile, $fileExt, $fileSize, 'avatar');


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

								$query = "INSERT INTO artista (nome, nome_completo, nacionalidade,
				        data_nasc, local_nasc, data_morte, local_morte, bio, webpage, tipo, avatar) VALUES (:nome, :nome_completo, :nacionalidade,
				        :data_nasc, :local_nasc, :data_morte, :local_morte, :bio, :webpage, :tipo, LAST_INSERT_ID())";

							} else {

								$query = "INSERT INTO artista (nome, nome_completo, nacionalidade,
				        data_nasc, local_nasc, data_morte, local_morte, bio, webpage, tipo) VALUES (:nome, :nome_completo, :nacionalidade,
				        :data_nasc, :local_nasc, :data_morte, :local_morte, :bio, :webpage, :tipo)";

							}

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
			        $sth->bindParam(':tipo', $tipo);

			        $result = $sth->execute();

							$id_artista = $dbh->lastInsertId();

						 $new_upload_dir = uploadDir("artist_files/", $id_artista);

						 rename($upload_dir.$userfile,$new_upload_dir.$userfile);


			        $affected_rows = $sth->rowCount();

							var_dump($affected_rows);


			        if($affected_rows == 1){

			              echo '<br><div class="alert alert-success alert-dismissible" role="alert">
			          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			          <strong>SUBMETERU!</strong> Artista adicionado.
			        </div>';

			              $sth = null;
			              $dbh = null;

			          } else {
			              echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
			          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			          <strong>ERRO!</strong> Artista não adicionado: '.$sth->errorInfo().'
			        </div>';

			              $sth = null;
			              $dbh = null;
			          }
			      } else {

			          echo '
			          <br><div class="alert alert-warning alert-dismissible" role="alert">
			      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			      <strong>Artista não adicionado!</strong> Preencha os campos obrigatórios:</br>';

			          foreach($data_missing as $missing) echo "$missing<br />";

			          echo "</div>";
			      }

			  }

			echo '

		<form action="addartistsolo.php" method="post" accept-charset="uft8" enctype="multipart/form-data">

		<h1 class="col-10">Adicionar artista solo</h1>

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
				<select name="nacionalidade" id="nacionalidade" class="form-control selectpicker" data-live-search="true" multiple data-max-options="1" multiple>';
				showCountries('all');
				echo '</select>
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
		 <label class="control-label">Imagem</label>
		 <div class="col-12">
				<input class="input-group" type="file" name="avatar" accept="media_type" />
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
