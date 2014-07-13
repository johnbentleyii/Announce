<?php
class Doctor extends AppModel {

	public $hasMany = array (
		'Filter' => array(
						'className' => 'Filter'
		)
	);
	
	public $order = "Doctor.sortOrder ASC";
	
	public function afterDelete( ) {
		$this->Filter->deleteAll( Array( 'Filter.doctor_id' => $this->id ), false );
	}
}