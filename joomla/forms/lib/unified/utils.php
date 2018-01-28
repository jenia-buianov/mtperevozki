<?php defined('_JEXEC') or die('Restricted access');

	class TabUtils

	{
	
		public $suffix = 'movers_';

		public $db;

		public $dbc;

		public $language;

		public $transport_list;

		public $country_list_object;

		public $itemid;

		public $sess;

		public $_tab;

		public $_priority;

		public $_validate_errors;

		public $_post;

		public $_required;
		
		public $_filter_fields;

		public $_session_data;
		
		public $link_id;

		public $offer_id;

		public $_split;
		
		public $_allsplit = array();

		public $_filter = array();

		private $_aux_tabs;

		private $_handleRequest;
		
		public $_params;
		
		public $test;

		
		//cache
		private $cache = array();
		private $table_cache = array();
		public $cacheObj;
		public $cacheLifetime = 300;
		

		/*Инициализация*/

		function __construct ()
		{			
			//$this->_makeNewData();
			//exit;
			$this->db = $this->getdb();
			
			$this->dbc = & JFactory::getDBO();

			$this->language = $this->getLanguage();

			$this->itemid = $this->_getItemId();

			$this->sess = $this->_getSession();

			$this->refreshFlashData();
			
			$this->link_id = intval($_GET['link_id']);
			
			$this->offer_id = intval($_GET['offer_id']);
			
			$this->test = $this->storeData();
			
			$this->cacheObj = $this->getCacheObj($this->cacheLifetime);
		}

		

//*******ПОлучение объекта кеширования джумла*******//
			private function getCacheObj($lifetime = 300)
			{
				
				$cache = & JFactory::getCache();
				$cache->setCaching( 1 );
				$cache->setLifeTime($lifetime);
				
				return $cache;
			
			}

		

//*******Функции обработки GET и POST запросов********//
		
		public function getLink()
		{
			$link_id = null;
			$db =& JFactory::getDBO();

			//сохраняем в переменную данные из сессии
			$session_data = $this->_session_data;
			
			//keep keys
			$keep = array('search', 'first', 'second', 'pagination');
			
			foreach ($session_data as $key=>$value)
				if (!in_array($key, $keep)) unset($session_data[$key]);

			//1-st pass
			$this->multisort($session_data);

			//2-nd pass
			$this->multisort($session_data);
			
			if (!empty($session_data))
			{

				$session_data = serialize($session_data);

				$db->setQuery("SELECT link_id FROM `jos_getlinks` WHERE `data`='".$session_data."' AND `item_id`= '".$this->itemid."'");

				$link_id = $db->loadResult();
				
				if (!$link_id)
				{
					$db->setQuery("INSERT INTO `jos_getlinks` SET `data`='".$session_data."', `link` = '"."http://".$_SERVER['SERVER_NAME'].$this->get_menulink_byid($this->itemid).$_SERVER['QUERY_STRING']."', `item_id`='".$this->itemid."', `accessed`=NOW()");

					$db->query();

					$id = $db->insertid();

					$link_id = crc32($id);

					$db->setQuery("UPDATE `jos_getlinks` SET `link_id`='".$link_id."' WHERE `id`='".$id."';");

					$db->query();
				}
			}

			return $link_id;	
		}

		

		public function storeData()
		{

			$this->_session_data = array();

			
			if (!empty($_GET['s']))
			{
				//check if valid link, if not - show 404
				$this->_session_data = $this->_processNewData($_GET['s']);
				return;
			}
			
			
			
			if (!empty($this->link_id))
			{
				
				$db =& JFactory::getDBO();

				$db->setQuery("SELECT data FROM `jos_getlinks` WHERE `link_id`='".$this->link_id."' AND `item_id`='".$this->itemid."'");

				$data = $db->loadResult();

				if ($data) $data = unserialize($data);

				
				if (is_array($data) and !empty($data))
				{
					$this->_session_data = $data;

					$db->setQuery("UPDATE `jos_getlinks` SET `accessed`=NOW(), `hits` = `hits`+1 WHERE `link_id`='".$this->link_id."'");

					$db->query();
				}
			}
			
			return true;
		}
		
		private function _makeNewData()
		{
		
			error_reporting(E_ALL);
			@ini_set('display_errors', 1);
			@ini_set('memory_limit', '512M');
			set_time_limit(0);
	
	
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT id, data FROM `jos_getlinks_copy`");
			$arr = $db->loadAssocList();

			foreach($arr as $link)
			{	
				if (!empty($link['data']))
				{
					$data = unserialize(base64_decode($link['data']));
					
					if (is_array($data) and !empty($data))
					{
						$suffix = "";
						$new_data = array();
						
						//filter handler
						if (!empty($data['filter']['search']))
						{
							foreach ($data['filter']['search'] as $key => $filter)
							{
								if (!isset($filter['table']) or empty($filter['table']))
								{
									echo "<br /><font color='red'>".$link['id']." - table is missing</font><br /><pre>";
									var_dump($filter);
									echo "</pre>";								
									continue;
								}
								
								$table = $filter['table'];
								unset($filter['table']);
								
								//suffix asumption
								if (stristr($table, '_'))
								{
									$parts = explode('_', $table);
									if (!isset($parts[1]))
									{
										echo '<br /><font color="red"> error for table - '.$table.'</font><br /><pre>';
										var_dump($parts);
										echo '</pre>';
									}
									
									$suffix = "_".$parts[1];
								}
						
								if (isset($data['first']) and isset($data['second']) and $data['first'] == $data['second'])
								{
									$filter['alias'] = 'display_table_'.$table;
									$new_data['search']['display_table_'.$table] = $filter;
								}
								else
								{
									$filter['alias'] = 'display_filter_'.$table;
									$new_data['search']['display_filter_'.$table] = $filter;
								}
								
								if (isset($data['first']) and $data['first'] == $key)
								{
									$new_data['first'] = 'display_filter_'.$table;
								}
								elseif (isset($data['second']) and $data['second'] == $key)
								{
									$new_data['second'] = 'display_table_'.$table;
								}
							}
						}
						
						if (!empty($data['tabs']))
						{	
							foreach ($data['tabs'] as $key => $tab)
							{
								
								if (!isset($tab['type']) or empty($tab['type']))
								{
									echo "<br /><font color='red'>".$link['id']." - type is missing</font><br /><pre>";
									var_dump($tab);
									echo "</pre>";								
									continue;
								}
								//suffix asumption
								if (empty($suffix) and isset($tab['suffix'])) $suffix = $tab['suffix'];
								
								$_suffix = (isset($tab['suffix']) ? $tab['suffix'] : "");
								
								//check once more for active tab info
								if (empty($new_data['first']) and isset($data['second']) and  $data['first'] == $key)
								{
									$new_data['first'] = 'display_filter_'.$tab['type'].$_suffix;
								}
								
								if (empty($new_data['second']) and isset($data['second']) and  $data['second'] == $key)
								{
									$new_data['second'] = 'display_table_'.$tab['type'].$_suffix;
								}
								
								//check for pagination
								if (!empty($data['pagination'][$key])) $new_data['pagination']['display_table_'.$tab['type'].$_suffix] = $data['pagination'][$key];								
								
								//check tabs with empty filter
								if (stristr($key, 'search_result'))
								{
									if (empty($data['filter']['search']) or !isset($new_data['search']['display_filter_'.$tab['type'].$_suffix]))
									{
										$new_data['search']['display_filter_'.$tab['type'].$_suffix] = array('alias' =>'display_filter_'.$tab['type'].$_suffix);
										if ($data['first'] == $key) $new_data['first'] = 'display_filter_'.$tab['type'].$_suffix;
										if ($data['second'] == $key) 
										{
											if (empty($new_data['first'])) $new_data['first'] = 'display_filter_'.$tab['type'].$_suffix;
											if (empty($new_data['second'])) $new_data['second'] = 'display_table_'.$tab['type'].$_suffix;
										}
									}
								}	
							}
						}
						
						if (empty($new_data['first']) and !empty($data['first']))
						{	
							if(stristr($data['first'],'offer'))
							{
								//form subbision activated tab
								$new_data['first'] = 'display_from_'.($data['first'] == 'offer_transport' ? 'order' : 'cargo').$suffix;
							}
							elseif (false and stristr($data['first'],'search'))
							{
								$new_data['first'] = 'display_filter_'.($data['first'] == 'order_search' ? 'order' : 'cargo').$suffix;							
							}
							
						}
						
						if (empty($new_data['second']) and !empty($data['second']))
						{	
							if(stristr($data['second'],'offer'))
							{
								//form subbision activated tab
								$new_data['second'] = 'display_from_'.($data['second'] == 'offer_transport' ? 'order' : 'cargo').$suffix;
							}
							elseif (false and stristr($data['second'],'search'))
							{
								$new_data['second'] = 'display_table_'.($data['second'] == 'order_search' ? 'order' : 'cargo').$suffix;							
							}
							
						}
						
						//keep keys
						$keep = array('search', 'first', 'second', 'pagination');
			
						foreach ($new_data as $key=>$value)
							if (!in_array($key, $keep)) unset($new_data[$key]);

						//1-st pass
						$this->multisort($new_data);

						//2-nd pass
						$this->multisort($new_data);
						
						//3-nd pass
						$this->multisort($new_data);
	

						if (!empty($new_data))
						{
							$new_data = serialize($new_data);
							$db->setQuery("UPDATE `jos_getlinks_copy` SET `data` = '".$new_data."' WHERE `id` = '".$link['id']."'");
							$db->query();
							echo "<br /><font color='green'>".$link['id']." - stored new data</font>";
						}
						else
						{
							$db->setQuery("DELETE FROM `jos_getlinks_copy` WHERE `id` = '".$link['id']."'");
							$db->query();
							echo "<br /><font color='red'>".$link['id']." - data is empy, deleted row</font><br /><pre>";
							var_dump($data);
							echo "</pre>";
						}
					}
				}
			}
			
			echo "<br /><font color='green'>DONE</font>";
		}

		private function _processNewData($string)
		{
			$maps = array (
							'FUNCTIONS' => array (
												1 => 'display_table_cargo',
												2 => 'display_table_order',
												3 => 'display_filter_cargo',
												4 => 'display_filter_order',
												5 => 'display_statistics_cargo_order',
												6 => 'display_statistics_cargo',
												7 => 'display_statistics_order',
											),
											
							'SUFFIX' => array (
												1 => '',
												2 => '_seafreight',
												3 => '_post',
												4 => '_passengers',
												5 => '_internal',
											),
											
							'TABS' => array (
												'a' => 'first',
												'b' => 'second'
											),
											
							'PAGINATION' => array (
												'c' =>'pagination',
											),
							
							'FIELDS' => array (
												'd' => 'export',
												'e' => 'import',
												'f' => 'export_city',
												'g' => 'import_city',
												'h' => 'date',
												'i' => 'date_from',
												'j' => 'date_to',
												'k' => 'type',
												'l' => 'name',
												'm' => 'volume',
											),
						);
			
			//explode tabs
			$tabs = explode('a',$string);
			$tabs = array_filter($tabs);
			
			$data = array();
			foreach ($tabs as $tab)
			{
				preg_match('/^a(?<function>\d{1,7})b(?<suffix>\d{1,5})c(?<page>\d+)(?<filter>[a-z0-9]+)/', 'a'.$tab, $data);
			}
			
			$this->d($data);
			exit;
			
			
			//Get tabs
			return array();
		}
		
		
		/*
		FUNCTIONS
		1 - display_table_cargo
		2 - display_table_order
		3 - display_filter_cargo
		4 - display_filter_order
		5 - display_statistics_cargo_order
		6 - display_statistics_cargo
		7 - display_statistics_order

		SUFFIX
		1 - ""
		2 - _seafreight
		3 - _post
		4 - _passengers
		5 - _internal

		TABS
		a - first line
		b - second line

		PAGINATION
		c

		FIELDS CODING
		d - export
		e - import
		f - export_city
		g - import_city
		h - date
		i - date_from
		j - date_to
		k - type
		l - name
		m - volume
		*/
		
		public function multisort(&$data)

		{

			if (is_array($data))
			{

				ksort($data);

				$data = array_filter($data);
				
				foreach ($data as $key => &$value)
				{
					if (empty($key)) unset($data[$key]);
						else $this->multisort(&$value);
				}
					
				if (empty($data)) unset($data);
			}

		}

		

		public function _redirect ($this_page = null)
		{

			$link_id = $this->getLink();

			if (!$this_page) $this_page = $_SERVER['REQUEST_URI'];
			if (strpos($this_page, "?") !== false) $this_page = reset(explode("?", $this_page));
			$this_page = "http://".$_SERVER['SERVER_NAME'].$this_page;

			if ($link_id)
			{
				$this_page = rtrim($this_page, "/");
				$this_page .= "/p".$link_id.".html";
			}

			JFactory::getApplication()->redirect($this_page);
			die();
		}

		

		public function _redirect_orders ()
		{

			$filter = JRequest::getVar('order', array());
			
			$is = '';			
			if(($this->_params->get('tab_usertab',0) and $this->userHasAccess()) or JRequest::getVar('is_ajax', 0)) $is = 'user';
				
			if ($this->split->original == "" and $filter['export'] == $filter['import'])
				$view_offers_id = (int)$this->_tab['tab_'.$is.'success_internaloffers_url'];
			else
				$view_offers_id = (int)$this->_tab['tab_'.$is.'success_offers_url'];	

			if (!empty($view_offers_id))
			{
				$url = $this->get_menulink_byid($view_offers_id);
				
				if (!$is) $url.= "?&filter[export]=".$filter['export']."&filter[import]=".$filter['import']."&alias=display_table_".($this->_tab['type'] == "cargo" ? "order" : "cargo").$this->_tab['suffix'];
			}
			else
			{
				$url = $this->uricurrent();
			}
				
			$page = "http://".$_SERVER['SERVER_NAME'].$url;
			
			JFactory::getApplication()->redirect($page);
			die();

		}


		public function _requests ()
		{
		
			if (JRequest::getString('alias','') != $this->_tab['alias']) return;

			$this->_handlePagination();

			$this->_handleFilter();

			$this->_handleOrders();
			
			$this->_handleActions();

			if ($this->_priority == "redirect") $this->_redirect_orders();
			if ($this->_priority) $this->_redirect();
			
		}

		private function _handlePagination() 
		{

			//сохраняем в переменную данные из сессии
			$session_data = $this->_session_data;

			//Pagination
			if (isset($_GET['page']))
			{

				$session_data['pagination'][$this->_tab['alias']] = (int)$_GET['page'];

				$session_data['second'] = $this->_tab['alias'];
				
				if (isset($this->_tab['tab_link'])) 
					$session_data['first'] = $this->_tab['tab_link'];
				
				$this->_priority = "get";
				
				//flash data
				$this->setFlashCounter(1);
				$this->updateFlashData('pagination',$this->_params->get('tab_type'));
			}

			//сохраняем данные в сессию
			$this->_session_data = $session_data;
			
		}

		

		

		//Обрабатывает и сохраняет в сессию информацию о закладке результата поиска

		private function _handleFilter()
		{
			//skip if handle pagination was encounter
			if (isset($this->_priority)) return;
			
			$session_data = $this->_session_data;

			if (count(($filter = JRequest::getVar('filter', array('alias' => $this->_tab['alias'])))) > 0)
			{
				//Обработка случая когда человек в международных грузоперевозках указывает одинаковое направение по странам
				if ($this->_tab['suffix'] == "_internal" and !empty($filter['export'])) $filter['import'] = $filter['export'];

				
				if ($filter['export'] == $filter['import'] and !empty($filter['export']) and $this->_tab['suffix'] == "" and intval($this->_tab['tab_internal_url']) > 0 )
				{
					//clear session data
					$session_data = array();
					$source = $target = $this->_tab['tab_link']."_internal";
					$redirect = true;
				}
				elseif (($function = JRequest::getString('f','')) !== '' and stristr($function, 'display_table_'))
				{
					$source = str_replace("_table_", "_filter_", $function).$this->_tab['suffix'];
					$target = $function.$this->_tab['suffix'];					
				}
				else
				{
					$source = $this->_tab['alias'];
					$target = $this->_tab['tab_link'];
				}
				
				//активация закладок
				$session_data['first'] = $source;
				$session_data['second'] = $target;

				//обнуление пагинации
				unset($session_data['pagination'][$target]);	

				

				//check date
				$patterns = array ('/(\d{1,2})-(\d{1,2})-(19|20)(\d{2})/');
				$replace = array ('\3\4-\2-\1');
				
				$date_presets = array('today', 'twodays', 'week', 'month');
				foreach ($filter as $key=>$value)
				{
					if ( stristr($key, "date") and ($value == JText::_('LABEL_SELECT_LOADING_DATE') or (!strtotime(preg_replace($patterns, $replace, $value)) and !in_array($value, $date_presets))))
						unset($filter[$key]);
				}
				
				//add alias to handle tabs created with empty filter, if removed, the tab will not be created
				$filter['alias'] = $this->_tab['alias'];
				
				if ($this->_params->get('tab_allow_searches') == "0") unset($session_data['search']); // delete old searches

				$session_data['search'][$source] = array_filter($filter);
					
				$this->_priority = "post";

			}

			//сохраняем данные в сессию

			$this->_session_data = $session_data;

			if ($redirect)
			{
				$this->itemid = intval($this->_tab['tab_internal_url']);
				$this->_session_data =  $session_data;
				
				$redirect = $this->get_menulink_byid($this->itemid);			
				$this->_redirect($redirect);
			}
		}

		

		

		//Обрабатывает запросы с форм заявок

		private function _handleOrders()
		{							
			if (count(($post = JRequest::getVar('order', array()))))
			{
				//активируем вкладку заявки
				$this->_session_data["first"] = $this->_tab['alias'];
				
				$this->_priority = "post";

				$target = $source = $this->_tab['alias'];
				
				$source_path = isset($post['path'])?$_SERVER['DOCUMENT_ROOT'].$post['path']:false;

				$table = $this->suffix.$this->_tab['type'].$this->_split->suffix;
				
				//show messages for redirected orders
				if($this->_params->get('tab_usertab',0) and $this->userHasAccess()) $user_redirected = true;
				
				if (!$this->_tableExists ($table)) return;			
				
				//check if user can edit offer
				if ($post['id'] > 0) 
				{
					$error = false;
					
					if (!$this->userHasAccess())
					{
						JFactory::getApplication()->enqueueMessage(JText::sprintf('NOACCESS_SIMPLE_WARNING', $this->get_menulink_byid($this->cabinet_id)), 'warning');
						$error = true;
					}
					
					if (!$this->checkOffer($this->_tab['type'], $this->_tab['suffix'], $post['id']))
					{
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_EDIT_NOACCESS', '#'.$post['id']), 'warning');
						$error = true;						
					}
					
					if ($error)
					{
						//if ($this->_params->get('behavior') or $user_redirected ) $this->_priority = "redirect";
						$this->_priority = "redirect";
						return;
					}
				}

				if (JRequest::getVar('use_profile', 0))
				{
					$profile = $this->getUserContacts();
					$post = array_merge($post, $profile);
				}

				//валидация данных
				$error = $this->_validate ($post);

				//flash data
				$this->setFlashCounter(1);
				$this->updateFlashData($source,$post);

				if (!empty($error))
				{
					//передаём информацию об ошибке в временную сессию
					$this->updateFlashData('validate_errors',$error);
				}
				else 
				{
					if ($post['id'] <= 0)
					{
						//default values
						$post['order_date'] = date("d-m-Y");
						$post['source'] = "comstil";
						
						$this->db->setQuery("SELECT id FROM movers_contact");
						$id_contacts = $this->db->loadResultArray();
						if (!empty($id_contacts) and count($id_contacts) > 0)
							$post['by_admin'] = $id_contacts[array_rand($id_contacts)];
					
						unset($post['id']);
					}
					
					if (empty($post['date'])) $post['date'] = date("d-m-Y");

					//Обрабатываем данные для сохранения в БД

					if ($post['id'] <= 0) $query = "INSERT INTO `$table` SET ";
						else $query = "UPDATE `$table` SET ";
					
					
					//replace is not good
					//$query = "REPLACE INTO `$table` SET ";
					
					$value_set = "";

					if ($this->_tab['suffix'] == "_internal" )
					{
						$post['import'] = $post['export'];
						$_POST['order']['import'] = $_POST['order']['export'];
					}

					foreach ($post as $key=>$value)

					{
						//if ($key == 'id') continue;
						
						if (stristr($key, "phone")) $value = $post["temp"][$key];

						if ($this->_fieldExists($table, $key))

						{

							if (!is_numeric($value) and $key != "email" and $key != "skype" and $key != "source") $value = ucwords(strtolower($value)); 	

							$value_set.= "`$key` = '$value',";

						}
					}

					

					if (empty($value_set)) return;
					
					$query .= trim($value_set,',');

					if ($post['id'] > 0) $query .= " WHERE `id` = '".$post['id']."'";

					//Сохраняем в БД
					$this->db->setQuery($query);

					$is = $this->db->query();
					
					$id = $this->db->insertid();
					
					if (!$is) 
					{
						//inser failed - rise error?
						$this->updateFlashData('validate_errors',$error);					
						//exit
						return;
					}
					
					//if data is added by user, store data
					if ($post['export'] == $post['import'] and $this->_tab['suffix'] == '')
					{
						$this->updateFlashData('internal_order',1); //
						$this->_tab['suffix'] = '_internal';
					}
					
					if ($this->getUser('id') > 0 and $post['id'] <= 0 and $id > 0)
					{
						JFactory::getDBO()->setQuery("INSERT INTO jos_users_orders SET `uid` = '".$this->getUser('id')."', `cid`='".$id."', `table`='".$this->_tab['type']."', `suffix`='".$this->_tab['suffix']."';");
						JFactory::getDBO()->query();
					}

					//Сохраняем в временную сессию информацию о успешном добавлении в БД
					$this->updateFlashData('success',$source);
					//$this->updateFlashData($source,array());
					
					//redirect to my offers or filtered table
					if ($this->_params->get('tab_behavior') or $user_redirected or $post['id'] > 0 or JRequest::getVar('is_ajax', 0)) $this->_priority = "redirect";
					
					if( $this->_priority == "redirect")
					{						
						if($post['id'] > 0)
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_EDIT_SUCCESS' ,'#'.$post['id']), 'notice');
						else
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_ADD_SUCCESS' ,'#'.$id), 'message');
					}
	
					//переносим изображения и сохраняем пути в БД

					if ($source_path)

					{

						if (!$files = scandir($source_path) or count($files) < 3)

						{

							rmdir($source_path);

						}

						else

						{

							$target_path = $_SERVER['DOCUMENT_ROOT']."/images/orders/".$target."/".$id."/";

							if (!is_dir($target_path)) {

								if (mkdir($target_path,0777,true)) {

									chown($target_path, "mweb3");

									chmod($target_path, 0777);

									$files = array_slice($files, 2, 5);

									foreach ($files as $key=>$file)

									{

										rename($source_path.$file, $target_path.$file);

										chown($target_path.$file, "mweb3");

										chmod($target_path.$file, 0777);

										unlink($source_path.$file);

										$files[$key] = "/images/orders/".$target."/".$id."/".$file; //for web

									}

									

									$images = serialize($files);

									$this->db->setQuery("UPDATE $table SET `images`='$images' WHERE `id` = '$id'");

									$this->db->query();

									

									if (!$files = scandir($source_path) or count($files) < 3)

									{

											rmdir($source_path);

									}

									

								}

							}

						}

					}					

				}

			}
		}
		
		
		public $updateLimit = 10;
		
		private function _handleActions()
		{

			if ($this->getUser('id') <= 0 or empty($_REQUEST['action'])) return;
			
			$this->_priority = "post";

			//has no acces to actions
			if (!$this->userHasAccess()) 
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('NOACCESS_SIMPLE_WARNING', $this->get_menulink_byid($this->cabinet_id)), 'warning');
				return;
			}
			$action = $_REQUEST['action'];
			
			if (empty($action['task'])) return; // rise error?
			
			if (empty($action['ids']))
			{
				JFactory::getApplication()->enqueueMessage(JText::_('ACTION_NOT_SELECTED'), 'warning');			
				return;
			}
			
			$error = false;
			
			switch($action['task'])
			{
				case 'delete':	
					
					$success=$no_access=$error_del=array();
						
					foreach ($action['ids'] as $cid)
					{
						
						$is = $this->deleteOffer($this->_tab['type'], $this->_tab['suffix'], $cid);
						
						if ($is === true) $success[] = $cid;
							elseif ($is === false) $error_del[] = $cid;
								elseif ($is === -1) $no_access[] = $cid;
					}			
					
					//messages
					if (!empty($success))
					{
						$multi = '';
						if (count($success) > 1) $multi = 'MULTI';
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'DELETE_SUCCESS' ,'#'.implode(', #', $success)), 'notice');
					}
						
					if (!empty($no_access))
					{
						$multi = '';
						if (count($no_access) > 1) $multi = 'MULTI';
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'DELETE_NOACCESS' ,'#'.implode(', #', $no_access)), 'warning');
					}	

					if (!empty($error_del))
					{
						$multi = '';
						if (count($error_del) > 1) $multi = 'MULTI';
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'DELETE_ERROR' ,'#'.implode(', #', $error_del)), 'error');
					}						
					
					break;
					
				case 'up':
				
					$success=$no_access=$error=array();	

					$refresh_limit = $this->updateLimit;
					
					if (($limit = $this->checkUpdateAllowed($this->_tab['type'], $this->_tab['suffix'], $refresh_limit)) > 0)
					{
					
						$to_update = array_slice($action['ids'], 0, $limit);
						foreach($to_update as $cid)
						{
							$is = $this->updateOffer(array('date_create' => date( 'Y-m-d H:i:s')), $this->_tab['type'], $this->_tab['suffix'], $cid);
						
							if ($is === true) $success[] = $cid;
								elseif ($is === false) $error[] = $cid;
									elseif ($is === -1) $no_access[] = $cid;
									
							if ($is)
							{
								JFactory::getDBO()->setQuery("INSERT INTO jos_users_orders_payedfunctions SET `cid` = '".$cid."', `uid` = '".$this->getUser('id')."', `table` = '".$this->_tab['type']."', `suffix` = '".$this->_tab['suffix']."', `function` = 'UP';");
								$is = JFactory::getDBO()->query();
							}
						}
						
						//messages
						if (!empty($success))
						{
							$multi = '';
							if (count($success) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'UP_SUCCESS' ,'#'.implode(', #', $success)), 'notice');
							
							//nulify cache
							$this->_update_allowed = null;
						}
							
						if (!empty($no_access))
						{
							$multi = '';
							if (count($no_access) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'UP_NOACCESS' ,'#'.implode(', #', $no_access)), 'warning');
						}	

						if (!empty($error))
						{
							$multi = '';
							if (count($error) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'UP_ERROR' ,'#'.implode(', #', $error)), 'error');
						}
						
						if (count($to_update) < count($action['ids']))
						{
							$multi = '';
							$failed = array_diff($action['ids'], $to_update);
							if (count($failed) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'UP_ERRORLIMIT' ,'#'.implode(', #', $failed), $refresh_limit), 'warning');						
						}
						
						
					}
					else
					{
						$multi = '';
						if (count($action['ids']) > 1) $multi = 'MULTI';
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'UP_ERRORLIMIT' ,'#'.implode(', #', $action['ids']), $refresh_limit), 'warning');
					}
					break;
					
				case 'refresh':
				
					$success=$no_access=$error=array();	

					$refresh_limit = $this->updateLimit;
					
					$days = (intval($action['data']) > 0 ? intval($action['data']) : 4);
					
					if (($limit = $this->checkUpdateAllowed($this->_tab['type'], $this->_tab['suffix'], $refresh_limit)) > 0)
					{

						$to_update = array_slice($action['ids'], 0, $limit);
						
						$splitter = $this->splitTable($this->_tab['suffix']);
						$movers_table = $this->suffix.$this->_tab['type'].$splitter->suffix;
						
						$updated_string = $update = null;
						
						if ($this->_fieldExists($movers_table, 'date_from') and $this->_fieldExists($movers_table, 'date_to'))
						{
							$update = array('date_from' => date('d-m-Y'), 'date_to' => date('d-m-Y', strtotime('+'.$days.' days')));
							$updated_string = JText::_($this->_tab['type'].$this->_tab['suffix']."_DATE_FROM")." ".$update['date_from']." ".JText::_("LABEL_TILL")." ".$update['date_to'];
		
						}	
						elseif ($this->_fieldExists($movers_table, 'date'))
						{
							$update = array('date' => date('d-m-Y', strtotime('+'.$days.' days')));	
							$updated_string = JText::_($this->_tab['type'].$this->_tab['suffix']."_DATE")." ".$update['date'];							
						}		
						
						if (!empty($update))
						{
							foreach($to_update as $cid)
							{	
								$is = $this->updateOffer($update, $this->_tab['type'], $this->_tab['suffix'], $cid);													


								if ($is === true) $success[] = $cid;
									elseif ($is === false) $error[] = $cid;
										elseif ($is === -1) $no_access[] = $cid;
										
								if ($is)
								{
									JFactory::getDBO()->setQuery("INSERT INTO jos_users_orders_payedfunctions SET `cid` = '".$cid."', `uid` = '".$this->getUser('id')."', `table` = '".$this->_tab['type']."', `suffix` = '".$this->_tab['suffix']."', `function` = 'REFRESH';");
									$is = JFactory::getDBO()->query();
								}
							}
						}
						else
						{
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'REFRESH_ERROR' ,'#'.implode(', #', $to_update)), 'error');
						}
						
						//messages
						if (!empty($success))
						{
							$multi = '';
							if (count($success) > 1) $multi = 'MULTI';
							
							$message = JText::sprintf('ACTION_'.$multi.'REFRESH_SUCCESS' ,'#'.implode(', #', $success));
							if (!empty($updated_string)) $message.= "<br />".JText::sprintf('ACTION_UPDATESTRING',$updated_string);
							
							
							JFactory::getApplication()->enqueueMessage($message, 'notice');
							
							//nulify cache
							$this->_update_allowed = null;
						}
							
						if (!empty($no_access))
						{
							$multi = '';
							if (count($no_access) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'REFRESH_NOACCESS' ,'#'.implode(', #', $no_access)), 'warning');
						}	

						if (!empty($error))
						{
							$multi = '';
							if (count($error) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'REFRESH_ERROR' ,'#'.implode(', #', $error)), 'error');
						}
						
						if (count($to_update) < count($action['ids']))
						{
							$multi = '';
							$failed = array_diff($action['ids'], $to_update);
							if (count($failed) > 1) $multi = 'MULTI';
							JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'REFRESH_ERRORLIMIT' ,'#'.implode(', #', $failed), $refresh_limit), 'warning');						
						}
						
						
					}
					else
					{
						$multi = '';
						if (count($action['ids']) > 1) $multi = 'MULTI';
						JFactory::getApplication()->enqueueMessage(JText::sprintf('ACTION_'.$multi.'REFRESH_ERRORLIMIT' ,'#'.implode(', #', $action['ids']), $refresh_limit), 'warning');
					}
					break;
			}
		}

		

		

		//Валидация данных

		private function _validate (&$data, $required_fields = array())

		{	

			$error = array ();

			if (isset($data) and !empty($data))

			{

				//импортируем массив с обязательными полями

				$temp = explode("_",$_POST['target']);

				$type = $temp[0];

				$suffix = isset($temp[1])?$temp[1]:"";

				

				$label = $type.$suffix;
				
				$label = $this->_tab['type'].$this->_tab['suffix'];

				$fields = (array)$this->_required[$label];

				

				//патерны проврки даты

				$patterns = array ('/(\d{1,2})-(\d{1,2})-(19|20)(\d{2})/');

				$replace = array ('\3\4-\2-\1');

				//валидация

				foreach ($data as $key=> &$value)

				{

					if (!is_array($value)) $data[$key] = $value = trim($value);
					
					
					//Validate empty
					if (empty($value) and array_key_exists($key,$fields))

					{

						if ($key == "phone2" || $key == "phone3") continue;

						$error[$key] = $fields[$key];
						
					}

					
					//Validate specific fields
					if (array_key_exists($key,$fields) or stristr($key, "phone"))

					{

						if ($key == "email" and !$this->isValidEmail($value, true))

						{

							$error[$key] = $fields[$key];

						}

						

						if ( stristr($key, "date") and !$this->isValidDate($value) )

						{

							$error[$key] = $fields[$key];

						}

						

						if (stristr($key, "phone"))

						{

							$phone = array();

							

							if (is_array($value))

							{

								//собираем inputы с телефонным номером

								foreach ($value as $kkey=>$vvalue)

								{

									$data[$key][$kkey] = str_replace(array("-", " ", "+"), "", $data[$key][$kkey]);

									$phone[] = $data[$key][$kkey];

									if (empty($data[$key][$kkey])) unset ($data[$key][$kkey]);

								}
								
								if (!empty($phone)) $phone = '+'.implode('-',$phone);

							}

							else

							{

								$phone = $value;

							}

							

							if (($key == "phone1" or $key == "phone" ) and ($phone =="+--" or $phone =="--" or empty($phone)))

							{

								$error[$key] = $fields[$key];

							}

							else if (!empty($phone) and $phone !="+--" and $phone !="--" and !$this->isValidPhone($phone))

							{

								$error[$key] = $fields[$key].' | '.JTEXT::_('FORMAT').': +'.JTEXT::_('COUNTRY_CODE').'-'.JTEXT::_('CITY_CODE').'-'.JTEXT::_('PHONE_NUMBER');



							} else if (!empty($phone) and $phone !="+--" and $phone !="--") {

								$data["temp"][$key] = $phone;

							}

							

						}

					}

				}

				

				if (empty($error) and (isset($data['date_from']) or isset($data['date_to'])))

				{

					//date check

					$now = time();

					if (!empty($data['date_from']) and $this->isValidDate($data['date_from']))
						$date_from = strtotime($data['date_from']);
					else
						$date_from = $now;

					
					if (!empty($data['date_to']) and $this->isValidDate($data['date_to']))
						$date_to = strtotime($data['date_to']);
					else
						$date_to = $now;

							

					if ($date_from > $date_to)

					{

						$temp = $date_from;

						$date_from = $date_to;

						$date_to = $temp;

					}

							

					if ($date_from == $date_to && $date_to == $now)

					{

						$date_to += 604800; //week

					}

							

					$data['date_from'] = date('d-m-Y', $date_from);

					$data['date_to'] = date('d-m-Y', $date_to);

				}

			}

			return $error;

		}

		

		

		private function _tableExists ($table = false)

		{
			
			if (!empty($table))

			{
				
				if (isset($this->table_cache[$table])) return true;
				
				$cache = $this->getCacheObj(86400);
			
				//static call for cache
				$is = $cache->call(array('TabUtils', '_tableExistsSQL'), $table);
				
				if ($is)
				{
					$this->table_cache[$table] = array();
					return true;
				}

			}

			return false;

		}

		public function _tableExistsSQL($table = false)
		{
			$arResult = false;
			
			$db = TabUtils::getdb();
			
			$db->setQuery("SHOW TABLES LIKE '".$table."'");

			$db->query();

			if ($db->getNumRows() > 0) $arResult = true;

			return $arResult;
		
		}

		public function _fieldExists($table = false, $field = false)

		{
			
			if (empty($table) or empty($field)) return false;
			
			if (!$this->_tableExists($table)) return false;
			
			if (isset($this->table_cache[$table][$field])) return true;
			
			$cache = $this->getCacheObj(86400);
			
			//static call for cache
			$this->table_cache[$table] = $cache->call(array('TabUtils', '_fieldExistsSQL'), $table);

			if (isset($this->table_cache[$table][$field])) return true;

			return false;

		}

		
		public function _fieldExistsSQL($table = false)
		
		{
		
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			$db->setQuery("Show columns from ".$table);

			$fields = $db->loadAssocList();
			
			if (is_array($fields))
			{
				//$optimize = &$fields;
				foreach ($fields as &$data)
				{
					$arResult[$data['Field']] = $data;
				}
			
			}
			
			return $arResult;
			
		}

		

		function _getAuxTabs()
		{
			if (!empty($this->_session_data['search']))
			{
				$this->_filter = $this->_session_data['search'];			

				foreach ($this->_filter as $key => $data)
				{
					if (stristr($key, 'display_filter'))
					{	
						$parts = explode('_', $key);
						
						if (count($parts) > 2)
						{
							$alias = str_replace('display_filter', 'display_table', $key);
							$function = 'display_table_'.$parts[2];
							$type = $parts[2];
							$suffix = (isset($parts[3]) and !empty($parts[3])) ? '_'.$parts[3] : '';
							
							$this->_filter[$alias] = $data;							
							$title = $this->buildTabTitle($alias, $type.$suffix);
							
							$tab = array (
											'function' => $function,
											'alias' => $alias,
											'type' => $type,
											'suffix' => $suffix,
											'title' => $title,
											'link' => $key,
											'tab_link' => $key,
											'tab_offers_url' => $this->_params->get('tab_offers_'.$type),
										);
										
							$tabs[$alias] = $tab;
						}
					}
				}	

				return $tabs;
			}
		}

		

		public function _getTabs ()
		{

			if ($this->_params->get('tab_type') == "tabs_2") $aux_tabs = $this->_getAuxTabs();
			$behavior = $this->_params->get('tab_behavior');

			$tabs = array();

			for ($i = 1; $i <=4; $i++)
			{
				if ($this->_params->get('tab_'.$i) == 1)
				{
					$check = true;
					$tabs[$i]['tab'] = 'tab_'.$i;
					$tabs[$i]['title']= $this->_params->get('tab_'.$i.'_title');
					$tabs[$i]['inner_title']= $this->_params->get('tab_'.$i.'_innertitle');

					if (empty($tabs[$i]['title'])) $check = false;

					$tabs[$i]['function'] = $this->_params->get('tab_'.$i.'_function');
					if (empty($tabs[$i]['function'])) $check = false;
					if (!$check) { unset($tabs[$i]); continue;}

					
					$tabs[$i]['suffix'] = $this->_params->get('tab_'.$i.'_suffix');	
					$tabs[$i]['filter'] = $this->_params->get('tab_'.$i.'_filter');
					$tabs[$i]['alias'] = $tabs[$i]['function'].$tabs[$i]['suffix'];

					if (empty($tabs[$i]['alias'])) $tabs[$i]['alias'] = 'tab_$i';

					
					$tabs[$i]['tab_link'] = $tabs[$i]['alias'];	
					$type = explode("_",$tabs[$i]['function']);
					$tabs[$i]['type'] = $type[2];
					
					//_aux ?
					if ($behavior != 1)
					{
						if (stristr($tabs[$i]['function'],'filter_cargo'))
							$tabs[$i]['tab_link'] = $tabs[$i]['link'] = "display_table_cargo".$tabs[$i]['suffix'];

						elseif (stristr($tabs[$i]['function'],'filter_order'))
								$tabs[$i]['tab_link'] = $tabs[$i]['link'] = "display_table_order".$tabs[$i]['suffix'];

						if ($this->_params->get('tab_type') == "tabs")	$position[$i] = $tabs[$i]['link'];
					}

					

					$tabs[$i]['perpage'] = $this->_params->get('tab_'.$i.'_perpage');

					if (empty($tabs[$i]['perpage']) or $tabs[$i]['perpage'] < 0) $tabs[$i]['perpage'] = 45;

					

					$tabs[$i]['submit'] = $this->_params->get('tab_'.$i.'_submit');

					if (empty($tabs[$i]['submit'])) $tabs[$i]['submit'] = "LABEL_SEND";
					
					$tabs[$i]['tab_url'] = $this->_params->get('tab_'.$i.'_url');
					$tabs[$i]['tab_internal_url'] = $this->_params->get('tab_'.$i.'_internal_url');
					$tabs[$i]['tab_success_url'] = $this->_params->get('tab_'.$i.'_success_url');
					$tabs[$i]['tab_success_offers_url'] = $this->_params->get('tab_'.$i.'_success_offers_url');
					$tabs[$i]['tab_success_internaloffers_url'] = $this->_params->get('tab_'.$i.'_success_internaloffers_url');
					$tabs[$i]['tab_user_url'] = $this->_params->get('tab_'.$i.'_user_url');
					$tabs[$i]['tab_userinternal_url'] = $this->_params->get('tab_'.$i.'_userinternal_url');
					$tabs[$i]['tab_usersuccess_url'] = $this->_params->get('tab_'.$i.'_usersuccess_url');
					$tabs[$i]['tab_usersuccess_offers_url'] = $this->_params->get('tab_'.$i.'_usersuccess_offers_url');
					$tabs[$i]['tab_usersuccess_internaloffers_url'] = $this->_params->get('tab_'.$i.'_usersuccess_internaloffers_url');
					$tabs[$i]['tab_offers_url'] = $this->_params->get('tab_'.$i.'_offers_url');
					if (isset($aux_tabs) and count($aux_tabs) > 1 and $source_tab = $this->array_search_key('source_tab', $aux_tabs) and $tabs[$i]['alias'] == $source_tab ) $index = $i;

				}

			}

			if (isset($position)) $session_data['position'] = $position;

			if ($this->_params->get('tab_type') == "tabs_2" and count($tabs) < 3 and isset($aux_tabs))
			{
			

				//we have tabs to add and there is more room
				//sort aux_tabs;
				if (count($aux_tabs) > 2) $aux_tabs = array_slice($aux_tabs,0,2);
				
				if (count($aux_tabs) == 2  and $behavior != 1)
				{
					$data =  $this->_session_data['position'];

					if (is_array($data))
					{
						$first_aux_tab_pos = array_search($first_aux_tab['alias'],$data);
						$second_aux_tab_pos = array_search($second_aux_tab['alias'],$data);

						if ((int)$second_aux_tab_pos < (int)$first_aux_tab_pos) 
						{
							$first_aux_tab = reset($aux_tabs);
							$second_aux_tab = next($aux_tabs);

							unset($aux_tabs);

							$aux_tabs['first'] = $second_aux_tab;
							$aux_tabs['second'] = $first_aux_tab;
						}
					}
				}

				$tabs = $tabs + $aux_tabs;

				if (isset($index) and count($tabs) > 2)
				{
					$path = $this->array_get_path('source_tab',$aux_tabs, array());
					$statistics_tab[$path[0]] = $tabs[$path[0]];
					
					unset($tabs[$path[0]]);

					$temp = $tabs;
					$first_piece = array_slice($temp,0,$index);
					$second_piece = array_slice($temp,$index,count($tabs));
					$tabs = array_merge($first_piece,$statistics_tab,$second_piece);	
				}

				if (count($tabs) > 3) $tabs = array_slice($tabs, 0, 3);
			}
			return $tabs;
		}


		public function getFilterTab ($param)
		{
			if (!$param) return false;
			
			if (is_array($this->_filter[$param]) and !empty($this->_filter[$param]))
					return $this->_filter[$param];

			return false;
		}

		

		public function getFilterSearch ($param)
		{			
			if (!$param) return false;
			
			if (is_array($this->_filter[$param]) and !empty($this->_filter[$param]))
					return $this->_filter[$param];

			return false;
		}

		
		public function _getActiveTab ()

		{

			$session_data = $this->_session_data;
			
			$this->test = $this->_params->get('tab_type');
			
			if (isset($_GET['tab'])) {

				$active_tab = (string)$_GET['tab'];

			} elseif ($this->_params->get('tab_type') == "tabs" and isset($session_data['first'])) {

				$active_tab = $session_data['first'];

			} elseif ($this->_params->get('tab_type') == "tabs_2" and isset($session_data['second'])) {

				$active_tab = $session_data['second'];

			} else {

				$active_tab = 0;

			}

			return $active_tab;

		}

		

		public function _setActiveTab ($position = "", $tab = "display_filter_cargo")
		{
			if (isset($position) and isset($tab) and !empty($position) and !empty($tab))
			{
				$session_data = $this->_session_data;				
				$session_data[$position]=$tab;
				$this->_session_data = $session_data;
			}
		}

		public function getFilteredIDS ($table = false, $data = false)
		{

			if (!$this->_tableExists($table)) return;
			
			$suffix = ($this->_split->original == "" ? "_international" : $this->_split->original);
			
			if (empty($this->cache[$table][$suffix]))
			{		
				//static call for cache
				//adding suffix
				$this->cache[$table][$suffix] = $this->cacheObj->call( array( 'TabUtils', 'getFilteredIDSQuery' ) , $table, $this->_split->merge );
			}

			if (!empty($data)) return $this->cache[$table][$suffix][$data];
		}
		
