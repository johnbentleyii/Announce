<?php

	include( "getAnnouncements.php" );
	include( "isAdmin.php" );
	include( "updateList.php" );

	function publishResults( $resultType, $results ) {

        header( 'Content-Type: text/xml' );
		echo( '<?xml version=\'1.0\' ?>' );

        echo( '<' . $resultType .'List>' );
		foreach( $results as $key => $row ) {
			echo( "<" . $resultType . ">");
			foreach( $row as $column => $value ) {
				echo( "<" . $column . ">");
				echo( $value );
				echo( "</" . $column . ">");
			}
			echo( "</" . $resultType . ">");
		}
        echo( '</' . $resultType .'List>' );
	}

	
	if( isset( $_REQUEST['requestType'] ) ) {

		$requestType = $_REQUEST['requestType'];

		switch( $requestType ) {
			case 'getAnnouncements':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'Announcement', 
						getAnnouncements( $_REQUEST['username'] ) );
				}
				break;
			case 'isAdmin':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'User', 
						isAdmin( $_REQUEST['username'] ) );
				}
				break;
            case 'updatePatientList':
				if( ( isset( $_REQUEST['doctorname'] ) ) && ( isset( $_REQUEST['patientlist'] ) ) ) {
					updatePatientList( $_REQUEST['doctorname'], isset( $_REQUEST['patientlist'] ) );
				}
				break;
			case 'updateNoteList':
				if( ( isset( $_REQUEST['doctorname'] ) ) && ( isset( $_REQUEST['notelist'] ) ) ) {
					updatePatientList( $_REQUEST['doctorname'], isset( $_REQUEST['notelist'] ) );
				}
				break;

		}
	}
?>