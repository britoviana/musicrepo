<?php

// Opens a connection to the database
// Since it is a php file it won't open in a browser
// It should be saved outside of the main web documents folder
// and imported when needed

// Defined as constants so that they can't be changed

DEFINE('DB_USER', 'rodrigo');
DEFINE('DB_PASSWORD', 'hqWxD3FN');
DEFINE('DB_HOST', '127.0.0.1');
DEFINE('DB_NAME', 'musicrepo2');
DEFINE('DB_CHARSET', 'utf8');

// $link will contain a resource link to the database

function openConnection(){

	try {

		$dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

	} catch (PDOException $e) {

			echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
	}

	// Change character set to UTF-8
	$dbh->exec("set names " . DB_CHARSET);

	return $dbh;
}

	function showArtists($id) {

		$dbh = openConnection();

		$sth = $dbh->prepare("SELECT id_artista, nome from artista ORDER BY nome");
		$response = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row as $artista) {
			if (in_array($artista[id_artista], $id)) echo '<option selected value="'.$artista['id_artista'].'">'.$artista['nome'].'</option>';

				else echo '<option value="'.$artista['id_artista'].'">'.$artista['nome'].'</option>';

		}

		$sth = null;
		$dbh = null;

	}
	function showComposers($id) {

		$dbh = openConnection();

		$query = "SELECT DISTINCT id_artista, nome FROM artista as a
		 					JOIN musica_compositor AS mc ON a.id_artista = mc.id_compositor
							JOIN musica AS m ON mc.id_musica = m.id_musica
							ORDER BY a.nome";

		$sth = $dbh->prepare($query);
		$response = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row as $compositor) {
			if (in_array($compositor[id_artista], $id)) echo '<option selected value="'.$compositor['id_artista'].'">'.$compositor['nome'].'</option>';

				else echo '<option value="'.$compositor['id_artista'].'">'.$compositor['nome'].'</option>';

		}

		$sth = null;
		$dbh = null;

	}

	function showReleasedYear() {

		$dbh = openConnection();

		$query = "SELECT DISTINCT ano_lanc from musica ORDER BY ano_lanc";

		$sth = $dbh->prepare($query);
		$response = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row as $ano)  echo '<option value="'.$ano['ano_lanc'].'">'.$ano['ano_lanc'].'</option>';

		$sth = null;
		$dbh = null;
	}

function showBirthYear($option) {

		$dbh = openConnection();

		switch ($option) {
			case 'nasc':
				$query = "SELECT DISTINCT year(data_nasc) as ano_nasc from artista WHERE data_nasc IS NOT NULL ORDER BY ano_nasc";


				$sth = $dbh->prepare($query);
				$response = $sth->execute();
				$row = $sth->fetchAll(PDO::FETCH_ASSOC);

				foreach ($row as $ano)  echo '<option value="'.$ano['ano_nasc'].'">'.$ano['ano_nasc'].'</option>';
					break;

			case 'morte':
				$query = "SELECT DISTINCT year(data_morte) as ano_morte from artista WHERE data_morte IS NOT NULL ORDER BY ano_morte";

				$sth = $dbh->prepare($query);
				$response = $sth->execute();
				$row = $sth->fetchAll(PDO::FETCH_ASSOC);

				foreach ($row as $ano)  echo '<option value="'.$ano['ano_morte'].'">'.$ano['ano_morte'].'</option>';
					break;

			default:
				# code...
				break;
		}

		$sth = null;
		$dbh = null;
	}

function showGenres($id) {

	try {
		$dbh = openConnection();

		$query = "SELECT id_genero, nome_genero from genero ORDER BY nome_genero";

		$sth = $dbh->prepare($query);
		$response = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row as $genero) {

		if (in_array($genero[id_genero], $id)) echo '<option selected value="'.$genero['id_genero'].'">'.$genero['nome_genero'].'</option>';

			else echo '<option value="'.$genero['id_genero'].'">'.$genero['nome_genero'].'</option>';
		}

	} catch (Exception $e) {

	}

	$sth = null;
	$dbh = null;
}

