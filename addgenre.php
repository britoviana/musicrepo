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

		<title>Adicionar gênero</title>

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



					if ( (isset($_POST['cadastrar'])) || (isset($_POST['atualizar'])) ){


					    $data_missing = array();

					    if (empty($_POST['nome'])){

					        // Adds nome to array
					        $data_missing[] = 'Nome do gênero';

					    } else {

					        // Trim white space and escape the nome and store the nome
					        $nome = trim($_POST['nome']);

					    }



					    if(empty($data_missing)){

								$dbh = openConnection();


								if (isset($_POST['cadastrar'])) {

									$query = "INSERT INTO genero (nome_genero) VALUES (:genero)";

						      $sth = $dbh->prepare($query);

						      $sth->bindParam(':genero', $nome);

						      $result = $sth->execute();

						      $affected_rows = $sth->rowCount();

								} else if (isset($_POST['atualizar'])) {


									$query = "UPDATE genero SET nome_genero = :genero WHERE id_genero =" .$_POST['id_genero'];

									$sth = $dbh->prepare($query);

									$sth->bindParam(':genero', $nome);

									$result = $sth->execute();

						      $affected_rows = $sth->rowCount();

								}


					      if($affected_rows == 1){

					            $sth = null;
					            $dbh = null;

					            echo '<br><div class="col-12 alert alert-success alert-dismissible" role="alert">
					        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <strong>Sucesso!</strong> '.$nome.' adicionado.</div>';


					        } else {
					            echo '<br><div class="col-12 alert alert-danger alert-dismissible" role="alert">
					        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Error Occurred: ' .$sth->errorInfo().'</div>';

					            $sth = null;
					            $dbh = null;
					        }

					    } else {

					        echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
					    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <strong>Gênero não adicionado!</strong> Preencha o campo obrigatório:</br>';

					        foreach($data_missing as $missing){

					            echo "$missing<br />";
					        }
					        echo "</div>";
					    }
					}



		 echo '<form action="addgenre.php" method="post" accept-charset="uft8">

		<h1>Gêneros</h1>

		<div class="form-group">
      <div class="col-12">
      <select id="genero" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Gêneros cadastrados" multiple>';

          showGenres('all');

      echo '</select>
    				</div>
    				</div>';

		if (isset($_GET['id'])){

			$dbh = openConnection();

			$query = "SELECT * FROM genero WHERE id_genero =" .$_GET['id'];
			$sth = $dbh->prepare($query);
			$sth->execute();

			$genero = $sth->fetch(PDO::FETCH_ASSOC);


			echo '<div class="form-group">
			<label for="nome" class="col-12 col-form-label">Editar nome</label>
			<div class="col-12">
				<input class="form-control" type="text" name="nome" id="nome" size="30" value="'.$genero['nome_genero'].'">
			</div>
		</div>
		<div class="form-group">
		<input type="hidden" name="id_genero" value="'.$_GET['id'].'">

		</div>
		<div class="form-group">
			<div class="col-12">
				<button type="submit" name="atualizar" class="btn btn-block btn-success" style="background-color:#fff; color:green;">Atualizar</button>
			</div>
		</div>

	</form>';

	} else {

		echo '<div class="form-group">
		<label for="nome" class="col-12 col-form-label">Novo gênero</label>
		<div class="col-12">
			<input class="form-control" type="text" name="nome" id="nome" size="30">
		</div>
	</div>
	<div class="form-group">
		<div class="col-12">
			<button type="submit" name="cadastrar" class="btn btn-block btn-success" style="background-color:#fff; color:green;">Cadastrar</button>
		</div>
	</div>

</form>';
	}

}
?>
	</div>
<?php

			$dbh = openConnection();
 			$query = "SELECT * from genero";
			$sth = $dbh->prepare($query);
			$response = $sth->execute();

			if($response){

			echo '<div class="col-lg-6 col-md-6">
			<table id="listagenero" class="table table-hover">
			<tr><td><b>Nome do gênero</b></td>
					<td></td>
			</tr></div>';

			// fetchAll will return a row of data from the query
			// until no further data is available
			$rows = $sth->fetchAll(PDO::FETCH_ASSOC);


			foreach($rows as $row){
				echo '<tr><td class="col-lg-10 col-md-10">' .
				$row['nome_genero'] . '</td><td class="col-lg-1 col-md-1">';
				if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="addgenre.php?id='. $row['id_genero'] .'"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#f0ad4e"></span></a></center></td>';
			;
				echo '</tr>';
			}
				echo '</table>';

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
