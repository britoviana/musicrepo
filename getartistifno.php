<?php if (!isset($_SESSION)) session_start(); ?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<title>Lista de artistas</title>
		<!-- Required meta tags -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="css/bootstrap-select.min.css">
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

		<style>
		input[type=search] {
    width: 130px;
    box-sizing: border-box;
    background-image: url('searchicon.png');
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 12px 10px 12px 10px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
		}
		input[type=search]:focus {
    width: 100%;
		}
	</style>


	</head>

	<body>

		<header>
			<?php
			require_once('connection.php');
			navBar();
			?>
    </header>

		<div class="container">
		<center><h1>Artistas</h1></center>
		<div class="row" style="margin-top: 10px;">
		<div class="col-md-10 col-md-offset-1" >
		<?php
		// Get a connection for the database
		date_default_timezone_set('America/Sao_Paulo');
		require_once('connection.php');

		$dbh = openConnectionPDO();

		// Create a query for the database
		$query = "SELECT nome, nome_completo, nacionalidade, data_nasc, local_nasc, data_morte, local_morte, bio,
		webpage FROM ARTISTA ORDER BY nome";

		// Get a response from the database by sending the connection
		// and the query
		$response = $dbh->query($query);

		// If the query executed properly proceed
		if($response){

		echo '<table id="lista" class="table table-hover table-stripped table-responsive">
		<a class="btn btn-success" href="addartistsolo.php" role="button">Adicionar artista solo</a>
		<a class="btn btn-success" href="addartist.php" style="margin-left: 5px;" role="button">Adicionar artista</a>
		<hr>
		<form action="searchartist.php?id=busca" method="get" accept-charset="uft8">
      <div class="form-group">
        <input type="search" class="form-control" name="busca" id="busca" placeholder="Buscar...">
      </div>


    </form>
		<hr>
		<thead>
		<tr><th><b>Nome</b></th>
		<th>Nome Completo</th>
		<th>Nacionalidade</th>
		<th>Data de nascimento</th>
		<th>Local de nascimento</th>
		<th>Data de morte</th>
		<th>Local de morte</th>
		<th>Bio</th>
		<th>Webpage</th></tr>
		</thead>';

		// fetchAll will return a row of data from the query
		// until no further data is available
		$row = $response->fetchAll(PDO::FETCH_ASSOC);

		echo '<tbody>';
		foreach($row as $rows){
			echo '<tr><td>' .
			$rows['nome'] . '</td><td>' .
			$rows['nome_completo'] . '</td><td>' .
			$rows['nacionalidade'] . '</td><td>' .
			date('d-m-Y', strtotime($rows['data_nasc'])) . '</td><td>' .
			$rows['local_nasc'] . '</td><td>';

			// Formated date issue 1969-12-31 treated
			if (strtotime($rows['data_morte'])) echo date('d-m-Y', strtotime($rows['data_morte'])) . '</td><td>';
			 else echo '</td><td>';

			echo $rows['local_morte'] . '</td><td>' .
			$rows['bio'] . '</td><td>' .
			$rows['webpage'] . '</td><td>';
			echo '</tr>';
		}
			echo '</tbody>
						</table>';

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

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#lista').DataTable();
		} );
	</script>

	</body>


</html>
