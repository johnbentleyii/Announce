
<div id="UserDiv">
   <h1>Users</h1>
   <div id="AddUserDiv">
		<p>Add User: 
			<input id="NewUserFld" type="text" size="50" onkeypress="javascript:addUserKeyPress();"/>
			<input id="AddBtn" type="button" value="Add" onclick="javascript:addUser();" />
		</p>
   </div>
   <h2>Current Users:</h2>
   <table id="UserTbl">
		<tbody id="UserTblBody">
<?php
	i=0;
	foreach( $users as $user ) {
		$userPrefix = str_replace( '.', '', str_replace( ' ', '', 	
												$user['User']['username'] ) );
		echo $'<tr' . ( ( i % 2 )? ' class="UserRowOdd" ' : '' ) . '>';
		
		// Username
		echo $this->Html->tag( 'td',
							$user['User']['username'], 
							Array( 'class' => 'UserName UserData' )
					);
		
		// Editor
		echo $'<td class="CanEdit UserData">';
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'CanEditCB',
					'class' => 'CanEdit';
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
		
		// Administrator
		echo $'<td class="CanAdmin UserData">';
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'CanAdminCB',
					'class' => 'CanAdmin';
					'user_id' => $user['User']['id'],
					'type' => 'checkbox',
					'checked' => $user['User']['CanAdmin']
				)
		);
		echo $this->Html->tag( 'span', 'Admin', Array (
												'class' => 'CanAdmin'
											)
		);
		echo '</td>'; // edit

		// Update
		echo $'<td class="CanAdmin UserData">';
		echo $this->Html->tag( 'input', '', Array (
					'id' => $userPrefix . 'CanAdminCB',
					'class' => 'CanAdmin';
					'user_id' => $user['User']['id'],
					'type' => 'checkbox',
					'checked' => $user['User']['CanAdmin']
				)
		);
		echo $this->Html->tag( 'span', 'Admin', Array (
												'class' => 'CanAdmin'
											)
		);
		echo '</td>'; // edit

		
		i++;
	}
?>
		</tbody>
   </table>
</div>
<div id="ToolsDiv">
	<input id="CloseBtn" type="button" value="Close" onclick="window.close()"/>
</div>
