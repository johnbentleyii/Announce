<?xml version="1.0" standalone="yes"?>
<response>
  <?php
  
  include( "getUsers.php" );
	include( "getAnnouncements.php" );
	include( "getPermissions.php" );
	include( "getFilter.php" );
	include( "getDoctors.php" );
	include( "updateList.php" );
	include( "updateFilter.php" );
  include( "manageDoctors.php" );
  include( "manageUsers.php" );
  include( "manageSettings.php" );
  
	function publishResults( $resultType, $results ) {
       
        header( 'Content-type: text/xml' );
        header( 'Cache-Control: no-cache' );
        header( 'Pragma: no-cache' );

    echo( '<' . $resultType .'List>' );
		foreach( $results as $key => $row ) {
			echo( "<" . $resultType . ">\n");
			foreach( $row as $column => $value ) {
			    echo( "<" . $column . ">");
				echo( $value );
                echo( "</" . $column . ">\n");
			}
			echo( "</" . $resultType . ">\n");
		}
        echo( "</" . $resultType ."List>\n" );
	}


	if( isset( $_REQUEST['requestType'] ) ) {

		$requestType = $_REQUEST['requestType'];

		switch( $requestType ) {
      case 'getSettings':
				publishResults( 'Settings', getSettings( ) );
				break;            			
      case 'getUsers':
				publishResults( 'User', getUsers() );
				break;
			case 'getAnnouncements':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'Announcement', 
						getAnnouncements( $_REQUEST['username'] ) );
				}
				break;
			case 'getPermissions':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'Permission', 
						getPermissions( $_REQUEST['username'] ) );
				}
				break;
			case 'getFilter':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'Filter', getFilter( $_REQUEST['username'] ) );
				}
				break;
			case 'getDoctors':
				publishResults( 'Doctor', getDoctors( ) );
				break;
            case 'updatePatientList':
				if( ( isset( $_REQUEST['doctorname'] ) ) && ( isset( $_REQUEST['patientlist'] ) ) ) {
					publishResults( 'UpdateResults', updatePatientList( $_REQUEST['doctorname'], $_REQUEST['patientlist'] ) );
				}
				break;
			case 'updateNoteList':
				if( ( isset( $_REQUEST['doctorname'] ) ) && ( isset( $_REQUEST['notelist'] ) ) ) {
					publishResults( 'UpdateResults', updateNoteList( $_REQUEST['doctorname'],$_REQUEST['notelist'] ) );
				}
				break;
            case 'updateFilterShowAll':
				if( isset( $_REQUEST['username'] ) ) {
    				publishResults( 'UpdateResults', updateFilterShowAll( $_REQUEST['username'] ) );
				}
				break;
            case 'updateFilterSelected':
				if( ( isset( $_REQUEST['username'] ) ) && ( isset( $_REQUEST['showList'] ) ) ) {
    				publishResults( 'UpdateResults', updateFilterSelected( $_REQUEST['username'], explode( ",", $_REQUEST['showList'] ) ) );
				}
				break;				
			case 'removeDoctor':
				if( isset( $_REQUEST['doctorname'] ) ) {
					publishResults( 'UpdateResults', removeDoctor( $_REQUEST['doctorname'] ) );
				}
				break;
			case 'addDoctor':
				if( ( isset( $_REQUEST['doctorname'] ) ) && ( isset( $_REQUEST['order'] ) ) ) {
					publishResults( 'UpdateResults', addDoctor( $_REQUEST['doctorname'],$_REQUEST['order'] ) );
				}
				break;            	
			case 'addUser':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'UpdateResults', addUser( $_REQUEST['username'] ) );
				}
				break;            	
      case 'removeUser':
				if( isset( $_REQUEST['username'] ) ) {
					publishResults( 'UpdateResults', removeUser( $_REQUEST['username'] ) );
				}
				break;
      case 'updateUser':
				if( ( isset( $_REQUEST['username'] ) ) && ( isset( $_REQUEST['edit'] ) ) && ( isset( $_REQUEST['admin'] ) ) ) {
					publishResults( 'UpdateResults', updateUser( $_REQUEST['username'], $_REQUEST['edit'], $_REQUEST['admin'] ) );
				}
				break;            			
      case 'updateRefreshRate':
			  if( isset( $_REQUEST['refreshrate'] ) ) {
					publishResults( 'UpdateResults', refreshRate( $_REQUEST['refreshRate'] ) );
				}
				break;            			
			}
	}
?>
</response>