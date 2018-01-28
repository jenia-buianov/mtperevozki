<?php
/**
* @version 1.3.0
* @package RSform!Pro 1.3.0
* @copyright (C) 2007-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * RSForm! Pro system plugin
 */
class plgSystemRSFPPayPal extends JPlugin
{
	var $_products = array();
	
	/**
	 * Constructor
	 *
	 * For php4 compatibility we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemRSFPPayPal( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->newComponents = array(21,22,23);
		
		global $_products;
	}
	
	function canRun()
	{
		if (class_exists('RSFormProHelper')) return true;
		
		$helper = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsform'.DS.'helpers'.DS.'rsform.php';
		if (file_exists($helper))
		{
			require_once($helper);
			RSFormProHelper::readConfig();
			return true;
		}
		
		return false;
	}
	
	/*
		Event Triggered Functions
	*/
	function rsfp_bk_onInit()
	{
		if (!$this->canRun()) return;
		
		$formId = JRequest::getInt('formId');
		
		$db = &JFactory::getDBO();
		//Cron that sets non paid subscribers to denied after 12 h
		$db->setQuery("UPDATE #__rsform_submission_values sv LEFT JOIN #__rsform_submissions s ON s.SubmissionId=sv.SubmissionId SET sv.FieldValue=-1 WHERE sv.FieldName = '_STATUS' AND sv.FieldValue = 0 AND s.DateSubmitted < '".date('Y-m-d H:i:s',strtotime('-12 hours'))."'");
		$db->query();
	}
	
	function rsfp_bk_onAfterShowComponents()
	{
		if (!$this->canRun()) return;
		
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_system_rsfppaypal' );
		
		$mainframe =& JFactory::getApplication();
		$db = JFactory::getDBO();
		$formId = JRequest::getInt('formId');
		
		$link1 = "displayTemplate('21')";
		$link2 = "displayTemplate('23')";
		if ($components = RSFormProHelper::componentExists($formId, 21))
			$link1 = "displayTemplate('21', '".$components[0]."')";
		if ($components = RSFormProHelper::componentExists($formId, 23))
			$link2 = "displayTemplate('23', '".$components[0]."')";
			
		?>
		<li class="rsform_navtitle"><?php echo JText::_('RSFP_PAYPAL_LABEL'); ?></li>
		<li><a href="javascript: void(0);" onclick="<?php echo $link1;?>;return false;" id="rsfpc21"><span id="paypal"><?php echo JText::_('RSFP_PAYPAL_SPRODUCT'); ?></span></a></li>
		<li><a href="javascript: void(0);" onclick="displayTemplate('22');return false;" id="rsfpc22"><span id="paypal"><?php echo JText::_('RSFP_PAYPAL_MPRODUCT'); ?></span></a></li>
		<li><a href="javascript: void(0);" onclick="<?php echo $link2;?>;return false;" id="rsfpc23"><span id="paypal"><?php echo JText::_('RSFP_PAYPAL_TOTAL'); ?></span></a></li>
		<?php
	}
	
	
	function rsfp_bk_onAfterCreateComponentPreview($args = array())
	{
		if (!$this->canRun()) return;
		
		$nodecimals = RSFormProHelper::getConfig('paypal.nodecimals');
		$decimal    = RSFormProHelper::getConfig('paypal.decimal');
		$thousands  = RSFormProHelper::getConfig('paypal.thousands');
		$currency   = RSFormProHelper::getConfig('paypal.currency');
		
		switch ($args['ComponentTypeName'])
		{
			case 'paypalSingleProduct':
				$args['out'] = '<td>'.$args['data']['CAPTION'].'</td>';
				$args['out'].= '<td><img src="'.JURI::root(true).'/administrator/components/com_rsform/assets/images/icons/paypal.png" /> '.$args['data']['CAPTION'].' - '.number_format($args['data']['PRICE'], $nodecimals, $decimal, $thousands).' '.$currency.'</td>';	
			break;
			
			case 'paypalMultipleProducts':
				$args['out'] = '<td>'.$args['data']['CAPTION'].'</td>';
				$args['out'].= '<td><img src="'.JURI::root(true).'/administrator/components/com_rsform/assets/images/icons/paypal.png" /> '.$args['data']['CAPTION'].'</td>';
			break;
			
			case 'paypalTotal':
				$args['out'] = '<td>'.$args['data']['CAPTION'].'</td>';
				$args['out'].= '<td>'.number_format(0, $nodecimals, $decimal, $thousands).' '.$currency.'</td>';	
			break;
		}
	}
	
