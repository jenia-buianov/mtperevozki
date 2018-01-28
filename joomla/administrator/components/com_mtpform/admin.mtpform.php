<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
jimport('joomla.application.component.controller');
class mtpformController extends JController{
	function __construct(){
		parent::__construct();
		$this->registerTask('save', 'save');
	}
	function display(){
		JToolBarHelper::save();
		JToolBarHelper::title('Форма заявки: Редактировать шаблон письма');
		include JPATH_COMPONENT_ADMINISTRATOR.DS.'template.mtpform.php'; ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<p>
			Используйте в шаблоне письма следующие вставки:<br />
			{date} - дата заявки <br />
			{name} - ФИО заказчика<br />
			{phone} - телефон заказчика<br />
			{email} - email заказчика<br />
			{load_address} - адрес загрузки<br />
			{load_date} - дата загрузки<br />
			{shipping_address} - адрес доставки <br />
			{customs} - таможенные посты<br />
			{measures} - габариты груза<br />
			{weight} - вес груза<br />
			{code} - код ТНВЭД<br />
			{cargo_info} - подробное описание груза<br />
			{additional_info} - дополнительная информация
			</p>
			Адрес получателя:<br />
			<input name="email" value="<?php echo $email ?>" style="width:550px" /><br />
			Тема письма:<br />
			<input name="subject" value="<?php echo $subject ?>" style="width:550px" /><br />
			Текст письма:<br />
			<?php
				$editor =& JFactory::getEditor();
				echo $editor->display('body', $body, '550', '400', '60', '20', false);
			?>
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="option" value="com_mtpform" />
		</form>
		<?php
	}
	function save(){
		foreach($_POST as $k=>$v){
			$v = trim($v);
			if(get_magic_quotes_gpc()){
				$v = stripslashes($v);
				$v = str_replace("'","\\'",$v);
			}
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$content = '<?php 
	$email=\''.$_POST['email'].'\';
	$subject=\''.$_POST['subject'].'\';
	$body=\''.$_POST['body'].'\';
?>';
			$_POST[$k] = $v;
		}
		file_put_contents(JPATH_COMPONENT_ADMINISTRATOR.DS.'template.mtpform.php', $content);
		$this->setRedirect('index.php?option=com_mtpform', 'Шаблон сохранён');
	}
}
$controller   = new mtpformController();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();

?>