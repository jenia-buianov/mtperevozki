<?php
//init Joomla Framework
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', realpath(dirname(__FILE__) ));


require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	
// Get the document object.	
$mainframe = JFactory::getApplication('site');
$document =& JFactory::getDocument();
 
// Set the MIME type for JSON output.
$document->setMimeEncoding('application/json');
 
// Change the suggested filename.
JResponse::setHeader('Content-Disposition','attachment;filename="city.json"');
JResponse::setHeader('Content-type', 'application/json; charset=utf-8');
JResponse::setHeader('Cache-Control', 'no-cache');
JResponse::setHeader('Pragma', 'no-cache');


$city_list = array();
$languages = array('ru', 'en', 'ro');
$language = in_array($_REQUEST['language'], $languages) ? $_REQUEST['language'] : 'ru';
$id_country = intval($_REQUEST['id_country']);

if (!empty($id_country))
{
	$options = $mainframe->getCfg('orders_conn');
    
	//DBQuery
    $db = &JDatabase::getInstance($options);
		
	if ($db)
	{
		$db->setQuery("SELECT id_country FROM country WHERE id_country='".$id_country."'");
		$db->query();

		if ($db->getNumRows() == 1 )
		{
			$query = "SELECT id_city as data_id, city_name_". $language . " as data_title FROM city WHERE id_country = '".$id_country."' ORDER BY city_name_". $language ." ASC";
				
			$db->setQuery($query);
			$db->query();
		
			$res = $db->loadAssocList();
			foreach($res as $row)
			{
				$city_list[$row['data_id']] = $row['data_title'];
			}
		}
	}
}

JResponse::sendHeaders();
print json_encode($city_list);
?>