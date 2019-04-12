<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE HTML>
<html lang="pt-br">
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

		<title>Update Artist</title>

	</head>
	<body>

			<header>
				<?php
				require_once('connection.php');
				navBar();
				?>
	    </header>

		<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Deseja realmente excluir o artista?</h4>
      </div>
      <div class="modal-body">
        <p>Todas suas referências também serão excluídas&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<form action="updateartist.php?id=<?php echo $_GET['id']?>" method="post" accept-charset="uft8">
        <button type="submit" name="submit" value="delete" class="btn btn-danger">Excluir</button>
			</form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

		echo '<div class="col-lg-7 col-lg-offset-2">';

		if (isset($_POST['submit'])){

		  $dbh = openConnection();

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

						if (!empty($_FILES['avatar']['name'])){

							//Select current avatar FILE

							$sth = $dbh->prepare('SELECT f.id, f.file as avatar FROM artista AS a
																		JOIN file AS f ON f.id = a.avatar
																		WHERE id_artista = '.$id_artista.';');
							$sth->execute();
							$result = $sth->fetch(PDO::FETCH_ASSOC);
							$avatar_exists = $sth->rowCount();

							if ($avatar_exists == 1){
								$avatar_old = $result['avatar'];
								$id_old = $result['id'];
								$remove_dir = uploadDir('artist_files/', $_GET["id"]);
							}

							// Add new avatar FILE

							$imgFile = $_FILES['avatar']['name'];
							$tmp_file = $_FILES['avatar']['tmp_name'];
							$fileSize = $_FILES['avatar']['size'];

							// upload directory
							$upload_dir = uploadDir('artist_files/', $_GET["id"]);

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
																	 <strong>Sorry, your file is too large. Max 5MB.</strong></div>';

								 } else {
										die("Upload failed with error code " . $_FILES['avatar']['error']);
									 }
							 }

							} else {
							 $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												 <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>';

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

 		          $sth = $dbh->prepare("UPDATE artista SET avatar = LAST_INSERT_ID()
											 WHERE id_artista = :id_artista; DELETE FROM file WHERE id = :id_file;");

							$sth->bindParam(':id_artista', $id_artista);
							$sth->bindParam(':id_file', $id_old);

							$avatar_update = $sth->execute();


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
						}

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

		          if ($result || $avatar_update || ($sth->rowCount) == 1) $affected_rows = 1;


		      } else {

		         echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
		     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		     <strong>Artista não atualizado!</strong> Preencha os campos obrigatórios:</br>';

		         foreach($data_missing as $missing){

		             echo "$missing<br />";
		         }

						 echo "</div>";

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

						if (!empty($_FILES['avatar']['name'])){

							//Select current avatar FILE

							$sth = $dbh->prepare('SELECT f.id, f.file as avatar FROM artista AS a
																		JOIN file AS f ON f.id = a.avatar
																		WHERE id_artista = '.$id_artista.';');
							$sth->execute();
							$result = $sth->fetch(PDO::FETCH_ASSOC);
							$avatar_exists = $sth->rowCount();

							if ($avatar_exists == 1){
								$avatar_old = $result['avatar'];
								$id_old = $result['id'];
								$remove_dir = uploadDir('artist_files/', $_GET["id"]);
							}

							// Add new avatar FILE

							$imgFile = $_FILES['avatar']['name'];
							$tmp_file = $_FILES['avatar']['tmp_name'];
							$fileSize = $_FILES['avatar']['size'];

							// upload directory
							$upload_dir = uploadDir('artist_files/', $_GET["id"]);

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

										// Check file size '5MB'
										if ($fileSize < 5*1048576 && $fileSize > 0) $is_moved = move_uploaded_file($tmp_file,$upload_dir.$userfile);
										else $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
																	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	 <strong>Sorry, your file is too large. Max 5MB.</strong></div>';

								 } else {
										die("Upload failed with error code " . $_FILES['avatar']['error']);
									 }
							 }

							} else {
							 $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												 <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>';

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

 		          $sth = $dbh->prepare("UPDATE artista SET avatar = LAST_INSERT_ID()
											 WHERE id_artista = :id_artista; DELETE FROM file WHERE id = :id_file;");

							$sth->bindParam(':id_artista', $id_artista);
							$sth->bindParam(':id_file', $id_old);

							$avatar_update = $sth->execute();


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
						}


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
		     <strong>Artista não atualizado!</strong> Preencha os campos obrigatórios:</br>';

		         foreach($data_missing as $missing){

		             echo "$missing<br />";
		         }

		         echo "</div>";
		     }


		   }
		 }  else if ($_POST['submit'] == 'delete'){

		          $query = "DELETE FROM artista WHERE id_artista = :id_artista";

		          $sth = $dbh->prepare($query);

		          $sth->bindParam(':id_artista', $id_artista);

		          $result = $sth->execute();

		          $affected_rows = $sth->rowCount();
		        }

		      if($affected_rows == 1){

		            if ($_POST['submit'] == 'delete')
		            echo '<div class="alert alert-success text-center" role="alert"><strong>Artista removido!</strong>
		              </div>
									<a href="searchartist.php" class="btn btn-primary col-xs-12 col-xs-offset-0 col-md-2 col-md-offset-5">OK</a>';

		            if (!($_POST['submit'] == 'delete'))
		            echo '<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<strong>Artista atualizado!</strong>
		              </div>';

									if (is_file($remove_dir.$avatar_old)) {

										if (!unlink($remove_dir.$avatar_old)) die('Não removeu antigo');
										//else echo "removeu";

									}

		            $sth = null;
		            $dbh = null;

		        } else {
		            echo '<div class="alert alert-danger alert-dismissible" role="alert">
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <strong>ERRO!</strong> Artista não atualizado.<br></div>';

		          //echo $sth->errorInfo();

		            $sth = null;
		            $dbh = null;
		          }
		}


				$dbh = openConnection();


				$query = "SELECT tipo FROM artista WHERE id_artista = " . $_GET['id'];

				$sth = $dbh->prepare($query);

				$response = $sth->execute();

				$tipo = $sth->fetch(PDO::FETCH_ASSOC);

				if ($tipo['tipo'] == 'pessoa') {

					$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, ac.id_componente FROM artista as a
										LEFT JOIN artista_componente as ac ON a.id_artista = ac.id_artista
										WHERE a.id_artista = " . $_GET['id'];

				 	$sth = $dbh->prepare($query);

					$response = $sth->execute();

					$row = $sth->fetch(PDO::FETCH_ASSOC);

					echo '<form action="updateartist.php?id='.$_GET['id'].'" method="post" accept-charset="uft8" enctype="multipart/form-data">

					<h1 class="col-10">Editar artista</h1>

					<div class="form-group">
						<label for="nome" class="col-12 col-form-label">Nome</label>
						<div class="col-12">
							<input class="form-control" type="text" name="nome" id="nome" size="30" value="'.$row['nome'].'">
						</div>
					</div>

					<div class="form-group">
						<label for="nome" class="col-12 col-form-label">Nome completo</label>
						<div class="col-12">
							<input class="form-control" type="text" name="nome_completo" id="nome_completo" value="'.$row['nome_completo'].'"size="30">
						</div>
					</div>

					<div class="form-group">
						<div class="col-12">
							<label for="nacionalidade">Nacionalidade</label>
							<select name="nacionalidade" id="nacionalidade" class="form-control selectpicker" data-live-search="true" multiple data-max-options="1" multiple>';

								showCountries($row['nacionalidade']);

							echo '</select>
						</div>
					</div>

					<div class="form-group">
					  <label for="data_nasc" class="col-12 col-form-label">Data de nascimento</label>
					  <div class="col-12">
					    <input class="form-control" type="date" value="'.$row['data_nasc'].'" name="data_nasc" id="data_nasc">
					  </div>
					</div>

					<div class="form-group">
						<label for="local_nasc" class="col-12 col-form-label">Local de nascimento</label>
						<div class="col-12">
							<input class="form-control" type="text" value="'.$row['local_nasc'].'"name="local_nasc" id="local_nasc" size="30">
						</div>
					</div>

					<div class="form-group">
					  <label for="data_morte" class="col-12 col-form-label">Data de morte</label>
					  <div class="col-12">
					    <input class="form-control" type="date" value="'.$row['data_morte'].'" name="data_morte" id="data_morte">
					  </div>
					</div>

					<div class="form-group">
						<label for="local_morte" class="col-12 col-form-label">Local de morte</label>
						<div class="col-12">
							<input class="form-control" value="'.$row['local_morte'].'"type="text" name="local_morte" id="local_morte" size="30">
						</div>
					</div>

					<div class="form-group">
						<div class="col-12">
							<label for="exampleTextarea">Biografia</label>
							<textarea class="form-control" name ="bio" id="bio" rows="4">'. $row['bio'] .'</textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="webpage" class="col-3 col-form-label">Site Oficial</label>
						<div class="col-12">
							<input class="form-control" type="url" value="'. $row['webpage'].'" name ="webpage" id="webpage" size="30">
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
							<button type="submit" name="submit" value="update_person" class="btn btn-success btn-block">Atualizar</button>
							<br>
							<a class="btn btn-info btn-block" style="margin-top: 5px;" href="searchartist.php" role="button">Voltar</a>
							<button type="button" style="margin-top: 5px;" class="btn btn-danger btn-block" data-toggle="modal" data-target="#confirmDelete">Excluir</button>


						</div>
					</div>

				</form>';

				}

				if ($tipo['tipo'] == 'banda'){

					$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, ac.id_componente FROM artista as a
										LEFT JOIN artista_componente as ac ON a.id_artista = ac.id_artista
										WHERE a.id_artista = " . $_GET['id'];

				 	$sth = $dbh->prepare($query);

					$response = $sth->execute();

					$row = $sth->fetchAll(PDO::FETCH_ASSOC);


					$id_componente = array();
					foreach ($row as $componentes) {
							$id_componente[] = $componentes['id_componente'];
					}

					echo '<form action="updateartist.php?id='.$_GET['id'].'" method="post" accept-charset="uft8" enctype="multipart/form-data">

					<h1 class="col-10">Editar artista</h1>

					<div class="form-group">
						<label for="nome" class="col-12 col-form-label">Nome</label>
						<div class="col-12">
							<input class="form-control" type="text" name="nome" id="nome" size="30" value="'.$row[0]['nome'].'">
						</div>
					</div>


					<div class="form-group">
					<select name="componentes[]" id="componentes" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Componentes" multiple data-max-options="8" multiple>';

							showArtists($id_componente);

					echo '</select>
				</div>

					<div class="form-group">
						<div class="col-12">
							<label for="nacionalidade">País</label>
							<select name="nacionalidade" id="nacionalidade" class="form-control selectpicker" data-live-search="true" multiple data-max-options="1" multiple>';

							showCountries($row[0][nacionalidade]);

						echo '</select> </div>
					</div>

					<div class="form-group">
					  <label for="data_nasc" class="col-12 col-form-label">Data de início</label>
					  <div class="col-12">
					    <input class="form-control" type="date" value="'.$row[0]['data_nasc'].'" name="data_nasc" id="data_nasc">
					  </div>
					</div>

					<div class="form-group">
						<label for="local_nasc" class="col-12 col-form-label">Local de residência</label>
						<div class="col-12">
							<input class="form-control" type="text" value="'.$row[0]['local_nasc'].'" name="local_nasc" id="local_nasc" size="30">
						</div>
					</div>

					<div class="form-group">
					  <label for="data_morte" class="col-12 col-form-label">Data de término</label>
					  <div class="col-12">
					    <input class="form-control" type="date" value="'.$row[0]['data_morte'].'" name="data_morte" id="data_morte">
					  </div>
					</div>

					<div class="form-group">
						<div class="col-12">
							<label for="exampleTextarea">Biografia</label>
							<textarea class="form-control" name ="bio" id="bio" rows="4">'. $row[0]['bio'] .'</textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="webpage" class="col-3 col-form-label">Site Oficial</label>
						<div class="col-12">
							<input class="form-control" type="url" value="'.$row[0]['webpage'].'" name="webpage" id="webpage" size="30">
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
						<button type="submit" name="submit" value="update_band" class="btn btn-success btn-block">Atualizar</button>
						<br>
						<a class="btn btn-info btn-block" style="margin-top: 5px;" href="searchartist.php" role="button">Voltar</a>
						<button type="button" style="margin-top: 5px;margin-left:" class="btn btn-danger btn-block" data-toggle="modal" data-target="#confirmDelete">Excluir</button>

						</div>
					</div>

				</form>';

				}
}


	?>
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
