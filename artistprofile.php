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
<strong>Please Enter File Description.</strong></div>';

  else if (empty($imgFile)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>"Please Select File.</strong></div>';

 else {
   // upload directory
   $upload_dir = uploadDir('artist_files/', $id);


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

		$stmt = fileUpload('artist', $dbh, $userfile, $fileExt, $fileSize, $fileDesc, 'artist');

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

		<title>Artist Profile</title>

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
			<div class="row">
				<div class="col-md-12">

				<?php

				require_once('connection.php');
        date_default_timezone_set('America/Sao_Paulo');

				$dbh = openConnection();

				$query = "SELECT tipo FROM artista WHERE id_artista = " . $_GET['id'];

				$sth = $dbh->prepare($query);

				$response = $sth->execute();

				$tipo = $sth->fetch(PDO::FETCH_ASSOC);

				if (isset($successMSG)) {
					echo $successMSG;
					header("refresh:5;"); // redirects image view page after 5 seconds.

				}

				if (isset($errMSG)){
					echo $errMSG;
					//header("refresh:5;"); // redirects image view page after 5 seconds.
				}

				if ($tipo['tipo'] == 'pessoa') {

					$row = selectArtistByID($_GET['id']);


					if (isset($row['avatar'])) {
            list($width, $height, $type, $attr) = getimagesize('artist_files/'.$_GET['id'].'/'.$row['avatar']);
            echo "<center><img src='artist_files/".$_GET['id']."/".$row['avatar']."' width='300' height='300' alt='avatar' class='img-circle'/></center>";
          }

					echo '<h1 href="'.$row['webpage'].'" target="_blank" class="col-12">'.$row['nome'].' <small>'.$row['nome_completo'].'</small></h1>
          <h5>'.$row['nacionalidade'].', nasceu no dia '. date('d/m/Y', strtotime($row['data_nasc'])) .' @ '. $row['local_nasc'];
          if (isset($row['data_morte'])) echo ' morreu no dia '. date('d/m/Y', strtotime($row['data_morte'])).' @ '.$row['local_morte'].'</h5>';
          echo '<a style="text-decoration:none;" href="'.$row['webpage'].'" target="_blank"<p>'.$row['webpage'].'</p></a>';
          if (isset($row['bio'])) echo '<blockquote ><p class="lead">'.$row['bio'].'</p></blockquote>';


					$generos = selectGenreByArtistID($_GET['id']);

					if ($generos){
						echo '<div class="row">
	                <div class="col-lg-12 col-md-12">
	                <h3>Gêneros <small>';
	          echo implode(', ' ,$generos).'.';
	          echo '</small></h3></div></div>';
					}


          $query = "SELECT m.nome_musica, m.id_musica FROM musica AS m
                    JOIN musica_artista AS ma ON m.id_musica = ma.id_musica
                    JOIN musica_compositor AS mc ON m.id_musica = mc.id_musica
                    JOIN artista AS a ON ma.id_artista = a.id_artista or mc.id_compositor = a.id_artista
                    WHERE a.id_artista = ". $_GET['id']." ORDER BY nome_musica";

          $sth = $dbh->prepare($query);

          $response = $sth->execute();

          $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="row">
          <div class="col-lg-6 col-md-6">
          <h3>Músicas</h3>';
          foreach ($rows as $row) echo '<a style="text-decoration:none;" href="music.php?id='.$row['id_musica'].'"><h5>'.$row['nome_musica'].'</h5></a>';
          echo '</div>';

          $query = "SELECT al.nome_album, al.id_album FROM album AS al
                    LEFT JOIN album_artista AS aa ON al.id_album = aa.id_album
                    LEFT JOIN artista AS a ON aa.id_artista = a.id_artista
                    WHERE a.id_artista = ". $_GET['id']." ORDER BY nome_album";

          $sth = $dbh->prepare($query);

          $response = $sth->execute();

          $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="col-lg-6 col-md-6">
          <h3>Albums</h3>';

          foreach ($rows as $row) echo '<a style="text-decoration:none;" href="album.php?id='.$row['id_album'].'"><h5>'.$row['nome_album'].'</h5></a>';

          echo '</div> </div>';

					/*echo '<div class="row">
          <div class="col-lg-6 col-md-6">
          <p><h3>Arquivos ';
					if ($_SESSION['nivel_acesso'] == 1) echo '<span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span>';

					foreach ($rows as $row) echo '<a style="text-decoration:none;" href="artist_files/'.$row[id_artista].'/'.$row[file].'"><h5>'.$row[type].'</h5></a>';

          echo "</div></div>";*/


          if ($_SESSION['nivel_acesso'] == 1) echo '<h3>Arquivos <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span></h3>';
          else echo '<h3>Arquivos</h3>';

          $in_img = "('".implode("' , '" , $img_extensions)."')";
          $in_audio = "('".implode("' , '" , $audio_extensions)."')";
          $in_video = "('".implode("' , '" , $video_extensions)."')";
          $in_txt = "('".implode("' , '" , $txt_extensions)."')";

          $count_img = countFilesByID('artist',$in_img, $_GET['id']);
          $count_audio = countFilesByID('artist',$in_audio, $_GET['id']);
          $count_video = countFilesByID('artist',$in_video, $_GET['id']);
          $count_txt = countFilesByID('artist',$in_txt, $_GET['id']);

          $rows = selectFilesByID('artist', $_GET['id']);



          echo '<!-- Nav tabs -->
          <div class="card col-lg-6 col-md-">
          <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#audio" aria-controls="audio" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span><span class="badge">'.$count_audio['qtd'].'</span></a></li>
              <li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span><span class="badge">'.$count_video['qtd'].'</span></a></li>
              <li role="presentation"><a href="#imagem" aria-controls="imagem" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span><span class="badge">'.$count_img['qtd'].'</span></a></li>
              <li role="presentation"><a href="#texto" aria-controls="texto" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-text-size" aria-hidden="true"></span><span class="badge">'.$count_txt['qtd'].'</span></a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
          <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="audio">';
              foreach ($rows as $row) if (in_array($row['type'], $audio_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</div>
              <div role="tabpanel" class="tab-pane" id="video">';
              foreach ($rows as $row) if (in_array($row['type'], $video_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</div>
              <div role="tabpanel" class="tab-pane" id="imagem">';
              foreach ($rows as $row) if (in_array($row['type'], $img_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo '</div>
              <div role="tabpanel" class="tab-pane" id="texto">';
              foreach ($rows as $row) if (in_array($row['type'], $txt_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
              echo'</br></div>
          </div>
          </div>';



				}

				if ($tipo['tipo'] == 'banda'){

          $row = selectArtistByID($_GET['id']);

					list($width, $height, $type, $attr) = getimagesize("artist_files/avatar/288554-cartolaavatar.jpg");
					if (isset($row['avatar'])) echo "<center><img src='artist_files/".$_GET['id']."/".$row['avatar']."' width='300' height='300' alt='avatar' class='img-responsive img-circle'/></center>";

					$componentes = selectComponentsByArtistID($_GET['id']);

					echo '<h1 class="col-10">'.$row['nome'].' <small>';
          foreach ($componentes as $id => $nome) echo '<a class="deco-none" href="artistprofile.php?id='.$id.'">'.$nome.'</a> | ';

					echo '</small><h5>'.$row['nacionalidade'].', começou em '. date('Y', strtotime($row['data_nasc'])).' @ '.$row['local_nasc'];
          if (isset($row['data_morte'])) echo ' terminou em '.date('Y', strtotime($row['data_morte'])).'</h5>
          <p>'.$row['webpage'].'</p>';
          if (isset($row['bio'])) echo '<blockquote><p class="lead">'.$row['bio'].'</p></blockquote>';



								$generos = selectGenreByArtistID($_GET['id']);

                if ($generos) {
                  echo '<div class="row">
                        <div class="col-lg-12 col-md-12">
                        <h3>Gêneros <small>';
  								echo implode(', ' ,$generos).'.';
                  echo '</small></h3></div></div>';

                }

                $query = "SELECT m.nome_musica, m.id_musica FROM musica AS m
                          LEFT JOIN musica_artista AS ma ON m.id_musica = ma.id_musica
                          LEFT JOIN artista AS a ON ma.id_artista = a.id_artista
                          WHERE a.id_artista = ". $_GET['id']." ORDER BY nome_musica";

                $sth = $dbh->prepare($query);

                $response = $sth->execute();

                $rows = $sth->fetchAll(PDO::FETCH_ASSOC);


                echo '<div class="row">
                <div class="col-lg-6 col-md-6">
                <h3>Músicas</h3>';
                foreach ($rows as $row) echo '<a style="text-decoration:none;" href="music.php?id='.$row["id_musica"].'"><h5>'.$row["nome_musica"].'</h5></a>';

                echo '</div>';

                $query = "SELECT al.nome_album, al.id_album FROM album AS al
                          LEFT JOIN album_artista AS aa ON al.id_album = aa.id_album
                          LEFT JOIN artista AS a ON aa.id_artista = a.id_artista
                          WHERE a.id_artista = ". $_GET['id']." ORDER BY nome_album";

                $sth = $dbh->prepare($query);

                $response = $sth->execute();

                $rows = $sth->fetchAll(PDO::FETCH_ASSOC);


                echo '<div class="col-lg-6 col-md-6">
                <h3>Albums</h3>';

                foreach ($rows as $row) echo '<a style="text-decoration:none;" href="album.php?id='.$row['id_album'].'"><h5>'.$row['nome_album'].'</h5></a>';

                echo "</div></div>";

								$rows = selectFilesByID('artist', $_GET['id']);



                /*echo '<div class="row">
                <div class="col-lg-6 col-md-6">
                <p><h3>Arquivos ';
      					if ($_SESSION['nivel_acesso'] == 1) echo '<span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span>';

      					foreach ($rows as $row) echo '<a style="text-decoration:none;" href="artist_files/'.$row[id_artista].'/'.$row[file].'"><h5>'.$row[type].'</h5></a>';

                echo "</div></div>";*/
                if ($_SESSION['nivel_acesso'] == 1) echo '<h3>Arquivos <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#5cb85c; font-size:14px; cursor:pointer" data-toggle="modal" data-target="#myModal"></span></h3>';
                else echo '<h3>Arquivos</h3>';

                $in_img = "('".implode("' , '" , $img_extensions)."')";
                $in_audio = "('".implode("' , '" , $audio_extensions)."')";
                $in_video = "('".implode("' , '" , $video_extensions)."')";
                $in_txt = "('".implode("' , '" , $txt_extensions)."')";

                $count_img = countFilesByID('artist',$in_img, $_GET['id']);
                $count_audio = countFilesByID('artist',$in_audio, $_GET['id']);
                $count_video = countFilesByID('artist',$in_video, $_GET['id']);
                $count_txt = countFilesByID('artist',$in_txt, $_GET['id']);

                $rows = selectFilesByID('artist', $_GET['id']);

                echo '<!-- Nav tabs -->
                <div class="card col-lg-6 col-md-">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#audio" aria-controls="audio" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span><span class="badge">'.$count_audio["qtd"].'</span></a></li>
                    <li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span><span class="badge">'.$count_video["qtd"].'</span></a></li>
                    <li role="presentation"><a href="#imagem" aria-controls="imagem" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span><span class="badge">'.$count_img["qtd"].'</span></a></li>
                    <li role="presentation"><a href="#texto" aria-controls="texto" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-text-size" aria-hidden="true"></span><span class="badge">'.$count_txt["qtd"].'</span></a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="audio">';
                    foreach ($rows as $row) if (in_array($row['type'], $audio_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
                    echo '</div>
                    <div role="tabpanel" class="tab-pane" id="video">';
                    foreach ($rows as $row) if (in_array($row['type'], $video_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
                    echo '</div>
                    <div role="tabpanel" class="tab-pane" id="imagem">';
                    foreach ($rows as $row) if (in_array($row['type'], $img_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
                    echo '</div>
                    <div role="tabpanel" class="tab-pane" id="texto">';
                    foreach ($rows as $row) if (in_array($row['type'], $txt_extensions)) echo '<a style="text-decoration:none;" href="artist_files/'.$_GET['id'].'/'.$row['file'].'" target="_blank"><h5>'.$row['type'].'</h5></a>';
                    echo'</br></div>
                </div>
                </div>';


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
