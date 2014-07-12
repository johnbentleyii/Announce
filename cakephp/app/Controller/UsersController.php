<?php
App::uses( 'User', 'Model' );
class UsersController extends AppController {
	public $helpers = array( 'html', 'form' );
	public $components = array ( 'Session' );
	
	public function index() {
		
		$this->set( 'users', $this->User->find( 'all' ) );
	
	}
	
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
			$current_user = $this->User->findById( $user_id );
			if( ( $success = ($current_user != NULL) ) ) {
				$this->Session->write( 'User.id', $user_id );
				$this->Session->write( 'User.edit', $current_user['User']['edit'] );
				$this->Session->write( 'User.admin', $current_user['User']['admin'] );
			}
			else
					$error = "User " . $user_id . " does not exist.";
		} else
			$error  = "Request was not a post.";		

		$return_message = array (
							"success" => $success,
							"error" => $error
						);
		
		return json_encode( $return_message );
		
	}

	/*public function app_admin() {
		if( $this->Session->read( 'admin' ) )
			$this->set( 'users', $this->User->find( 'all' ) );
		} else
			$this->redirect( '/Doctors/' );
	
	}
	*/

}