function showCountries($id){

	$nationalities = array ("afghan" => "Afghan",
										      "albanian" => "Albanian",
										      "algerian" => "Algerian",
										      "american" => "American(USA)",
										      "andorran" => "Andorran",
										      "angolan" => "Angolan",
										      "antiguans" => "Antiguans",
										      "argentinean" => "Argentinean",
										      "armenian" => "Armenian",
										      "australian" => "Australian",
										      "austrian" => "Austrian",
										      "azerbaijani" => "Azerbaijani",
										      "bahamian" => "Bahamian",
										      "bahraini" => "Bahraini",
										      "bangladeshi" => "Bangladeshi",
										      "barbadian" => "Barbadian",
										      "barbudans" => "Barbudans",
										      "batswana" => "Batswana",
										      "belarusian" => "Belarusian",
										      "belgian" => "Belgian",
										      "belizean" => "Belizean",
										      "beninese" => "Beninese",
										      "bhutanese" => "Bhutanese",
										      "bolivian" => "Bolivian",
										      "bosnian" => "Bosnian",
										      "brazilian" => "Brazilian",
										      "british" => "British",
										      "bruneian" => "Bruneian",
										      "bulgarian" => "Bulgarian",
										      "burkinabe" => "Burkinabe",
										      "burmese" => "Burmese",
										      "burundian" => "Burundian",
										      "cambodian" => "Cambodian",
										      "cameroonian" => "Cameroonian",
										      "canadian" => "Canadian",
										      "cape verdean" => "Cape Verdean",
										      "central african" => "Central African",
										      "chadian" => "Chadian",
										      "chilean" => "Chilean",
										      "chinese" => "Chinese",
										      "colombian" => "Colombian",
										      "comoran" => "Comoran",
										      "congolese" => "Congolese",
										      "costa rican" => "Costa Rican",
										      "croatian" => "Croatian",
										      "cuban" => "Cuban",
										      "cypriot" => "Cypriot",
										      "czech" => "Czech",
										      "danish" => "Danish",
										      "djibouti" => "Djibouti",
										      "dominican" => "Dominican",
										      "dutch" => "Dutch",
										      "east timorese" => "East Timorese",
										      "ecuadorean" => "Ecuadorean",
										      "egyptian" => "Egyptian",
										      "emirian" => "Emirian",
										      "equatorial guinean" => "Equatorial Guinean",
										      "eritrean" => "Eritrean",
										      "estonian" => "Estonian",
										      "ethiopian" => "Ethiopian",
										      "fijian" => "Fijian",
										      "filipino" => "Filipino",
										      "finnish" => "Finnish",
										      "french" => "French",
										      "gabonese" => "Gabonese",
										      "gambian" => "Gambian",
										      "georgian" => "Georgian",
										      "german" => "German",
										      "ghanaian" => "Ghanaian",
										      "greek" => "Greek",
										      "grenadian" => "Grenadian",
										      "guatemalan" => "Guatemalan",
										      "guinea-bissauan" => "Guinea-Bissauan",
										      "guinean" => "Guinean",
										      "guyanese" => "Guyanese",
										      "haitian" => "Haitian",
										      "herzegovinian" => "Herzegovinian",
										      "honduran" => "Honduran",
										      "hungarian" => "Hungarian",
										      "icelander" => "Icelander",
										      "indian" => "Indian",
										      "indonesian" => "Indonesian",
										      "iranian" => "Iranian",
										      "iraqi" => "Iraqi",
										      "irish" => "Irish",
										      "israeli" => "Israeli",
										      "italian" => "Italian",
										      "ivorian" => "Ivorian",
										      "jamaican" => "Jamaican",
										      "japanese" => "Japanese",
										      "jordanian" => "Jordanian",
										      "kazakhstani" => "Kazakhstani",
										      "kenyan" => "Kenyan",
										      "kittian and nevisian" => "Kittian and Nevisian",
										      "kuwaiti" => "Kuwaiti",
										      "kyrgyz" => "Kyrgyz",
										      "laotian" => "Laotian",
										      "latvian" => "Latvian",
										      "lebanese" => "Lebanese",
										      "liberian" => "Liberian",
										      "libyan" => "Libyan",
										      "liechtensteiner" => "Liechtensteiner",
										      "lithuanian" => "Lithuanian",
										      "luxembourger" => "Luxembourger",
										      "macedonian" => "Macedonian",
										      "malagasy" => "Malagasy",
										      "malawian" => "Malawian",
										      "malaysian" => "Malaysian",
										      "maldivan" => "Maldivan",
										      "malian" => "Malian",
										      "maltese" => "Maltese",
										      "marshallese" => "Marshallese",
										      "mauritanian" => "Mauritanian",
										      "mauritian" => "Mauritian",
										      "mexican" => "Mexican",
										      "micronesian" => "Micronesian",
										      "moldovan" => "Moldovan",
										      "monacan" => "Monacan",
										      "mongolian" => "Mongolian",
										      "moroccan" => "Moroccan",
										      "mosotho" => "Mosotho",
										      "motswana" => "Motswana",
										      "mozambican" => "Mozambican",
										      "namibian" => "Namibian",
										      "nauruan" => "Nauruan",
										      "nepalese" => "Nepalese",
										      "new zealander" => "New Zealander",
										      "ni-vanuatu" => "Ni-Vanuatu",
										      "nicaraguan" => "Nicaraguan",
										      "nigerien" => "Nigerien",
										      "north korean" => "North Korean",
										      "northern irish" => "Northern Irish",
										      "norwegian" => "Norwegian",
										      "omani" => "Omani",
										      "pakistani" => "Pakistani",
										      "palauan" => "Palauan",
										      "panamanian" => "Panamanian",
										      "papua new guinean" => "Papua New Guinean",
										      "paraguayan" => "Paraguayan",
										      "peruvian" => "Peruvian",
										      "polish" => "Polish",
										      "portuguese" => "Portuguese",
										      "qatari" => "Qatari",
										      "romanian" => "Romanian",
										      "russian" => "Russian",
										      "rwandan" => "Rwandan",
										      "saint lucian" => "Saint Lucian",
										      "salvadoran" => "Salvadoran",
										      "samoan" => "Samoan",
										      "san marinese" => "San Marinese",
										      "sao tomean" => "Sao Tomean",
										      "saudi" => "Saudi",
										      "scottish" => "Scottish",
										      "senegalese" => "Senegalese",
										      "serbian" => "Serbian",
										      "seychellois" => "Seychellois",
										      "sierra leonean" => "Sierra Leonean",
										      "singaporean" => "Singaporean",
										      "slovakian" => "Slovakian",
										      "slovenian" => "Slovenian",
										      "solomon islander" => "Solomon Islander",
										      "somali" => "Somali",
										      "south african" => "South African",
										      "south korean" => "South Korean",
										      "spanish" => "Spanish",
										      "sri lankan" => "Sri Lankan",
										      "sudanese" => "Sudanese",
										      "surinamer" => "Surinamer",
										      "swazi" => "Swazi",
										      "swedish" => "Swedish",
										      "swiss" => "Swiss",
										      "syrian" => "Syrian",
										      "taiwanese" => "Taiwanese",
										      "tajik" => "Tajik",
										      "tanzanian" => "Tanzanian",
										      "thai" => "Thai",
										      "togolese" => "Togolese",
										      "tongan" => "Tongan",
										      "trinidadian or tobagonian" => "Trinidadian or Tobagonian",
										      "tunisian" => "Tunisian",
										      "turkish" => "Turkish",
										      "tuvaluan" => "Tuvaluan",
										      "ugandan" => "Ugandan",
										      "ukrainian" => "Ukrainian",
										      "uruguayan" => "Uruguayan",
										      "uzbekistani" => "Uzbekistani",
										      "venezuelan" => "Venezuelan",
										      "vietnamese" => "Vietnamese",
										      "welsh" => "Welsh",
										      "yemenite" => "Yemenite",
										      "zambian" => "Zambian",
										      "zimbabwean" => "Zimbabwean");

				if (!isset($id)) $id = "brazilian";

				foreach ($nationalities as $key => $value) {

					if ($id == $key) echo '<option selected value="'. $key .'">'. $value .'</option>';
					else echo ('<option value="'. $key .'">'. $value .'</option>');
				}
}
function selectComponentsByArtistID($id){

	$dbh = openConnection();

	$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, ac.id_componente FROM artista as a
						LEFT JOIN artista_componente as ac ON a.id_artista = ac.id_artista
						WHERE a.id_artista = " . $id;

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row as $id_componente) {
			//$componentes[] = $id_componente['id_componente'];

			$query = "SELECT id_artista, nome from artista WHERE id_artista = " .$id_componente['id_componente'];

			$sth = $dbh->prepare($query);

			$response = $sth->execute();

			$row = $sth->fetch(PDO::FETCH_ASSOC);

			$componentes[$row['id_artista']] = $row['nome'];

}


	$dbh = null;
	$sth = null;

	return $componentes;
}

