<?php

if(!defined('JPATH_BASE')) define ('JPATH_BASE',dirname(dirname(__FILE__)));
if(!defined('_JEXEC')) define ('_JEXEC',1);
if(!defined('DS')) define ('DS',DIRECTORY_SEPARATOR);

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ('kcaptcha.php');

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$captcha = new KCAPTCHA();

$sess = JFactory::getSession();
if($_REQUEST[$sess->getName()]){
	$sess->set('captcha_keystring', $captcha->getKeyString());
}

?>