<?php	

require_once( "queryDB.php" );

function getDoctors( ) {
	

	$tsql = "SELECT [DoctorName], [SortOrder] FROM AnnouncementTbl ORDER BY [SortOrder]";

  return( queryDB( $tsql ) );
}

?>