<?php	

require_once( "queryDB.php" );

function removeUser( $username ) {

  $tsql = "DELETE FROM FilterTbl " .
    "WHERE UserName = '" . $username ."'";
  
  queryDB( $tsql );
    
  $tsql = "DELETE FROM UserTbl " .
    "WHERE UserName = '" . $username ."'";

  return( queryDB( $tsql ) );
}

function addUser( $username ) {

    $tsql = "INSERT INTO FilterTbl " . 
	    "([UserName], [Show] ) ".
        "VALUES " .
        "('" . $username . "', '%' )";

    queryDB( $tsql );
  
    $tsql = "INSERT INTO UserTbl " . 
	    "([UserName], [Edit], [Admin] ) ".
        "VALUES " .
        "('" . $username . "', 0, 0 )";
    
    return( queryDB( $tsql ) );

}

function updateUser( $username, $edit, $admin ) {

    $tsql = "UPDATE UserTbl " . 
        "SET [Edit] = " . $edit . ", " .
        "[Admin] = " . $admin . " ".
        "WHERE [UserName] = '" . $username . "'";
    
    return( queryDB( $tsql ) );

}

?>