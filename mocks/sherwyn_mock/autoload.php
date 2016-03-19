<?php
	// AutoLoad all the classes inside the classes folder under sherwyn_mock
	spl_autoload_register(function($class){
		$class_files = "classes/{$class}.php";
		require $class_files;
	});

?>