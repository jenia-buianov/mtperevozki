<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>

<?php if ($this->item->params->get('show_title')) : ?>
<h3 class="contentTitle">
	<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
		<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->escape($this->item->params->get( 'pageclass_sfx' )); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
</h3>
<?php endif; ?>

<?php if ($canEdit || $this->item->params->get('show_pdf_icon') || $this->item->params->get('show_print_icon') || $this->item->params->get('show_email_icon')) : ?>
<div>
	<?php if ($this->item->params->get('show_pdf_icon')) : ?>

	<?php echo JHTML::_('icon.pdf', $this->item, $this->item->params, $this->access); ?>

	<?php endif; ?>

	<?php if ( $this->item->params->get( 'show_print_icon' )) : ?>

	<?php echo JHTML::_('icon.print_popup', $this->item, $this->item->params, $this->access); ?>

	<?php endif; ?>

	<?php if ($this->item->params->get('show_email_icon')) : ?>

	<?php echo JHTML::_('icon.email', $this->item, $this->item->params, $this->access); ?>

	<?php endif; ?>
	<?php if ($canEdit) : ?>

		<?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?>

	<?php endif; ?>
</div>
<?php endif; ?>

<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>

<?php if (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid)) : ?>
<div>
		<?php if ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->item->section)) : ?>
		<span>
			<?php if ($this->item->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->item->section); ?>
			<?php if ($this->item->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
				<?php if ($this->item->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
		<?php if ($this->item->params->get('show_category') && $this->item->catid) : ?>
		<span>
			<?php if ($this->item->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->item->category); ?>
			<?php if ($this->item->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
</div>
<?php endif; ?>

<?php if (($this->item->params->get('show_author')) && ($this->item->author != "")) : ?>

		<span class="small">
			<?php JText::printf( 'Written by', ($this->escape($this->item->created_by_alias) ? $this->escape($this->item->created_by_alias) : $this->escape($this->item->author)) ); ?>
		</span>

<?php endif; ?>

<?php if ($this->item->params->get('show_create_date')) : ?>
	<div><?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?></div>
<?php endif; ?>

<?php if ($this->item->params->get('show_url') && $this->item->urls) : ?>

		<a href="http://<?php echo $this->escape($this->item->urls) ; ?>" target="_blank">
			<?php echo $this->escape($this->item->urls); ?></a>

<?php endif; ?>


<?php if (isset ($this->item->toc)) : ?>
	<div><?php echo $this->item->toc; ?></div>
<?php endif; ?>

<?php echo $this->item->text; ?>


<?php if ( intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
	<div><?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?></div>
<?php endif; ?>

<?php if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>

		<a href="<?php echo $this->item->readmore_link; ?>" class="readon<?php echo $this->escape($this->item->params->get('pageclass_sfx')); ?>">
			<?php if ($this->item->readmore_register) :
				echo JText::_('Register to read more...');
			elseif ($readmore = $this->item->params->get('readmore')) :
				echo $readmore;
			else :
				echo JText::sprintf('Read more...');
			endif; ?></a>

<?php endif; ?>


<?php echo $this->item->event->afterDisplayContent; ?>
