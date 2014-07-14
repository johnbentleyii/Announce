<?php
App::uses( 'Filter', 'Model' );

class DoctorsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array ( 'Session' );

	// Answer all the doctors that match the filter criteria for the
	// current user
	private function filter_doctors( $current_user_id ) {
	
		$filtered_doctors = array();
			
		$all_doctors = $this->Doctor->find( 'all' );
			
		foreach( $all_doctors as $doctor )
				foreach( $doctor['Filter'] as $filter )
					if( $filter['user_id'] == $current_user_id )
						$filtered_doctors[] = $doctor['Doctor'];
	
		return( $filtered_doctors );
	}

	// Answer the list of doctor_ids that can be viewed
	private function visible_doctor_ids( $current_user_id ) {
	
		$visible_doctors = $this->filter_doctors( $current_user_id );
		
		$visible_doctor_ids = array ();
		
		foreach( $visible_doctors as $doctor )
			$visible_doctor_ids[] = $doctor['id'];
			
		return $visible_doctor_ids;	
	}
	
	// Main system view - shows list of doctors based on the filter
	public function announcements() {
		
		$this->set( 'doctors', $this->filter_doctors( $this->Session->read( 'User.id' ) ) );
	}
		
	// Allows editing of filters for the given user
	public function edit_filters( ) {
	
		$this->set( 'visible_doctor_ids', $this->visible_doctor_ids( $this->Session->read( 'Edit.user_id' ) ) );
		$this->set( 'all_doctors', $this->Doctor->find( 'all' ) );		
	}
	
	// Provides updated information for view-only users
	//
	// JSON response is an Array of 'Doctor' with each having an arrray
	//		containing patientList and notesList
	public function request_update() {
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		$return_message = array (
			'success' => true,
			'content' => $this->filter_doctors( $this->Session->read( 'User.id' ) )
		);
		return json_encode( $return_message );
	}
	
	// Process an update for a single doctor
	//
	// Excpected JSON message is an Array with a value of patientList, noteList, or // //	sortOrder.
	public function update_doctor() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$update = Array ( 'Doctor' => $data );
			$this->Doctor->save( $update );
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}
	
	// Process an update for a single doctor
	//
	// Excpected JSON message is an Array of Arrays with a 
	// value of patientList, noteList, and/or sortOrder.
	public function update_doctors() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			foreach( $data as $doctor ) {
				$update = Array ( 'Doctor' => $doctor );
				$this->Doctor->save( $update );
			}
			$success = true;
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}	
	// Add a new doctor
	//
	// Expected JSON message is an Array with an doctorname, patientList, noteList, and //	sortOrder.
	public function add_doctor() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			
			$duplicate = $this->Doctor->find( 'first', 
								Array( 'conditions' =>
										Array( 'doctorName' => $data['doctorName'])
									)
							);
						
			if( $duplicate != NULL )
				$error = 'Cannot add as ' . $data['doctorName'] . ' already exists.';
			else {
				$this->Doctor->create();
				$this->Doctor->save( Array( 'Doctor' => $data ) );
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
	
	// Provides the interface for managing the list of doctors
	// If this is not an admin, redirect to the announcement view
	public function edit_doctors() {
	
		if( $this->Session->read( 'User.admin' ) )
			$this->set( 'doctors', $this->Doctor->find( 'all' ) );
		else
			$this->redirect( '/Doctors/announcements' );
		
	}
	// Delete the doctor
	//
	// Excpected JSON message is an Array with a id.
	public function delete_doctor() {
	
		$success = false;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			$data = $this->request->input( 'json_decode', true );
			$this->Doctor->delete( $data['id'], true );
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
