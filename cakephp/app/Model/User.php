<?php

App::uses( 'AppModel', 'Model' );

class User extends AppModel {
	
	public $hasMany = array (
		'Filter' => array(
						'className' => 'Filter'
		)
	);
	
	public function afterDelete( ) {
		$this->Filter->deleteAll( Array( 'Filter.user_id' => $this->id ), false );
	}
}