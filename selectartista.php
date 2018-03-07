<!DOCTYPE HTML>
<html lang="pt_BR">
	<head>
		<meta charset="UTF-8">
		<title>Add Musica</title>
	</head>
	<body>	
<?php

require_once( 'connection.php' );

$query = "SELECT id_artista, nome from artista ORDER BY nome";

$result = mysqli_query( $link, $query ) or die(mysqli_error());

//if (!mysqli_num_rows($result)) 
//			return false 
//		else {
			echo '<p>Artista: ';
		    echo '<select name="artista">';
			while ($row = mysqli_fetch_assoc( $result )) {
				$data[] = $row;
			}
			foreach ( $data as $artista ) {
				echo '<option value="'.$artista[ 'id_artista' ].'">'.$artista[ 'nome' ].'</option>';
				
			}
			echo '</select>'; 
			echo '</p>';
			//var_dump($result);
			//print_r($data);
			mysqli_free_result( $result );

//			return 'FOI!';		
//		}

//		return $result;


//var_dump($result);




mysqli_close($link);

?>

</body>
</html>