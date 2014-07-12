<?php
	// Manages updates from editors to the doctors' lists
	$this->Html->ScriptBlock( '
	
			function updateList( doctorID, listType, value ) {
				
				var jsonRequest = new Object();

				jsonRequest.id = doctorID;

				if( listType == "patientList" )
					jsonRequest.patientList = value;
				else
					jsonRequest.noteList = value;
				
		
				jQuery.post( "/Doctors/update_list",
						JSON.stringify( jsonRequest ),
						function ( response ) {
							if( response.error ) {
								alert( response.error );
								console.log( response.error );
							}
						}
					);
			}
			
			function patientListUpdated( doctorID ) {

				updateList( doctorID, "patientList", event.currentTarget.value );
			}
			
			function patientListKeydown( doctorID ) {
			
				if (event.ctrlLeft || (event.keyCode == 13 ) )
					updateList( doctorID, "patientList", event.currentTarget.value );
					
				return( true );
			}
			
			function noteListUpdated( doctorID ) {
				updateList( doctorID, "noteList", event.currentTarget.value );
			}
			
			function noteListKeydown( doctorID ) {
			
				if (event.ctrlLeft || (event.keyCode == 13 ) )
					updateList( doctorID, "noteList", event.currentTarget.value );
					
				return( true );
			}
		', Array( 'inline' => false ) );
		
	// Refreshes to keep all non-editor views current
	$this->Html->ScriptBlock( '
		function refreshLists( response ) {
		
			if( response.error ) {
				alert( response.error );
				console.log( response.error );
			} else
				for( i=0; i<response.content.length; i++ ) {
					idPrefix = "#" + (response.content[i].doctorName).replace(/ /g,"").replace(/\./g,"");
					$( idPrefix + "PatientTA").val( response.content[i].patientList );
					$( idPrefix + "NoteTA" ).val(response.content[i].noteList );
				}
		}
		
		function requestRefresh( ) {
			
			jQuery.post( "/Doctors/request_update",
						refreshLists
					);
		}
		
		// Attach event to the body
		window.onload = function() {
			setInterval( requestRefresh, 5000 );
		}		
	', Array( 'inline' => false ) );
?>

<div id="AnnouncementDiv" >
<?php
	// Each doctor is given an equal size column for display on a single page.
	// 95% is used to all some space based on browser inconsistency.
	$sizePercent = ( count( $doctors ) )? ( (1/count( $doctors ) ) * 95 ) : ( 0 );
	
	foreach( $doctors as $doctor ) {
		$doctorPrefix = str_replace( '.', '', str_replace( ' ', '', $doctor['doctorName'] ) );
	
		// The AnnounceDiv provides consistent sizing across all columns.
		
		echo '<div id="' . $doctorPrefix . 'AnnounceDiv" class="AnnounceDiv"';
		echo ' style="width: ' . $sizePercent . '%">';
		
		// The InnerAnnounceDiv creates consistent sizing among column components.
		echo '<div id="' . $doctorPrefix . 'InnerAnnounceDiv" class="InnerAnnounceDiv">';
		
		// Display the name of the Doctor
		echo $this->Html->para( 'DoctorP', $doctor['doctorName'], array (
													'id' => $doctorPrefix . 'DoctorP'
												)
		);
	
		// Set up a Textarea for the list of pending patients
		$taOptions = array( 'id' => $doctorPrefix . 'PatientTA',
							'class' => 'PatientTA'
					);
		//Add update actions if the user is an editor, else make read only
		if( $this->Session->read( 'User.edit' ) ) {
			$taOptions[ 'onchange' ] = 'patientListUpdated( ' . $doctor['id'] . ');';
			$taOptions[ 'onkeydown' ] = 'patientListKeydown( ' . $doctor['id'] . ');';
		} else {
			$taOptions['readonly'] = 'true';
		}
		
		echo $this->Html->tag( 'textarea', $doctor['patientList'], $taOptions );
	
		
		// Set up a Textarea for the list of notes for the doctor
		$taOptions = array( 'id' => $doctorPrefix . 'NoteTA',
							'class' => 'NoteTA'
					);
		//Add update actions if the user is an editor, else make read only
		if( $this->Session->read( 'User.edit' ) ) {
			$taOptions[ 'onchange' ] = 'noteListUpdated( ' . $doctor['id'] . ');';
			$taOptions[ 'onkeydown' ] = 'noteListKeydown( ' . $doctor['id'] . ');';
		} else {
			$taOptions['readonly'] = 'true';
		}
		
		echo $this->Html->tag( 'textarea', $doctor['noteList'], $taOptions );
		
		echo '</div>'; // InnerAnnounceDiv
		echo '</div>'; // AnnounceDiv
	}
?>
</div>
<div id="ToolsDiv">
<input id="FilterBtn" class="ToolBtn" type="button" value="Edit Filter" 		
		onclick="window.open( '/Doctors/edit_filters', '_self' );">
<?php
	if( $this->Session->read( 'User.admin' ) )
		echo '<input id="AdminBtn" class="ToolBtn" type="button" value="Administer App" onclick="User/app_admin;"/>'	
?>
</div>