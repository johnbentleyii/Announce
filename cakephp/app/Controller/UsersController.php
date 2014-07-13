<?php
App::uses( 'User', 'Model' );
class UsersController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array ( 'Session' );
	

	
	// Sets the current user for the session
	//		$id - User id for the current user
	// Answers true if the user is found
	private function set_current_user( $id ) {
		
		$current_user = $this->User->findById( $id );
		
		// Provides easy access profile of the user
		if( ( $success = ($current_user != NULL) ) ) {
				$this->Session->write( 'User.id', $id );
				$this->Session->write( 'User.username', $current_user['User']['username'] );
				$this->Session->write( 'User.edit', $current_user['User']['edit'] );
				$this->Session->write( 'User.admin', $current_user['User']['admin'] );
				$this->Session->write( 'Edit.user_id', $id );
				$this->Session->write( 'Edit.username', $current_user['User']['username'] );
			}
		
		return $success;
	}
	
	// Provides a drop down list to select the user.
	// Note: customer did not want any authentication
	public function user_list() {
		
		// Default to the doctor view if a user_id is passed - supports bookmarking.
		if( ( ( $user_id_arg = $this->request->query['user_id'] ) != NULL ) ) {
				
				if( $this->set_current_user( $user_id_arg ) )
					$this->redirect( '/Doctors/announcements' );
				else
					throw new NotFoundException( 'Could not find user_id ' . $user_id_arg );
		}
		else
			$this->set( 'users', $this->User->find( 'all' ) );
	}
	
	// Select the current user based on the unique user id provided
	//
	// JSON message should contain single value of 'user_id'.
	public function select_user() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$user_id = $data['user_id'];
			if( !( $success = $this->set_current_user( $user_id ) ) )
					$error = "User " . $user_id . " does not exist.";
		} else
			$error  = "Request was not a post.";
		
		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}

	// Provide the admin interface unless your not an admin, then
	// show the announcements
	public function edit_users() {
	
		if( $this->Session->read( 'User.admin' ) )
			$this->set( 'users', $this->User->find( 'all' ) );
		else
			$this->redirect( '/Doctors/announcements' );
	
	}
	
	// Change the identify of the edit user
	//
	// Excpected JSON message is an Array with user_edit and username values.
	public function update_edit_user() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		//$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$this->Session->write( 'Edit.user_id', $data['user_id'] );
			$this->Session->write( 'Edit.username', $data['username'] );
			$success = true;
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}
	
	// Add a new user
	//
	// Expected JSON message is an Array with an username, edit and admin values.
	public function add_user() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			
			if( count( $this->User->findByUsername( $data['username'] ) ) )
				$error = 'Cannot add as ' . $data['username'] . ' already exists.';
			else {
				$this->User->create();
				$this->User->save( Array( 'User' => $data ) );
				$success = true;
			}
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
	}
	
	// Process an update to the users
	//
	// Expected JSON message is an Array with an id, edit, and admin values.
	public function update_user() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$update = Array( 'User' => $data );
			$this->User->update( $update );
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}
	
	// Delete the user
	//
	// Excpected JSON message is an Array with a id.
	public function delete_user() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$this->User->delete( $data['id'], true );
			$success = true;
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}
}
