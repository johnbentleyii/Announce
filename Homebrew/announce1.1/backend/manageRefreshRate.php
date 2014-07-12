<?php	

require_once( "queryDB.php" );

function getSettings( ) {

  $tsql = "SELECT * FROM SettingsTbl ";
  
  queryDB( $tsql );
    
}

function updateRefreshRate( $refreshRate ) {

    $tsql = "UPDATE SettingsTbl " . 
        "SET [RefreshRate] = " . $refrshRate . ";
    
    return( queryDB( $tsql ) );
}

?>