//****For cache purpose*****//

		public function getFilteredIDSQuery($table = false, $merge = '')
		{
				
				$arResult = array();
				
				return $arResult;
				
				$db = TabUtils::getdb();
				
				$fields = array ('import', 'export', 'type', 'name', 'volume');
				
				$select = str_replace('m.,', '', 'm.'.implode(",m.", $fields));
				
				$query = "SELECT DISTINCT ".$select." FROM ".$table." as m WHERE m.hidden = 0 ".$merge;
				
				$db->setQuery($query);
				
				$temp = $db->loadAssocList();
				
				//$optimize = &$temp;
				//$optimize2 = &$fields;
				
				//reset($temp);
				/*
				while(list($i, $item) = each($temp))
				{
					reset($fields);
					while (list($j, $field) = each($fields))
					{
						if (intval($item[$field]) > 0) $arResult[$field][] = $item[$field];
					}
				}
				
				reset($fields);
				while (list($j, $field) = each($fields))
				{
					if (!empty($arResult[$field])) $arResult[$field] = array_unique($arResult[$field]);
				}
				*/
				
				foreach ($temp as &$item)
				{
					foreach ($fields as &$field)
					{
						if (intval($item[$field]) > 0) $arResult[$field][] = $item[$field];
					}
				}
				
				foreach ($fields as $field)
				{
					if (!empty($arResult[$field])) $arResult[$field] = array_unique($arResult[$field]);
				}
				
				return $arResult;
		}
