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
  $filedesc = $_POST['file_desc'];// file description

  $imgFile = $_FILES['user_file']['name'];
  $tmp_file = $_FILES['user_file']['tmp_name'];
  $fileSize = $_FILES['user_file']['size'];
  //$fileType = $_FILES['user_file']['type'];

  //echo $fileSize;
  if (empty($filename)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Enter Username.</strong></div>';

  else if (empty($filedesc)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Enter File Description.</strong></div>';

  else if (empty($imgFile)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Select File.</strong></div>';

 else {
   // upload directory
   $upload_dir = uploadDir('music_files/');

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
                        <strong>Sorry, your file is too large. Max 5MB.</strong></div>';

      } else {
         die("Upload failed with error code " . $_FILES['user_file']['error']);
        }
    }

   }
   else{
    $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Sorry, only JPG, JPEG, PNG, GIF & PDF files are allowed.</strong></div>';

   }
  }

  // if no error occured, continue ....
  if(!isset($errMSG) && $is_moved) {

	 $sth = fileUpload('music', $dbh, $userfile, $fileExt, $fileSize, 'music');

   if($sth->execute()) {

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
   //header("refresh:5;"); // redirects image view page after 5 seconds.

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

		<title>Song Details</title>

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
			<div class="col-md-10" >
				<?php

        date_default_timezone_set('America/Sao_Paulo');

				$dbh = openConnection();

				$row = selectMusicDetailsByAlbumID($_GET['id']);

				$id_album = $row['id_album'];


				if (isset($successMSG)) {
					echo $successMSG;
					header("refresh:5;"); // redirects image view page after 5 seconds.

				}

				if (isset($errMSG)){
					echo $errMSG;
					//header("refresh:5;"); // redirects image view page after 5 seconds.
				}


					echo '<h1 href="'.$row['webpage'].'" class="col-12">'.$row['nome_musica'].' <small> <a class="deco-none" href="artistprofile.php?id='.$row['id_artista'].'">'.$row['nome'].'</a></small></h1>
          <h5>lançada em '. $row['ano_lanc'].', faixa '.$row['num_faixa'].' do álbum <a style="
						text-decoration:none;" href="album.php?id='.$row['id_album'].'">'.$row['nome_album'].'</a></h5>'	;


          $generos = selectGenreByMusicID($_GET['id']);


          echo '<div class="row">
                <div class="col-lg-12 col-md-12">
                <h3>Gêneros <small>';

								echo implode(', ' ,$generos).'.';


          echo '</small></h3></div></div>';


					$rows = selectComposerByMusicID($_GET['id']);

          echo '<div class="row">
          <div class="col-lg-6 col-md-6">
          <h3>Compositores</h3>';
          foreach ($rows as $row) {
            echo '<a style="text-decoration:none;" href="artistprofile.php?id='.$row['id_artista'].'"><h5>'.$row['nome'].'</h5></a>';
          }
          echo '</div>';

          $rows = selectMusicListByAlbumID($id_album);

          echo '<div class="col-lg-6 col-md-6">
          <h3>Músicas relacionadas</h3>';

          foreach ($rows as $row) {
            echo '<a style="text-decoration:none;" href="music.php?id='.$row['id_musica'].'"><h5>'.$row['nome_musica'].'</h5></a>';
          }
          echo '</div> </div>';

          $rows = selectFilesByID('music', $_GET['id']);

          /*echo '<div class="row">
          <div class="col-lg-6 col-md-6">
          <p><h3>Arquivos ';
					if ($_SESSION['nivel_acesso'] == 1) echo '<span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:18px" data-toggle="modal" data-target="#myModal"></span>
					</div></div>';

          foreach ($rows as $row) {
            echo '<a style="text-decoration:none;" href="music_files/'.$row[id_musica].'/'.$row[file].'"><h5>'.$row[type].'</h5></a>';
          }
          echo '</div> </div>';*/

          if ($_SESSION['nivel_acesso'] == 1) echo '<h3>Arquivos <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span></h3>';
          else echo '<h3>Arquivos</h3>';

          $in_img = "('".implode("' , '" , $img_extensions)."')";
          $in_audio = "('".implode("' , '" , $audio_extensions)."')";
          $in_video = "('".implode("' , '" , $video_extensions)."')";
          $in_txt = "('".implode("' , '" , $txt_extensions)."')";

          $count_img = countFilesByID('music',$in_img, $_GET['id']);
          $count_audio = countFilesByID('music',$in_audio, $_GET['id']);
          $count_video = countFilesByID('music',$in_video, $_GET['id']);
          $count_txt = countFilesByID('music',$in_txt, $_GET['id']);

          $rows = selectFilesByID('music', $_GET['id']);

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
              <div role="tabpanel" class="tab-pane active" id="audio"></div>
              <div role="tabpanel" class="tab-pane" id="video"></div>
              <div role="tabpanel" class="tab-pane" id="imagem">';

              foreach ($rows as $row) echo '<a style="text-decoration:none;" href="album_files/'.$row['id_musica'].'/'.$row['file'].'"><h5>'.$row['type'].'</h5></a>';

            echo '</div>
              <div role="tabpanel" class="tab-pane" id="texto"></div>
          </div>
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
