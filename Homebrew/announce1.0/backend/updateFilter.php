<?php	

require_once( "queryDB.php" );

function clearFilter( $username ) {

  $tsql = "DELETE FROM FilterTbl " .
    "WHERE UserName = '" . $username ."'";

  return( queryDB( $tsql ) );
}

function updateFilterShowAll( $username ) {

  clearFilter( $username );
  
  $tsql = "INSERT INTO FilterTbl " . 
	  "([UserName], [Show]) ".
    "VALUES " .
    "('" . $username . "', '%')";
    
	return( queryDB( $tsql ) );

}


function updateFilterSelected( $username, $showList ) {

  clearFilter( $username );
	
  $tsql =   $tsql = "INSERT INTO FilterTbl " . 
	  "([UserName], [Show]) ".
    "VALUES ";
  
  foreach( $showList as $name ) {
    $tsql .= "('" . $username . "', '" . urldecode($name) . "'), ";
  }

  $tsql = substr( $tsql, 0, strlen($tsql)-2 );
  
  return( queryDB( $tsql ) );
}

?>