function selectArtistByID($id){
	$dbh = openConnection();

	$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, f.file as avatar, ac.id_componente FROM artista as a
						LEFT JOIN artista_componente as ac ON a.id_artista = ac.id_artista
						LEFT JOIN file as f ON a.avatar = f.id
						WHERE a.id_artista = " . $id;

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetch(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $row;
}

function selectComposerByMusicID($id){
	$dbh = openConnection();

	$query = "SELECT a.nome, a.id_artista FROM musica AS m
						LEFT JOIN musica_compositor AS mc ON m.id_musica = mc.id_musica
						LEFT JOIN artista AS a ON mc.id_compositor = a.id_artista
						WHERE m.id_musica = ". $_GET['id']." ORDER BY a.nome";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $rows;
}

function selectComposersByMusicID($id){
	$dbh = openConnection();

	$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, a.id_artista FROM musica as m
						LEFT JOIN musica_compositor AS mc ON m.id_musica = mc.id_musica
						LEFT JOIN artista AS a ON mc.id_compositor = a.id_artista
						WHERE m.id_musica = ".$id." ORDER BY a.nome";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	$count = $sth->rowCount();

	foreach ($row as $id_compositor) $compositores[$id_compositor['id_artista']] = $id_compositor['nome'];

	$dbh = null;
	$sth = null;

	return $compositores;
}

function selectArtistByMusicID($id){
	$dbh = openConnection();

	$query = "SELECT a.nome, a.nome_completo, a.nacionalidade, a.data_nasc, a.local_nasc, a.data_morte, a.local_morte, a.bio, a.webpage, a.tipo, a.id_artista FROM MUSICA as m
						LEFT JOIN musica_artista AS ma ON m.id_musica = ma.id_musica
						LEFT JOIN artista AS a ON ma.id_artista = a.id_artista
						WHERE m.id_musica = ".$id." ORDER BY a.nome";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row as $id_artista) $artistas[$id_artista['id_artista']] = $id_artista['nome'];

	$dbh = null;
	$sth = null;

	return $artistas;
}

function selectGenreByMusicID($id){

	$dbh = openConnection();

	$query = "SELECT DISTINCT g.nome_genero FROM genero as g
	LEFT JOIN musica_genero as mg ON g.id_genero = mg.genero
	JOIN musica as m ON mg.id_musica = m.id_musica
	WHERE m.id_musica = ". $_GET['id']." ORDER BY g.nome_genero";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	foreach ($rows as $key) {

		$generos[] = $key['nome_genero'];
	}

	return $generos;

}

