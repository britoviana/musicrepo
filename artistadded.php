<!DOCTYPE HTML>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <title>Add Artist</title>

</head>

<body>

  <header>
    <?php
    require_once('connection.php');
    navBar();
    ?>
  </header>

  <div class="container">
    <div class="row" style="margin-top: 20px;">
    <div class="col-md-5" >

<?php

if (isset($_POST['submit'])){

    $data_missing = array();

    if (empty($_POST['nome'])){

        // Adds nome to array
        $data_missing[] = 'Nome';

    } else {

        // Trim white space and escape the nome and store the nome
        $nome = trim($_POST['nome']);

    }


    if (empty($_POST['nacionalidade'])){

        // Adds name to array
        $data_missing[] = 'País';

    } else {

        // Trim white space from the name and store the name
        $nacionalidade = trim($_POST['nacionalidade']);

    }

    if (empty($_POST['data_nasc'])){

        // Adds name to array
        $data_missing[] = 'Data de início';

    } else {

        // Trim white space from the name and store the name
        $data_nasc = trim($_POST['data_nasc']);

    }

    if (empty($_POST['local_nasc'])){

        // Adds name to array
        $data_missing[] = 'Local de residencia';

    } else {

        // Trim white space from the name and store the name
        $local_nasc = trim($_POST['local_nasc']);

    }
    /*
    if(empty($_POST['data_morte'])){

        // Adds name to array
        $data_missing[] = 'Data de morte';

    } else {

        // Trim white space from the name and store the name
        $data_morte = trim($_POST['data_morte']);

    }

    if(empty($_POST['local_morte'])){

        // Adds name to array
        $data_missing[] = 'Local de morte';

    } else {

        // Trim white space from the name and store the name
        $local_morte = trim($_POST['local_morte']);

    }

    if(empty($_POST['bio'])){

        // Adds name to array
        $data_missing[] = 'Biografia';

    } else {

        // Trim white space from the name and store the name
        $bio = trim($_POST['bio']);

    }

    if(empty($_POST['webpage'])){

        // Adds name to array
        $data_missing[] = 'Webpage';

    } else {

        // Trim white space from the name and store the name
        $webpage = trim($_POST['webpage']);

    }*/

    if (isset($data_morte)) trim($_POST['data_morte']);
    $local_morte = trim($_POST['local_morte']);
    $bio = trim($_POST['bio']);
    $webpage = trim($_POST['webpage']);
    $tipo = "banda";



    if(empty($data_missing)){

      require_once('connection.php');
      $link = openConnectionPDO();

      $query = "INSERT INTO artista (nome, nome_completo, nacionalidade,
      data_nasc, local_nasc, data_morte, local_morte, bio, webpage, tipo) VALUES (:nome, :nome_completo, :nacionalidade,
      :data_nasc, :local_nasc, :data_morte, :local_morte, :bio, :webpage, :tipo)";

      $stmt = $link->prepare($query);

      $stmt->bindParam(':nome', $nome);
      $stmt->bindParam(':nome_completo', $nome_completo);
      $stmt->bindParam(':nacionalidade', $nacionalidade);
      $stmt->bindParam(':data_nasc', $data_nasc);
      $stmt->bindParam(':local_nasc', $local_nasc);
      $stmt->bindParam(':data_morte', $data_morte);
      $stmt->bindParam(':local_morte', $local_morte);
      $stmt->bindParam(':bio', $bio);
      $stmt->bindParam(':webpage', $webpage);
      $stmt->bindParam(':tipo', $tipo);

      $result = $stmt->execute();

      $id_artista = $link->lastInsertId();

      foreach ($_POST['componentes'] as $id_componente){

          $query = "INSERT INTO artista_componente (id_artista, id_componente) VALUES (:id_artista, :id_componente)";
          $stmt = $link->prepare($query);
          $stmt->bindValue(':id_artista', $id_artista);
          $stmt->bindValue(':id_componente', $id_componente);
          $result = $stmt->execute();
      }

      $affected_rows = $stmt->rowCount();

      if($affected_rows == 1){

            echo '<br><div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>SUBMETERU!</strong> Artista adicionado.
      </div>';

            $stmt = null;
            $link = null;

        } else {
            echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>ERRO!</strong> Artista não adicionado.
      </div>';

            echo $stmt->errorInfo();

            $stmt = null;
            $link = null;
        }
    } else {

        echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Faixa não atualizada!</strong> Preencha os campos obrigatórios:</br>';

        foreach($data_missing as $missing){

            echo "$missing<br />";
        }
        echo "</div>";
    }
}

