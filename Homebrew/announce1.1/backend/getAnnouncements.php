<?php	

require_once( "queryDB.php" );

function getAnnouncements( $username ) {
	

	$tsql = "SELECT a.* FROM AnnouncementTbl a " .
		"INNER JOIN FilterTbl f " . 
		"ON (a.DoctorName LIKE f.Show) " . 
		"WHERE f.username = '" . $username . "' " .
		"ORDER BY a.SortOrder";



	return( queryDB( $tsql ) );

	
}

?>