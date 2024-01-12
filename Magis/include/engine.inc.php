<?


function engine_auth($ruoli) {  // verifica se il ruolo in sessione è uno di quelli autorizzati
$info = $_SESSION["userinfo"];
if (in_array($info["ruolo"], $ruoli)) return true;
else {
	header("Location: noauth.php"); // rinvia ad una pagina di "accesso non consentito" (con link alla pagina di login) 
	exit;
  }
}

function engine_logged() {  // verifica se un ruolo diverso da guest è presente in sessione
$info = $_SESSION["userinfo"];
if ( $info["ruolo"] and ($info["ruolo"] != "guest")) return true;
else return false;
}


// ..............................................

function generate_elem ($elem_tag, $elem_cont, $elem_css = "", $elem_attrib = "") {
	
	if ($elem_css) $tag_classes = ' class="'.$elem_css.'"';
	else $tag_classes = '';
	
	if ($elem_attrib) $tag_attributes = ' '.$elem_attrib; 
	else $tag_attributes = ''; 

	$elemento = '<' . $elem_tag . $tag_classes . $tag_attributes . '>';
	$elemento .= $elem_cont;
	$elemento .= '</' . $elem_tag . '>';
	
	return $elemento;	
}

// ..............................................

function json_element ($name, $type, $pre, $content, $post) {

	// funziona in abbinamento con il javascript xassegnare()

	$elemento = array();
	$elemento["tipo"] = $type; 
	$elemento["nome"] = $name;
	$elemento["cont"] = $pre . $content . $post;
	
	return $elemento;
}

function add_json ($element_id, $element_html) {

	global $json_out;
	$json_out[] = json_element ($element_id, "htmlet", "", $element_html, "");
}

function add_script ($jscode) {

	global $json_out;
	$json_out[] = json_element ("jscript", "script", "", $jscode, "");
}



$json_out = array();

?>