//**************//
		
		/*Получение названия страны в слоге*/
		public function getCountryNameInflected($country_name = false, $inflect = false)
		{
			$arResult = $country_name;
			
			//store the cache
			if (empty($this->cache['country']))
			{
				$cache = $this->getCacheObj(86400);
				$this->cache['country'] = $cache->call(array('TabUtils', 'getCountryListQuery'), $this->language);
			}

			if (isset($this->cache['country'][$country_name]))
			{
				if (!in_array($inflect, array('from', 'to')) or $this->language != 'ru')
					$arResult = $this->cache['country'][$country_name]['data_title'];
				else
					$arResult = $this->cache['country'][$country_name]['data_title_'.$inflect];
			}
			
			return $arResult;
		}
		
		
		/*Формирование списка стран */
		public function getCountryList($table = false, $data = false, $exception = false)

		{
			
			$arResult = array();
			
			//store the cache
			if (empty($this->cache['country']))
			{
				$cache = $this->getCacheObj(86400);
				$this->cache['country'] = $cache->call(array('TabUtils', 'getCountryListQuery'), $this->language);
			}

			//no data
			if (!is_array($this->cache['country']) or empty($this->cache['country'])) return $arResult;
			
			if ($filtered_id = $this->getFilteredIDS($table, $data) and !empty($filtered_id))

			{

				//для фильтра - если нас перекинуло с другого раздела, и в новом разделе не заявок по нужным направлениям - выводим в селекте страну как исключение
				if (!empty($exception) and (int)$exception > 0) $filtered_id[] = $exception;
				
				//$optimize = &$this->cache['country'];
				foreach ($this->cache['country'] as &$item)
				{
					if (in_array($item['data_id'], $filtered_id)) $arResult[] = $item;
				}

			}

			else
			
			{

				//$optimize = &$this->cache['country'];
				foreach ($this->cache['country'] as &$item)
				{
					if ($item['data_hidden'] === '0') $arResult[] = $item;
				}
				
			}

			return $arResult;

		}

		

		public function getCountryListbyArray($filtered_id = false)

		{
		
			$arResult = array();
			
			//store the cache
			if (empty($this->cache['country']))
			{
				$cache = $this->getCacheObj(86400);
				$this->cache['country'] = $cache->call(array('TabUtils', 'getCountryListQuery'), $this->language);
			}
			
			//no data
			if (!is_array($this->cache['country']) or empty($this->cache['country'])) return $arResult;
			
			$arResult = $this->cache['country'];
			ksort($arResult);

			if (is_array($filtered_id) and !empty($filtered_id))
			{

				foreach ($arResult as $key => &$data)
				{
					if (!in_array($data['data_id'], $filtered_id)) unset($arResult[$key]);
				}

			}

			return $arResult;
			
		}

		public function getCountryListQuery($language = "ru")
		{
		
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			$query = "SELECT country_name_ru_from as data_title_from, country_name_ru_to as data_title_to, country_hidden as data_hidden, country_group as group_id, id_country as data_id, country_name_".$language." as data_title, alpha3 as code FROM country ORDER BY country_group ASC, country_name_".$language." ASC";
				
			$db->setQuery($query);

			$db->query();
				
			$temp = $db->loadAssocList();
				
			//$optimize = &$temp;
			foreach ($temp as &$item)
			{
				$arResult[$item['data_title']] = $item;
			}
			
			return $arResult;
		}		

		public function buildCountryList($table = false, $data = false, $exception = false)

		{

			

			$list = "";	
			$result = $this->getCountryList($table,$data,$exception);
			

			if (!empty($result))

			{

				$group_id = $result[0]['group_id'];

				$list .= "<optgroup label='- - - - - - - - - - - - - - - - - - - -'>\n";

				foreach ($result as $row)

				{

					if ($row['group_id'] != $group_id)

					{

						$list .="</optgroup>\n";

						$group_id = $row['group_id'];

						$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - -'>\n";

					}

					

					$list.= "<option value='".$row['data_id']."'";
					
					if (intval($exception) == intval($row['data_id']))
						$list.= " selected";					

					$list .= ">".$row['data_title'];

					if ($row['code']) $list .= " [".$row['code']."]";

					$list .= "</option>\n";

				}

				$list .="</optgroup>\n";

				$list .= "<optgroup class='delimiter' label=''></optgroup>\n";

			}

			return $list;

		}

		/*Формирование списка стран */

		

		/*Формирование списка городов */

		public function buildCityList ($id_country = false, $selected = false)

		{

			$city_list = "";
			
			$cache = $this->getCacheObj(86400);
			$city = $cache->call(array('TabUtils', 'buildCityListQuery'), $id_country, $this->language);
			
			if (count($city) > 0)
			{
				$city_list .="<optgroup label='- - - - - - - - - - - - - - - - - - - -'>";

				foreach ($city as $key=>$row)

				{

					$city_list .= "<option value='".$row['data_id']."'";
							
					if ($selected === $row['data_id'])
						$city_list.= " selected";
								
					$city_list .= ">".$row['data_title']."</option>\n";

				}

				$city_list .= "</optgroup>\n";

			}

			return $city_list;

		}

		public function buildCityListQuery($id_country, $language = 'ru')
		{
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			if (intval($id_country) > 0)
			{
				$db->setQuery("SELECT id_country FROM country WHERE id_country='".$id_country."'");

				$db->query();

				if ($db->getNumRows() == 1 )
				{

					$db->setQuery("SELECT id_city as data_id, city_name_". $language . " as data_title FROM city WHERE id_country = '".$id_country."' ORDER BY city_name_". $language ." ASC");

					$arResult = $db->loadAssocList();	
						
				}
			}
			
			return $arResult;
		}

		/*Формирование списка транспорта */

		public function getTransportTypeList($table = false, $data = "type")

		{

			$arResult = array();
			
			$s1 = ($this->_split->original == "" ? "_international" : $this->_split->original);;
			$s2 = "transport_type";
			
			//store the cache
			if (empty($this->cache[$s1][$s2]))
			{
			
				$cache = $this->getCacheObj(86400);
				$this->cache[$s1][$s2] = $cache->call(array('TabUtils', 'getTransportTypeListQuery'), $this->_split->suffix, $this->_split->split, $this->language);

			}
			
			//no data
			if (!is_array($this->cache[$s1][$s2]) or empty($this->cache[$s1][$s2])) return $arResult;
			//$optimize = &$this->cache[$s1][$s2];

			if ($filtered_id = $this->getFilteredIDS($table, $data) and !empty($filtered_id))
			{	
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if (in_array($item['data_id'], $filtered_id)) $arResult[] = $item;
				}
			}
			else
			{
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if ($item['data_hidden'] === '0') $arResult[] = $item;
				}
			}

			return $arResult;
			
		}

		
		public function getTransportTypeListQuery($suffix, $split, $language = 'ru')
		{
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			$query = "SELECT transport_type_hidden as data_hidden, id as data_id, transport_type_group as group_id , transport_type_".$language." as data_title  FROM  transport_type".$suffix." WHERE 1=1 ".$split." ORDER BY transport_type_group ASC, `order` ASC, transport_type_".$language." ASC";
					
			$db->setQuery($query);
					
			$temp = $db->loadAssocList();
					
			//$optimize = &$temp;
			foreach ($temp as &$item)
			{
				$arResult[$item['data_title']] = $item;
			}
			
			return $arResult;
		}
		

		public function buildTransportTypeList($table = false, $data = false, $selected = false)
		{	

			$list = "";

			$result = $this->getTransportTypeList($table,$data);

			

			if (!empty($result))

			{

				$group_id = $result[0]['group_id'];

				$list .= "<optgroup label='- - - - - - - - - - - - - - - - - - - - - -'>\n";

				foreach ($result as $row)

				{

					if ($row['group_id'] != $group_id)

					{

						$list .="</optgroup>\n";

						$group_id = $row['group_id'];

						$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - -'>\n";

					}

					

					$list.= "<option value='".$row['data_id']."'";
					
					if (intval($selected) == intval($row['data_id']))
						$list.= " selected";

					$list .= ">".$row['data_title'];

					$list .= "</option>\n";

				}

				$list .="</optgroup>\n";

				$list .= "<optgroup class='delimiter' label=''></optgroup>\n";

			}

			return $list;

		}

		

		public function buildCargoTypeList ($suffix = false) {

			

			$cargo_type_list = "";

			if (!$suffix) $suffix = $this->_split->suffix;

			

			$query = "SELECT id, cargo_type_group, cargo_type_".$this->language." as cargo_types FROM cargo_type".$suffix." WHERE cargo_type_hidden != 1 ORDER BY cargo_type_group ASC, `order` ASC, cargo_type_".$this->language." ASC";

			$this->db->setQuery($query);

			$this->db->query();

			

			if ($this->db->getNumRows() > 0) {

				$cargo_type = $this->db->loadAssocList();

				$group_id = $cargo_type[0]['cargo_type_group'];

				$cargo_type_list = "<optgroup label='- - - - - - - - - - - - - - - - - - - - - -'>";

				foreach ($cargo_type as $key=>$row) {

					if ($row['cargo_type_group'] != $group_id) {

						$cargo_type_list .= "</optgroup>";

						$group_id = $row['cargo_type_group'];

						$cargo_type_list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - - - -'>";

					}

					$cargo_type_list .= "<option value='".$row['id']."'>".$row['cargo_types']."</option>";

				}

				$cargo_type_list .= "</optgroup>";

				$cargo_type_list .= "<optgroup class='delimiter' label=''></optgroup>";

			}

			

			return $cargo_type_list;

		}

		

		

