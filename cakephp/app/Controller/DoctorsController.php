<?php
App::uses( 'Filter', 'Model' );

class DoctorsController extends AppController {
	public $helpers = array( 'html', 'form' );
	public $components = array ( 'Session' );

	private function filter_doctors() {
	
		$filtered_doctors = array();
		$current_user_id = $this->Session->read( 'User.id' );
			
		$all_doctors = $this->Doctor->find( 'all' );
			
		foreach( $all_doctors as $doctor )
				foreach( $doctor['Filter'] as $filter )
					if( $filter['user_id'] == $current_user_id )
						$filtered_doctors[] = $doctor['Doctor'];
	
		return( $filtered_doctors );
	}
	
	public function index() {
		
		$this->set( 'doctors', $this->filter_doctors() );
	}
	
	public function request_update() {
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		$return_message = array (
			'success' => true,
			'content' => $this->filter_doctors()
		);
		return json_encode( $return_message );
	}
	
	public function edit_filters_for( $user_id) {
	
		$visible_doctors = $this->filter_doctors();
		$visible_doctor_ids = array ();
		$hidden_doctor_ids = array();
		
		foreach( $visible_doctors as $doctor )
			$visible_doctor_ids[] = $doctor['id'];
		
		$this->Session->write( 'Edit.user_id', $user_id );
		$this->set( 'visible_doctor_ids', $visible_doctor_ids );
		$this->set( 'all_doctors', $this->Doctor->find( 'all' ) );
	}
	
	public function edit_filters( ) {
		
		$this->edit_filters_for( $this->Session->read( 'User.id' ) );
	}
	
	public function update_list() {
	
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
	
	
}