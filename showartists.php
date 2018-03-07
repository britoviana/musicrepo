<?php if (!isset($_SESSION)) session_start(); session_destroy();?>

<!DOCTYPE html>
<html lang="pt-br">
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

    <header>
      <?php
      require_once('connection.php');
      navBar();
      ?>
    </header>


  <div class="container">

  <h1>Artistas</h1>
  <div class="row">
  <div class="col-md-7" >
    <hr>
    <form action="showartists.php?id=busca" method="get" accept-charset="uft8">
      <div class="form-group">
        <input type="search" class="form-control" name="busca" id="busca" placeholder="Buscar...">
      </div>


    </form>
  <?php
  // Get a connection for the database
  date_default_timezone_set('America/Sao_Paulo');
  require_once('connection.php');

  $dbh = openConnectionPDO();

  $id_album = $_GET['id'];

  // Create a query for the database
  $query = "SELECT id_artista as id, nome, tipo from artista
            WHERE nome LIKE '%". $_GET['busca'] ."%' OR nome_completo LIKE '%". $_GET['busca'] . "%'
            ORDER BY nome";

  $sth = $dbh->prepare($query);


  // Get a response from the database by sending the connection
  // and the query
  $response = $sth->execute();


  // If the query executed properly proceed
  if($response){

  echo '<table id="listaartista" class="table table-hover table-stripped table-responsive">
  <thead>
  <tr><th><b>Nome</b></th>
  </thead>';

  // fetchAll will return a row of data from the query
  // until no further data is available
  $row = $sth->fetchAll(PDO::FETCH_ASSOC);

  echo '<tbody>';
  foreach($row as $rows){
    echo '<tr><td>' .
    $rows['nome'] . '</td><td class="col-md-1">' .
    '<a href="artistprofile.php?id='. $rows['id'] .'" class="btn btn-primary btn-xs">Ver perfil</a></td>';
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
