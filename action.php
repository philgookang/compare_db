<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

    include('Postman.php');

    $host       = $_POST['host'];
    $user       = $_POST['user'];
    $password   = $_POST['password'];
    $database   = $_POST['database'];
    $port       = $_POST['port'];

    $p = Postman::init( $host, $user, $password, $database, $port );

    // check if connection was made
    if ( $p == null ) {
        echo json_encode(array( 'code' => 404, 'msg' => 'unable to connect to database' ));
        return;
    }

    // -------------------------------------------------

    // 1. get table list
    $table_list = $p->execute("SHOW TABLES;");

    // 2. get table column
    $return_list = array();

    foreach($table_list as $table) {

        $column_result = $p->execute("DESCRIBE `".$table['Tables_in_stock']."`;");

		$attribute_data = array();
		while ($row = $column_result->fetch_array(MYSQLI_ASSOC)) {
			$object = new stdClass();
			foreach ($row as $key => $value) { $object->$key = $value; }
			array_push($attribute_data, $object);
		}

        // ============

        $index_data = $p->returnDataList("SHOW INDEX FROM `".$table['Tables_in_stock']."`;");

        $index_summary = array();
        foreach($index_data as $index_item) {

            if (!isset($index_summary[$index_item->Key_name])) {
                $index_summary[$index_item->Key_name] = array(
                    'type'          => $index_item->Index_type,
                    'unique'        => ($index_item->Non_unique) ? 'Yes' : 'No',
                    'packed'        => ($index_item->Packed == null) ? 'No' : 'Yes',
                    'column'        => array(
                        array(
                            'name'          => $index_item->Column_name,
                            'cardinality'   => $index_item->Cardinality,
                            'collation'     => $index_item->Collation,
                            'null'          => $index_item->Null,
                            'comment'       => $index_item->Comment
                        )
                    )
                );
                continue;
            }

            array_push($index_summary[$index_item->Key_name]['column'], array(
                'name'          => $index_item->Column_name,
                'cardinality'   => $index_item->Cardinality,
                'collation'     => $index_item->Collation,
                'null'          => $index_item->Null,
                'comment'       => $index_item->Comment
            ));
        }

        // ============

        array_push($return_list, array( 'name' => $table['Tables_in_stock'], 'attribute' => $attribute_data, 'index' => $index_summary ));
    }

    // -------------------------------------------------

    function unload($file, $args) {
        // Make values in the associative array easier to access by extracting them
        extract($args);
        // buffer the output (including the file is "output")
        ob_start();
        include $file;
        $html =  ob_get_clean();
        return $html;
    }

    echo json_encode(array( 'code' => 200, 'data' => $return_list, 'html' => unload('./view.php', array('table_list' => $return_list, 'index_list' => $index_summary)) ));