//////////////////////////

		/*Формирование списка объёма */	

		public function getСargoVolumeList($table = false, $data = "volume")

		{

		
			$arResult = array();

			$s1 = ($this->_split->original == "" ? "_international" : $this->_split->original);
			$s2 = "cargo_volume";
			
			//store the cache
			if (empty($this->cache[$s1][$s2]))
			{
			
				$cache = $this->getCacheObj(86400);
				$this->cache[$s1][$s2] = $cache->call(array('TabUtils', 'getСargoVolumeListQuery'), $this->_split->suffix, $this->_split->split, $this->language);

			}
			
			//no data
			if (!is_array($this->cache[$s1][$s2]) or empty($this->cache[$s1][$s2])) return $arResult;
			//$optimize = &$this->cache[$s1][$s2];

			if ($filtered_id = $this->getFilteredIDS($table, $data) and !empty($filtered_id))
			{	
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if (in_array($item['data_id'], $filtered_id)) $arResult[] = $item;
				}
			}
			else
			{
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if ($item['data_hidden'] === '0') $arResult[] = $item;
				}
			}

			return $arResult;
			
		}

		
		public function getСargoVolumeListQuery($suffix, $split, $language = 'ru')
		{
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			$query = "SELECT cargo_volume_hidden as data_hidden, id as data_id, cargo_volume_group as group_id , cargo_volume_".$language." as data_title  FROM  cargo_volume".$suffix."   WHERE 1=1 ".$split." ORDER BY `order` ASC, id ASC";

			$db->setQuery($query);
				
			$temp = $db->loadAssocList();
				
			//$optimize = &$temp;
			
			foreach ($temp as &$item)
			{
				$arResult[$item['data_title']] = $item;
			}
			
			return $arResult;
		}

		
		public function buildCargoVolumeList($table = false, $data = false, $selected = false)

		{

			

			$list = "";

			

			$result = $this->getСargoVolumeList($table,$data);

			

			if (!empty($result))

			{

				$group_id = $result[0]['group_id'];

				$list .= "<optgroup label='- - - - - - - - - - - - - - - - - - - - - -'>\n";

				foreach ($result as $row)

				{

					if ($row['group_id'] != $group_id)

					{

						$list .="</optgroup>\n";

						$group_id = $row['group_id'];

						$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - -'>\n";

					}

					

					$list.= "<option value='".$row['data_id']."'";
					
					if (intval($selected) == intval($row['data_id']))
						$list.= " selected";

					$list .= ">".$row['data_title'];

					$list .= "</option>\n";

				}

				$list .="</optgroup>\n";

				$list .= "<optgroup class='delimiter' label=''></optgroup>\n";

			}

			return $list;

		}

		
