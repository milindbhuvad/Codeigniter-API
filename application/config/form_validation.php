<?php

$config = [
	'addUser' =>[
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|required|xss_clean'
		],
		[
			'field' => 'email',
			'label' => 'email address',
			'rules' => 'required|valid_email'
		],
		[
			'field' => 'password',
			'label' => 'password',
			'rules' => 'required|callback_valid_password'
		]
	],
	
	'updateUser' =>[
		[
			'field' => 'id',
			'label' => 'id',
			'rules' => 'trim|required|xss_clean'
		],
		// [
		// 	'field' => 'user',
		// 	'label' => 'user',
		// 	'rules' => 'trim|required|xss_clean'
		// ],
		// [
		// 	'field' => 'email',
		// 	'label' => 'email address',
		// 	'rules' => 'required|valid_email'
		// ],
		// [
		// 	'field' => 'password',
		// 	'label' => 'password',
		// 	'rules' => 'required|callback_valid_password'
		// ]
	],
];