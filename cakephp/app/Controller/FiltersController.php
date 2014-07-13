<?php

class FiltersController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array ( 'Session' );

	// Update the filters for the user being edited.
	// JSON message should be an array of 'user_id' and 'doctor_id' pairs
	public function update_filters() {
	
		$success = true;
		$error = NULL;
		$data = NULL;
		$update = NULL;
		$newFilters = array ();
		
		$this->autoRender = false;
		$this->request->onlyAllow( 'ajax' );
		$this->response->type( 'json' );
		
		if( $this->request->is( 'post' ) ) {
			
			$data = $this->request->input( 'json_decode', true );
			//Clear out the old
			$this->Filter->deleteAll( array( 'user_id' => $data['user_id'] ) );
			
			//Build the new
			foreach ( $data['filters'] as $value_array ) {
				$this->Filter->create();
				$this->Filter->save( Array( 'Filter'=> $value_array ) );
			}
		} else {
			$error  = "Request was not a post.";		
			$success = false;
		}
		
		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
	}
}
