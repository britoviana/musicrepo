<?php if (!isset($_SESSION)) session_start(); ob_start();?>

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

    <h1>Comece aqui</h1>
    <hr>

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
                   </ul>
               </div>
               <input type="hidden" name="search_param" id="search_param">
               <input type="text" class="form-control" name="busca" id="busca" placeholder="artista, música ou álbum...">
               <span class="input-group-btn">
                   <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
               </span>
           </div>

</form>
</div></div>

<?php

if ($_GET['search_param']){


  header("Location:".$_GET['search_param']."?busca=".$_GET['busca']);
exit();

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
