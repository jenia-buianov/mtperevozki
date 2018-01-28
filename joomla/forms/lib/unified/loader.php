<?php

autoloader::init();

class autoloader
{

    private static $loader;

    public static function init()
    {
        if (self::$loader == NULL)
            self::$loader = new self();

        return self::$loader;
    }

    private function __construct()
    {
		//form builder
		include_once  dirname(__FILE__).'/lib/PFBC/Form.php';
		
		//library
        spl_autoload_register(array($this,'library'));
    }

    private function library($class)
    {
		if (file_exists(dirname(__FILE__).'/lib/class.'.$class.'.lib.php'))
			include_once dirname(__FILE__).'/lib/class.'.$class.'.lib.php';
			
        //set_include_path(dirname(__FILE__).'/lib/');
        //spl_autoload_extensions('.lib.php');
        //spl_autoload('class.'.$class);
    }
}

?>