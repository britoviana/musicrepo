<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Editar faixa</title>

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

    <?php

    if ((!isset($_SESSION['usuario'])) || !($_SESSION['nivel_acesso'] == '1')) {

      echo '<div class="container"><div class="col-md-12 alert alert-danger" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong></strong> Voce não tem permissão para acessar esta pagina, faça o login!.
</div></div>';

  session_destroy();

} else {

    require_once("connection.php");
    $dbh = openConnection();

    $id = $_GET['id'];
		$id_album = $_GET['album'];

    $query = "SELECT m.nome_musica, m.versao, m.ano_lanc FROM musica as m
              JOIN musica_artista as ma
              ON m.id_musica = ma.id_musica
              JOIN artista as a
              ON a.id_artista = ma.id_artista
              WHERE m.id_musica = :id";

		$query2 = "SELECT a.id_artista from artista as a
							 JOIN musica_artista as ma
							 ON a.id_artista =ma.id_artista
							 JOIN musica as m
							 ON ma.id_musica = m.id_musica
							 WHERE m.id_musica = :id";

		$query3 = "SELECT mc.id_compositor from artista as a
					 		 JOIN musica_compositor as mc
							 ON a.id_artista =mc.id_compositor
							 JOIN musica as m
 							 ON mc.id_musica = m.id_musica
 						 	 WHERE m.id_musica = :id";

		$query4 = "SELECT am.num_faixa, g.id_genero FROM album_musicas as am
							JOIN musica as m
							ON am.id_musica = m.id_musica
							JOIN musica_genero as mg
							ON m.id_musica = mg.id_musica
							JOIN genero as g
							ON mg.genero = g.id_genero
							WHERE m.id_musica = :id";


		$sth = $dbh->prepare($query);
		$sth2 = $dbh->prepare($query2);
		$sth3 = $dbh->prepare($query3);
		$sth4 = $dbh->prepare($query4);


		$sth->bindParam(':id', $id);
		$sth2->bindParam(':id', $id);
		$sth3->bindParam(':id', $id);
		$sth4->bindParam(':id', $id);


		// Get a response from the database by sending the connection
	  // and the query
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		$sth2->execute();
		$row2 = $sth2->fetchAll(PDO::FETCH_ASSOC);

		$sth3->execute();
		$row3 = $sth3->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row2 as $rows) $artistas[] = $rows['id_artista'];

		foreach ($row3 as $rows) $compositores[] = $rows['id_compositor'];

		$sth4->execute();
		$row4 = $sth4->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row4 as $rows) {

			$generos[] = $rows['id_genero'];
		}


		if (!$sth || !$sth2 || !$sth3 || !$sth4) {
    echo "\nPDO::errorInfo():\n";
    print_r($dbh->errorInfo());
}

		echo '<div class="container">
			<div class="row">
			<div class="col-md-6" >

		<h2>Editar música</h2>

		<form action="musicalbum.php?id='.$id_album.'" method="post" accept-charset="utf8">

			<div class="form-group">

				<div class="form-group">
					<label class="sr-only" for="exampleInputEmail3">Faixa</label>
					<input type="text" class="form-control bfh-number" name="num_faixa" id="num_faixa" data-min="1" data-max="99"  value="'.$row4[0]['num_faixa'].'">
				</div>

  			<label for="nome" class="col-2 col-form-label">Nome da música</label>
  			<div class="col-10">
    			<input class="form-control" type="text" name="nome" id="nome" maxlength="60" value="'.$row['nome_musica'].'">
  			</div>
			</div>

			<div class="form-group">
				<div class="col-2">
					<label for="versao">Versão</label>
					<select class="form-control" name="versao" id="versao">';
              if ($row['versao'] == 'estudio'){
                echo
                '<option selected value="estudio">Estúdio</option>'.
                '<option value="ao vivo">Ao Vivo</option>';
              } elseif ($row['versao'] == 'ao vivo') {
                echo
                '<option value="estudio">Estúdio</option>'.
                '<option selected value="ao vivo">Ao Vivo</option>';
              }
					echo '</select>
				</div>
			</div>

      <div class="form-group">
      <input type="hidden" name="id" value="'.$id.'"/>
      </div>

			<div class="form-group">
			  <label for="ano" class="col-12 col-form-label">Ano de lançamento</label>
	      <div class="col-12">
	        <input type="text" class="form-control bfh-number" name="ano_lanc" id="ano_lanc" data-min="1800" data-max="2018" value="'.$row['ano_lanc'].'">
	      </div>
			</div>
			<div class="form-group">
	      <label for="artista" class="col-12 col-form-label">Artista</label>
	      <div class="col-12">
	      <select name="artista[]" id="artista" class="form-control selectpicker"  data-live-search="true" data-none-selected-text="Artista" multiple data-max-options="5" multiple>';
	          showArtists($artistas);

	     echo '</select>
	    </div>
	    </div>

			<div class="form-group">
	      <label for="compositor" class="col-12 col-form-label">Compositor</label>
	      <div class="col-12">
	      <select name="compositor[]" id="compositor" class="form-control selectpicker"  data-live-search="true" data-none-selected-text="Compositor" multiple data-max-options="5" multiple>';
	          showArtists($compositores);

	      echo '</select>
	    </div>
	    </div>

			<div class="form-group">
				<label for="genero" class="col-12 col-form-label">Gênero</label>
				<div class="col-12">
				<select name="genero[]" id="genero" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Gênero" multiple>';
						showGenres($generos);

				echo '</select>
			</div>
			</div>

			<div class="form-group">
				<div class="col-10">
					<button type="submit" name="updatetrack" class="btn btn-success btn-block">Atualizar</button>
				</div>
			</div>
      <div class="form-group">
				<div class="col-10">
      <button type="submit" name="deletetrack" class="btn btn-danger">Excluir</button>
      <button onclick="goBack()" name="voltar" class="btn btn-primary">Voltar</button>
      </div>
      </div>
		</form>

		</div>
		</div>
	</div>';
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

  <script>
function goBack() {
    window.history.back();
}
</script>
