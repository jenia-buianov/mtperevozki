<?php
/**
 * @package JoomlaPack
 * @copyright Copyright (c)2006-2008 JoomlaPack Developers
 * @license GNU General Public License version 2, or later
 * @version $id$
 * @since 2.1
 * 
 * The main page of the JoomlaPack component is where all the fun takes place :)
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jpimport('helpers.sajax', true);
sajax_init();
sajax_export('testdirectory');
$null = null;
sajax_handle_client_request( $null );

function testdirectory($fsdir)
{
	$JP_Error_Reporting = @error_reporting(E_ERROR | E_PARSE);
	
	// Check for open_basedir restrictions
	if (_checkOpenBasedirs($fsdir)) return JText::_('EFF_CHECK_RESTRICTED');
	
	// Is it a directory at all?
	if(!@is_dir($fsdir)) return JText::_('EFF_CHECK_NOTADIR');
	
	// Primary check for readability
	if(!is_readable($fsdir)) return JText::_('EFF_CHECK_NOTOK');
	
	// Thorough check for readability (PHP pre-5.1.6 did not take Safe Mode restrictions into account!)
	$handle = @opendir($fsdir);
	if($handle === FALSE) return JText::_('EFF_CHECK_NOTOK');
	closedir($handle);
	
	return JText::_('EFF_CHECK_OK');
}

	/**
	 * Checks if a path is restricted by open_basedirs
	 *
	 * @param string $check The path to check
	 * @return bool True if the path is restricted (which is bad)
	 */
	function _checkOpenBasedirs($check)
	{
		static $paths;
		
		if(empty($paths))
		{
			$open_basedir = ini_get('open_basedir');
			if(empty($open_basedir)) return false;
			$delimiter = strpos($open_basedir, ':') !== false ? ':' : ';';
			$paths = explode($delimiter, $open_basedir);
		}
		
		if(empty($paths))
		{
			return false; // no restrictions
		}
		else
		{
			$check = realpath($check); // Resolve symlinks, like PHP does
			$included = false;
			foreach($paths as $path)
			{
				$path = realpath($path);
				if(strlen($check) >= strlen($path))
				{
					// Only check if the path to check is longer than the inclusion path.
					// Otherwise, I guarantee it's not included!!
					// If the path to check begins with an inclusion path, it's permitted. Easy, huh?
					if(substr($check,0,strlen($path)) == $path) $included = true;
				}
			}
			
			return !$included;
		}
	}