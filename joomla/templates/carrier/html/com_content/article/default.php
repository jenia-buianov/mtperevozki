<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>

<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
	<h3 class="contentTitle<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h3>
<?php endif; ?>

<?php if ($this->params->get('show_title')) : ?>
	<?php if ($this->params->get('show_title')) : ?>
	<h3 class="contentTitle<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
		<a href="<?php echo $this->article->readmore_link; ?>" class="contentpagetitle<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $this->escape($this->article->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->article->title); ?>
		<?php endif; ?>
	</h3>
	<?php endif; ?>
<?php endif; ?>

<?php if ($canEdit || $this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
	<div>
	<?php if (!$this->print) : ?>
		<?php if ($this->params->get('show_pdf_icon')) : ?>

		<?php echo JHTML::_('icon.pdf',  $this->article, $this->params, $this->access); ?>

		<?php endif; ?>

		<?php if ( $this->params->get( 'show_print_icon' )) : ?>

		<?php echo JHTML::_('icon.print_popup',  $this->article, $this->params, $this->access); ?>

		<?php endif; ?>

		<?php if ($this->params->get('show_email_icon')) : ?>

		<?php echo JHTML::_('icon.email',  $this->article, $this->params, $this->access); ?>

		<?php endif; ?>
		<?php if ($canEdit) : ?>

			<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>

		<?php endif; ?>
	<?php else : ?>

		<?php echo JHTML::_('icon.print_screen',  $this->article, $this->params, $this->access); ?>

	<?php endif; ?>
	</div>
<?php endif; ?>

<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>

<?php if (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid)) : ?>

		<?php if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
		<span>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->article->section); ?>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
				<?php if ($this->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
		<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
		<span>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->article->category); ?>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>

<?php endif; ?>
<?php if (($this->params->get('show_author')) && ($this->article->author != "")) : ?>
		<span class="small">
			<?php JText::printf( 'Written by', ($this->escape($this->article->created_by_alias) ? $this->escape($this->article->created_by_alias) : $this->escape($this->article->author)) ); ?>
		</span>
<?php endif; ?>

<?php if ($this->params->get('show_create_date')) : ?>
		<div><?php echo JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC2')) ?></div>
<?php endif; ?>

<?php if ($this->params->get('show_url') && $this->article->urls) : ?>
		<a href="http://<?php echo $this->article->urls ; ?>" target="_blank"><?php echo $this->escape($this->article->urls); ?></a>
<?php endif; ?>

<?php if (isset ($this->article->toc)) : ?>
	<div><?php echo $this->article->toc; ?></div>
<?php endif; ?>

<?php echo $this->article->text; ?>

<?php if ( intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
<div><?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2'))); ?></div>
<?php endif; ?>

<?php echo $this->article->event->afterDisplayContent; ?>
