<?php	

require_once( "queryDB.php" );

function getSettings( ) {

  $tsql = "SELECT * FROM SettingsTbl ";
  
  return( queryDB( $tsql ) );
    
}

function updateRefreshRate( $refreshRate ) {

    $tsql = "UPDATE SettingsTbl " . 
        "SET [RefreshRate] = " . $refreshRate;
    
    return( queryDB( $tsql ) );
}

?>