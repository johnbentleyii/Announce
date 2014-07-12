<?php
	// Finalizes the initial view
	$this->Html->ScriptBlock( '
	
		var allSelected = false;
		
		function disableCheckboxes( disable ) {
		
			var checkboxes = $( "input[name=\'DoctorList\']" );
			
			checkboxes.each( function() {
					$( this ).prop( "disabled", disable );
				}
			);
		
			$( "#doctorListDiv" ).prop( "disabled", disable );					
		}
		
		function selectAll() {
		
			disableCheckboxes( allSelected = true );
		}
		
		function selectSome() {
		
			disableCheckboxes( allSeleced = false );
		}
		
		function saveFilter () {
		
			var filters = [];
			var current_user_id = ' . $this->Session->read( 'Edit.user_id' ) . ';
			var count = 0;
			
			var checkboxes = $( "input[name=\'DoctorList\']" );
			
			checkboxes.each( function() {
					if( $( this ).is( ":checked" ) || allSelected ) {
						item = {};
						item[ "user_id" ] = current_user_id;
						item[ "doctor_id" ] = $( this ).attr( "doctor_id" );
						filters.push( item );
					}
				}
			);
			
			jQuery.post( "/Filters/update_filters",
						JSON.stringify( filters ),
						function ( response ) {
							if( response.error ) {
								alert( response.error );
								console.log( response.error );
							} else
								window.open( "/Doctors/", "_self" );	
						}
					);
				
		}
		
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

<div id="FilterDiv">
	<p><input id="ShowAllRB" type="radio" name="Filter" value="showall" onclick="selectAll();" />Show All Doctors</p>
	<p><input id="SelectRB" type="radio" name="Filter" value="select" onclick="selectSome();" />Show Doctors Selected Below:</p>
	<div id="DoctorListDiv" style="display: block">
<?php

	
	foreach( $all_doctors as $doctor ) {
		$doctorPrefix = str_replace( '.', '', str_replace( ' ', '', 	
												$doctor['Doctor']['doctorName'] ) );
		echo '<p id="' . $doctorPrefix . 'P" class="DoctorList">';
		
		$shown = in_array( $doctor['Doctor']['id'], $visible_doctor_ids, true );
		
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