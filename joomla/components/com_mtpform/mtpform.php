<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
foreach($_POST as $k=>$v){
	$_POST[$k] = trim($v);
	$_POST[$k] = strip_tags($_POST[$k]);
}
$error = false;
$errors = array();
if(!isset($_POST['osolCatchaTxt'])||empty($_POST['osolCatchaTxt'])){
	$error = true;
	$errors[] = JText::_('MTP_MESSAGE_CAPTCHA');
}
if(!isset($_POST['name'])||empty($_POST['name'])){
	$error = true;
	$errors[] = JText::_('MTP_MESSAGE_NAME');
}
if(!isset($_POST['phone'])||empty($_POST['phone'])){
	$error = true;
	$errors[] = JText::_('MTP_MESSAGE_PHONE');
}
if(!isset($_POST['email'])||empty($_POST['email'])){
	$error = true;
	$errors[] = JText::_('MTP_MESSAGE_EMAIL1');
}else if(!preg_match('/^[А-яЁёa-z_0-9-.]+@[А-яЁёa-z_0-9-]+\.[А-яЁёa-z-_.]+$/iu',$_POST['email'])){
	$error = true;
	$errors[] = JText::_('MTP_MESSAGE_EMAIL2');
}
?>
<h3 class="contentTitle"><?php echo JText::_('MTP_FORM') ?></h3>
<?php 
if($error){ 
	if(isset($_POST) && sizeof($_POST)){?>
	<div class="formError">
	<?php foreach($errors as $E){ echo "\t".$E.'<br />'."\n"; } ?>
	</div>
	<?php
	}
	include JPATH_COMPONENT_SITE.DS.'form.mtpform.php';
}else{
	//отправка 
	include JPATH_COMPONENT_ADMINISTRATOR.DS.'template.mtpform.php';
	
	$_POST['date'] = date('Y.m.d H:i:s');
	foreach($_POST as $k=>$v){
		$body=str_replace('{'.$k.'}', $v, $body);
		$subject=str_replace('{'.$k.'}', $v, $subject);
	}
	
	//$body.=' REF: '.$_SERVER['HTTP_REFERER'].'<br>';
	//$body.=' IP: '. $_SERVER['REMOTE_ADDR'] .'<br>';
	//$body.=' CPT: '.JRequest::getVar('osolCatchaTxt', '-').'<br>';
	
	$mailer =& JFactory::getMailer();
	$config =& JFactory::getConfig();
	$sender = array(
		$config->getValue( 'config.mailfrom' ),
		$config->getValue( 'config.fromname' )
	); 
	$mailer->setSender($sender);
	$mailer->addRecipient($email);
	$mailer->isHTML(true);
	$mailer->Encoding = 'base64';
	$mailer->setSubject($subject);
	$mailer->setBody($body);
	$send =& $mailer->Send();
	
	echo JText::_('MTP_MESSAGE_SUCCESS');
}
?>