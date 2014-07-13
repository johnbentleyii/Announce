<?php

	$this->Html->ScriptBlock( '
	
			// Limit username to valid characters
			function addUserKeyPress() {

				return( event.returnValue = 
						(((event.keyCode >= 48) && (event.keyCode <= 57)) ||
                        ((event.keyCode >= 65) && (event.keyCode <= 90)) ||
                        ((event.keyCode >= 97) && (event.keyCode <= 122)))
				);	
			}
			
			// Add a new user to the system
			function addUser() {
			
				var jsonRequest = new Object();
				
				jsonRequest.username = $( "#NewUserFld" ).val();
				jsonRequest.edit = false;
				jsonRequest.admin = false;
				
				jQuery.post( "/Users/add_user",
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
			
			// Update a user\'s profile
			function updateUser( ) {
				
				userPrefix = event.currentTarget.getAttribute( "user_prefix" );
				
				var jsonRequest = new Object();

				jsonRequest.id = event.currentTarget.getAttribute( "user_id" );
				jsonRequest.edit = $( "#" + userPrefix + "CanEditCB" ).is( ":checked" );
				jsonRequest.admin = $( "#" + userPrefix + "CanAdminCB" ).is( ":checked" );
				
				jQuery.post( "/Users/update_user",
						JSON.stringify( jsonRequest ),
						function ( response ) {
							if( !response.success ) {
								alert( "Error - " + response.error );
								console.log( "Error - " + response.error );
							}
						}
					);
			}	

			// Delete a user\'s profile
			function deleteUser( ) {
				
				userPrefix = event.currentTarget.getAttribute( "user_prefix" );
				
				var jsonRequest = new Object();

				jsonRequest.id = event.currentTarget.getAttribute( "user_id" );
				
				jQuery.post( "/Users/delete_user",
						JSON.stringify( jsonRequest ),
						function ( response ) {
						
							if( response.success ) {
							
								// Remove the row for the deleted user
								parent = $( "#" + userPrefix + "TR" ).parent();
								parent.hide();
								$( "#" + userPrefix + "TR" ).remove();
								parent.show();
							} else {
								alert( "Error - " + response.error );
								console.log( "Error - " + response.error );
							}

						}
					);
			}	

			// Delete a user\'s profile
			function editUserFilter( ) {

				var jsonRequest = new Object();

				jsonRequest.user_id = event.currentTarget.getAttribute( "user_id" );
				jsonRequest.username = event.currentTarget.getAttribute( "username" );
				
				jQuery.post( "/Users/update_edit_user",
						JSON.stringify( jsonRequest ),
						function ( response ) {
						
							if( response.success ) {
								filterWindow = window.open( "/Doctors/edit_filters" );
								
								// Reset to the session user which is the default
								filterWindow.onunload = function() {
									var jsonRequest = new Object();

									jsonRequest.user_id = "' . $this->Session->read( 'User.id' ) . '";
									jsonRequest.username = "' . $this->Session->read( 'User.username' ) . '"; 
									
									jQuery.post( "/Users/update_edit_user",
										JSON.stringify( jsonRequest ),
										function() {
											if( !response.success ) {
												alert( "Error - " + response.error );
												console.log( "Error - " + response.error );
											}
										}
									);	
								};
							}
							else {
								alert( "Error - " + response.error );
								console.log( "Error - " + response.error );
							}
						}
					);
				
			}	
			
	', Array( 'inline' => false ) );
?>

<div id="UserDiv">
   <h1>Users</h1>
   <div id="AddUserDiv">
		<p>Add User: 
			<input id="NewUserFld" type="text" size="50" onkeypress="addUserKeyPress();">
			<input id="AddBtn" type="button" value="Add" onclick="addUser();">
		</p>
   </div>
   <h2>Current Users:</h2>
   <table id="UserTbl">
		<tbody id="UserTblBody">
<?php
	$i=0;
	foreach( $users as $user ) {
		$userPrefix = $this->Html->make_css_friendly( $user['User']['username'] );
		
		echo '<tr id="' . $userPrefix . 'TR" ' . ( ( $i % 2 )? ' class="UserRowOdd" ' : '' ) . '>';
		
		// Username
		echo $this->Html->tag( 'td',
							$user['User']['username'], 
							Array( 'class' => 'UserName UserData' )
					);
		
		// Set to determine if this user is an editor
		echo '<td class="CanEdit UserData">';
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'CanEditCB',
					'class' => 'CanEdit',
					'user_id' => $user['User']['id'],
					'type' => 'checkbox',
					'checked' => $user['User']['edit']
				)
		);
		echo $this->Html->tag( 'span', 'Edit', Array (
												'class' => 'CanEdit'
											)
		);
		echo '</td>'; // edit
		
		// Set to determine if this user is an admin
		echo '<td class="CanAdmin UserData">';
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'CanAdminCB',
					'class' => 'CanAdmin',
					'user_id' => $user['User']['id'],
					'type' => 'checkbox',
					'checked' => $user['User']['admin']
				)
		);
		echo $this->Html->tag( 'span', 'Admin', Array (
												'class' => 'CanAdmin'
											)
		);
		echo '</td>'; // admin

		// Action Buttons
		echo '<td class="CanAdmin UserData">';

		// Button to update the user
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'UpdateBtn',
					'class' => 'UpdateBtn',
					'value' => 'Update',
					'user_id' => $user['User']['id'],
					'username' => $user['User']['username'],
					'user_prefix' => $userPrefix,
					'type' => 'button',
					'onclick' => 'updateUser( );'
				)
		);

		// Button to update the user
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'DeleteBtn',
					'class' => 'DeleteBtn',
					'value' => 'Delete',
					'user_id' => $user['User']['id'],
					'username' => $user['User']['username'],
					'user_prefix' => $userPrefix,
					'type' => 'button',
					'onclick' => 'deleteUser( );'
				)
		);
	
		// Button to filter the user
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'FilterBtn',
					'class' => 'FilterBtn',
					'value' => 'Edit Filter',
					'user_id' => $user['User']['id'],
					'username' => $user['User']['username'],
					'user_prefix' => $userPrefix,
					'type' => 'button',
					'onclick' => 'editUserFilter( );'
				)
		);
		echo '</td>'; // Action Buttons
		
		$i++;
	}
?>
		</tbody>
   </table>
</div>
<div id="ToolsDiv">
	<input id="CloseBtn" type="button" value="Close" onclick="window.close()"/>
</div>
