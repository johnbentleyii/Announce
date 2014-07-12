<?php	

require_once( "queryDB.php" );

function getUsernames( ) {
	

	$tsql = "SELECT [UserName], [Edit], [Admin] FROM UserTbl ORDER BY [UserName]";

  return( queryDB( $tsql ) );
}

?>