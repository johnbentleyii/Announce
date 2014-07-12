<?php	

require_once( "queryDB.php" );

function removeDoctor( $doctorname ) {

  $tsql = "DELETE FROM FilterTbl " .
    "WHERE Show = '" . $doctorname ."'";
  
  queryDB( $tsql );
    
  $tsql = "DELETE FROM AnnouncementTbl " .
    "WHERE DoctorName = '" . $doctorname ."'";

  return( queryDB( $tsql ) );
}

function addDoctor( $doctorname, $order ) {

  $tsql = "INSERT INTO AnnouncementTbl " . 
	  "([DoctorName], [PatientList], [NoteList], [SortOrder] ) ".
    "VALUES " .
    "('" . $doctorname . "', '', '', " . $order . ")";
    
	return( queryDB( $tsql ) );

}

function updateDoctorOrder( $doctornames ) {

  $doctors = explode( ",", $doctornames );
  $result;
  
  for( $i=0; $i<sizeof($doctors); $i++ ) {
    $tsql = "UPDATE AnnouncementTbl " . 
      "SET [SortOrder] = " . $i . " " .
      "WHERE [DoctorName] = '" . $doctors[$i] . "'";
    $result = queryDB( $tsql );
  }

  return( $result ); 
}
?>