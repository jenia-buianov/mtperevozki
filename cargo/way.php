<?php


$sqlWays = mysqli_query($CONNECTION,"SELECT `wayTitle`,`Link` FROM `".DB_PREFIX."ways` ORDER BY `wayTitle`");

		if (mysqli_num_rows($sqlWays)>0)

		{

			

			$arrayWays = array();

			$del = mysqli_num_rows($sqlWays)/4;

			$Way = mysqli_fetch_array($sqlWays);

			$i=0; $k=0;			

			do

			{

				if ($i==$del){ $i=0;$k++;}

				$arrayWays[$k][$i] = array('link'=>$Way['Link'],'title'=>$Way['wayTitle']);

				$i++;

			}

			while($Way = mysqli_fetch_array($sqlWays));

			if (ID=='homepage') echo '<div id="ways">'; else echo '<div id="ways" style="margin-top:-1em;padding-top:0px;">';

			if (ID=='homepage') echo '<div class="ttl" style="margin-bottom:1em">Грузоперевозки по направлениям</div>';

			for($i=0;$i<$del;$i++)

			{

				for($j=0;$j<4;$j++)

				echo'<font><a href="'.Lng($arrayWays[$j][$i]['link']).'"><h4>'.$arrayWays[$j][$i]['title'].'</h4></a></font>';

				

			}

			echo '</div>';

		}

		?>