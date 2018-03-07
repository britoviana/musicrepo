<?php if (!isset($_SESSION)) session_start();
?>

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

		<title>Editar Album</title>

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
        <h4 class="modal-title">Deseja realmente excluir o album?</h4>
      </div>
      <div class="modal-body">
        <p>Todas suas referências e suas faixas também serão excluídas&hellip;</p>
      </div>
      <div class="modal-footer">
				<form action="editalbum.php?id=<?php echo $_GET['id']?>" method="post" accept-charset="uft8">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" name="deletealbum" value="delete" class="btn btn-danger">Excluir</button>
			</form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

		<div class="container">

		<?php
		if ((!isset($_SESSION['usuario'])) || !($_SESSION['nivel_acesso'] == '1')) {

			echo '<div class="col-md-12 alert alert-danger" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<strong></strong> Voce não tem permissão para acessar esta pagina, faça o login!.
</div>';

			session_destroy();

		} else {

		$dbh = openConnection();

		if (!empty($_GET['id'])) $_SESSION['id_album'] = $_GET['id'];

		$id_album = $_SESSION['id_album'];

		if (isset($_POST['updatealbum'])){

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


				if (!empty($_FILES['cover']['name'])){

					echo "Foi";

					//Select current cover FILE

					$sth = $dbh->prepare('SELECT f.id, f.file as cover FROM album AS al
																JOIN file AS f ON f.id = al.cover
																WHERE id_album = '.$id_album.';');
					$sth->execute();
					$result = $sth->fetch(PDO::FETCH_ASSOC);
					$cover_exists = $sth->rowCount();

					if ($cover_exists == 1){
						$cover_old = $result['cover'];
						$id_old = $result['id'];
						$remove_dir = uploadDir('album_files/');
					}

					// Add new cover FILE

					$imgFile = $_FILES['cover']['name'];
					$tmp_file = $_FILES['cover']['tmp_name'];
					$fileSize = $_FILES['cover']['size'];

					// upload directory
					$upload_dir = uploadDir('album_files/', $_GET[id]);

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

					$sth = $dbh->prepare("UPDATE album SET cover = LAST_INSERT_ID()
									 WHERE id_album = :id_album; DELETE FROM file WHERE id = :id_file;");

					$sth->bindParam(':id_album', $id_album);
					$sth->bindParam(':id_file', $id_old);

					$cover_update = $sth->execute();


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


			$query = "UPDATE album SET nome_album = :nome, num_faixas = :faixas, gravadora = :gravadora, formato = :formato, ano_lanc = :ano, producao = :producao
								WHERE id_album = :id_album";

			$sth = $dbh->prepare($query);
			$sth->bindParam(':nome', $nome);
      $sth->bindParam(':faixas', $num_faixas);
      $sth->bindParam(':gravadora', $gravadora);
      $sth->bindParam(':formato', $formato);
      $sth->bindParam(':ano', $ano);
      $sth->bindParam(':producao', $producao);
			$sth->bindParam(':id_album', $id_album);

			$result = $sth->execute();



			if (isset($_POST['artista'])){


				$query = "DELETE FROM album_artista WHERE id_album = :id_album";
				$sth = $dbh->prepare($query);
				$sth->bindParam(':id_album', $id_album);
				$result = $sth->execute();

        foreach ($_POST['artista'] as $id_artista){

            $query = "INSERT INTO album_artista (id_album, id_artista) VALUES (:id_album, :id_artista)";
            $sth = $dbh->prepare($query);
            $sth->bindValue(':id_album', $id_album);
            $sth->bindValue(':id_artista', $id_artista);
            $result = $sth->execute();

        }
      }

      if (isset($_POST['genero'])){

				$query = "DELETE FROM album_genero WHERE id_album = :id_album";
				$sth = $dbh->prepare($query);
				$sth->bindParam(':id_album', $id_album);
				$result = $sth->execute();

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


				        echo '<br><div class="alert alert-success alert-dismissible" role="alert">
				    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    <strong>SUBMETERU!</strong> Album atualizado.
				  </div>';

				        $sth = null;


				    } else {
				        echo '<div class="alert alert-danger alert-dismissible" role="alert">
				    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    <strong>Error! </strong></div>';

				        echo $sth->errorInfo();

				        $sth = null;

				      }


		} else {

        echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Faixa não atualizada!</strong> Preencha os campos obrigatórios:</br>';

        foreach($data_missing as $missing){

            echo "$missing<br />";
        }
				echo "</div>";
    }
} else if (isset($_POST['deletealbum'])){

	$query = "DELETE FROM album WHERE id_album = :id_album";
	$sth = $dbh->prepare($query);
	$sth->bindParam(':id_album', $id_album);
	$result = $sth->execute();

	$affected_rows = $sth->rowCount();

	if($affected_rows == 1){

		echo '<br><div class="alert alert-success alert-dismissible text-center" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>SUBMETERU!</strong> Album '.$row['nome_album'].' removido.
</div><a href="searchalbum.php" role="button" name="voltar" class="btn btn-default btn-lg btn-block">Voltar</a>
';

		$sth = null;

	}


}
		$query = "SELECT nome_album, num_faixas, gravadora, formato, ano_lanc, producao FROM album
							WHERE id_album = :id_album";

		$sth = $dbh->prepare($query);
		$sth->bindValue(':id_album', $id_album);
		$response = $sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		$query2 = "SELECT aa.id_artista from album as al
							JOIN album_artista as aa ON al.id_album = aa.id_album
							JOIN artista as ar ON aa.id_artista = ar.id_artista
							WHERE al.id_album = :id_album";

		$sth2 = $dbh->prepare($query2);
		$sth2->bindValue(':id_album', $id_album);
		$response2 = $sth2->execute();
		$row2 = $sth2->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row2 as $rows) {
			$artista[] = $rows['id_artista'];
		}

		$query3 = "SELECT ag.genero from album_genero as ag WHERE ag.id_album = :id_album";

		$sth3 = $dbh->prepare($query3);
		$sth3->bindValue(':id_album', $id_album);
		$response3 = $sth3->execute();
		$row3 = $sth3->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row3 as $rows) {
			$genero[] = $rows['genero'];
		}

	if(!isset($_POST['deletealbum'])) {

	echo '<div class="row">
				<div class="col-md-5">

			<form action="editalbum.php?id="'.$id_album.'" method="post" accept-charset="uft8" enctype="multipart/form-data">

			<h1 class="col-10">Editar album</h1>

			<div class="form-group">
				<label for="nome" class="col-12 col-form-label">Nome do álbum</label>
				<div class="col-12">
					<input class="form-control" type="text" name="nome" id="nome" size="30" value="'.$row['nome_album'].'">
				</div>
			</div>

	    <div class="form-group">
	      <label for="artista" class="col-12 col-form-label">Artistas do álbum</label>
	      <div class="col-12">
	      <select name="artista[]" id="artista" class="form-control selectpicker"  data-live-search="true" data-none-selected-text="Artistas" multiple data-max-options="5" multiple>';

	          showArtists($artista);

	      echo '</select>
	    </div>
	    </div>

	    <div class="form-group">
	      <div class="col-12">
	      <select name="genero[]" id="genero" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Gênero" multiple>';
	          showGenres($genero);

	      echo '</select>
	    </div>
	    </div>

			<div class="form-group">
				<label for="num_faixas" class="col-12 col-form-label">Número de faixas</label>
				<div class="col-12">
					<input type="text" class="form-control bfh-number" name="num_faixas" id="num_faixas" data-min="1" data-max="99" value="'.$row['num_faixas'].'">
				</div>
			</div>

			<div class="form-group">
			  <label for="ano" class="col-12 col-form-label">Ano de lançamento</label>
	      <div class="col-12">
	        <input type="text" class="form-control bfh-number" name="ano" id="ano" data-min="1800" data-max="2017" value="'.$row['ano_lanc'].'">
	      </div>
			</div>

			<div class="form-group">
				<label for="gravadora" class="col-12 col-form-label">Gravadora</label>
				<div class="col-12">
					<input class="form-control" type="text" name="gravadora" id="gravadora" size="30" value="'.$row['gravadora'].'">
				</div>
			</div>

			<div class="form-group">
			  <label for="formato" class="col-12 col-form-label">Formato</label>
			  <div class="col-12">
			    <input class="form-control" type="text" value="'.$row['formato'].'" name="formato" id="formato">
			  </div>
			</div>

			<div class="form-group">
				<label for="producao" class="col-12 col-form-label">Produção</label>
				<div class="col-12">
					<input class="form-control" type="text" name="producao" id="producao" size="30" value="'.$row['producao'].'">
				</div>
			</div>

			<div class="form-group">
			 <label class="control-label">Capa do álbum</label>
			 <div class="col-12">
					<input class="input-group" type="file" name="cover" accept="media_type" />
				</div>
			</div>


			<div class="form-group">
				<div class="col-12">
					<button type="submit" name="updatealbum" class="btn btn-success btn-block">Atualizar</button></br>
					<button type="button" class="btn btn-danger col-xs-2" data-toggle="modal" data-target="#confirmDelete">Deletar</button>
				</div>
			</div>

			</form>
		</div>';



	$query = "SELECT m.ID_MUSICA AS id, am.NUM_FAIXA AS faixa, m.NOME_MUSICA AS musica, a.NOME_ALBUM AS album, a.ID_ALBUM as id_album FROM musica AS m
						JOIN album_musicas AS am ON am.ID_MUSICA = m.ID_MUSICA
						JOIN album AS a ON am.ID_ALBUM = a.ID_ALBUM WHERE a.ID_ALBUM = :id_album
						ORDER BY am.NUM_FAIXA";

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

	echo '<div class="col-md-offset-4">
	</div>';

	echo '<div class="col-md-7">
	<table id="listamusica" class="table table-hover table-stripped">

	<h2>'.$row[0]['album'].'</h2>
	<tr><th><b>#</b></th>
	<th>Faixa</th>
	<th>Album</th>
	<th></th>';


	foreach($row as $rows){
		echo '<tr class="clickableRow" data-href="music.php?id='. $rows['id'] .'"><td class="col-1">' .
		$rows['faixa'] . '</td><td>' .
		$rows['musica'] . '</td><td>'.
		$rows['album'] . '</td><td class="col-lg-1 col-md-1">'.
		'<a href="updatemusic.php?id='. $rows['id'] .'&album='.$rows['id_album'].'"><center><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></center></a></td>';
		//<td class="col-lg-1 col-md-1"><center><a href="album.php?id='. $row['id_album'] .'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td><button type="button" name="deletetrack" class="btn btn-xs btn-danger" data-toggle="modal" data-target=".confirma-exclusao">Delete</button></td>';
		echo '</tr>';
	}
		echo '</table>';
		echo '<a href="musicalbum.php?id='. $id_album .'"><center><span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c"></center></span>
					</a>';

	// Close connection to the database
	$dbh = null;
	$sth = null;
	}
}
}
	?>
</div>

</div>

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

jQuery(document).ready(function($) {
    $(".clickableRow").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
