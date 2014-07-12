<?php	

	function queryDB( $tsql ) {


		$connectionInfo = array( "UID" => "rps_access",
					"PWD" => "rp5acc3$$",
					"Database" => "RPSAnnounceDB" );

	
		$conn = sqlsrv_connect( "(local)\SQLEXPRESS", $connectionInfo );
		
		if( $conn == false ) {
			echo( "Unable to connect database.<br/>" );
			die( print_r( sqlsrv_errors(), true ) );
		}


		$stmt = sqlsrv_query( $conn, $tsql );

		if( $stmt == false ) {
			echo( "The following query erred out:<br/>" );
			echo( $tsql . "<br/>" );
			die( print_r( sqlsrv_errors(), true ) );
		}

		$results = Array();
		$resultC = 1;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
			$results[$resultC] = $row;
			$resultC++;
		}

    sqlsrv_free_stmt( $stmt );
    sqlsrv_close( $conn );
		return( $results );

	}
?>