/*Формирование списка типов груза */

		public function getСargoList($table = false, $data = "name")

		{

			
			$arResult = array();

			$s1 = ($this->_split->original == "" ? "_international" : $this->_split->original);
			$s2 = "cargo_type";
			
			//store the cache
			if (empty($this->cache[$s1][$s2]))
			{
			
				$cache = $this->getCacheObj(86400);
				$this->cache[$s1][$s2] = $cache->call(array('TabUtils', 'getСargoListQuery'), $this->_split->suffix, $this->language);

			}
			
			//no data
			if (!is_array($this->cache[$s1][$s2]) or empty($this->cache[$s1][$s2])) return $arResult;
			//$optimize = &$this->cache[$s1][$s2];

			if ($filtered_id = $this->getFilteredIDS($table, $data) and !empty($filtered_id))
			{	
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if (in_array($item['data_id'], $filtered_id)) $arResult[] = $item;
				}
			}
			else
			{
				foreach ($this->cache[$s1][$s2] as &$item)
				{
					if ($item['data_hidden'] === '0') $arResult[] = $item;
				}
			}

			return $arResult;
		}

		
		public function getСargoListQuery($suffix, $language = 'ru')
		{
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			$query = "SELECT cargo_type_hidden as data_hidden, id as data_id, cargo_type_group as group_id , cargo_type_".$language." as data_title  FROM  cargo_type".$suffix." ORDER BY `cargo_type_group` ASC, `order` ASC, cargo_type_".$language." ASC";

			$db->setQuery($query);
				
			$temp = $db->loadAssocList();
				
			//$optimize = &$temp;
			foreach ($temp as &$item)
			{
				$arResult[$item['data_title']] = $item;
			}
			
			
			return $arResult;
			
		}
		
		
		public function buildCargoList($table = false, $data = false, $selected = false)

		{

			

			$list = "";

			

			$result = $this->getСargoList($table,$data);

			

			if (!empty($result))

			{

				$group_id = $result[0]['group_id'];

				$list .= "<optgroup label='- - - - - - - - - - - - - - - - - - - - - -'>\n";

				foreach ($result as $row)

				{

					if ($row['group_id'] != $group_id)

					{

						$list .="</optgroup>\n";

						$group_id = $row['group_id'];

						$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - -'>\n";

					}

					

					$list.= "<option value='".$row['data_id']."'";
					if (intval($selected) == intval($row['data_id']))
						$list.= " selected";
					$list .= ">".$row['data_title'];

					$list .= "</option>\n";

				}

				$list .="</optgroup>\n";

				$list .= "<optgroup class='delimiter' label=''></optgroup>\n";

			}

			return $list;

		}
		
		
		public function buildContainerTypeList ($suffix = false) {

			

			$list = "";

			if (!$suffix) $suffix = $this->_split->suffix;
			
			if (empty($this->cache['container_type']))
			{
			
				$cache = $this->getCacheObj(86400);
				$this->cache['container_type'] = $cache->call(array('TabUtils', 'buildContainerTypeListQuery'), $suffix, $this->language);			
				
			}


			$result = $this->cache['container_type'];
			
			if (!empty($result))
			{

				$group_id = $result[0]['group_id'];

				$list = "<optgroup label='- - - - - - - - - - - - - - - - - - - - - -'>";

				foreach ($result as $key=>$row) {

					if ($row['group_id'] != $group_id) {

						$list .= "</optgroup>";

						$group_id = $row['group_id'];

						$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - - - -'>";

					}

					$list .= "<option value='".$row['data_id']."'>".$row['data_title']."</option>";

				}

				$list .= "</optgroup>";

				$list .= "<optgroup class='delimiter' label=''></optgroup>";

			}

			return $list;

		}
		
		public function buildContainerTypeListQuery ($suffix, $language = 'ru')
		{
			$arResult = array();
			
			$db = TabUtils::getdb();

			$query = "SELECT id as data_id, container_type_group as group_id, container_type_".$language." as data_title FROM container_type".$suffix." WHERE container_type_hidden = 0 ORDER BY container_type_group ASC, `order` ASC, container_type_".$language." ASC";

			$db->setQuery($query);
			
			$arResult = $db->loadAssocList();
			
			return $arResult;
			
			
		}

