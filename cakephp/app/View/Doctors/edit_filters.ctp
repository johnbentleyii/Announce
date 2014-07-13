<?php

	$this->Html->ScriptBlock( '
	
		var allSelected = false;
		
		// Disables or enables the doctor checkboxes
		//	disable - true if checkbox should be disabled
		function toggleDoctorCheckboxes( disable ) {
		
			var checkboxes = $( "input[name=\'DoctorList\']" );
			
			checkboxes.each( function() {
					$( this ).prop( "disabled", disable );
				}
			);
		
			$( "#doctorListDiv" ).prop( "disabled", disable );					
		}
		
		// Callback for selecting all doctors
		function selectAll() {
		
			toggleDoctorCheckboxes( allSelected = true );
		}
		
		// Callback for choosing individual doctors
		function selectSome() {
		
			disableCheckboxes( allSeleced = false );
		}
		
		// Updates the filter list based on what is selected
		function saveFilter () {
		
			var filters = [];
			var current_user_id = ' . $this->Session->read( 'Edit.user_id' ) . ';
			var count = 0;
			
			var checkboxes = $( "input[name=\'DoctorList\']" );
			
			checkboxes.each( function() {
			
			// Add the doctor to the filter if it is check or all is selected
			if( $( this ).is( ":checked" ) || allSelected ) {
						item = {};
						item[ "user_id" ] = current_user_id;
						item[ "doctor_id" ] = $( this ).attr( "doctor_id" );
						filters.push( item );
					}
				}
			);
			
			// JSON message has Array of user_ids and doctor_ids			
			jQuery.post( "/Filters/update_filters",
						JSON.stringify( filters ),
						function ( response ) {
							if( response.error ) {
								alert( response.error );
								console.log( response.error );
							}
							else
								window.close();
						}
					);
				
		}
		
		// Finalize the view by setting the radio buttons based on
		// how the checkboxes are set in the HTML.
		function initView( ) {
			
			var checkboxes = $( "input[name=\'DoctorList\']" );
			
			// One false makes allSelected false. Ain\'t logic grand?
			checkboxes.each( function() {
					allSelected = allSelected && ($( this ).is( ":checked" ) );
				}
			);
			
			disableCheckboxes( allSelected );
			
			$( "#ShowAllRB" ).attr( "checked", allSelected );	
			$( "#SelectRB" ).attr( "checked", !allSelected );
		}
		
		
		// Attach event to the body
		window.onload = initView;		
	', Array( 'inline' => false ) );

?>

<?php
	echo $this->Html->tag( 'h1', 
						'Editing Filter for ' . $this->Session->read( 'Edit.username' )
			);
?>

<div id="FilterDiv">

	<!-- Radio Buttons are used to toggle between all or a specific set of doctors -->
	<p><input id="ShowAllRB" type="radio" name="Filter" value="showall" onclick="selectAll();" />Show All Doctors</p>
	<p><input id="SelectRB" type="radio" name="Filter" value="select" onclick="selectSome();" />Show Doctors Selected Below:</p>
	<div id="DoctorListDiv" style="display: block">
<?php

	
	foreach( $all_doctors as $doctor ) {
		$doctorPrefix = $this->Html->make_css_friendly( $doctor['Doctor']['doctorName'] );
		echo '<p id="' . $doctorPrefix . 'P" class="DoctorList">';

		// A Doctor is selected if it is in the visible list.
		$shown = in_array( $doctor['Doctor']['id'], $visible_doctor_ids, true );
		
		// Create a checkbox for each doctor
		echo $this->Html->tag( 'input', '', Array (
					'id' => $doctorPrefix . 'CB',
					'doctor_id' => $doctor['Doctor']['id'],
					'name' => 'DoctorList',
					'type' => 'checkbox',
					'checked' => $shown
				)
		);
				
		echo $this->Html->tag( 'span', $doctor['Doctor']['doctorName'], Array (
					'id' => $doctorPrefix . 'Span'
				)
		);
					
		echo '</p>'; // DoctorList paragraph
	}
?>
	</div> <!-- DoctorList Div -->
</div> <!-- FilterDive -->

<div id="ToolsDiv">
	<input id="FilterBtn" type="button" value="OK" onclick="saveFilter();"/>
	<input id="FilterBtn" type="button" value="Cancel" onclick="window.close()"/>
</div>