Download rest.php file and place in the application/config/ 
	->Enable REST API authentication
	$config['rest_auth'] = 'basic';

	->Set the username and password for API authentication.
	$config['rest_valid_logins'] = ['admin' => '1234'];

	->Set the table name that holds the API key.
	$config['rest_keys_table'] = 'keys';

	->Enable REST API key.
	$config['rest_enable_keys'] = TRUE;

Download REST_Controller.php and Format.php file and place in the
	application/libraries/

Download rest_controller_lang.php file and place in the
	application/language/english/
