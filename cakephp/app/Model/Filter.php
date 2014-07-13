<?php
App::uses( 'AppModel', 'Model' );

class Filter extends AppModel {

	public $belongsTo = Array (
		'User' => Array (
			'className' => 'User',
			'foreignKey' => 'user_id',
			'dependent' => true
		),
		'Doctor' => Array (
			'className' => 'Doctor',
			'foreignKey' => 'doctor_id',
			'dependent' => true
		)
	);
}