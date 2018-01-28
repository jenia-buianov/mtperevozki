<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if($mainframe->isAdmin()) {
	return;
}

$mainframe->registerEvent( 'onAfterInitialise', 'plgCleanURI' );

function plgCleanURI(){
	$u =& JURI::getInstance();
	$u->setPath(str_replace('_','',$u->getPath()));
	return true;
}
