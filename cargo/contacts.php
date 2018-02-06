<p align="center"><img src="<?php echo HOME; ?>images/managers.jpg" style="max-width:300px; max-height:300px;" /></p>
<div id="contacts_">
<?php
	$getContacts = mysqli_query($CONNECTION, "SELECT * FROM `".DB_PREFIX."operators` WHERE `isEnabled`='1'");
	if (mysqli_num_rows($getContacts)>0)
	{
		$Contacts = mysqli_fetch_array($getContacts);
		do
		{
			echo '<div>';
			echo'<h4><img src="'.HOME.'images/ContIcon.png" style="height:1.8em;margin-right:0.2em"> <span style="color:#3498db">'.$Contacts['Name'].'</span>';
			if (mb_strlen($Contacts['Category'],"UTF-8")>50) echo '<span style="font-size:0.7em;padding-left:1em">'.$Contacts['Category'].'</span>';
			else echo '<span style="font-size:0.9em; padding-left:1em">'.$Contacts['Category'].'</span>';
			echo'</h4>';
			if(!empty($Contacts['Phone'])) echo'<font><i class="fa fa-phone" aria-hidden="true" style="color:green" style="margin-right:0.2em"></i> '.$Contacts['Phone'].'</font>';
			if(!empty($Contacts['Skype'])) echo'<font><i class="fa fa-skype" aria-hidden="true" style="color:#00ADED;margin-right:0.2em"></i> '.$Contacts['Skype'].'</font>';
			if(!empty($Contacts['Email'])) echo'<font><i class="fa fa-envelope-o" aria-hidden="true" style="margin-right:0.2em"></i> '.$Contacts['Email'].'</font>';
			echo'</div>';
		}
		while($Contacts = mysqli_fetch_array($getContacts));
	}
?>
</div>