	function rsfp_bk_onAfterShowConfigurationTabs()
	{
		if (!$this->canRun()) return;
		
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_system_rsfppaypal' );
		
		jimport('joomla.html.pane');
		$tabs =& JPane::getInstance('Tabs', array(), true);
		
		echo $tabs->startPanel(JText::_('RSFP_PAYPAL_LABEL'), 'form-paypal');
			$this->paypalConfigurationScreen();
		echo $tabs->endPanel();
	}
	
	function rsfp_bk_onAfterCreateFrontComponentBody($args)
	{
		if (!$this->canRun()) return;
		
		RSFormProHelper::readConfig(true);
		$nodecimals = RSFormProHelper::getConfig('paypal.nodecimals');
		$decimal    = RSFormProHelper::getConfig('paypal.decimal');
		$thousands  = RSFormProHelper::getConfig('paypal.thousands');
		$currency   = RSFormProHelper::getConfig('paypal.currency');
		
		$value = $args['value'];
		
		switch($args['r']['ComponentTypeId'])
		{
			case 21:
			{
				if(isset($args['data']['SHOW']) && $args['data']['SHOW']=='NO')
				{
					//Hidden
					$args['out'] = '<input type="hidden" name="rsfp_paypal_item[]" value="'.RSFormProHelper::htmlEscape($args['data']['PRICE']).'"/>
					<input type="hidden" name="form['.$args['data']['NAME'].']" id="'.$args['data']['NAME'].'" value="'.RSFormProHelper::htmlEscape($args['data']['CAPTION']).'"/>';
				}
				else
				{
					$args['out'] = '<input type="hidden" name="rsfp_paypal_item[]" id="'.$args['data']['NAME'].'" value="'.RSFormProHelper::htmlEscape($args['data']['PRICE']).'"/>
					<input type="hidden" name="form['.$args['data']['NAME'].']" id="'.$args['data']['NAME'].'" value="'.RSFormProHelper::htmlEscape($args['data']['CAPTION']).'"/>';
				}
			}
			break;
			
			case 22:
			{
				switch($args['data']['VIEW_TYPE'])
				{
					case 'DROPDOWN':
					{
						$args['out'] .= '<select '.($args['data']['MULTIPLE']=='YES' ? 'multiple="multiple"' : '').' name="form['.$args['data']['NAME'].'][]" id="paypal-'.$args['componentId'].'" '.$args['data']['ADDITIONALATTRIBUTES'].' '.(!empty($args['data']['SIZE']) ? 'size="'.$args['data']['SIZE'].'"' : '').' onchange="getPrice_'.$args['formId'].'();" >';
						$items = RSFormProHelper::isCode($args['data']['ITEMS']);
						$items = str_replace("\r", "", $items);
						$items = explode("\n", $items);
						
						foreach ($items as $item)
						{
							$buf = explode('|',$item);
							
							$option_value = $buf[0];
							$option_value_trimmed = str_replace('[c]','',$option_value);
							$option_shown = count($buf) == 1 ? $buf[0] : $buf[1];
							$option_shown_trimmed = str_replace('[c]','',$option_shown);
							$option_shown_value = $option_value == '' ? '' : $option_shown_trimmed;
							$option_shown_trimmed = count($buf) == 1 ? $buf[0] : $option_shown_trimmed.($buf[0] > 0 ? ' - '.number_format($buf[0],$nodecimals, $decimal, $thousands).' '.$currency : '');
							
							$product = array($args['data']['NAME'].'|_|'.$buf[count($buf) == 1 ? 0 : 1] => count($buf) == 1 ? 0 : $buf[0]);
							global $_products;
							$_products = $this->merge($_products, $product);
							
							$option_checked = false;
							if (empty($value) && preg_match('/\[c\]/',$option_shown))
								$option_checked = true;
							if (!empty($value[$args['data']['NAME']]) && array_search($option_shown_value,$value[$args['data']['NAME']]) !== false)
								$option_checked = true;
							
							$args['out'] .= '<option '.($option_checked ? 'selected="selected"' : '').' value="'.RSFormProHelper::htmlEscape($option_shown_value).'">'.RSFormProHelper::htmlEscape($option_shown_trimmed).'</option>';
						}
						$args['out'] .= '</select>';
					}
					break;
					
					case 'CHECKBOX':
					{
						$i=0;
						$items = RSFormProHelper::isCode($args['data']['ITEMS']);
						$items = str_replace("\r", "", $items);
						$items = explode("\n", $items);
						
						foreach($items as $item)
						{
							$buf = explode('|',$item);
							
							$option_value = $buf[0];
							$option_value_trimmed = str_replace('[c]','',$option_value);
							$option_shown = count($buf) == 1 ? $buf[0] : $buf[1];
							$option_shown_trimmed = str_replace('[c]','',$option_shown);
							$option_shown_value = $option_shown_trimmed;
							$option_shown_trimmed = count($buf) == 1 ? $buf[0] : $option_shown_trimmed.' - '.number_format($buf[0],$nodecimals, $decimal, $thousands).' '.$currency;
							
							if(!isset($buf[1])) $buf[1] = $option_shown_value = $buf[0] = 0;
							
							$product = array($args['data']['NAME'].'|_|'.$buf[1] => $buf[0]);
							global $_products;
							$_products = $this->merge($_products, $product);
							
							$option_checked = false;
							if (empty($value) && preg_match('/\[c\]/',$option_shown))
								$option_checked = true;
							if (!empty($value[$args['data']['NAME']]) && array_search($option_shown_value,$value[$args['data']['NAME']]) !== false)
								$option_checked = true;
								
							$args['out'] .= '<input '.($option_checked ? 'checked="checked"' : '').' name="form['.$args['data']['NAME'].'][]" type="checkbox" value="'.RSFormProHelper::htmlEscape($option_shown_value).'" id="paypal-'.$args['componentId'].'-'.$i.'" '.$args['data']['ADDITIONALATTRIBUTES'].' onclick="getPrice_'.$args['formId'].'();" /><label for="paypal-'.$args['componentId'].'-'.$i.'">'.RSFormProHelper::htmlEscape($option_shown_trimmed).'</label>';
							if($args['data']['FLOW']=='VERTICAL') $args['out'].='<br/>';
							$i++;
						}
					}
					break;
				}
			}
			break;
		
			case 23:
			{
				$args['out'] = '<span id="paypal_total_'.$args['formId'].'" class="rsform_paypal_total">'.number_format(0,$nodecimals, $decimal, $thousands).'</span> '.$currency.' <input type="hidden" id="'.$args['data']['NAME'].'" value="" name="form['.$args['data']['NAME'].']" />';
			}
			break;
		}
	}
	
