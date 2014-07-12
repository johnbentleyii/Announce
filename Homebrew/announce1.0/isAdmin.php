<?php	

require_once( "queryDB.php" );

function isAdmin( $username ) {
	

	$tsql = "SELECT Admin FROM UserTbl " . 
		"WHERE username = '" . $username . "' ";

	return( queryDB( $tsql ) );

	
}

?>