function selectGenreByArtistID($id){

	$dbh = openConnection();

	$query = "SELECT DISTINCT g.nome_genero FROM genero as g
	LEFT JOIN musica_genero as mg ON g.id_genero = mg.genero
	JOIN musica as m ON mg.id_musica = m.id_musica
	JOIN musica_artista as ma ON m.id_musica = ma.id_musica
	WHERE ma.id_artista = ".$id." ORDER BY g.nome_genero";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	foreach ($rows as $key) {

		$generos[] = $key['nome_genero'];
	}

	return $generos;

}

function selectArtistsByAlbumID($id){
	$dbh = openConnection();

	$query = "SELECT a.id_artista, a.nome from artista AS a
						JOIN album_artista AS aa ON a.id_artista = aa.id_artista
						WHERE aa.id_album =" . $id;

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	//foreach ($rows as $row) $artists[] = $row['id_artista'];
	foreach ($rows as $row) $artists[$row['id_artista']] = $row['nome'];

	$dbh = null;
	$sth = null;

	return $artists;
}

function selectMusicDetailsByAlbumID($id){

	$dbh = openConnection();

	$query = "SELECT m.nome_musica, m.versao, m.ano_lanc, a.nome, a.id_artista, al.id_album, al.nome_album, am.num_faixa FROM musica as m
						LEFT JOIN musica_artista as ma
						ON m.id_musica = ma.id_musica
						LEFT JOIN artista as a
						ON ma.id_artista = a.id_artista
						JOIN album_musicas as am
						ON m.id_musica = am.id_musica
						JOIN album as al
						ON am.id_album = al.id_album
						WHERE m.id_musica = " . $_GET['id'];

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetch(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $row;
}

function selectGenresIdByAlbumID($id){

	$dbh = openConnection();

	$query = "SELECT DISTINCT g.nome_genero, g.id_genero FROM genero as g
	LEFT JOIN album_genero as ag ON g.id_genero = ag.genero
	JOIN album as al ON ag.id_album = al.id_album
	WHERE al.id_album = ". $id." ORDER BY g.nome_genero";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach ($rows as $row) {
		$generos[] = $row['id_genero'];
	}

	$dbh = null;
	$sth = null;

	return $generos;

}