//some utils function to work with count orders

		public function countOrdersByTable($table_type = "cargo", $suffix = false, $field = 'id', $filter = array())
		{
			$arResult = array();
			
			//get data
			if ($suffix !== false) $splitter = $this->splitTable($suffix);
				elseif (isset($this->_split)) $splitter = $this->_split;
					else return $arResult;
			
			//cache fields of table
			$table = $this->suffix.$table_type.$splitter->suffix;
			
			if (!empty($field) and !$this->_fieldExists($table, $field)) return $arResult;
			
			$filter_string = '';
			$filter = array_filter((array)$filter);
				
			foreach ($filter as $filter_field => $filter_value)
			{
				if ($this->_fieldExists($table, $filter_field)) $filter_string .= " AND m.".$filter_field."='".$filter_value."' ";
			}
			
			
			$join_id = "id";
			$join_table = "transport_type".$splitter->suffix;
			
			switch ($field)
			{
			
				case "export":
				case "import":
					$join_id = "id_country";
					$join_table = "country";
					break;
				
				case "export_city":
				case "import_city":
					$join_id = "id_city";
					$join_table = "city";
					break;

				case "type":
					$join_table = "transport_type".$splitter->suffix;
					break;
					
				case "name":
					$join_table = "cargo_type".$splitter->suffix;
					break;
					
				case "volume":
					$join_table = "cargo_volume".$splitter->suffix;
					break;
				case "id":
					//simple count
					$field = false;
					
					break;
			}
			
			if (empty($field))
				$query = "SELECT COUNT(m.id) AS count FROM ".$table." AS m WHERE 1=1 ".$splitter->default_offer_filter.$splitter->merge;		
			else
				$query = "SELECT m.".$field." AS cid, COUNT(m.".$field.") AS count FROM ".$table." AS m RIGHT JOIN ".$join_table." AS c on m.".$field." = c.".$join_id." WHERE 1=1 ".$filter_string.$splitter->default_offer_filter.$splitter->merge." GROUP BY c.".$join_id;		

			$cache = $this->getCacheObj(60);
			$arResult = $cache->call(array('TabUtils', 'countOrdersByTableQuery'), $query, $field);
			
			return $arResult;
		}
		
		public function countOrdersByTableQuery($query, $field)
		{
			$arResult = array();
			
			$db = TabUtils::getdb();
			
			if (strlen($query) > 0)
			{
				$db->setQuery($query);

				if(!empty($field))
				{
					$temp = $db->loadAssocList();
					//$optimize = &$temp;
					foreach	($temp as &$data)
						$arResult[$data['cid']] = $data['count'];			
				}
				else
					$arResult = $db->loadResult();
			}
			
			return $arResult;
		}

		public function getGetParam ($param = false)

		{

			if ($param and isset($_GET[$param])) return (int)$_GET[$param];

			return false;

		}

		

		public function getPage ($param = false)

		{

			$session_data = $this->_session_data;

			if ( isset($session_data['pagination']) and isset($session_data['pagination'][$param]) and intval($session_data['pagination'][$param]) > 0) return intval($session_data['pagination'][$param]);

				else return 1;

		}

		

		public function buildFilterQuery($param = false)
		{
			$query = "";

			if ($param and is_array($this->_filter[$param]) and !empty($this->_filter[$param]))
			{
				$filter = $this->_filter[$param];		
					
				$splitter = $this->splitTable($this->_tab['suffix']);
				$table = $this->suffix.$this->_tab['type'].$splitter->suffix;;

				foreach ($filter as $key=>$value)
				{
					if (!empty($value) and ($key == "date_between" or $this->_fieldExists($table, $key)))
					{
						if (stristr($key,"date"))
						{
							$query .= $this->getDateWhere(false, $key, $value);
							continue;
						}

						$query .=" AND m.$key = '$value'";
					}
				}
			}
			return $query ;
		}

		

		private function buildTabTitle ($param = false, $type = false)
		{
			$title = "";

			if ($param and $type)
			{		
				$data = $this->buildFilterRequest($param, true);

				if (!empty($data["export"]) and !empty($data["import"]))
				{

					if ($data["export"]->country_name == $data["import"]->country_name) $title = JTEXT::_($type)." ".$data["export"]->country_name;

						else $title = JTEXT::_($type)." ".JTEXT::_('LABEL_FROM')." ".$data["export"]->country_name_from." ".JTEXT::_('LABEL_TO')." ".$data["import"]->country_name_to;

				}
				elseif (!empty($data["export"]) and empty($data["import"]))
				{

					$title = JTEXT::sprintf('statistics_'.$type.'_export',$data["export"]->country_name_from);

				} 
				elseif (empty($data["export"]) and !empty($data["import"])) 
				{

					$title = JTEXT::sprintf('statistics_'.$type.'_import',$data["import"]->country_name_to);

				}
				else
				{

					$title = JTEXT::_('allproposal_'.$type);

				}		
			}
			else
			{
				$title = JTEXT::_('LABEL_TAB_SEARCH');
			}

			return $title;
		}

		

		public function buildFilterRequest($param = false, $return_array = false)

		{

			$request = "";
			$filter = $this->getFilterTab($param);

			if (!empty($filter))

			{

				$from = $to = "";

					

				if ($this->language == "ru")

				{

					$from = "_from";

					$to = "_to";

				}

					

				//get country and city ID's

				$cid = array();

				$cyid = array();

				if (!empty($filter['export']))

				{

					$cid["export"] = $filter["export"];

					if (!empty($filter['export_city'])) $cyid["export"] = $filter['export_city'];

				}

				if (!empty($filter['import']))

				{

					$cid["import"] = $filter["import"];

					if (!empty($filter['import_city'])) $cyid["import"] = $filter['import_city'];

				}



				if (!empty($cid))

				{

					$this->db->setQuery("SELECT id_country as id, country_name_".$this->language." as country_name, country_name_".$this->language.$from." as country_name_from, country_name_".$this->language.$to." as country_name_to FROM country WHERE id_country IN (".implode(",",$cid).")");

					$this->db->query();

					

					$cname = $this->db->loadObjectList('id');

					$cname = array("export"=>$cname[$cid["export"]], "import"=>$cname[$cid["import"]]);

					

					//tab title

					if ($return_array) return $cname;

					if (!empty($cname) and !empty($cyid))

					{

						$this->db->setQuery("SELECT id_city as id, city_name_".$this->language." as city_name FROM city WHERE id_city IN (".implode(",",$cyid).")");

						$this->db->query();

						

						$cyname = $this->db->loadObjectList('id');

						$cyname = array("export"=>$cyname[$cyid["export"]], "import"=>$cyname[$cyid["import"]]);

					}

				}

				

				

				//get text

				$text_export = "";

				$country_export = "";

				$text_import = "";

				$country_import = "";

				if (!empty($cname))

				{

					if(!empty($cname["export"]))

					{

						$text_export = JTEXT::_('LABEL_FROM')." ".$cname["export"]->country_name_from;

						$country_export = $cname["export"]->country_name;

						if (!empty($cyname["export"]))

						{

							$text_export .= " (".$cyname["export"]->city_name.")";

							$country_export .= " (".$cyname["export"]->city_name.")";

						}

					}

					

					if(!empty($cname["import"]))

					{

						$text_import = JTEXT::_('LABEL_TO')." ".$cname["import"]->country_name_to;

						$country_import = $cname["import"]->country_name;

						if (!empty($cyname["import"]))

						{

							$text_import  .= " (".$cyname["import"]->city_name.")";

							$country_import .= " (".$cyname["import"]->city_name.")";

						}

					}						

				

					//set tab title

					if ($filter['import'] == $filter['export']) $request = $cname["export"]->country_name;

						else $request = $text_export.((empty($text_import ) or empty($text_export))?"":" ").$text_import ;					

					$export_import = $country_export.((empty($country_import ) or empty($country_export))?"":" - ").$country_import;

					$export_import_inflect = $cname["export"]->country_name_from.((empty($cname["import"]->country_name_from) or empty($cname["export"]->country_name_from))?"": " ").$cname["import"]->country_name_from;
					
					if ($this->_tab['alias'] == $this->_session_data['second'])
					{
						$metadata = array(

										"{export}"=>$cname["export"]->country_name,

										"{export_inflect}"=>$cname["export"]->country_name_from,

										"{export_city}"=>$cyname["export"]->city_name,

										"{import}"=>$cname["import"]->country_name,

										"{import_inflect}"=>$cname["import"]->country_name_to,

										"{import_city}"=>$cyname["import"]->city_name,

										"{export_import_all_inflect}"=>$request,

										"{export_import_inflect}"=>$export_import_inflect,

										"{export_import}"=>$export_import,

										"{import_all_inflect}"=>$text_import,

										"{export_all_inflect}"=>$text_export,

										"{import_all}"=>$country_import,

										"{export_all}"=>$country_export,

									);
							
						$tabs = $this->_session_data['search'];
						if (!empty($tabs[$this->_tab['tab_link']]))
						{
							$table = $this->_tab['type'].$this->split->suffix;
							$this->setMeta($table, $metadata);				
						}
					}
				}

			}

			

			return $request;

		}

		

		public function setMeta($table, $data, $preserve_title = true)
		{
			//get document object

			$document = JFactory::getDocument();

			//Get pathway

				

			//set title

			/*

				Груз из России в Молдову, найти груз из России в Молдову, ищу

				попутный груз Россия Молдова, поиск груза из России в Молдову

			*/

			$metatext = JTEXT::_($table."_TITLE");

			if(!empty($metatext) and $metatext != $table."_TITLE" and $metatext = $this->processText($metatext, $data))

			{

				if ($preserve_title)
				{
					$pagetitle = $document->getTitle();

					$pagetitle = reset(explode(">>",$pagetitle));

					$pagetitle = trim($pagetitle);

					$parts = explode(" ", $pagetitle, 8);

					unset($parts[count($parts)-1]);

					foreach ($parts as &$part) if(strlen($part) <=2) unset($part);

					$pagetitle = implode(" ", $parts);

					$pagetitle = trim($pagetitle, ",");

					$metatext = $pagetitle." >> ".$metatext;
				}
				
				$document->setTitle( $metatext );

			}

			//set description

			/*

				Поиск грузов из России в Молдову. Найти грузы из России в Молдаву, груз

				Россия Молдова, нужен груз в, поиск груза из, грузы Россия Молдова , ищу груз из России, поиск груза в

				Молдову.

			*/

			$metatext = JTEXT::_($table."_DESCRIPTION");

			if(!empty($metatext) and $metatext != $table."_DESCRIPTION" and $metatext = $this->processText($metatext, $data))

			{

				$document->setDescription($metatext);

			}

			

			//set keywords

			/*

				найти груз в, ищу груз из , поиск грузов из России в Молдову, где найти

				груз, все грузы из России в Молдову, попутный груз Россия Молдова, поиск груза, поисковик грузов,

				грузовладельцы из России, где найти нужный груз, транспортный поисковик по поиску грузов

			*/

			

			$metatext = JTEXT::_($table."_KEYWORDS");

			if(!empty($metatext) and $metatext != $table."_KEYWORDS" and $metatext = $this->processText($metatext, $data))

			{

				$document->setMetaData("keywords", $metatext);

			}			

		}

		public function processMetaText($metatext, $data)

		{

			if (empty($metatext) or empty($data) or !is_array($data)) return null;

			

			$data = array_filter($data);

			

			$keys = array_keys($data);

			$values = array_values($data);

			

			$metatext = str_replace($keys, $values, $metatext);

			//$metatext = preg_replace ('#[{block}\w+{\w+}\w+{\/block}]#is','',$metatext);

			$metatext = preg_replace ('#{block}[^/]*?{*?}[^/]*?{/block}#iu','',$metatext);

			$metatext = str_replace(array("{block}", "{/block}"), "", $metatext);

			return $metatext;

		

		}

		

		public function splitTable ($suffix="")

		{

			if(isset($this->_split) and $this->_split->original == $suffix) return $this->_split;
				elseif (isset($this->_allsplit[$suffix])) return $this->_allsplit[$suffix];

			
			//split orders from movers_cargo and movers_order to international, internal and seafreight
			//cache split criteria
			if (!isset($this->_allsplit['IDS']))
			{
				$this->db->setQuery("SELECT id FROM transport_type WHERE split_seafreight = 1");
				$IDs = $this->db->loadResultArray();
				if (count($IDs) > 0)
					$this->_allsplit['IDS'] = (string)implode(",",$IDs);	
			}
			

			$parameters = array();

			$parameters['suffix'] = $parameters['original'] = $suffix;

			if (isset($suffix))

			{

				switch ($suffix)

				{

					case "":
					case "_international":
						
						$parameters['suffix'] = "";
						
						$suffix = "_international";

						$parameters['merge'] = " AND m.export <> m.import AND m.type NOT IN (".$this->_allsplit['IDS'].") ";

						$parameters['split'] = " AND split = 1 ";

						break;

					case "_internal":
					
						$parameters['suffix'] = "";

						$parameters['merge'] = " AND m.export = m.import AND m.type NOT IN (".$this->_allsplit['IDS'].") ";

						$parameters['split'] = " AND split$suffix = 1 ";

						break;

					case "_seafreight":

						$parameters['suffix'] = "";

						$parameters['merge'] = " AND m.type IN (".$this->_allsplit['IDS'].") ";

						$parameters['split'] = " AND split$suffix = 1 ";

						$parameters['aux_select'] = ",x.container_type_".$this->language." as container_type_title ";

						$parameters['aux_join'] = " LEFT JOIN container_type as x ON x.id=m.container_type ";

						break;

				}
			}

			$parameters['default_offer_filter'] = " AND (m.hidden = 0 OR m.source = 'comstil') ";

			$parameters = (object) $parameters;

			$this->_split = $this->_allsplit[$suffix] = $parameters;

			return $parameters;

		}

		

