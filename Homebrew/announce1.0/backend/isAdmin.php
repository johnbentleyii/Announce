<?php	

require_once( "queryDB.php" );

function getPermissions( $username ) {
	

	$tsql = "SELECT Edit, Admin FROM UserTbl " . 
		"WHERE username = '" . $username . "' ";

	return( queryDB( $tsql ) );
	
}

?>