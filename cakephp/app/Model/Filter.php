<?php
App::uses( 'AppModel', 'Model' );

class Filter extends AppModel {
	public $hasMany = array (
		'Doctor' => array (
			'className' => 'Doctor',
			'foreignKey' => 'id'
		)
	);
}