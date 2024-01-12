<?php

class dbConnection {

	var $db, $connected, $result, $error, $nrows;

	// costruttore:
	public function __construct($db_host, $db_user, $db_pwd, $db_name, $db_port) {

		if ($db_port && $db_port != "") 
			// $db_host .= ':' . $db_port;
			$this->db = new mysqli ($db_host, $db_user, $db_pwd, $db_name, $db_port);		
		else 
			$this->db = new mysqli ($db_host, $db_user, $db_pwd, $db_name);


		if ($mysqli->connect_error) {
			$this->connected = false;
			$this->error = 'Collegamento MySQL non riuscito: ' . $this->db->connect_error;
		}
		else {
			$this->db->set_charset("utf8mb4");
			$this->connected = $db_name;
		}
	}



function check_input($valore) {
  if (is_string( $valore )) {
      $valore = str_replace( '\'', '\'\'', $valore );
      $valore = str_replace( '"', '', $valore );
  }
  return $valore;
}

	public function select($query) {

		$this->result = $this->db->query($query);

		if ($this->result) {
			$this->error = "";
			$this->nrows = $this->result->num_rows;
		}
		else {
			$this->error = $this->db->error;
			$this->nrows = 0;
			$this->result = false;
		}
		return $this->result;
	}

	public function sel_record($tabella, $condizione) {


		$this->result = $this->db->query("SELECT * FROM $tabella WHERE $condizione");

		if ($this->result) {
			$this->error = "";
			$this->nrows = $this->result->num_rows;
			$riga = $this->result->fetch_assoc();
		}
		else {
			$this->error = $this->db->error;
			$this->nrows = 0;
			$riga = null; // $riga = array() ?
		}
		return $riga;
	}

	public function sel_data($tabella, $condizione="TRUE") {

		$queryGoingToDo = "SELECT * FROM $tabella WHERE $condizione";
		//var_dump($queryGoingToDo);//Per mostrare la query (è necessario commentare perché i json siano corretti)

		$this->result = $this->db->query("SELECT * FROM $tabella WHERE $condizione");

		if ($this->result) {
			$this->error = "";
			$this->nrows = $this->result->num_rows;
		}
		else {
			$this->error = $this->db->error;
			$this->nrows = 0;
			$this->result = false;
		}
		return $this->result;
	}

	public function count_data($tabella, $condizione="TRUE") {

		$this->result = $this->db->query("SELECT count(*) FROM $tabella WHERE $condizione");

		if ($this->result) {
			$this->error = "";
			$this->nrows = $this->result->num_rows;
			$riga = $this->result->fetch_row();
			$num = $riga[0];
		}
		else {
			$this->error = $this->db->error;
			$this->nrows = 0;
			$num = 0;
		}
		return $num;
	}

	public function execute($query) {

		$this->result = $this->db->query($query);

		if ($this->result) {
			$this->error = "";
			$this->nrows = $this->db->affected_rows;
		}
		else {
			$this->error = $this->db->error;
			$this->nrows = 0;
		}
		return $this->error;
	}

	public function insert_id() {		
		return $this->db->insert_id;
	}
	
	public function close() {
		if (is_object($this->result)) $this->result->free();
		$this->db->close();
	}



public function do_update ($valori, $tabella, $keyfield, $debug=false) {

  $sql = "UPDATE $tabella SET";

  $k = 0;
  foreach ($valori as $key => $value) {
    if ($key != $keyfield) {
      $k++;
      if ($k > 1) $sql .= ",";
      $sql .= " $key = '" . $this->check_input($value) . "'";
    }
  }

  $sql .= " WHERE $keyfield= '" . $valori[$keyfield] . "' ";

  if ($debug) return $sql;
  else {

     $this->result = $this->db->query($sql);

     if ($this->result) {  // if ($this->db->error) << meglio ??
       $this->error = "";
       $this->nrows = $this->db->affected_rows;
       return true;
     }
     else {
       $this->error = "Error in do_update: " . $this->db->error;
       $this->nrows = 0;
       return false;
     }
  }

}


public function do_insert ($valori, $tabella, $keyfield, $debug=false) {

  $fields = array_keys($valori);

  $sql = "INSERT INTO $tabella (";

  $k = 0;
  foreach($fields as $field) {
    if ($field != $keyfield) {
      $k++;
      if ($k > 1) $sql .= ", ";
      $sql .= $field ;
    }
  }
  $sql .= ") VALUES (";

  $k = 0;
  foreach($fields as $field) {
    if ($field != $keyfield) {
      $k++;
      if ($k > 1) $sql .= ",";
      $sql .= " '" . $this->check_input($valori[$field]) . "'";
    }
  }
  $sql .= " ) ";


  if ($debug) return $sql;
  else {

     $this->result = $this->db->query($sql);

     if ($this->result) {  // if ($this->db->error) << meglio ??
       $this->error = "";
       $this->nrows = $this->db->affected_rows;
       $inserted = $this->db->insert_id;
       return $inserted;
     }
     else {
       $this->error = "Error in do_insert: " . $this->db->error;
       $this->nrows = 0;
       return false;
     }
  }

}


public function do_remove ($valore, $tabella, $keyfield, $debug=false) {

  $sql = "DELETE FROM $tabella WHERE $keyfield = '$valore'";

  if ($debug) return $sql;
  else {

     $this->result = $this->db->query($sql);

     if ($this->result) {  // if ($this->db->error) << meglio ??
       $this->error = "";
       $this->nrows = $this->db->affected_rows;
       return true;
     }
     else {
       $this->error = "Error in do_remove: " . $this->db->error;
       $this->nrows = 0;
       return false;
     }
  }

}

	

	

	
} // end class

?>
