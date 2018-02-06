<?php

function Navigation($page,$pages) {
    $n='';
    if($pages>1){
        $n.='<div class="pages">';
        if($pages <= 9) {
            $start = 1;
            $end = $pages;
        }
        else {
            if(($page - 4) < 1) {
                $start = 1;
                $end = 9;
            }
            elseif(($page + 4) > $pages) {
                $end = $pages;
                $start = $pages - 9;
            }
            else {
                $start = ($page - 4);
                $end = ($page +4);
            }
        }
        for($i = $start; $i <= $end; $i++){
            $n.='<a href="'.HOME.'ru/news'.(($i != 1) ? '?page='.$i : '').'"'.(($page == $i) ? ' style="font-weight:bold"' : '').'>'.$i.'</a>';
        }
        if($end < $pages) {
            if($end != ($pages - 1)) $n.='<span>...</span>';
            $n.='<a href="'.Lng('ru/news?page='.$pages).'">'.$pages.'</a>';
        }
        $n.='</div>';
    }
return $n;
}
if (isset($_GET['page'])) {
	$page = htmlspecialchars($_GET['page'],ENT_QUOTES);
	$start = ($page-1)*20;
	$query = "SELECT * FROM `".DB_PREFIX."pages` WHERE `News`='1' AND `langAbb`='".LANG."' AND `Active`='1' ORDER BY `date` LIMIT ".$start.",20";
}
else {
	$query = "SELECT * FROM `".DB_PREFIX."pages` WHERE `News`='1' AND `langAbb`='".LANG."' AND `Active`='1' ORDER BY `date` LIMIT 20";
	$page=1;
	}

$getNews = mysqli_query($CONNECTION,$query);
if(mysqli_num_rows($getNews)>0)
{
	$news = mysqli_fetch_array($getNews);
	do
	{
		if (empty($news['shortText']))
		{
		$getRedirect = mysqli_query($CONNECTION,"SELECT `newurl` FROM `jos_redirection` WHERE `oldurl`='$news[URL]'");
		if(mysqli_num_rows($getRedirect)>0)
		{
			$redirect = mysqli_fetch_array($getRedirect);
			$explode= explode('id=',$redirect['newurl']);
			$ex2 = explode('&',$explode[1]);
			$getContent = mysqli_query($CONNECTION,"SELECT `introtext` FROM `jos_content` WHERE `id`='$ex2[0]'");
			$Content = mysqli_fetch_array($getContent);
			echo '<div style="margin-bottom:5em"><h3>'.$news['pageName'].'</h3><p>'.$Content['introtext'].'</p><p style="float:right"><a href="'.Lng($news['URL']).'"><button type="button" class="btn btn-info">Подробнее</button></a></p></div>';

		}else echo '<div><h3>'.$news['pageName'].'</h3></div>';
		}
		else echo '<div style="margin-bottom:5em"><h3>'.htmlspecialchars_decode($news['pageName']).'</h3><p>'.htmlspecialchars_decode($news['shortText']).'</p><p style="float:right"><a href="'.Lng($news['URL']).'"><button type="button" class="btn btn-info">Подробнее</button></a></p></div>';
	}
	while($news = mysqli_fetch_array($getNews));
	
	$PagesCount=intval(($ResultCount - 1) / 20) + 1;
	Navigation($page,$PagesCount);	
}
?>