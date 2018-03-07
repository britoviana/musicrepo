<?php

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

 if (!isset($_SESSION)) session_start();
 error_reporting( ~E_NOTICE ); // avoid notice
 require_once 'connection.php';

 $dbh = openConnection();
 $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


 if(isset($_POST['btnsave'])) {

  $username = $_POST['user_name'];// user name
  $userjob = $_POST['user_job'];// user email

  $imgFile = $_FILES['user_image']['name'];
  $tmp_file = $_FILES['user_image']['tmp_name'];
  $fileSize = $_FILES['user_image']['size'];
  //$fileType = $_FILES['user_image']['type'];

  echo $fileSize;


  if (empty($username)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Enter Username.</strong></div>';

  else if (empty($userjob)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Please Enter Your Job Work.</strong></div>';

  else if (empty($imgFile)) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>"Please Select Image File.</strong></div>';

 else {
   // upload directory
   $upload_dir = uploadDir('music_files/');

   // get file extension
   $fileExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

   // valid file extensions
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'tif', 'tiff', 'bmp', 'pdf',
   'txt', 'rft', 'mp4', 'mov', 'avi', 'flv', 'wmv', 'vob', 'mp3', 'wav', 'mid', 'wma', 'm4a');

   //$img_extensions = array('jpeg', 'jpg', 'png', 'gif', 'tif', 'tiff', 'bmp');
   //$txt_extensions = array('pdf', 'txt', 'rft');
   //$video_extensions = array('mp4', 'mov', 'avi', 'flv', 'wmv', 'vob');
   //$audio_extensions = array('mp3', 'wav', 'mid', 'wma', 'm4a');

   // rename uploading file
   $userfile = $username.rand(1000,1000000)."-".$imgFile;

   // allow valid file formats
   if(in_array($fileExt, $valid_extensions)){

     if(array_key_exists('user_image', $_FILES)){
      if ($_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
         //echo 'upload was successful';

         // Check file size '5MB'
         if ($fileSize < 5*MB && $fileSize > 0) $is_moved = move_uploaded_file($tmp_file,$upload_dir.$userfile);
         else $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Sorry, your file is too large.</strong></div>';

      } else {
         die("Upload failed with error code " . $_FILES['user_image']['error']);
        }
    }

   }
   else {
    $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Sorry, only JPG, JPEG, PNG, GIF & PDF files are allowed.</strong></div>';

   }
  }

  // if no error occured, continue ....
  if(!isset($errMSG) && $is_moved) {

   //$stmt = $dbh->prepare('INSERT INTO tbl_users (userName,userProfession,userPic) VALUES (:uname, :ujob, :upic)');
   //$stmt->bindParam(':uname',$username);
   //$stmt->bindParam(':ujob',$userjob);

   $stmt = $dbh->prepare('INSERT INTO file(file,type,size) VALUES(:upic, :type, :size)');
   $stmt->bindParam(':upic',$userfile);
   $stmt->bindParam(':type',$fileExt);
   $stmt->bindParam(':size',$fileSize);


   if($stmt->execute()) {

    $successMSG = '<div class="alert alert-success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Arquivo adicionado com sucesso!</strong></div>';
//"new record succesfully inserted ...";
    header("refresh:5;"); // redirects image view page after 5 seconds.

  } else {

    $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Error while inserting!</strong></div>';

   }

   //echo $successMSG;
   //header("refresh:5;"); // redirects image view page after 5 seconds.


 } else {
   if (!$is_moved) $errMSG = '<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Upload directory error!</strong></div>';
   //echo $errMSG;
   //header("refresh:5;"); // redirects image view page after 5 seconds.

 }
 }
?>

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

   <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">...</div>
    <div role="tabpanel" class="tab-pane" id="profile">...</div>
    <div role="tabpanel" class="tab-pane" id="messages">...</div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  </div>

</div>


    <form method="post" enctype="multipart/form-data">

<div class="col-md-5">
  <?php
  if (isset($successMSG)) {
    echo $successMSG;
    header("refresh:5;"); // redirects image view page after 5 seconds.

  }

  if (isset($errMSG)){
    echo $errMSG;
    header("refresh:5;"); // redirects image view page after 5 seconds.
  }
  ?>
        <div class="form-group">
         <label class="control-label">Nome</label>
         <div class="col-12">
            <input class="form-control" type="text" name="user_name" placeholder="Nome do arquivo" value="<?php echo $username; ?>" />
          </div>
        </div>

         <div class="form-group">
           <label class="control-label">Descrição</label>
           <div class="col-12">
            <input class="form-control" type="text" name="user_job" placeholder="Descricao do arquivo" value="<?php echo $userjob; ?>" />
          </div>
        </div>

        <div class="form-group">
         <label class="control-label">Arquivo</label>
         <div class="col-12">
            <input class="input-group" type="file" name="user_image" accept="media_type" />
          </div>
        </div>

            <button type="submit" name="btnsave" class="btn btn-default">
            <span class="glyphicon glyphicon-save"></span> &nbsp; save
            </button>

</div>
    </form>
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
