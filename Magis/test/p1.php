<?


echo "CURRENT DIR NAME: ";
echo dirname(__FILE__);
echo "<br/>";

echo "<br/><br/>";


foreach($_SERVER as $key => $value) {
  echo $key . " : " . $value . "<br/>";
}

?>
