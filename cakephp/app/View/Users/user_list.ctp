	<?php
	$this->Html->ScriptBlock( '

		// Set the user based on the selection and open the main view
		function openAnnouncements() {
		
			var jsonRequest = new Object();
			
			// Sets user_id to be the selected user
			jsonRequest.user_id = $( "#UserSelect" ).val();
			
			
			jQuery.post( "/Users/select_user",
						JSON.stringify( jsonRequest ),
						function ( response ) {
							if( response.error ) {
								alert( "Error - " + response.error );
								console.log( "Error - " + response.error );
							} else {
								window.open( "/Doctors/announcements", "_self" );
							}
						}
			);
		}
	', Array( 'inline' => false ) );
?>

<div id="UserDiv">
    <select id="UserSelect">
<?php
	foreach( $users as $user ) {
	
		// Create an entry for every user and use the unique user id
		// as the option value
		echo '<option value="' . $user['User']['id'] . '">';
		echo $user['User']['username'];
		echo '</option>';
	}
?>
	</select>
</div>

<div id="ToolsDiv">
    <input id="OKBtn" type="button" value="OK" onclick="openAnnouncements();"/>
</div>

