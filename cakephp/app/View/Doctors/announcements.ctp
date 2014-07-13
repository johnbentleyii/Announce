<?php
	// Manages updates from editors to the doctors' lists
	if( $this->Session->read( 'User.edit' ) )
		$this->Html->ScriptBlock( '

				// Update a doctor\'s patient or note list
				//		doctorID 	- unique ID of the doctor updated
				//		listType 	- patientList or noteList
				//		value 		- new information for the list
				function updateList( doctorID, listType, value ) {
					
					var jsonRequest = new Object();

					jsonRequest.id = doctorID;

					// The attribute name creates the appropriate string value
					// for the Doctor Model
					if( listType == "patientList" )
						jsonRequest.patientList = value;
					else
						jsonRequest.noteList = value;
					
					jQuery.post( "/Doctors/update_list",
							JSON.stringify( jsonRequest ),
							function ( response ) {
								if( response.error ) {
									alert( "Error - " + response.error );
									console.log( "Error - " + response.error );
								}
							}
						);
				}
				
				// Callback for change to field, usually caused by change in focus
				function patientListUpdated( doctorID ) {

					updateList( doctorID, "patientList", event.currentTarget.value );
				}
				
				// Callback for Left Control and Enter keys to trigger update
				function patientListKeydown( doctorID ) {
				
					if (event.ctrlLeft || (event.keyCode == 13 ) )
						updateList( doctorID, "patientList", event.currentTarget.value );
						
					return( true );
				}

				// Callback for change to field, usually caused by change in focus
				function noteListUpdated( doctorID ) {
					updateList( doctorID, "noteList", event.currentTarget.value );
				}
				
				// Callback for Left Control and Enter keys to trigger update
				function noteListKeydown( doctorID ) {
				
					if (event.ctrlLeft || (event.keyCode == 13 ) )
						updateList( doctorID, "noteList", event.currentTarget.value );
						
					return( true );
				}
			', Array( 'inline' => false ) );
	else
		// Refreshes to keep all non-editor views current
 		$this->Html->ScriptBlock( '
			function refreshLists( response ) {
			
				if( response.error ) {
					alert( "Error - " + response.error );
					console.log( "Error - " + response.error );
				} else
					for( i=0; i<response.content.length; i++ ) {
						// Remove spaces and periods to create css friendly string
						idPrefix = "#" + (response.content[i].doctorName).replace(/ /g,"").replace(/\./g,"");
						$( idPrefix + "PatientTA").val( response.content[i].patientList );
						$( idPrefix + "NoteTA" ).val(response.content[i].noteList );
					}
			}
			
			// Send request for updated information
			function requestRefresh( ) {
				
				jQuery.post( "/Doctors/request_update",
							refreshLists
						);
			}
			
			// Attach event to the window and refresh very 5 seconds
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
		$doctorPrefix = $this->Html->make_css_friendly( $doctor['doctorName'] );
	
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
		onclick="window.open( '/Doctors/edit_filters' );">
<?php	
	if( $this->Session->read( 'User.admin' ) ) {
		echo '<input id="EditUsersBtn" class="ToolBtn" type="button" value="Edit Users" onclick="window.open( \'/Users/edit_users\' );"/>';
		echo '<input id="EditListBtn" class="ToolBtn" type="button" value="Edit Doctors" onclick="window.open( \'/Doctors/edit_doctors\' );"/>';
	}		
?>
</div>