	function rsfp_f_onSwitchTasks()
	{
		$plugin_task = JRequest::getVar('plugin_task');
		switch($plugin_task){
			
			case 'paypal.notify':
				$this->rsfp_f_paypalNotify();
				exit();
			break;
			
			default:
			break;
		}	
	}
	
	function rsfp_f_onBeforeFormDisplay($args)
	{
		if (!$this->canRun()) return;
		
		RSFormProHelper::readConfig(true);
		$nodecimals = RSFormProHelper::getConfig('paypal.nodecimals');
		$decimal    = RSFormProHelper::getConfig('paypal.decimal');
		$thousands  = RSFormProHelper::getConfig('paypal.thousands');
		$currency   = RSFormProHelper::getConfig('paypal.currency');
		
		$paypals = RSFormProHelper::componentExists($args['formId'], 22);
		$total = RSFormProHelper::componentExists($args['formId'], 23);
		$totaldetails = RSFormProHelper::getComponentProperties(@$total[0]);
		
		$properties = RSFormProHelper::getComponentProperties($paypals);
		
		if (!empty($paypals))
		{
			$args['formLayout'] .='<script type="text/javascript">';
			$args['formLayout'] .='
				function getPrice_'.$args['formId'].'()
				{
					price = 0;
					
					products = new Array();
					';
					global $_products;
					foreach ($_products as $product => $price)
					{
						$product = addslashes($product);
						$product = str_replace('[c]','',$product);
						$args['formLayout'] .= "products['".$product."'] = '".$price."';\n";
					}
					
					foreach ($paypals as $componentId)
					{	
						$details = $properties[$componentId];
						
						if($details['MULTIPLE'] == 'YES' && $details['VIEW_TYPE']== 'DROPDOWN')
						{
							$args['formLayout'] .= "var elemd = document.getElementById('paypal-".$componentId."');
	
	for(i=0;i<elemd.options.length;i++)
	{
		if(elemd.options[i].selected == true ) price += parseFloat(products['".$details['NAME']."|_|' + elemd.options[i].value]); 
	}";
							
						}
						elseif ($details['VIEW_TYPE']== 'DROPDOWN')
							$args['formLayout'] .= "price += parseFloat(products['".$details['NAME']."|_|' + document.getElementById('paypal-".$componentId."').value]);\n";
						
						if ($details['VIEW_TYPE'] == 'CHECKBOX')
						{
							$args['formLayout'] .= "\n var elemc = document.getElementsByName('form[".$details['NAME']."][]');
	for(i=0;i<elemc.length;i++)
	{
		if(elemc[i].checked == true ) price += parseFloat(products['".$details['NAME']."|_|' + elemc[i].value]);
	}";
							
						}
					}
					
					if (!empty($total))
						$args['formLayout'] .= '
					document.getElementById(\'paypal_total_'.$args['formId'].'\').innerHTML = number_format( price, '.$nodecimals.', \''.$decimal.'\', \''.$thousands.'\');
					document.getElementById(\'paypal_total_'.$args['formId'].'\').value = price;';
					
					if (!empty($totaldetails['NAME']))
						$args['formLayout'] .= "\n".'document.getElementById(\''.$totaldetails['NAME'].'\').value = price;';
			
		$args['formLayout'] .='}</script>';
		$args['formLayout'] .='<script type="text/javascript">getPrice_'.$args['formId'].'();</script>';
		
		}
		
		if (RSFormProHelper::componentExists($args['formId'], 21))
		{
			$args['formLayout'].='<script type="text/javascript">';
			$args['formLayout'].="rsfp_paypal_items = document.getElementsByName('rsfp_paypal_item[]');
			total = 0;
			for(i=0;i<rsfp_paypal_items.length;i++)
			{
				total += parseFloat(rsfp_paypal_items[i].value);
			}
			total = number_format( total, ".$nodecimals.", '".$decimal."', '".$thousands."' );
			";
			if (!empty($total))
				$args['formLayout'].= "document.getElementById('paypal_total_".$args['formId']."').innerHTML = total;";
			if (!empty($totaldetails['NAME']))
				$args['formLayout'].= "document.getElementById('".@$totaldetails['NAME']."').value = total;";
			$args['formLayout'].= "</script>\n\n";
		}
	}
	
	function rsfp_f_onBeforeStoreSubmissions($args)
	{
		if (!$this->canRun()) return;
		
		if (RSFormProHelper::componentExists($args['formId'], $this->newComponents))
			$args['post']['_STATUS'] = '0';
	}
	
	function rsfp_f_onAfterFormProcess($args)
	{
		if (!$this->canRun()) return;
		
		$mainframe =& JFactory::getApplication();
		
		if (RSFormProHelper::componentExists($args['formId'], $this->newComponents))
		{
			$db = JFactory::getDBO();
			
			$products = '';
			$price = ''; 
			$total = RSFormProHelper::componentExists($args['formId'], 23);
			$totaldetails = RSFormProHelper::getComponentProperties(@$total[0]);
			$multiplePayments = RSFormProHelper::componentExists($args['formId'], 22);
			if(!empty($multiplePayments))
			{
				foreach($multiplePayments as $payment)
				{
					$pdetail = RSFormProHelper::getComponentProperties($payment);
					$detail = $this->getSubmissionValue($args['SubmissionId'], $payment);
					if($detail == '') continue;
					
					$items = str_replace("\r\n", "\n", $pdetail['ITEMS']);
					$items = explode("\n", $items);
					foreach ($items as $item)
					{
						if (strpos($item, '|') === false && $item == $detail)
							continue 2;
					}
					
					$products .= urlencode(strip_tags($pdetail['CAPTION']).' - '.strip_tags($detail)).',';
				}	
				$price = urlencode($this->getSubmissionValue($args['SubmissionId'],$totaldetails['componentId']));
				$products = rtrim($products,',');
			}
			else
			{
				//Get Component properties
				$data = RSFormProHelper::getComponentProperties($this->getComponentId('rsfp_Product', $args['formId']));
				$products = urlencode(strip_tags($data['CAPTION']));
				$price = urlencode($data['PRICE']);
			}
			
			//build verification code
			$db->setQuery("SELECT DateSubmitted FROM #__rsform_submissions WHERE SubmissionId = '".$args['SubmissionId']."'");
			$code = md5($args['SubmissionId'].$db->loadResult());
			
			$paypal_link = RSFormProHelper::getConfig('paypal.test') ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/';
			$cancel_link = RSFormProHelper::getConfig('paypal.cancel');
			$cancel_link = !empty($cancel_link) ? '&cancel_return='.urlencode($cancel_link) : '';
			$language	 = RSFormProHelper::getConfig('paypal.language');
			$language	 = !empty($language) ? '&lc='.urlencode($language) : '&lc=US';
			$tax		 = RSFormProHelper::getConfig('paypal.tax.value');
			$tax		 = !empty($tax) ? $tax : 0;
			$taxtype	 = RSFormProHelper::getConfig('paypal.tax.type');
			
			if ($tax)
			{
				$tax_code = $taxtype ? '&tax='.urlencode($tax) : '&tax_rate='.urlencode($tax);
			} else $tax_code = '';
			
			if($price > 0)
			{
				$price = number_format($price, 2, '.', '');
				$link = $paypal_link . '?cmd=_xclick&business=' . urlencode(RSFormProHelper::getConfig('paypal.email')) . '&item_name=' . $products . '&currency_code=' . urlencode(RSFormProHelper::getConfig('paypal.currency')). '&amount=' . $price . '&return_url='.urlencode(JURI::root().'index.php?option=com_rsform&formId='.$args['formId'].'&task=plugin&plugin_task=paypal.return&code='.$code).'&notify_url='.urlencode(JURI::root().'index.php?option=com_rsform&formId='.$args['formId'].'&task=plugin&plugin_task=paypal.notify&code='.$code).'&return='.urlencode(RSFormProHelper::getConfig('paypal.return')).'&charset=utf-8'.$cancel_link.$language.$tax_code;
				$mainframe->redirect($link);
			}
		}
	}
	
	/*
		Additional Functions
	*/
	
	function getComponentName($componentId)
	{
		$componentId = (int) $componentId;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT PropertyValue FROM #__rsform_properties WHERE ComponentId='".$componentId."' AND PropertyName='NAME'");
		return $db->loadResult();
	}
	
	function getComponentId($name, $formId)
	{
		$formId = (int) $formId;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT p.ComponentId FROM #__rsform_properties p LEFT JOIN #__rsform_components c ON (p.ComponentId=c.ComponentId) WHERE p.PropertyValue='".$db->getEscaped($name)."' AND p.PropertyName='NAME' AND c.FormId='".$formId."'");
		
		return $db->loadResult();
	}
	
	function getSubmissionValue($submissionId, $componentId)
	{
		$name = $this->getComponentName($componentId);
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT FieldValue FROM #__rsform_submission_values WHERE SubmissionId='".(int) $submissionId."' AND FieldName='".$db->getEscaped($name)."'");
		return $db->loadResult();
	}
	
	//Notification receipt from Paypal
	function rsfp_f_paypalNotify()
	{
		$db = &JFactory::getDBO();
		$code = $db->getEscaped(JRequest::getVar('code'));
		$formId = JRequest::getInt('formId');
		$db->setQuery("UPDATE #__rsform_submission_values sv LEFT JOIN #__rsform_submissions s ON s.SubmissionId = sv.SubmissionId SET sv.FieldValue=1 WHERE sv.FieldName='_STATUS' AND sv.FormId='".$formId."' AND MD5(CONCAT(s.SubmissionId,s.DateSubmitted)) = '".$code."'");
		$db->query();
		
	}
	function paypalScreen()
	{
		echo 'paypal';
	}
	
	function paypalConfigurationScreen()
	{
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_system_rsfppaypal' );
		
		?>
		<div id="page-payments">
			<table  class="admintable">
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="currency"><?php echo JText::_( 'RSFP_PAYPAL_EMAIL' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.email]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.email')); ?>" size="100" maxlength="64"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="return"><?php echo JText::_( 'RSFP_PAYPAL_RETURN' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.return]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.return'));  ?>" size="100"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="cancel"><?php echo JText::_( 'RSFP_PAYPAL_CANCEL' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.cancel]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.cancel'));  ?>" size="100"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="currency"><?php echo JText::_( 'RSFP_PAYPAL_TEST' ); ?></label></td>
					<td><?php echo JHTML::_('select.booleanlist', 'rsformConfig[paypal.test]' , '' , RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.test')));?></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="tax.type"><?php echo JText::_( 'RSFP_PAYPAL_TAX_TYPE' ); ?></label></td>
					<td><?php echo JHTML::_('select.booleanlist', 'rsformConfig[paypal.tax.type]' , '' , RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.tax.type')), JText::_('RSFP_PAYPAL_TAX_TYPE_FIXED'), JText::_('RSFP_PAYPAL_TAX_TYPE_PERCENT'));?></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="tax.value"><?php echo JText::_( 'RSFP_PAYPAL_TAX_VALUE' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.tax.value]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.tax.value'));  ?>" size="4" maxlength="5"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="language"><?php echo JText::_( 'RSFP_PAYPAL_LANGUAGE' ); ?></label></td>
					<td>
						<input type="text" name="rsformConfig[paypal.language]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.language'));  ?>" size="4" maxlength="2">
						<?php echo JText::_('PAYPAL_LANGUAGES_CODES') ?>
					</td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="currency"><?php echo JText::_( 'RSFP_PAYPAL_CURRENCY' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.currency]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.currency'));  ?>" size="4" maxlength="50"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="thousands"><?php echo JText::_( 'RSFP_PAYPAL_THOUSANDS' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.thousands]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.thousands'));  ?>" size="4" maxlength="50"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="decimal"><?php echo JText::_( 'RSFP_PAYPAL_DECIMAL_SEPARATOR' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.decimal]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.decimal'));  ?>" size="4" maxlength="50"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="nr.decimal"><?php echo JText::_( 'RSFP_PAYPAL_NR_DECIMALS' ); ?></label></td>
					<td><input type="text" name="rsformConfig[paypal.nodecimals]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('paypal.nodecimals'));  ?>" size="4" maxlength="50"></td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	function merge($a,$b)
	{	
		foreach($b as $key => $value)
			$a[$key] = $value; 
		return $a;
	}
	
