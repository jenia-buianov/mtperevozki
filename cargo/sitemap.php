<?php
$sqlMap = mysqli_query($CONNECTION,"SELECT  * FROM `".DB_PREFIX."sitemap` WHERE `Parent`='0' ORDER BY `siteID`");
		if (mysqli_num_rows($sqlMap)>0)
		{
			
			$map = mysqli_fetch_array($sqlMap);
			
			echo '<ul>';
			do
			{
				
				echo '<li><a href="'.HOME.$map['Link'].'">'.$map['Title'].'</a></li>';
				$sqlMap2 = mysqli_query($CONNECTION,"SELECT  DISTINCT `Title`,`Link` FROM `".DB_PREFIX."sitemap` WHERE `Parent`='".$map['siteID']."' AND `siteID`<>'$map[siteID]' ORDER BY `siteID`");
				if (mysqli_num_rows($sqlMap2)>0)
				{
					
					$map2 = mysqli_fetch_array($sqlMap2);
					echo '<ul>';
					do
					{
						echo '<li><a href="'.HOME.$map2['Link'].'">'.$map2['Title'].'</a></li>';
					}
					while($map2 = mysqli_fetch_array($sqlMap2));
					echo '</ul>';
				}
				
			}
			while($map = mysqli_fetch_array($sqlMap));
			echo '</ul>';
		}
		?>