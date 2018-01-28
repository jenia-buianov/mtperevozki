<?
if (!class_exists('AntiBotCaptcha'))
{

	@session_start();

	class AntiBotCaptcha
	{
		var $form_div;
	
		function AntiBotCaptcha($params = false)
		{
			global $_SERVER;
			
			$this->form_div = strtolower(substr(md5( $_SERVER['SERVER_NAME'] ), 3, 12));

			$this->GetImage($params);
			
		}
	
		function CaptchaID()
		{
			return $this->form_div;
		}
		
		function GetImageCode ()
		{
			global $_SERVER;
			
			$img = '';
			$img = '<img src="?image='.time().'" align="absbottom" />';
			
			return $img;
		}
		
		function GetInputCode ($params = false)
		{
		
			$defaults = array (
									"size" => 6,
									"tabindex" => "4",
									"class" => "textfield"
								);
			$params = array_filter(array_merge($defaults, (array)$params));
			
			$input = '';
			$input .= '<input type="text" name="'.$this->form_div.'" id="'.$this->form_div.'" ';
			
			if (is_array($params))
			{
				foreach ($params as $param => $value)
					$input .= $param.'="'.$value.'" ';
			}
			$input .= '/>';
			
			return $input;
		
		}
	
		function Check()
		{
			global  $_POST, $_SESSION;
			
			$securitycode = $_POST[$this->form_div];
			
			if ( '' == $securitycode ) return -1;
			
			if ( $_SESSION['IMAGE_CODE'] != $securitycode )	return 0;
			else
			{
				unset($_SESSION['IMAGE_CODE']);
				return 1;
			}
			
		}
	
		function GetImage($params = false)
		{
		
			$defaults = array (
								'count' => 4, 					/* symbol count */
								'width' => 100, 				/* picture width */
								'height' => 30,					/* picture height */
								'font_size_min' => 14,			/* minimum symobl height */
								'font_size_max' => 18,			/* maximum symobl height */
								'font_file' => (dirname(__FILE__))."/gothic.otf",	/* font name, otf or ttfs */
								'char_angle_min' => -10,		/* maximum skew of the symbol to the left*/
								'char_angle_max' => 10,			/*  maximum skew of the symbol to the right */
								'char_angle_shadow' => 5,		/*shadow size */
								'char_align' => 25,				/* align symbol verticaly */
								'start' => 5,					/* first symbol position */
								'interval' => 16,				/* interval between the start position of characters */
								'chars' =>	"2345689",			/* charset */
								'noise' => 0,					/* noise level */
							);
							
			$params = array_filter(array_merge($defaults, (array) $params));
			
			if (isset($_GET['image']) && preg_match('/^[0-9]+$/', $_GET['image']))
			{  
				session_start();
				 @ob_end_clean();


				$count=4;	/* symbol count */
				$width=100; /* picture width */
				$height=30; /* picture height */
				$font_size_min=14; /* minimum symobl height */
				$font_size_max=18; /* maximum symobl height */
				$font_file=(dirname(__FILE__))."/gothic.otf"; /* font name, otf or ttfs */
				$char_angle_min=-10; /* maximum skew of the symbol to the left*/
				$char_angle_max=10;	/*  maximum skew of the symbol to the right */
				$char_angle_shadow=5;	/*shadow size */
				$char_align=25;	/* align symbol verticaly */
				$start=5;	/* first symbol position */
				$interval=16;	/* interval between the start position of characters */
				$chars="2345689"; /* charset */
				$noise=0; /* noise level */

				$image=imagecreatetruecolor($params['width'], $params['height']);

				$background_color=imagecolorallocate($image, 255, 255, 255); /* rbg background color*/
				$font_color=imagecolorallocate($image, 32, 64, 96); /* rbg shadow color*/

				imagefill($image, 0, 0, $background_color);

				$str="";

				$num_chars=strlen($params['chars']);
				for ($i=0; $i<$count; $i++)
				{
					$char=$params['chars'][rand(0, $num_chars-1)];
					$font_size=rand($params['font_size_min'], $params['font_size_max']);
					$char_angle=rand($params['char_angle_min'], $params['char_angle_max']);
					imagettftext($image, $font_size, $char_angle, $params['start'], $params['char_align'], $font_color, $params['font_file'], $char);
					imagettftext($image, $font_size, $char_angle+$params['char_angle_shadow']*(rand(0, 1)*2-1), $params['start'], $params['char_align'], $background_color, $params['font_file'], $char);
					$params['start']+=$params['interval'];
					$str.=$char;
				}

				if ($params['noise'])
				{
					for ($i=0; $i<$params['width']; $i++)
					{
						for ($j=0; $j<$params['height']; $j++)
						{
							$rgb=imagecolorat($image, $i, $j);
							$r=($rgb>>16) & 0xFF;
							$g=($rgb>>8) & 0xFF;
							$b=$rgb & 0xFF;
							$k=rand(-$params['noise'], $params['noise']);
							$rn=$r+255*$k/100;
							$gn=$g+255*$k/100;		
							$bn=$b+255*$k/100;
							if ($rn<0) $rn=0;
							if ($gn<0) $gn=0;
							if ($bn<0) $bn=0;
							if ($rn>255) $rn=255;
							if ($gn>255) $gn=255;
							if ($bn>255) $bn=255;
							$color=imagecolorallocate($image, $rn, $gn, $bn);
							imagesetpixel($image, $i, $j , $color);					
						}
					}
				}

				$_SESSION["IMAGE_CODE"]=$str;

				if (function_exists("imagepng"))
				{
					header("Content-type: image/png");
					imagepng($image);
				}
				elseif (function_exists("imagegif"))
				{
					header("Content-type: image/gif");
					imagegif($image);
				}
				elseif (function_exists("imagejpeg"))
				{
					header("Content-type: image/jpeg");
					imagejpeg($image);
				}

				imagedestroy($image);
				exit;
			}
		}
	}
}