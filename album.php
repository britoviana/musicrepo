<?php
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

$img_extensions = array('jpeg', 'jpg', 'png', 'gif', 'tif', 'tiff', 'bmp');
$txt_extensions = array('pdf', 'txt', 'rft');
$video_extensions = array('mp4', 'mov', 'avi', 'flv', 'wmv', 'vob');
$audio_extensions = array('mp3', 'wav', 'mid', 'wma', 'm4a');

 if (!isset($_SESSION)) session_start();
 error_reporting( ~E_NOTICE ); // avoid notice
 require_once 'connection.php';

 $dbh = openConnection();
 $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


 if(isset($_POST['btnsave'])) {

	 $filename = $_GET['id'];// file name
   $fileDesc = $_POST['file_desc'];// file description

   $imgFile = $_FILES['user_file']['name'];
   $tmp_file = $_FILES['user_file']['tmp_name'];
   $fileSize = $_FILES['user_file']['size'];
   //$fileType = $_FILES['user_file']['type'];

  //echo $fileSize;
  if (empty($filename)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Enter Username.</strong></div>';

  else if (empty($fileDesc)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Favor preencher descrição de arquivo.</strong></div>';

  else if (empty($imgFile)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Favor selecionar arquivo.</strong></div>';

 else {
   // upload directory
   $upload_dir = uploadDir('album_files/', $id );

	 // get file extension
   $fileExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

   // valid file extensions
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'tif', 'tiff', 'bmp', 'pdf',
   'txt', 'rft', 'mp4', 'mov', 'avi', 'flv', 'wmv', 'vob', 'mp3', 'wav', 'mid', 'wma', 'm4a');

   // rename uploading file
   $userfile = $filename.rand(1000,1000000)."-".$imgFile;

   // allow valid file formats
   if(in_array($fileExt, $valid_extensions)){

     if(array_key_exists('user_file', $_FILES)){
      if ($_FILES['user_file']['error'] === UPLOAD_ERR_OK) {
         //echo 'upload was successful';

         // Check file size '5MB'
         if ($fileSize < 5*MB && $fileSize > 0) $is_moved = move_uploaded_file($tmp_file,$upload_dir.$userfile);
         else $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Desculpe, arquivo muito grande. Máx. 5Mb.</strong></div>';

      } else {
         die("Upload failed with error code " . $_FILES['user_file']['error']);
        }
    }

   }
   else{
    $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Desculpe, somente arquivos de áudio, vídeo, imagem e texto são permitidos.</strong></div>';

   }
  }

  // if no error occured, continue ....
  if(!isset($errMSG) && $is_moved) {

		$stmt = fileUpload('album', $dbh, $userfile, $fileExt, $fileSize, $fileDesc, 'album');


   if($stmt->execute()) {

    $successMSG = '<div class="alert alert-success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Arquivo adicionado com sucesso!</strong></div>';
//"new record succesfully inserted ...";

		chmod($upload_dir, 0755);

    header("refresh:5;"); // redirects image view page after 5 seconds.

  } else {

    $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Error while inserting!</strong></div>';

   }

   //echo $successMSG;
   header("refresh:5;"); // redirects image view page after 5 seconds.


 } else {
   if (!$is_moved && !isset($errMSG)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Upload directory error!</strong></div>';
   //echo $errMSG;
   header("refresh:5;"); // redirects image view page after 5 seconds.

 }
 }
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
    a.deco-none {
      color:#777 !important;
      text-decoration:none;

  }

  .bg-menu:hover {
      background-color:#0079C1;
      color:#FFFFFF;
  }

  .clickable {
      cursor:pointer;
  }
</style>

<style>
.nav-tabs { border-bottom: 1px solid #DDD; }
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
.nav-tabs > li > a { border: none; color: #666; }
.nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none; color: #4285F4 !important; background: transparent; }
.nav-tabs > li > a::after { content: ""; background: #4285F4; height: 1px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
.nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
.tab-nav > li > a::after { background: #21527d none repeat scroll 0% 0%; color: #fff; }
.tab-pane { padding: 10px 0; }
.tab-content{padding:20px}
/*.card {background: #FFF none repeat scroll 0% 0%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3); margin-bottom: 30px; }*/
.body{ background: #EDECEC; padding:50px}
</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		<title>Album Details</title>

	</head>
	<body>

			<header>
				<?php
				require_once('connection.php');
				navBar();
				?>
	    </header>

			<!-- Modal -->
			<?php modalUploadFile(); ?>


		<div class="container">
			<div class="row" style="margin-top: 20px;">
			<div class="col-lg 12 col-md-12 col-12" >
				<?php

					require_once('connection.php');
        	date_default_timezone_set('America/Sao_Paulo');
					$dbh = openConnection();

					if (isset($successMSG)) {
						echo $successMSG;
						header("refresh:5;"); // redirects image view page after 5 seconds.

					}

					if (isset($errMSG)){
						echo $errMSG;
						//header("refresh:5;"); // redirects image view page after 5 seconds.
					}

					$row = selectAlbumDetailsByAlbumID($_GET['id']);

          $query2 = "SELECT count(aa.id_artista) as artistas, al.id_album, al.nome_album from artista as a
                     JOIN album_artista as aa ON a.id_artista = aa.id_artista
                     JOIN album as al ON aa.id_album = al.id_album
				             WHERE al.id_album = " . $_GET['id'] . "
                     GROUP BY al.id_album, al.id_artista
                     HAVING artistas > 1";

          $sth = $dbh->prepare($query2);
          $sth->execute();
          $row2 = $sth->fetchAll(PDO::FETCH_ASSOC);

          // Inicia um array id_album com 0 caso não haja albuns com mais de um artista
          $id_album[] = 0;

          // Cria um array com todos os id_album dos albuns com mais de um artista
          foreach ($row2 as $key => $value) $id_album[] = $value['id_album'];

          //Cria um array com id_album e os artistas relacionados
          foreach ($id_album as $key => $id) $artists['$id'] = selectArtistsByAlbumID($id);

          if ($row2) foreach ($artists['$id'] as $id => $nome) $artistlinks[] = sprintf('<a class="deco-none" href="artistprofile.php?id=%s">%s</a>', $id, $nome);

					// TESTING
					if (isset($row['cover'])) {
            list($width, $height, $type, $attr) = getimagesize("album_files/$_GET[id]/$row[cover]");
  					 echo "<center><img src='album_files/".$_GET['id']."/".$row['cover']."' width='300' height='300' alt='' class='img-fluid'/></center>";

          }


					if($row2) echo '<h1 href="'.$row['webpage'].'" class="col-12">'.$row['nome_album'].' <small> de '.implode(' & ', $artistlinks).'</small></h1>
          <h5>álbum com '.$row['num_faixas'].' faixas lançado em '. $row['ano_lanc'].'</h5>'	;
          else echo '<h1 href="'.$row['webpage'].'" class="col-12">'.$row['nome_album'].' <small> <a class="deco-none" href="artistprofile.php?id='.$row['id_artista'].'">'.$row['nome'].'</a></small></h1>
          <h5>álbum com '.$row['num_faixas'].' faixas lançado em '. $row['ano_lanc'].'</h5>'	;

					$rows = selectMusicListByAlbumID($_GET['id']);
					//echo "</div>"; //first row

          echo '<div class="row">
					<div class="col-lg-6 col-md-6">';

          foreach ($rows as $row) {
            echo '<h5>#'.$row['num_faixa'].' <a style="text-decoration:none;" href="music.php?id='.$row['id_musica'].'">'.$row['nome_musica'].'</h5></a>';
          }

					$generos = selectGenresByAlbumID($_GET['id']);

          if ($generos){
						echo '<div class="row">
	                <div class="col-lg-6 col-md-6">
	                <h3>Gêneros <small>';
	          echo implode(', ' ,$generos).'.';
	          echo '</small></h3></div></div>';
					}


          if ($_SESSION['nivel_acesso'] == 1) echo '<h3>Arquivos <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span></h3>';
          else echo '<h3>Arquivos</h3>';

          $in_img = "('".implode("' , '" , $img_extensions)."')";
          $in_audio = "('".implode("' , '" , $audio_extensions)."')";
          $in_video = "('".implode("' , '" , $video_extensions)."')";
          $in_txt = "('".implode("' , '" , $txt_extensions)."')";

          $count_img = countFilesByID('album',$in_img, $_GET['id']);
          $count_audio = countFilesByID('album',$in_audio, $_GET['id']);
          $count_video = countFilesByID('album',$in_video, $_GET['id']);
          $count_txt = countFilesByID('album',$in_txt, $_GET['id']);

          $rows = selectFilesByID('album', $_GET['id']);

          echo '<!-- Nav tabs -->
          <div class="card">
          <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#audio" aria-controls="audio" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span><span class="badge">'.$count_audio['qtd'].'</span></a></li>
              <li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span><span class="badge">'.$count_video['qtd'].'</span></a></li>
              <li role="presentation"><a href="#imagem" aria-controls="imagem" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span><span class="badge">'.$count_img['qtd'].'</span></a></li>
              <li role="presentation"><a href="#texto" aria-controls="texto" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-text-size" aria-hidden="true"></span><span class="badge">'.$count_txt['qtd'].'</span></a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="audio">';
              foreach ($rows as $row) if (in_array($row['type'], $audio_extensions)) echo '<a style="text-decoration:none;" href="album_files/'.$row['id_album'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</br></div>
              <div role="tabpanel" class="tab-pane" id="video">';
              foreach ($rows as $row) if (in_array($row['type'], $video_extensions)) echo '<a style="text-decoration:none;" href="album_files/'.$row['id_album'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</br></div>
              <div role="tabpanel" class="tab-pane" id="imagem">';
              foreach ($rows as $row) if (in_array($row['type'], $img_extensions)) echo '<a style="text-decoration:none;" href="album_files/'.$row['id_album'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</br></div>
              <div role="tabpanel" class="tab-pane" id="texto">';
              foreach ($rows as $row) if (in_array($row['type'], $txt_extensions)) echo '<a style="text-decoration:none;" href="album_files/'.$row['id_album'].'/'.$row['file'].'" target="_blank"><h5>'.$row['desc'].'</h5></a>';
              echo '</br></div>
              </div>';
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
