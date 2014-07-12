<?php
class Doctor extends AppModel {

	public $hasMany = array (
		'Filter' => array(
						'className' => 'Filter'
		)
	);
}