//*******Вспомогательные функции для инициализации переменных и настроек внутри класа *******//

		

		//Загружает языковой файл для данного модуля

		public function loadLanguage()

		{

			$language =& JFactory::getLanguage();

			$extension = 'mod_secondtabv2';

			$base_dir = JPATH_SITE;

			$language_tag = $language->getTag();

			$language->load($extension, $base_dir, $language_tag, false);

		}

		

		//Сохраняет в переменную параметры модуля

		public function _setParams ($params)

		{
			
			$this->_params = $params;

		}

		
		//функции для работы с данными пользователя
		public $user;
		
		public function getUser($param = false)
		{
			if (empty($this->user)) $this->user	=& JFactory::getUser();
			
			if ($param) return $this->user->get($param);
				else return $this->user;
		}
		
		public function userIsAdm()
		{
			return $this->getUser('gid') == 25;
		}
		
		/*check if user profile is completed*/
		public $profile_required = array('company_name', 'company_profile', 'country', 'city', 'name', 'phone', 'fax_phone'); 
		public $user_rights_cache = null;
		public $cabinet_id = 121;
		
		public function getUserRights($skip_admin = false)
		{
			if ((!$skip_admin and $this->userIsAdm()) or empty($this->profile_required)) return true;
			
			if ($this->getUser('id') < 0) return false;
			
			if (!is_null($this->user_rights_cache)) return $this->user_rights_cache;

			$this->user_rights_cache = true;

			JFactory::getDBO()->setQuery("SELECT * FROM jos_contact_details WHERE `user_id`='".$this->getUser('id')."' LIMIT 1;");	
			$profile = JFactory::getDBO()->loadAssoc();
			
			if (empty($profile))
			{
				$this->user_rights_cache = false;
			}
			else
			{
				$profile['name'] = $this->getUser('name');
				
				foreach($this->profile_required as $field)
				{
					
					if (empty($profile[$field]))
					{
						$this->user_rights_cache = false;
						break;
					}
					
				}
			}
			
			return $this->user_rights_cache;
		}
		
		public function userHasAccess()
		{
			return ($this->getUser('id') > 0 and $this->getUserRights());
		}
		
		
		
		public $user_contacts;
		
		public function getUserContactsByID($uid = false)
		{
			$profiles = array();
			
			if (empty($uid))
				$user = array($this->getUser('id'));
			else
			{
				if (is_array($uid)) $user = array_filter($uid);
					else $user = array(intval($uid));
			}
			
			if (!empty($user))
			{
				JFactory::getDBO()->setQuery("SELECT u.*, u.id as userid, u.name as username, a.*, a.company_profile as company_profile_id, b.company_profile_".$this->language." as company_profile FROM jos_users as u LEFT JOIN jos_contact_details a ON u.id = a.user_id LEFT JOIN jos_users_company_profile b ON b.id = a.company_profile WHERE u.id IN (".implode(',', $user).")");
				$arr = JFactory::getDBO()->loadAssocList();

				foreach ($arr as $profile)
				{
				
					$profile['id'] = $profile['userid'];
					$profile['name'] = $profile['username'];
					$profile['company_profile'] = (!empty($profile['company_profile']) ? $profile['company_profile'] : (is_numeric($profile['company_profile_id']) ? '' : $profile['company_profile_id']));
					$profile['country_title'] = $this->getCountryInflected($profile['country'], false);
					$profile['city_title'] = $this->getCity($profile['city']);

					
					//phone management
					$copy = array();
					$profile['phones'] = array();
					
					foreach ($profile as $key => $value)
					{
						if (stristr($key, 'phone') and !empty($value))
							$copy[] = $value;
					}
					
					foreach ($copy as $i => $phone)
					{
						$profile['phones']['phone'.($i == 0 ? '':$i+1)] = $phone;
						if ($i == 2) break;
					}
					
					$profiles[$profile['userid']] = $profile;
				}
			}
			
			if (is_array($uid)) return $profiles;
				else return reset($profiles);	
		}
		
		public function getUserContacts($check_access = true, $filter = true)
		{
			$use_filter = (empty($filter) ? 'raw' : 'filter');
			//verificam daca are drept de acces si daca este un utilizator inregistrat
			if (($check_access and !$this->userHasAccess()) or $this->getUser('id') <= 0) return false;
			
			if (empty($this->user_contacts[$use_filter]))
			{
				$profile = $this->getUserContactsByID();
				$profile = array_merge($profile, $profile['phones']);
				
				$this->user_contacts['raw'] = $profile;
				
				if ($filter)
				{
					$ufilter['company'] = $profile['company_name'];
					$ufilter['face'] = $profile['name'];
					$ufilter['email'] = $profile['email'];
					$ufilter['skype'] = $profile['skype'];
					$ufilter['phone'] = $profile['phone'];
					$ufilter['phone2'] = $profile['phone2'];
					$ufilter['phone3'] = $profile['phone3'];
					
					$profile = array_filter($ufilter);
				}
				
				$this->user_contacts[$use_filter] = $profile;
			}
			
			return $this->user_contacts[$use_filter];
		}
		
		
		
		public function getOfferOwnerID($cid, $table, $suffix)
		{
				JFactory::getDBO()->setQuery("SELECT uid FROM jos_users_orders WHERE `cid`='".$cid."' AND `table`='".$table."' AND `suffix` = '".$suffix."';");
				return JFactory::getDBO()->loadResult();		
		}

		//проверка прав на управление заявками
		private $_offercache = array();
		
		public function checkOffer($table, $suffix, $cid)
		{
			if ($this->userIsAdm()) return true;
		
			if(empty($table) or empty($cid)) return false;

			if (empty($this->_offercache))
			{
				JFactory::getDBO()->setQuery("SELECT cid FROM jos_users_orders WHERE `uid`='".$this->getUser('id')."' AND `table`='".$table."' AND `suffix` = '".$suffix."';");
				$this->_offercache = JFactory::getDBO()->loadResultArray();
			}
			
			return in_array($cid, $this->_offercache);
		}
		
		
		private $_update_allowed = null;
		
		public function checkUpdateAllowed($table, $suffix, $update_limit = 1)
		{
			if ($this->userIsAdm()) return $this->_tab['perpage'];
			
			if(empty($table)) return false;
			
			if (!is_null($this->_update_allowed)) return $this->_update_allowed;
			
			$this->_update_allowed = false;

			//JFactory::getDBO()->setQuery("SELECT count(cid) as cnt FROM jos_users_orders WHERE `date_refresh` > '".date('Y-m-d h:i:s', strtotime('-1 day'))."' AND DATE_FORMAT(`date_refresh`, '%Y-%m-%d') <> '".date('Y-m-d')."' AND `uid` = '".$this->getUser('id')."' AND `table` = '".$table."' AND `suffix` = '".$suffix."';");
			
			JFactory::getDBO()->setQuery("SELECT count(cid) as cnt FROM jos_users_orders_payedfunctions WHERE DATE_FORMAT(`date`, '%Y-%m-%d') = '".date('Y-m-d')."' AND `uid` = '".$this->getUser('id')."' AND `table` = '".$table."' AND `suffix` = '".$suffix."' AND `function` = 'REFRESH';");

			$is = JFactory::getDBO()->loadResult();				
			
			$this->_update_allowed = $update_limit - intval($is);
			
			return $this->_update_allowed;
		}
		
		
		public function deleteOffer($table, $suffix, $cid)
		{

			if (!$this->checkOffer($table, $suffix, $cid))
			{
				//rise error?
				return -1;
			}
			else
			{
				$splitter = $this->splitTable($suffix);
				$movers_table = $this->suffix.$table.$splitter->suffix;
					
				//delete from database table
				$this->db->setQuery("DELETE FROM ".$movers_table." WHERE `id`='".$cid."'");
				$is = $this->db->query();
			
				if ($is)
				{
					//delete from user cabinet
					JFactory::getDBO()->setQuery("DELETE FROM jos_users_orders WHERE `uid`='".$this->getUser('id')."' AND `table`='".$table."' AND `suffix` = '".$suffix."' AND `cid`='".$cid."'");
					return JFactory::getDBO()->query();
				}

				return false;
			}
		}
		
		public function updateOffer($values, $table, $suffix, $cid)
		{
			
			if (!is_array($values) or empty($values)) return -1; //rise error?
			
			if (!$this->checkOffer($table, $suffix, $cid))
			{
				//rise error?
				return -1;
			}
			else
			{
				
				$splitter = $this->splitTable($suffix);
				$movers_table = $this->suffix.$table.$splitter->suffix;
				
				$update = array();
				foreach($values as $field => $value)
				{
					if($this->_fieldExists($movers_table, $field))
						$update[] = "`".$field."` = '".$value."'";
						
				}
				
				if (!empty($update))
				{
					$this->db->setQuery("UPDATE ".$movers_table." SET ".(implode(',',$update))." WHERE `id`='".$cid."'");
					return $this->db->query();					
				}
				
				return false;
			}
		}
		
		public function updateOfferDate($values, $table, $suffix, $cid, &$string)
		{
			
			if (!is_array($values) or empty($values)) return -1; //rise error?
			
			if (!$this->checkOffer($table, $suffix, $cid))
			{
				//rise error?
				return -1;
			}
			else
			{
				
				$splitter = $this->splitTable($suffix);
				$movers_table = $this->suffix.$table.$splitter->suffix;
				
				$update = array();
				if ($this->_fieldExists($movers_table, 'date_from') and $this->_fieldExists($movers_table, 'date_to'))
				{
					foreach($values as $field => $value)
					{
						if($this->_fieldExists($movers_table, $field))
							$update[] = "`".$field."` = '".$value."'";
							
					}				
				}
				elseif ($this->_fieldExists($movers_table, 'date'))
				{
					foreach($values as $field => $value)
					{
						if($this->_fieldExists($movers_table, $field))
							$update[] = "`".$field."` = '".$value."'";
							
					}				
				}
				
				if (!empty($update))
				{
					$this->db->setQuery("UPDATE ".$movers_table." SET ".(implode(',',$update))." WHERE `id`='".$cid."'");
					return $this->db->query();					
				}
				
				return false;
			}
		}

		public function getOfferData($values, $table, $suffix, $cid = 0, $filter = '', $offset = 0, $per_page = 45, $type = 'assoc', $check_offer = false)
		{	
			if ($cid > 0 and $check_offer and !$this->checkOffer($table, $suffix, $cid))
			{
				//rise error?
				return -1;
			}
			else
			{
				$splitter = $this->splitTable($suffix);
				$movers_table = $this->suffix.$table.$splitter->suffix;
				
				if (!is_array($values) or empty($values))
				{
				
					$select =  "h.face as a_face, h.phone as a_phone, h.email as a_email, h.skype as a_skype, h.icq as a_icq, m.*,"
							."a.country_name_".$this->language." as export_title, a.alpha3 as export_code, b.country_name_".$this->language." as import_title,"
							."b.alpha3 as import_code,"
							."c.city_name_".$this->language." as export_city_title,d.city_name_".$this->language." as import_city_title,"
							."e.transport_type_".$this->language." as type_title, f.cargo_type_".$this->language." as name_title, g.cargo_volume_".$this->language." as volume_title";
						
					if ($splitter->aux_select) $select .= $splitter->aux_select;
					
					$table_joins = " LEFT JOIN country as a ON a.id_country=m.export LEFT JOIN country as b ON b.id_country=m.import"
							." LEFT JOIN city as c ON c.id_city=m.export_city LEFT JOIN city as d ON d.id_city=m.import_city"
							." LEFT JOIN transport_type".$splitter->suffix." as e ON e.id=m.type LEFT JOIN cargo_type".$splitter->suffix." as f ON f.id=m.name"
							." LEFT JOIN cargo_volume".$splitter->suffix." as g ON g.id=m.volume LEFT JOIN movers_contact as h ON h.id=m.by_admin";
			
					if ($splitter->aux_join) $table_joins .= $splitter->aux_join;
					
				}
				else
				{
					$select = 'm.'.implode(', m.',  $values);				
					$select = str_replace('m.,', '', $select);
				}
		
				$select .= " FROM $movers_table as m ";
			
				if ($cid > 0)
				{
					$where_clause = " WHERE m.id = '".$cid."' ".$splitter->merge;
				}
				else
				{
					if (empty($filter)) $filter = $splitter->default_offer_filter;
					
					$where_clause = " WHERE 1=1 ".$filter.$splitter->merge;
					$order_limit =  " ORDER BY m.date_create DESC, m.id DESC ";
					if ($per_page > 0) $order_limit .= " LIMIT $offset, $per_page";				
				}
			
				$query = "SELECT ".$close_contacts." ".$select." ".$table_joins." ".$where_clause." ".$order_limit;

				$this->db->setQuery($query);
				
				
				if ($cid > 0)
				{
					if ($type = 'assoc') return $this->db->loadAssoc();
						elseif ($type = 'obj') return $this->db->loadObject();
						else return $this->db->query();
				}
				else
				{
					if ($type = 'assoc') return $this->db->loadAssocList();
						elseif ($type = 'obj') return $this->db->loadObjectList();
						else return $this->db->query();
				}
			}
		}
		
		
		//Возвращает MenuID текущего меню

		private function _getItemId ()

		{

			$menus = &JSite::getMenu();

			$menu  = $menus->getActive();

			return $menu->id;

		}
		
		private function _getItem ()

		{

			$menus = &JSite::getMenu();

			$menu  = $menus->getActive();

			return $menu;

		}

		

		//Возвращает объект Session

		private function _getSession ()

		{

			$session = &JFactory::getSession();

			return $session;

		}

		

		//Возвращает текущий язык

		private function getLanguage()

		{

			$language = &JFactory::getLanguage();

			$language = substr($language->get('tag'),0,2);

			return $language;

		}

		

		

		//Возвращает объект JDatabase

		private function getdb()
		{
		
			$option = JFactory::getApplication()->getCfg('orders_conn');

			$db = & JDatabase::getInstance( $option );
		
			if ($db)
			{
				return $db;

			}
			else
			{
				echo 'Невозможно подключиться к базе данных';
				return false;
			}
		}
		

//******Работа с сессией. Дннные сохранённые в сесси действительно только в текущем меню. При переходе в другое меню, данные теряются******//

		

		//Вохвращает данные из сессии

		public function getSessionData ($param = false)

		{

			

			$data = $this->sess->get($this->itemid);

			if ($param) return $data[$param];

			return $data;

		}

		

		

		//сохраняет данные в сессию

		public function setSessionData ($data = false)

		{

			if (isset($data)) $this->sess->set($this->itemid, $data);

		}

		

		

		//обновляет данные в сессии

		public function updateSessionData ($param = false, $data = false)

		{

			if ($param and isset($data))

			{

				$s_data = $this->getSessionData();

				$s_data[$param]=$data;

				$this->setSessionData($s_data);

			}

		}

		

		

		//удаляет данные из сессии

		public function unsetSessionData ($param = false)

		{

			$data = $this->sess->get($this->itemid);

			if ($param and isset($data[$param])) unset($data[$param]);

			$this->setSessionData($data);

		}

		

		

		//Проверяет если для текущего меню есть данные, если нет, то сессия уничтожяется

		//Функция таким образом очищяет сессию при переходе в другой раздел.

		private function _clearSession ()

		{

			$itemid = $this->itemid;

			$session = $this->sess;

			

			$data = $session->get($itemid);



			if (count($data) < 1) $session->destroy();

		}

		



//******Работа с сессией для сохранения данных только на протяжении одного request-a******//

		

		//Инициирует, либо уменьшает на единицу внутренний счётчик

		//Если счётчик ноль - данные из сессии удаляються.

		public function refreshFlashData ()

		{

			$counter = $this->getFlashCounter();

			if ($counter < 1) $this->unsetFlashData();

				else $this->setFlashCounter($counter-1);

		}

		

		

		//Инициирует внутренний счётчик

		//Можно задать таким образом количество циклов для которых данные будут держаться в сессии. По умолчанию один цикл

		public function setFlashCounter ($data = false)

		{

			if(isset($data))

			{

				$flash_data = $this->sess->get('flash_data');

				$flash_data['counter'] = $data;

				$this->sess->set('flash_data', $flash_data);

			}

		}

		

		//Возвращает счётчик временной сессии

		public function getFlashCounter ()

		{

			$flash_data = $this->sess->get('flash_data');

			if (isset($flash_data['counter']) and $flash_data['counter'] > 0 ) return (int)$flash_data['counter'];

				else return 0;

		}

		

		//Возвращает данные из временной сессии.

		public function getFlashData ($param = false)

		{

			$data = $this->sess->get('flash_data');

			if (isset($data['data']))

			{

				$data = $data['data'];

				if ($param) return $data[$param];

				return $data;

			}

			else return array();

		}

		

		

		//Сохраняет данные в временную сессию

		public function setFlashData($data = false)

		{

			if (isset($data)) {

				$flash_data['counter'] = $this->getFlashCounter ();

				$flash_data['data'] = $data;

				$this->sess->set('flash_data', $flash_data);

			}

		}

		

		//Обновляет данные в временную сессии

		public function updateFlashData ($param = false, $data = false)

		{

			if ($param and isset($data))

			{

				$flash_data = $this->getFlashData();

				$flash_data[$param]=$data;

				$this->setFlashData($flash_data);

			}

		}

		

		

		//Удаляет данные из временной сессии

		public function unsetFlashData ($param = false)

		{

			$flash_data = $this->getFlashData();

			if ($param and isset($flash_data[$param]))

			{

				unset($flash_data[$param]);

				$this->setFlashData($data);

			}

			else

			{

				$this->sess->set('flash_data', array());

			}

		}

		

		

//******Полезные функции******//

		function load_image_from_string($data, $mime = 'image/gif') 
		{ 
		  $base64   = base64_encode($data); 
		  return ('data:' . $mime . ';base64,' . $base64);
		}

		public function uricurrent()
		{
			$item = $this->_getItem ();
			
			//return JURI::getInstance()->toString(array('scheme', 'host', 'port', 'path')).(intval($this->link_id) ? 'p'.$this->link_id.'.html' : '');
			return rtrim(JRoute::_($item->link . '&Itemid=' . $item->id), '/').'/'.(intval($this->link_id) ? 'p'.$this->link_id.'.html' : (intval($this->offer_id) ? 's'.$this->offer_id.'.html' : ''));
		}
		
		public function getofferuri($menu_id, $cid)
		{
			if (!empty($menu_id) and !empty($cid))
				return rtrim($this->get_menulink_byid($menu_id)).'/s'.$cid.'.html';			
		}

		public function get_menulink_byid ($id)

		{

			if (!empty($id))

			{

				$menu = JFactory::getApplication()->getMenu();

				$item = $menu->getItem( (int)$id);

				return JRoute::_($item->link . '&Itemid=' . $item->id);

			}

			return false;

		}

		

		public function get_menutitle_byid ($id)

		{

			if (!empty($id))

			{

				$menu = JFactory::getApplication()->getMenu();

				return $menu->getItem( (int)$id)->name;

			}

			return false;

		}

		

		//выводит в консоль фаирбага дамп переменной

		public function debug($data) {

			echo "<script>\r\n//<![CDATA[\r\nif(!console){var console={log:function(){}}}";

			$output    =    explode("\n", print_r($data, true));

			foreach ($output as $line) {

				if (trim($line)) {

					$line    =    addslashes($line);

					echo "console.log(\"{$line}\");";

				}

			}

			echo "\r\n//]]>\r\n</script>";

		}

		

		public function d($data)

		{

			echo "<pre>";

			var_dump($data);

			echo "</pre>";

		}
		
		public function fdump($data)
		{
			$file = fopen($_SERVER["DOCUMENT_ROOT"]."/dump.txt", "w");
			fwrite($file, var_export($data, true));
			fclose($file);
		}

		

		public function array_search_key( $needle_key, $array ) {

		  foreach($array AS $key=>$value){

			if($key == $needle_key) return $value;

			if(is_array($value)){

			  if( ($result = $this->array_search_key($needle_key,$value)) !== false)

				return $result;

			}

		  }

		  return false;

		}



	

		public function array_get_path($needle, $haystack, $path=array())

		{

			foreach($haystack as $id => $val)

			{

				 $path2=$path;

				 $path2[] = $id;

		 

				 if($id === $needle)

					  return $path2;

				 else if(is_array($val))

					  if($ret = $this->array_get_path($needle, $val, $path2))

						   return $ret;

			  }

			  return false;

		}

		
