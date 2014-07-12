<?php	

require_once( "queryDB.php" );

function getUsers( ) {
	

	$tsql = "SELECT [UserName], [Edit], [Admin] FROM UserTbl ORDER BY [UserName]";

  return( queryDB( $tsql ) );
}

?>