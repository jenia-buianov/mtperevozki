<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_("behavior.mootools");
$tmpl = $this->baseurl.'/templates/'.$this->template.'/';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
    <head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <jdoc:include type="head" />
        <link rel="stylesheet" type="text/css" href="<?php echo $tmpl; ?>css/style.css" />
		<script type="text/javascript" src="<?php echo $tmpl; ?>js/cufon-yui.js"></script>
		<script type="text/javascript" src="<?php echo $tmpl; ?>js/Chicago_400.font.js"></script>
		<script type="text/javascript" src="<?php echo $tmpl; ?>js/script.js"></script>
		<!--[if lte IE 7]><style>.slider{padding: 12px 0 0;}</style><![endif]-->	
	</head>
    <body>
		<div class="wrapper">
			<div class="header">
				<div class="headerTop">
					<h1><jdoc:include type="modules" style="raw" name="slogan" /></h1>
					<!--<address class="tel"><jdoc:include type="modules" style="raw" name="phone" /></address>-->
					<jdoc:include type="modules" style="raw" name="langs" />
					<div class="slider">
						<jdoc:include type="modules" style="raw" name="slider" />
					</div>
					<jdoc:include type="modules" style="raw" name="mainmenu" />
				</div>
				<div class="headerBott">
					<jdoc:include type="modules" style="raw" name="top" />
				</div>
			</div>
			<div class="container">
			<div class="stretchA">
			<div class="stretchB">
			<div class="stretchC">
				<div class="column content">
					<div class="contentInner">
						<jdoc:include type="message" />
						<jdoc:include type="component" />
						<jdoc:include type="modules" style="xhtml" name="goto" />
					</div>
				</div>
				<div class="column left" id="columnLeft">
					<div class="columnInner">
						<jdoc:include type="modules" style="xhtml" name="left" />
					</div>
				</div>
				<div class="column right" id="columnRight">
					<div class="columnInner">
						<jdoc:include type="modules" style="xhtml" name="right" />
					</div>
				</div>
			</div>
			</div>
			</div>
			</div>
			<div class="footer">
				<div class="footerInner">
					<div class="copyright"><jdoc:include type="modules" style="raw" name="copyright" /></div>
					<div class="counters">
					<?php if($_SERVER['HTTP_HOST']!='perevoz'){ ?>
					<jdoc:include type="modules" style="raw" name="counters" />
					<?php } ?>
					</div>
					<div class="phone"><jdoc:include type="modules" style="raw" name="phone" /></div>
				</div>
			</div>
		</div>
		<jdoc:include type="modules" style="raw" name="modalwin" />
    </body>
</html>