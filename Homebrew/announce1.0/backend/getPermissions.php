<?php	

require_once( "queryDB.php" );

function getPermissions( $username ) {
	

	$tsql = "SELECT * FROM UserTbl " . 
		"WHERE username = '" . $username . "' ";

	return( queryDB( $tsql ) );
	
}

?>