//******Функции работы со строками для вывода сообщений******//

		public function processText($content, $replaces = array())
		{
			if (empty($content)) return '';
			
			if (!empty($replaces) and is_array($replaces))
			{

				$replaces = array_filter($replaces);
				$keys = array_keys($replaces);
				$values = array_values($replaces);

				$content = str_replace($keys, $values, $content);
			}
			
			$content = preg_replace ('#{block}[^/]*?{*?}[^/]*?{/block}#iu','',$content);
			//$content = preg_replace ('#{[\/].*}#iu','',$content);
			$content = str_replace(array("{block}", "{/block}"), "", $content);

			return $content;
		}
		

		public function showOfferAddText($table, $suffix, $prefix, $filter = array())
		{
			
			$key = strtoupper($table.$suffix.$prefix);
			
			$content = "<p>".JTEXT::_('SUCCESS_MESSAGE_'.$key);
			
			//Сообщение о разделе в котором была добавлена заявка.		
			$view_menuitem_id = (int)$this->_tab['tab_success_url'];
			if ($this->getUser('id') > 0 and !empty($this->_tab['tab_usersuccess_offers_url']))
				$view_menuitem_id = (int)$this->_tab['tab_usersuccess_offers_url'];
				
			if ($suffix == "" and !empty($filter['export']) and $filter['export'] == $filter['import'])
			{
				$view_menuitem_id = (int)$this->_tab['tab_internal_url'];
				if ($this->getUser('id') > 0 and !empty($this->_tab['tab_usersuccess_internaloffers_url']))
					$view_menuitem_id = (int)$this->_tab['tab_usersuccess_internaloffers_url'];				
			}

			//если у нас есть ID menu раздела 
			if (!empty($view_menuitem_id) and ($text = JTEXT::_('SUCCESS_ADD_MESSAGE_'.$key)) != 'SUCCESS_ADD_MESSAGE_'.$key)
			{
				$replaces = array (
									'{url_href}' => $this->get_menulink_byid($view_menuitem_id),
									'{url_title}' => $this->get_menutitle_byid($view_menuitem_id),
									);
				$content .= $this->processText($text, $replaces);
			}
			$content .= "</p>";
		
			return $content;
		}
			
			
		public function showOfferAddLinkText($table, $suffix, $prefix, $filter = array())
		{
			
			$key = strtoupper($table.$suffix.$prefix);
			$content = $direction = '';
			
			//Ссылка на выдачу результата по направлению
			if (!empty($filter['export']) and !empty($filter['import']))
			{
				$splitter = $this->_split;
				
				if ($suffix == "" and $filter['export'] == $filter['import'])
				{
					$view_offers_id = (int)$this->_tab['tab_success_internaloffers_url'];
					$a_suffix = "_internal";
				}
				else
				{
					$view_offers_id = (int)$this->_tab['tab_success_offers_url'];	
					$a_suffix = $suffix;
				}
				
			
				if (!empty($view_offers_id))
				{
					$splitter = $this->splitTable($a_suffix);
					
					$a_table = ($table == "cargo")?"order":"cargo";
					$movers_a_table = "movers_".$a_table.$splitter->suffix;
					
					//запросы в БД
					$_filter = " AND m.export='".$filter['export']."' and m.import='".$filter['import']."' ";
					$this->db->setQuery("SELECT COUNT(m.id) FROM $movers_a_table as m WHERE 1=1 ".$splitter->default_offer_filter." $_filter ".$splitter->merge);
					$items = $this->db->loadResult();
					
					if (!empty($items))
					{
						if (($text = JTEXT::_('SUCCESS_ADD_VIEW_'.$key)) != 'SUCCESS_ADD_VIEW_'.$key)
						{
							$export = $this->getCountryInflected($filter['export']);				
							$import = $this->getCountryInflected($filter['import'], 'import');
							
							$_get = "?&filter[export]=".$filter['export']."&filter[import]=".$filter['import']."&alias=display_table_".$a_table.$a_suffix;

							if (strlen($export) and strlen($import))
								$direction = JTEXT::_('LABEL_FROM').' '.$export.' '.JTEXT::_('LABEL_TO').' '.$import;

							$replaces = array(
												'{direction}' => $direction,
												'{items}' => $items,
												'{export}' => $export,
												'{import}' => $import,
												'{url_href}' => $this->get_menulink_byid($view_offers_id).$_get,
												'{url_title}' => $this->get_menutitle_byid($view_offers_id),
												'{results_type}' => mb_strtolower(JTEXT::_($a_table.$suffix),'UTF-8'),
												'{results_type_a}' => mb_strtolower(JTEXT::_($a_table.$suffix."_A"),'UTF-8'),
												'{offer_type}' => mb_strtolower(JTEXT::_($a_table.$suffix),'UTF-8'),
												'{offer_type_a}' => mb_strtolower(JTEXT::_($a_table.$suffix."_A"),'UTF-8'),
											);
											
							$content = $this->processText($text, $replaces);
						}
					}
					else
					{
						if (($text = JTEXT::_('SUCCESS_ADD_VIEW_NOITEMS_'.$key)) != 'SUCCESS_ADD_VIEW_NOITEMS_'.$key)
						{
							$this->db->setQuery("SELECT COUNT(m.id) FROM $movers_a_table as m WHERE 1=1 ".$splitter->default_offer_filter.$splitter->merge);
							$items = $this->db->loadResult();

							$replaces = array(
												'{items}' => $items,
												'{url_href}' => $this->get_menulink_byid($view_offers_id),
												'{url_title}' => $this->get_menutitle_byid($view_offers_id),
												'{results_type}' => mb_strtolower(JTEXT::_($a_table.$suffix),'UTF-8'),
												'{results_type_a}' => mb_strtolower(JTEXT::_($a_table.$suffix."_A"),'UTF-8'),
												'{offer_type}' => mb_strtolower(JTEXT::_($table.$suffix),'UTF-8'),
												'{offer_type_a}' => mb_strtolower(JTEXT::_($table.$suffix."_A"),'UTF-8'),
											);
											
							$content = $this->processText($text, $replaces);
						}
					}
				}
			}
			
			return $content;
		}
		
		public function getCountryInflected($id, $inflect = "export")
		{
			$content = '';
			
			if (!empty($id))
			{
				$inflected = "";
				
				if ($this->language == 'ru')
				{
					if ($inflect == 'import') $inflected = "_to";
						elseif($inflect == "export") $inflected = "_from";
				}
				
				$this->db->setQuery("SELECT country_name_".$this->language.$inflected." FROM country WHERE id_country = ".$id);
				$content = $this->db->loadResult();				
			}
			
			return $content;
		}
		
		public function getCity($id)
		{
			$content = '';
			
			if (!empty($id))
			{

				$this->db->setQuery("SELECT city_name_".$this->language." FROM city WHERE id_city = ".$id);
				$content = $this->db->loadResult();				
			}
			
			return $content;
		}


//******Проверка валидности данных******//

		function isValidPhone($phone)

		{
			$phone = str_replace(' ','',$phone);
			return preg_match('/^[+]\d{1,3}-\d{1,4}-\d{1,15}/', $phone);

		}



		function isValidEmail ($email, $strict)

		{

			if ( !$strict ) $email = preg_replace('/^\s+|\s+$/g', '', $email);

			return (preg_match('/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i', $email));

		}
		
		function isValidDate(&$date)
		{
			$bret = true;
			
			if(strlen($date)>0)
			{
				if( preg_match ("/^(0?[1-9]|[12][0-9]|3[01])[\/\.-](0?[1-9]|1[0-2])[\/\.-](19|20)\d{2}$/", $date) and (($time = strtotime($date)) !== FALSE or $time !== -1))
				{
					$date = str_replace(array("\\", "-", "/"), ".", $date);
					
					$arr = explode(".", $date);    // разносим строку в массив
					$yyyy = $arr[2];            // год
					$mm = $arr[1];              // месяц
					$dd = $arr[0];              // день
					if(is_numeric($yyyy) && is_numeric($mm) && is_numeric($dd))
					{
						if(checkdate($mm, $dd, $yyyy))
							$date = $dd.'-'.$mm.'-'.$yyyy;
						else
						{
							$date = "";
							$bret = false;					
						}
					}
					else
					{
						$date = "";
						$bret = false;
					}
				}
				else
				{
					$date = "";
					$bret = false;
				}
			}
			else
			{
				$date = "";
				$bret = false;
			}
			
			return $bret;
		}
		
//*********DATE RANGES*********//
		function dateRange( $first, $last, $step = '+1 day', $format = 'd-m-Y' )
		{
			$dates = array();
			$current = strtotime( $first );
			$last = strtotime( $last );

			while( $current <= $last ) {

				$dates[] = date( $format, $current );
				$current = strtotime( $step, $current );
			}

			return $dates;
		}
	
		function getDateWhere($table, $field, $value)
		{
			$where = "";
								
			if (!empty($field) and !empty($value))//*$this->tab->utils->_fieldExists($table, $field)*/)
			{
				switch($field)
				{					
					case "date_between":
							if (preg_match("((0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d)", $value))
									$where = " AND (STR_TO_DATE(m.date_from,'%d-%m-%Y') <= STR_TO_DATE('".$value."','%d-%m-%Y')"
											." AND STR_TO_DATE(m.date_to,'%d-%m-%Y') >= STR_TO_DATE('".$value."','%d-%m-%Y'))";
						break;
						
					case "order_date":
						
						switch ($value)
						{
								
							case "today":
								$where .= " AND m.$field = '".date('d-m-Y')."'";
								break;
								

							case "twodays":
								$range = $this->dateRange(date('Y-m-d', strtotime('-1 day')), date('Y-m-d'));
								$where = " AND m.$field IN ('".implode('\',\'', $range)."')";
								break;
								
								
							case "week":
								$range = $this->dateRange(date('Y-m-d', strtotime('-1 week')), date('Y-m-d'));
								$where = " AND m.$field IN ('".implode('\',\'', $range)."')";
								break;
								
							case "month":
								$range = $this->dateRange(date('Y-m-d', strtotime('-1 months')), date('Y-m-d'));
								$where = " AND m.$field IN ('".implode('\',\'', $range)."')";
								break;
						
							default:
								if (preg_match("((0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d)", $value))
									$where =" AND m.$field = '$value'";	
								break;
						}
						break;
						
					default:
						if (preg_match("((0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d)", $value))
							$where =" AND m.$field LIKE '$value'";	
						break;
				}
			}
			
			return $where;
		}

	}
	
	class TabUser
	{
		static $user = null;
		
		function __construct()
		{
			self::getUser();
		}
		
		public function getUser($param = false)
		{
			if (empty(self::$user)) self::$user	=& JFactory::getUser();
			
			if ($param) return self::$user->get($param);
				else return self::$user;
		}
		
		public function userIsAdm()
		{
			return self::getUser('gid') == 25;
		}
		
		/*check is user profile is completed*/
		public $profile_required = array('company_name', 'company_profile', 'country', 'city', 'name', 'phone', 'fax_phone'); 
		public $user_rights_cache = null;
		public $cabinet_id = 121;
		
		public function getUserRights($skip_admin = false)
		{
			if ((!$skip_admin and self::userIsAdm()) or empty(self::$profile_required)) return true;
			
			if (self::getUser('id') < 0) return false;
			
			if (!is_null(self::user_rights_cache)) return self::user_rights_cache;

			self::$user_rights_cache = true;

			JFactory::getDBO()->setQuery("SELECT * FROM jos_contact_details WHERE `user_id`='".$this->getUser('id')."' LIMIT 1;");	
			$profile = JFactory::getDBO()->loadAssoc();
			
			if (empty($profile))
			{
				self::$user_rights_cache = false;
			}
			else
			{
				$profile['name'] = self::getUser('name');
				
				foreach(self::$profile_required as $field)
				{
					
					if (empty($profile[$field]))
					{
						self::$user_rights_cache = false;
						break;
					}
					
				}
			}
			
			return self::$user_rights_cache;
		}
		
		public function userHasAccess()
		{
			return (self::getUser('id') > 0 and self::getUserRights());
		}
		
		public $user_contacts;
		
		public function getUserContacts($check_access = true, $filter = true)
		{
			$use_filter = (empty($filter) ? 'raw' : 'filter');
			//verificam daca are drept de acces si daca este un utilizator inregistrat
			if (($check_access and !self::userHasAccess()) or self::getUser('id') <= 0) return false;
			
			if (empty(self::$user_contacts[$use_filter]))
			{
				JFactory::getDBO()->setQuery("SELECT * FROM jos_contact_details WHERE `user_id` = '".$this->getUser('id')."' LIMIT 1;");
				$profile = JFactory::getDBO()->loadAssoc();
				$profile['name'] = self::getUser('name');
				$profile['email'] = self::getUser('username');
				
				//phone management
				$copy = array();
				
				foreach ($profile as $key => $value)
				{
					if (stristr($key, 'phone') and !empty($value))
						$copy[] = $value;
				}
				
				foreach ($copy as $i => $phone)
				{
					$profile['phone'.($i == 0 ? '':$i+1)] = $phone;
					if ($i == 2) break;
				}
				
				if ($filter)
				{
					$ufilter['company'] = $profile['company_name'];
					$ufilter['face'] = $profile['name'];
					$ufilter['email'] = $profile['email'];
					$ufilter['skype'] = $profile['skype'];
					$ufilter['phone'] = $profile['phone'];
					$ufilter['phone2'] = $profile['phone2'];
					$ufilter['phone3'] = $profile['phone3'];
					
					$profile = array_filter($ufilter);
				}
				
				self::$user_contacts[$use_filter] = $profile;
			}
			
			return self::$user_contacts[$use_filter];
		}
	}
?>