	function rsfp_bk_onAfterShowExportComponents($formComponentsHtml, $order)
	{
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_system_rsfppaypal' );
		
		$formComponentsHtml .= '
			<tr>
				<th class="title">'._RSFORM_BACKEND_SUBMISSIONS_EXPORT_HEAD_EXPORT.'</th>
				<th class="title">PayPal</th>
				<th class="title">'._RSFORM_BACKEND_SUBMISSIONS_EXPORT_HEAD_COLUMN_ORDER.'</th></tr>';
				
		$formComponentsHtml .=
				'<tr class="row0">
					<td><input type="checkbox" name="ExportSubmission[_STATUS]" value="1"/></td>
					<td>'.JText::_('RSFP_PAYPAL_STATUS').'</td>
					<td><input type="text" name="ExportOrder[_STATUS]" value="'.$order.'" size="3"/></td>
				</tr>'."\r\n";
	}
	
	function rsfp_bk_onAfterLoadRowsSubmissions($args)
	{
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_system_rsfppaypal' );
		
		if ($args['SManager']->export && is_array($args['return']))
			foreach ($args['return'] as $i => $row)
			{
				if (isset($row['SubmissionValues']['_STATUS']))
					$args['return'][$i]['SubmissionValues']['_STATUS']['Value'] = JText::_('RSFP_PAYPAL_STATUS_'.$row['SubmissionValues']['_STATUS']['Value']);
			}
	}
}