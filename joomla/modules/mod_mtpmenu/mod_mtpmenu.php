<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$menu = $params->get('menu', false);
if($menu){
$db 	=& JFactory::getDBO();
$query = 'SELECT * FROM `#__menu` WHERE `menutype`="'.$db->getEscaped($menu).'" ORDER BY `ordering`';
$db->setQuery($query);
$rows = $db->loadObjectList();

$IN = '';
$cc;
foreach($rows as $k=>$row){
	$a = array();
	$a = explode('&id=', substr($row->link, 10));
	$IN .= ','.$a[1];
	$rows[$k]->article = $a[1];
	$params = new JParameter( $row->params );
	$rows[$k]->menu_image = $params->get('menu_image');
}
$query = "SELECT `id`, `introtext` FROM `#__content` WHERE `id` IN(".substr($IN,1).')';
$db->setQuery($query);
$cRows = $db->loadAssocList();
foreach($cRows as $arr){
	$cc[$arr['id']] = $arr['introtext'];
}
?><ul>
<?php foreach($rows as $row){ ?>
	<li>
		<a href="<?php echo jRoute::_($row->link.'&Itemid='.$row->id); ?>">
			<img src="/images/stories/<?php echo $row->menu_image; ?>" alt="<?php echo $row->name; ?>" />
			<span><?php echo strip_tags($cc[$row->title]); ?></span>
		</a>
	</li>
<?php } ?>
</ul>
<?php } ?>