function selectNextTrackNumByAlbumID($id)
{
	$dbh = openConnection();

	$query = "SELECT max(num_faixa) as next from album_musicas WHERE id_album =" . $id;

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetch(PDO::FETCH_ASSOC);

	$nexttrack = $row['next'] + 1;

	$dbh = null;
	$sth = null;

	return $nexttrack;

}

function selectGenresByAlbumID($id){

	$dbh = openConnection();

	$query = "SELECT DISTINCT g.nome_genero FROM genero as g
	LEFT JOIN album_genero as ag ON g.id_genero = ag.genero
	JOIN album as al ON ag.id_album = al.id_album
	WHERE al.id_album = ". $id." ORDER BY g.nome_genero";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach ($rows as $row) {
		$generos[] = $row['nome_genero'];
	}

	$dbh = null;
	$sth = null;

	return $generos;

}
function selectAlbumDetailsByAlbumID($id){

	$dbh = openConnection();

	$query = "SELECT al.id_album, al.nome_album, al.gravadora, al.formato, al.ano_lanc, al.producao, al.num_faixas, a.nome, a.id_artista, f.file as cover FROM album AS al
						LEFT JOIN album_artista AS aa ON al.id_album = aa.id_album
						LEFT JOIN artista AS a ON aa.id_artista = a.id_artista
						LEFT JOIN file AS f ON al.cover = f.id
						WHERE al.id_album = " . $id;

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetch(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $row;

}

function selectAlbumCovers($limit){

	$dbh = openConnection();

	$query =   "SELECT al.id_album, f.file as cover
							FROM album AS al
							LEFT JOIN album_artista AS aa ON al.id_album = aa.id_album
							LEFT JOIN artista AS a ON aa.id_artista = a.id_artista
							LEFT JOIN file AS f ON al.cover = f.id
							WHERE al.cover
							ORDER BY RAND()
							LIMIT " . $limit;

	$sth = $dbh->prepare($query);
	$response = $sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	$sth = null;

	return $rows;
}

function selectMusicListByAlbumID($id){

	$dbh = openConnection();

	$query = "SELECT m.id_musica, m.nome_musica, am.num_faixa from musica as m
						JOIN album_musicas as am ON m.id_musica = am.id_musica
						JOIN album as al ON am.id_album = al.id_album
						WHERE al.id_album = ". $id ." ORDER BY am.num_faixa";

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	//$musicList =  array();
	$dbh = null;
	$sth = null;

	return $row;

}

function selectFilesByID($type, $id){

	$dbh = openConnection();

	switch ($type) {
		case 'music':
		$query = "SELECT * FROM file AS f
							JOIN file_musica AS fm ON f.id = fm.id_file
							WHERE fm.id_musica =" . $id;
			break;
			case 'album':
			$query = "SELECT * FROM file AS f
								JOIN file_album AS fa ON f.id = fa.id_file
								WHERE fa.id_album =" . $id;
				break;
				case 'artist':
				$query = "SELECT * FROM file AS f
									JOIN file_artista AS fa ON f.id = fa.id_file
									WHERE fa.id_artista =" . $id;
					break;

		default:
			# code...
			break;
	}

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $row;

}

function countFilesByID($type, $in_params, $id){

	$dbh = openConnection();

	switch ($type) {
		case 'music':
		$query = "SELECT count(*) as qtd FROM file AS f
							JOIN file_musica AS fm ON f.id = fm.id_file
							WHERE fm.id_musica =" . $id ." AND f.type IN ". $in_params;
			break;
			case 'album':
			$query = "SELECT count(*) as qtd FROM file AS f
								JOIN file_album AS fa ON f.id = fa.id_file
								WHERE fa.id_album =" . $id ." AND f.type IN ". $in_params;
				break;
				case 'artist':
				$query = "SELECT count(*) as qtd FROM file AS f
									JOIN file_artista AS fa ON f.id = fa.id_file
									WHERE fa.id_artista =" . $id. " AND f.type IN ". $in_params;
					break;

		default:
			# code...
			break;
	}

	$sth = $dbh->prepare($query);

	$response = $sth->execute();

	$count = $sth->fetch(PDO::FETCH_ASSOC);

	$dbh = null;
	$sth = null;

	return $count;

}


function uploadDir($upload_dir, $id){
	// upload directory
	if (!empty($id)) $upload_dir = $upload_dir.$id.'/';
 	else if (!empty($_GET["id"])) $upload_dir = $upload_dir.$_GET["id"].'/';
	else $upload_dir = $upload_dir.'avatar/';

	chmod($upload_dir, 0777);

	if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

	return $upload_dir;

 }

function fileUpload($type, $dbh, $userfile, $fileExt, $fileSize, $category){

		 switch ($type) {
		 	case 'music':
				$sth = $dbh->prepare('INSERT INTO file(file,type,size) VALUES(:ufile, :ext, :size);
			 												 INSERT INTO file_musica(id_file,id_musica,category) VALUES(LAST_INSERT_ID(), :idmusica, :category)');
			 $sth->bindParam(':ufile',$userfile);
			 $sth->bindParam(':ext',$fileExt);
			 $sth->bindParam(':size',$fileSize);
			 $sth->bindParam(':idmusica',$_GET['id']);
			 $sth->bindParam(':category',$category);
		 		break;

			case 'artist':
				$sth = $dbh->prepare('INSERT INTO file(file,type,size) VALUES(:ufile, :ext, :size);
				 												 INSERT INTO file_artista(id_file,id_artista,category) VALUES(LAST_INSERT_ID(), :idartista, :category)');
			 $sth->bindParam(':ufile',$userfile);
			 $sth->bindParam(':ext',$fileExt);
			 $sth->bindParam(':size',$fileSize);
			 $sth->bindParam(':idartista',$_GET['id']);
			 $sth->bindParam(':category',$category);
			 		break;

			case 'album':
			$sth = $dbh->prepare('INSERT INTO file(file,type,size) VALUES(:ufile, :ext, :size);
		 												 INSERT INTO file_album(id_file,id_album,category) VALUES(LAST_INSERT_ID(), :idalbum, :category)');
		  $sth->bindParam(':ufile',$userfile);
		  $sth->bindParam(':ext',$fileExt);
		  $sth->bindParam(':size',$fileSize);
		  $sth->bindParam(':idalbum',$_GET['id']);
		  $sth->bindParam(':category',$category);
				break;

		 	default:
		 		# code...
		 		break;
		 }

		 return $sth;
}

 function modalUploadFile(){

	 echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		 <div class="modal-dialog" role="document">
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <h4 class="modal-title" id="myModalLabel">Adicionar arquivo</h4>
				 </div>
				 <div class="modal-body">
					 <div class="container">
							<form method="post" enctype="multipart/form-data">

					<div class="col-md-5">


									 <div class="form-group">
										 <label class="control-label">Descrição</label>
										 <div class="col-12">
											<input class="form-control" type="text" name="file_desc" placeholder="Breve descrição do arquivo" value="" />
										</div>
									</div>

									<div class="form-group">
									 <label class="control-label">Arquivo</label>
									 <div class="col-12">
											<input class="input-group" type="file" name="user_file" accept="media_type" />
										</div>
									</div>
					</div>

						</div>
				 </div>
				 <div class="modal-footer">
					 <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					 <button type="submit" name="btnsave" class="btn btn-primary">Enviar</button>
				 </div>
				 </form>
			 </div>
		 </div>
	 </div>';
 }



function navBar(){
	echo '<nav class="navbar navbar-default">
					<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="index.php">MusicDatabase</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Artistas<span class="caret"></span></a>
								<ul class="dropdown-menu">
								<li><a href="searchartist.php">buscar artista</a></li>
								<li role="separator" class="divider"></li>
									<li><a href="addartistsolo.php">adicionar artista solo</a></li>
									<li><a href="addartist.php">adicionar banda/grupo</a></li>
								</ul>
							</li>
							<li class="dropdown"><a href="searchmusic.php">Música <span class="sr-only">(current)</span></a></li>
							<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Album<span class="caret"></span></a>
							<ul class="dropdown-menu">
							<li><a href="searchalbum.php">buscar álbum</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="addalbum.php">adicionar álbum</a></li>
							</ul>
							</li>';
							if (isset($_SESSION['usuario'])) echo '<li class="dropdown"><a href="addgenre.php">Gêneros <span class="sr-only">(current)</span></a></li>';


						echo '</ul>

						<ul class="nav navbar-nav navbar-right">';
							if (isset($_SESSION['usuario'])) echo '<p class="navbar-text">Modo administrador</p>';
							echo '<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
</span></a>
								<ul class="dropdown-menu">';
								if (isset($_SESSION['usuario'])) echo '
								<li><a href="logout.php">Sair</a></li>';
								else echo '<li><a href="login.php">Entrar</a></li>';
								echo '
								</ul>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
					</nav>';
}

function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "um", "uma", "e","é", "I", "II", "III", "IV", "V", "VI"))
	 {
			 /*
				* Exceptions in lower case are words you don't want converted
				* Exceptions all in upper case are any words you don't want converted to title case
				*   but should be converted to upper case, e.g.:
				*   king henry viii or king henry Viii should be King Henry VIII
				*/
			 $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
			 foreach ($delimiters as $dlnr => $delimiter) {
					 $words = explode($delimiter, $string);
					 $newwords = array();
					 foreach ($words as $wordnr => $word) {
							 if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
									 // check exceptions list for any words that should be in upper case
									 $word = mb_strtoupper($word, "UTF-8");
							 } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
									 // check exceptions list for any words that should be in upper case
									 $word = mb_strtolower($word, "UTF-8");
							 } elseif (!in_array($word, $exceptions)) {
									 // convert to uppercase (non-utf8 only)
									 $word = ucfirst($word);
							 }
							 array_push($newwords, $word);
					 }
					 $string = join($delimiter, $newwords);
			}//foreach
			return $string;
	 }

?>