?>


<form action="artistadded.php" method="post" accept-charset="uft8">

<h1 class="col-10">Add new artist</h1>

<div class="form-group">
  <label for="nome" class="col-12 col-form-label">Nome</label>
  <div class="col-12">
    <input class="form-control" type="text" name="nome" id="nome" size="30">
  </div>
</div>

<div class="form-group">
  <label for="nome" class="col-12 col-form-label">Nome completo</label>
  <div class="col-12">
    <input class="form-control" type="text" name="nome_completo"id="nome_completo" size="30">
  </div>
</div>

<div class="form-group">
<select name="componentes[]" id="componentes" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Componentes" multiple data-max-options="8" multiple>
  <?php
    require_once('connection.php');
    showArtists();
  ?>
</select>
</div>

<div class="form-group">
  <div class="col-12">
    <label for="nacionalidade">Nacionalidade</label>
    <select name="nacionalidade" id="nacionalidade" class="form-control selectpicker" data-live-search="true" multiple data-max-options="1" multiple>
      <option value="afghan">Afghan</option>
      <option value="albanian">Albanian</option>
      <option value="algerian">Algerian</option>
      <option value="american">American(USA)</option>
      <option value="andorran">Andorran</option>
      <option value="angolan">Angolan</option>
      <option value="antiguans">Antiguans</option>
      <option value="argentinean">Argentinean</option>
      <option value="armenian">Armenian</option>
      <option value="australian">Australian</option>
      <option value="austrian">Austrian</option>
      <option value="azerbaijani">Azerbaijani</option>
      <option value="bahamian">Bahamian</option>
      <option value="bahraini">Bahraini</option>
      <option value="bangladeshi">Bangladeshi</option>
      <option value="barbadian">Barbadian</option>
      <option value="barbudans">Barbudans</option>
      <option value="batswana">Batswana</option>
      <option value="belarusian">Belarusian</option>
      <option value="belgian">Belgian</option>
      <option value="belizean">Belizean</option>
      <option value="beninese">Beninese</option>
      <option value="bhutanese">Bhutanese</option>
      <option value="bolivian">Bolivian</option>
      <option value="bosnian">Bosnian</option>
      <option selected value="brazilian">Brazilian</option>
      <option value="british">British</option>
      <option value="bruneian">Bruneian</option>
      <option value="bulgarian">Bulgarian</option>
      <option value="burkinabe">Burkinabe</option>
      <option value="burmese">Burmese</option>
      <option value="burundian">Burundian</option>
      <option value="cambodian">Cambodian</option>
      <option value="cameroonian">Cameroonian</option>
      <option value="canadian">Canadian</option>
      <option value="cape verdean">Cape Verdean</option>
      <option value="central african">Central African</option>
      <option value="chadian">Chadian</option>
      <option value="chilean">Chilean</option>
      <option value="chinese">Chinese</option>
      <option value="colombian">Colombian</option>
      <option value="comoran">Comoran</option>
      <option value="congolese">Congolese</option>
      <option value="costa rican">Costa Rican</option>
      <option value="croatian">Croatian</option>
      <option value="cuban">Cuban</option>
      <option value="cypriot">Cypriot</option>
      <option value="czech">Czech</option>
      <option value="danish">Danish</option>
      <option value="djibouti">Djibouti</option>
      <option value="dominican">Dominican</option>
      <option value="dutch">Dutch</option>
      <option value="east timorese">East Timorese</option>
      <option value="ecuadorean">Ecuadorean</option>
      <option value="egyptian">Egyptian</option>
      <option value="emirian">Emirian</option>
      <option value="equatorial guinean">Equatorial Guinean</option>
      <option value="eritrean">Eritrean</option>
      <option value="estonian">Estonian</option>
      <option value="ethiopian">Ethiopian</option>
      <option value="fijian">Fijian</option>
      <option value="filipino">Filipino</option>
      <option value="finnish">Finnish</option>
      <option value="french">French</option>
      <option value="gabonese">Gabonese</option>
      <option value="gambian">Gambian</option>
      <option value="georgian">Georgian</option>
      <option value="german">German</option>
      <option value="ghanaian">Ghanaian</option>
      <option value="greek">Greek</option>
      <option value="grenadian">Grenadian</option>
      <option value="guatemalan">Guatemalan</option>
      <option value="guinea-bissauan">Guinea-Bissauan</option>
      <option value="guinean">Guinean</option>
      <option value="guyanese">Guyanese</option>
      <option value="haitian">Haitian</option>
      <option value="herzegovinian">Herzegovinian</option>
      <option value="honduran">Honduran</option>
      <option value="hungarian">Hungarian</option>
      <option value="icelander">Icelander</option>
      <option value="indian">Indian</option>
      <option value="indonesian">Indonesian</option>
      <option value="iranian">Iranian</option>
      <option value="iraqi">Iraqi</option>
      <option value="irish">Irish</option>
      <option value="israeli">Israeli</option>
      <option value="italian">Italian</option>
      <option value="ivorian">Ivorian</option>
      <option value="jamaican">Jamaican</option>
      <option value="japanese">Japanese</option>
      <option value="jordanian">Jordanian</option>
      <option value="kazakhstani">Kazakhstani</option>
      <option value="kenyan">Kenyan</option>
      <option value="kittian and nevisian">Kittian and Nevisian</option>
      <option value="kuwaiti">Kuwaiti</option>
      <option value="kyrgyz">Kyrgyz</option>
      <option value="laotian">Laotian</option>
      <option value="latvian">Latvian</option>
      <option value="lebanese">Lebanese</option>
      <option value="liberian">Liberian</option>
      <option value="libyan">Libyan</option>
      <option value="liechtensteiner">Liechtensteiner</option>
      <option value="lithuanian">Lithuanian</option>
      <option value="luxembourger">Luxembourger</option>
      <option value="macedonian">Macedonian</option>
      <option value="malagasy">Malagasy</option>
      <option value="malawian">Malawian</option>
      <option value="malaysian">Malaysian</option>
      <option value="maldivan">Maldivan</option>
      <option value="malian">Malian</option>
      <option value="maltese">Maltese</option>
      <option value="marshallese">Marshallese</option>
      <option value="mauritanian">Mauritanian</option>
      <option value="mauritian">Mauritian</option>
      <option value="mexican">Mexican</option>
      <option value="micronesian">Micronesian</option>
      <option value="moldovan">Moldovan</option>
      <option value="monacan">Monacan</option>
      <option value="mongolian">Mongolian</option>
      <option value="moroccan">Moroccan</option>
      <option value="mosotho">Mosotho</option>
      <option value="motswana">Motswana</option>
      <option value="mozambican">Mozambican</option>
      <option value="namibian">Namibian</option>
      <option value="nauruan">Nauruan</option>
      <option value="nepalese">Nepalese</option>
      <option value="new zealander">New Zealander</option>
      <option value="ni-vanuatu">Ni-Vanuatu</option>
      <option value="nicaraguan">Nicaraguan</option>
      <option value="nigerien">Nigerien</option>
      <option value="north korean">North Korean</option>
      <option value="northern irish">Northern Irish</option>
      <option value="norwegian">Norwegian</option>
      <option value="omani">Omani</option>
      <option value="pakistani">Pakistani</option>
      <option value="palauan">Palauan</option>
      <option value="panamanian">Panamanian</option>
      <option value="papua new guinean">Papua New Guinean</option>
      <option value="paraguayan">Paraguayan</option>
      <option value="peruvian">Peruvian</option>
      <option value="polish">Polish</option>
      <option value="portuguese">Portuguese</option>
      <option value="qatari">Qatari</option>
      <option value="romanian">Romanian</option>
      <option value="russian">Russian</option>
      <option value="rwandan">Rwandan</option>
      <option value="saint lucian">Saint Lucian</option>
      <option value="salvadoran">Salvadoran</option>
      <option value="samoan">Samoan</option>
      <option value="san marinese">San Marinese</option>
      <option value="sao tomean">Sao Tomean</option>
      <option value="saudi">Saudi</option>
      <option value="scottish">Scottish</option>
      <option value="senegalese">Senegalese</option>
      <option value="serbian">Serbian</option>
      <option value="seychellois">Seychellois</option>
      <option value="sierra leonean">Sierra Leonean</option>
      <option value="singaporean">Singaporean</option>
      <option value="slovakian">Slovakian</option>
      <option value="slovenian">Slovenian</option>
      <option value="solomon islander">Solomon Islander</option>
      <option value="somali">Somali</option>
      <option value="south african">South African</option>
      <option value="south korean">South Korean</option>
      <option value="spanish">Spanish</option>
      <option value="sri lankan">Sri Lankan</option>
      <option value="sudanese">Sudanese</option>
      <option value="surinamer">Surinamer</option>
      <option value="swazi">Swazi</option>
      <option value="swedish">Swedish</option>
      <option value="swiss">Swiss</option>
      <option value="syrian">Syrian</option>
      <option value="taiwanese">Taiwanese</option>
      <option value="tajik">Tajik</option>
      <option value="tanzanian">Tanzanian</option>
      <option value="thai">Thai</option>
      <option value="togolese">Togolese</option>
      <option value="tongan">Tongan</option>
      <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
      <option value="tunisian">Tunisian</option>
      <option value="turkish">Turkish</option>
      <option value="tuvaluan">Tuvaluan</option>
      <option value="ugandan">Ugandan</option>
      <option value="ukrainian">Ukrainian</option>
      <option value="uruguayan">Uruguayan</option>
      <option value="uzbekistani">Uzbekistani</option>
      <option value="venezuelan">Venezuelan</option>
      <option value="vietnamese">Vietnamese</option>
      <option value="welsh">Welsh</option>
      <option value="yemenite">Yemenite</option>
      <option value="zambian">Zambian</option>
      <option value="zimbabwean">Zimbabwean</option>
    </select>
  </div>
