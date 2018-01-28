<?php
/**
* @version 1.3.0
* @package RSform!Pro 1.3.0
* @copyright (C) 2007-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class plgContentMtpform extends JPlugin
{
	var $_deleteCache;
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentMtpform( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}
	
	function canRun()
	{
		if (class_exists('RSFormProHelper')) return true;
		
		$helper = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsform'.DS.'helpers'.DS.'rsform.php';
		if (file_exists($helper))
		{
			require_once($helper);
			return true;
		}
		
		return false;
	}
	
	function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
	{
		if (isset($article->text))
			$this->onPrepareContent($article, $params, $limitstart);
	}
	
	function onContentPrepare($context, &$article, &$params, $limitstart=0)
	{
		if (isset($article->text))
			$this->onPrepareContent($article, $params, $limitstart);
	}
	
	function onAfterDispatch()
	{
		if (!$this->canRun()) return true;
		
		// 2.5
		if (RSFormProHelper::isJ16())
		{
			$app 	 = &JFactory::getApplication();
			$cache   = &JFactory::getCache('com_content','view');
			$caching = $app->getCfg('caching');
			if ($caching)
				$cache->setCaching(true);
		}
		// 1.5
		elseif ($this->_deleteCache)
		{
			$cache = &JFactory::getCache();
			$id	   = md5(serialize(array(JRequest::getURI(), 'ContentViewArticle', 'display')));
			$group = 'com_content';
			$cache->remove($id, $group);
		}
	}
	
	function onPrepareContent(&$article, $params, $limitstart=0)
	{
		$mainframe =& JFactory::getApplication();
		
		$option = JRequest::getVar('option');
		$task 	= JRequest::getVar('task');
		if ($option == 'com_content' && $task == 'edit')
			return true;
		
		if (strpos($article->text, '{mtpform}') === false)
			return true;
		
		if (!$this->canRun()) return true;
		
		// 2.5
		if (RSFormProHelper::isJ16())
		{
			$cache = &JFactory::getCache('com_content','view');
			$cache->setCaching(false);
		}
		// 1.5
		else
			$this->_deleteCache = true;
			
		$lang =& JFactory::getLanguage();
		$lang->load('com_rsform', JPATH_SITE);
			
		$formId = 3;
		$article->text = str_replace('{mtpform}', RSFormProHelper::displayForm($formId), $article->text);
		
		return true;
	}
}
?>