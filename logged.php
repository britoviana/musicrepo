<?php


if ( (isset($_POST['submit'])) && (!empty($_POST['usuario'])) && (!empty($_POST['senha'])) ){

  require_once('connection.php');

  $dbh = openConnection();

  $query = "SELECT usuario, nivel_acesso FROM usuario WHERE (usuario = :usuario) AND (senha = SHA1(:senha)) LIMIT 1";

  $sth = $dbh->prepare($query);

  $sth->bindParam(':usuario', $_POST['usuario']);
  $sth->bindParam(':senha', $_POST['senha']);

  $result = $sth->execute();

  $rows = $sth->rowCount();

  $usuario = $sth->fetch(PDO::FETCH_ASSOC);


  if (($result) && ($rows == 1)) {


  if (!isset($_SESSION)) session_start();

   $_SESSION['usuario'] = $usuario['usuario'];
   $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

   header("Location: searchartist.php"); exit;
 } else {
   echo 'Usuario ou senha incorretos.';
 }



} else {

  header("Location: login.php"); exit;


}


 ?>
