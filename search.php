<?php if (!isset($_SESSION)) session_start();?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search</title>

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

  <div class="row">
  <div class="col-lg-12 col-md-12" >

    <form method="get" accept-charset="uft8">

      <div class="input-group">
               <div class="input-group-btn search-panel">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                     <span id="search_concept">Buscar por</span> <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu" role="menu">
                     <li><a href="searchartist.php">Artista</a></li>
                     <li><a href="searchmusic.php">Música</a></li>
                     <li><a href="searchalbum.php">Álbum</a></li>
                     <li class="divider"></li>
                     <li><a href="#all">Anything</a></li>
                   </ul>
               </div>
               <input type="hidden" name="search_param" value="all" id="search_param">
               <input type="text" class="form-control" name="busca" id="busca" placeholder="Search artist, music, album...">
               <span class="input-group-btn">
                   <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
               </span>
           </div>
</form>
</div></div>


<?php

if ($_GET['search_param'] == 'searchartist'){

  echo '
  <div class="row">
  <div class="col-lg-12 col-md-12">

  <h3>Artistas</h3>';
  // Get a connection for the database
  date_default_timezone_set('America/Sao_Paulo');
  require_once('connection.php');

  $dbh = openConnection();

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

  echo '<div class="row"><div class="col-lg-7 col-md-7">
  <table id="listaartista" class="table">
  <tr><td><b>Nome</b></td>
      <td></td>
      <td></td>
  </tr></div>';

  // fetchAll will return a row of data from the query
  // until no further data is available
  $rows = $sth->fetchAll(PDO::FETCH_ASSOC);


  foreach($rows as $row){
    echo '<tr><td class="col-lg-10 col-md-10">' .
    $row['nome'] . '</td><td class="col-lg-1 col-md-1">';
    if ($_SESSION['nivel_acesso'] == 1) echo '<center><a href="updateartist.php?id='. $row['id'] .'" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></center></td>';
    echo '<td class="col-lg-1 col-md-1"><center><a href="artistprofile.php?id='. $row['id'] .'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></a></center></td>';
  ;
    echo '</tr>';
  }
    echo '</table>';

  }
  echo '</div></div></div>';


  // Close connection to the database
  $dbh = null;
  $response = null;

}



?>

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

<script>
$(document).ready(function(e){
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		$('.search-panel span#search_concept').text(concept);
		$('.input-group #search_param').val(param);
	});
});
</script>