</div>

<div class="form-group">
  <label for="data_nasc" class="col-12 col-form-label">Data de nascimento</label>
  <div class="col-12">
    <input class="form-control" type="date" value="" name="data_nasc" id="data_nasc">
  </div>
</div>

<div class="form-group">
  <label for="local_nasc" class="col-12 col-form-label">Local de nascimento</label>
  <div class="col-12">
    <input class="form-control" type="text" name="local_nasc" id="local_nasc" size="30">
  </div>
</div>

<div class="form-group">
  <label for="data_morte" class="col-12 col-form-label">Data de morte</label>
  <div class="col-12">
    <input class="form-control" type="date" value="" name="data_morte" id="data_morte">
  </div>
</div>

<div class="form-group">
  <label for="local_morte" class="col-12 col-form-label">Local de morte</label>
  <div class="col-12">
    <input class="form-control" type="text" name="local_morte" id="local_morte" size="30">
  </div>
</div>

<div class="form-group">
  <div class="col-12">
    <label for="exampleTextarea">Biografia</label>
    <textarea class="form-control" name ="bio" id="bio" rows="4"></textarea>
  </div>
</div>

<div class="form-group">
  <label for="webpage" class="col-3 col-form-label">Site Oficial</label>
  <div class="col-12">
    <input class="form-control" type="url" name ="webpage" id="webpage" size="30">
  </div>
</div>


<div class="form-group">
  <div class="col-12">
    <button type="submit" name="submit" class="btn btn-success">Cadastrar</button>
  </div>
</div>

</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-*.min.js"></script>

</body>
</html>

<script>
$(document).ready(function(){
 $('.selectpicker').selectpicker();

 $('#componente').change(function(){
  $('#hidden_componente').val($('#componente').val());
 });

 $('#multiple_select_form').on('submit', function(event){
  event.preventDefault();
  if($('#componente').val() != '')
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
   alert("Please select componente");
   return false;
  }
 });
});
</script>
