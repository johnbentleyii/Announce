<?php
	$this->Html->ScriptBlock( '


	function addDoctorKeyPress() {

		return( event.returnValue = (((event.keyCode >= 48) && (event.keyCode <= 57)) ||
							((event.keyCode >= 65) && (event.keyCode <= 90)) ||
							((event.keyCode >= 97) && (event.keyCode <= 122)) ||
							(event.keyCode == 32) )
		);	
	}

	function trimBlanks(str) {

		var begin = 0, end = str.length - 1;

		while ((begin < str.length) && (str[begin] == " ")) {
			begin++;
		}

		while ((end >= 0) && (str[end] == " ")) {
			end--;
		}

		return (str.substr(begin, end - begin + 1));
	}

	function addDoctor() {

		var doctor = trimBlanks( document.getElementById("NewDoctorFld").value );
		
		var jsonRequest = new Object();
		
		jsonRequest.doctorName = $( "#NewDoctorFld" ).val();
		jsonRequest.patientList = "";
		jsonRequest.noteList = "";
		jsonRequest.sortOrder = ' . ( count( $doctors ) + 1 ) . '; 
		
		jQuery.post( "/Doctors/add_doctor",
				JSON.stringify( jsonRequest ),
				function( response ) {
					if( response.success )
						location.reload();
					else {
						alert( "Error - " + response.error );
						console.log( "Error - " + response.error );
					}
				}
		);
	}

	function doctorSelected() {

		var selectedIndex = $( "#DoctorSelect" ).prop( "selectedIndex" );

		$( "#MoveUpBtn" ).prop( "disabled", ( ( selectedIndex == null ) || ( selectedIndex == 0 ) ) );
		$( "#MoveDownBtn").prop( "disabled", ( ( selectedIndex == null ) || ( selectedIndex == ($( "#DoctorSelect" ).children().length - 1) ) ) );    
	}

	

	function moveUp() {

		var doctorSelect = $( "#DoctorSelect");
		var selectedIndex = doctorSelect.selectedIndex;

		doctorSelect.hide();
		
		selected = $( "[value=\'" + doctorSelect.val() + "\']" );
		priorSelected = selected.prev();
	
		//selected.remove();
		selected.insertBefore( priorSelected ); 
		
		doctorSelect.selectedIndex = selectedIndex - 1;
		doctorSelected();
		
		doctorSelect.show();
	}

	function moveDown() {

		var doctorSelect = $( "#DoctorSelect");
		var selectedIndex = doctorSelect.selectedIndex;

		doctorSelect.hide();
		
		selected = $( "[value=\'" + doctorSelect.val() + "\']" );
		afterSelected = selected.next();
	
		//selected.remove();
		afterSelected.insertBefore( selected ); 
		
		doctorSelect.selectedIndex = selectedIndex + 1;
		doctorSelected();
		
		doctorSelect.show();
	}

	function saveOrder() {
		
		var doctors = [];
		var i;
		var options = $( "option" );
		

		for( i=0; i<options.length; i++ ) { 

			item = {};
			item[ "id" ] = options[i].getAttribute( "doctor_id" );
			item[ "sortOrder" ] = i+1;
			doctors.push( item );
		}
		
		// JSON message has Array of user_ids and doctor_ids			
		jQuery.post( "/Doctors/update_doctors",
					JSON.stringify( doctors ),
					function ( response ) {
						if( !response.success ) {
							alert( "Error - " + response.error );
							console.log( "Error - " + response.error );
						}
					}
				);
			
	}

	function deleteDoctor() {

		selected = $( "[value=\'" + ( $( "#DoctorSelect" ).val() ) + "\']" );
		
		var jsonRequest = new Object();

		jsonRequest.id = selected.attr( "doctor_id" );
				
		jQuery.post( "/Doctors/delete_doctor",
				JSON.stringify( jsonRequest ),
				function ( response ) {
				
					if( response.success ) {
					
						// Remove the row for the deleted user
						parent = selected.parent();
						parent.hide();
						selected.remove();
						parent.show();
						
						saveOrder();
					} else {
						alert( "Error - " + response.error );
						console.log( "Error - " + response.error );
					}

				}
			);
	}

	', Array( 'inline' => false ) );
?>

<div id="UserDiv">
   <h1>Doctors</h1>
   <div id="AddDoctorDiv">
		<p>Add Doctor: 
			<input id="NewDoctorFld" type="text" size="50" onkeypress="addDoctorKeyPress();"/>
			<input id="AddBtn" type="button" value="Add" onclick="addDoctor();" />
		</p>
   </div>
   <h2>Doctors:</h2>
   <div id="DoctorsDiv">
		<div id="DoctorSelectDiv">
			<select id="DoctorSelect" size="10" onclick="doctorSelected();">
<?php
		foreach( $doctors as $doctor ) {
			$doctorPrefix = $this->html->make_css_friendly( $doctor['Doctor']['doctorName'] );
			echo $this->Html->tag( 'option',
				$doctor['Doctor']['doctorName'],
				Array( 	'id' => $doctorPrefix + 'Option',
						'className' => 'doctorOption',
						'value' => $doctor['Doctor']['doctorName'],
						'doctor_id' => $doctor['Doctor']['id']
					)
			);
		}
?>
			</select>
		</div>
		<div id="DoctorControlsDiv">
			<input id="MoveUpBtn" type="button" disabled="true" value="Move Up" onclick="moveUp();"/>
			<input id="MoveDownBtn" type="button" disabled="true" value="Move Down" onclick="moveDown();"/>
			<input id="SaveOrderBtn" type="button" value="Save Order" onclick="saveOrder();"/>
			<input id="DeleteBtn" type="button" value="Delete" onclick="deleteDoctor();"/>
		</div>
   </div>
</div>
<div id="ToolsDiv">
	<input id="CloseBtn" type="button" value="Close" onclick="window.close()"/>
</div>