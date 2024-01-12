<?php

// connection configuration:
$host_name = '89.46.111.82';
$database = 'Sql1301987_3';
$user_name = 'Sql1301987';
$password = '737708q724';
// $port = '27776';

// connect :
//$host_name .= ':' . $port;
//$mysqli = new mysqli($host_name, $user_name, $password, $database, $port);
$mysqli = new mysqli($host_name, $user_name, $password, $database);


if ($mysqli->connect_error) {
   die('<p>Collegamento al server MySQL non riuscito: '. $mysqli->connect_error .'</p>');
   }
else 
{

   echo "OK connected <br>";




$sql = "select * from test"; 
// $sql .= " where valore > 10";

$res = $mysqli->query($sql) or die("errore");
$output = "";
while ($riga = mysqli_fetch_array($res)) {

   $output .= $riga["nome"] . " : " . $riga["valore"] . "<br>";
}

echo $output;

}

?>