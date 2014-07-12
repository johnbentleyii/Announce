<?php	

require_once( "queryDB.php" );

function getFilter( $username ) {
     
    $tsql = "SELECT [Show] FROM FilterTbl " .
                "WHERE username = '" . $username . "'";
            
    $results = queryDB( $tsql );
    
    $showAll = 0;
    if( $results[1]['Show'] == '%' )  
        $showAll = 1;
   
    $tsql = "((SELECT a.DoctorName as [DoctorName], 'True' as [Shown], a.SortOrder as [SortOrder] FROM AnnouncementTbl a " .
		        "INNER JOIN FilterTbl f " .
		        "ON (a.DoctorName LIKE f.Show) " .
		        "WHERE f.username = '" . $username . "') " .
		        "UNION " .
		        "(SELECT [DoctorName], 'False' as [Shown], [SortOrder] " .
		        "FROM " .
		        "((SELECT a.DoctorName as [DoctorName], a.SortOrder as [SortOrder] FROM AnnouncementTbl a) " .
		        "EXCEPT " .
		        "(SELECT a.DoctorName as [DoctorName], a.SortOrder as [SortOrder] FROM AnnouncementTbl a  " .
		        "INNER JOIN FilterTbl f " .
		        "ON (a.DoctorName LIKE f.Show) " .
		        "WHERE f.username = '" . $username . "')) tmp)) " .
		        "ORDER BY SortOrder";

     $results = queryDB( $tsql );
     
     $results[0]['ShowAll'] = $showAll;

     return( $results );
	
}

?>
