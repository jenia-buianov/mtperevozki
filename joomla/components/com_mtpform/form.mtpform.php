<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<form action="<?php echo JRoute::_('index.php?option=com_mtpform') ?>" method="post">
	<fieldset>
		<div class="legend"><?php echo JText::_('MTP_CONTACT_INFO') ?></div>
		<p>
			<label><?php echo JText::_('MTP_YOUR_NAME') ?> *</label>
			<input type="text" class="inputbox" name="name" value="<?php echo JRequest::getVar('name'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_YOUR_PHONE') ?> *</label>
			<input type="text" class="inputbox" name="phone" value="<?php echo JRequest::getVar('phone'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_YOUR_EMAIL') ?> *</label>
			<input type="text" class="inputbox" name="email" value="<?php echo JRequest::getVar('email'); ?>" />
		</p>
	</fieldset>
	<fieldset>
		<div class="legend"><?php echo JText::_('MTP_LOAD_POINT') ?></div>
		<p>
			<label><?php echo JText::_('MTP_LOAD_ADDRESS') ?></label>
			<input type="text" class="inputbox" name="load_address" value="<?php echo JRequest::getVar('load_address'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_LOAD_DATE') ?></label>
			<input type="text" class="inputbox" name="load_date" value="<?php echo JRequest::getVar('load_date'); ?>" />
		</p>
	</fieldset>
	<fieldset>
		<div class="legend"><?php echo JText::_('MTP_DESTINATION_POINT') ?></div>
		<p>
			<label><?php echo JText::_('MTP_SHIPPING_ADDRESS') ?></label>
			<input type="text" class="inputbox" name="shipping_address" value="<?php echo JRequest::getVar('shipping_address'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_CUSTOMS') ?></label>
			<input type="text" class="inputbox" name="customs" value="<?php echo JRequest::getVar('customs'); ?>" />
		</p>
	</fieldset>
	<fieldset>
		<div class="legend"><?php echo JText::_('MTP_CARGO') ?></div>
		<p>
			<label><?php echo JText::_('MTP_MEASURES') ?></label>
			<input type="text" class="inputbox" name="measures" value="<?php echo JRequest::getVar('measures'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_WEIGHT') ?></label>
			<input type="text" class="inputbox" name="weight" value="<?php echo JRequest::getVar('weight'); ?>" />
		</p>
		<p>
			<label><?php echo JText::_('MTP_CODE') ?></label>
			<input type="text" class="inputbox" name="code" value="<?php echo JRequest::getVar('code'); ?>" />
		</p>
		<div><?php echo JText::_('MTP_CARGO_INFO') ?></div>
		<textarea name="cargo_info"><?php echo JRequest::getVar('cargo_info'); ?></textarea>
		<div><?php echo JText::_('MTP_ADDITIONAL_INFO') ?></div>
		<textarea name="additional_info"><?php echo JRequest::getVar('additional_info'); ?></textarea>
		<?php 
				//set the argument below to true if you need to show vertically( 3 cells one below the other)
				JFactory::getApplication()->triggerEvent('onShowOSOLCaptcha', array(false)); 
			?>
		<!--p style="margin-top: 6px;">
			<label style="margin-top: 0px;"><img src="/kcaptcha/index.php" alt="captcha" id="mtpCaptcha" /></label>
			<input type="text" class="inputbox" name="captcha" value="" /><br />
			<label><?php echo JText::_('MTP_CAPTCHA') ?> *</label>
			<a style="margin: 13px;" href="#" onclick="return refreshCaptcha();"><?php echo JText::_('MTP_CAPTCHA_OTHER') ?></a>
		</p-->
	</fieldset>
	<div><input type="submit" class="button" value="<?php echo JText::_('MTP_SEND') ?>" /></div>
</form>