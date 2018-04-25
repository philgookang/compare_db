<?php

class Postman {

	// postman singleton
	static $singleton;

	// mysql connection
	var $mysqlConnection;

	public static function init( $host , $user, $pass, $database, $port ) {
		if ( Postman::$singleton == null) {

			// create new object
			Postman::$singleton = new Postman();

			// create connection
			Postman::$singleton->connect( $host , $user, $pass, $database, $port );
		}

		return Postman::$singleton;
	}

	public function connect( $host , $user, $pass, $database, $port ) {

		if ($this->mysqlConnection  == null ) {

			// init mysql connection
			$this->mysqlConnection = mysqli_init();

			// set the timout time to only 5 seconds
			mysqli_options($this->mysqlConnection, MYSQLI_OPT_CONNECT_TIMEOUT, 5);

			// create connection
			if(mysqli_real_connect($this->mysqlConnection, $host, $user, $pass, $database, $port)) {
				mysqli_set_charset( $this->mysqlConnection, " utf8mb4 " );
				mysqli_query($this->mysqlConnection, 'SET NAMES utf8mb4 ' );
			} else {
				return null;
			}
		}

		return $this->mysqlConnection;
	}

	function db_bind_param(&$stmt, $params) {
		if (count($params) <= 0) { return; }
		$f = array($stmt, "bind_param");
		return call_user_func_array($f, $params);
	}

	function __destruct() {
		if ( $this->mysqlConnection != null ) {
			@mysqli_close($this->mysqlConnection);
		}
	}

	// -------------------------------------------------

	function execute($query, $params= array(), $return_insert_idx = false) {

		$stmt = $this->mysqlConnection->stmt_init();
		$stmt = $this->mysqlConnection->prepare($query);

		$this->db_bind_param($stmt, $params);
		$result = $stmt->execute();

		if (!$result) {
			exit(json_encode( array( 'code' => '400', 'msg' => $this->mysqlConnection->error, 'sql' => $query3 ) ));
		}

		$result = $stmt->get_result();

		if ( $return_insert_idx ) {
			return $stmt->insert_id;
		} else {
			return $result;
		}
	}

	function returnDataList($query, $params = array()) {

		$result = $this->execute($query, $params);

		$return_data = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$object = new stdClass();
			foreach ($row as $key => $value) {
				$object->$key = $value;
			}
			array_push($return_data, $object);
		}

		return $return_data;
	}
}
