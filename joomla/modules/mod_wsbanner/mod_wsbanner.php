<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<a id="changeBanner" href="#" title="" rel=""><img src="#" alt="#" /></a>
<script type="text/javascript">
(function(global){
    "use strict";
    var bannerPlace,
        bannerImage,
        interval,
		images = [],
		titles = [],
		links = [];
		<?php
		$title = $link = $image = array();
		for($i=1;$i<=10;$i++){
			$im = $params->get('image'.$i, null);
			if($im && $im!='-1'){
				$lnk = $params->get('link'.$i, '');
				$ttl = $params->get('title'.$i, 'баннер '.$i); ?>
		images[images.length] = '<?php echo $im; ?>',
		titles[titles.length] = '<?php echo $ttl; ?>';
		links[links.length] = '<?php echo $lnk; ?>';
				<?php
			}else{
				break;
			}
		}
		?>
    function changeBanner(){
        var index = parseInt(bannerPlace.rel, 10);

        bannerImage.src = '/images/banners/' + images[index];
        bannerPlace.title = bannerImage.alt = titles[index];
        bannerPlace.href = links[index];
        
        if(images[index + 1]){
            bannerPlace.rel = index + 1;
        }else{
            bannerPlace.rel = 0;
        }
    }
	if(document.addEventListener){
       var addListener = "addEventListener";
       var loadEvent = "load";
    }else{
       var addListener = "attachEvent";
       var loadEvent = "onload";
    };
    window[addListener](loadEvent, function(){
   // onload = function(){
		bannerPlace = $('changeBanner');
		bannerImage = bannerPlace.getElementsByTagName("img")[0];
		bannerPlace.rel = 0;
		interval = setInterval(changeBanner, <?php echo $params->get('speed', 4000); ?>);
		bannerPlace.onclick = function(){
			clearInterval(interval);
		};
		changeBanner();
		$('changeBanner').onmouseover = function(){
			clearInterval(interval);
		}
		$('changeBanner').onmouseout = function(){
			interval = setInterval(changeBanner, <?php echo $params->get('speed', 4000); ?>);
		}
	}
	);
}(document));
</script>