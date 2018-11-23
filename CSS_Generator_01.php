#!/usr/bin/php
<?php

include'CSS_Generator_02.php';

// les argument ne sont pas fini
// function argument($argv)
// {	
// 	$option = array ("-r");
// 	var_dump($argv);
	

// 	foreach ($argv as $key => $value) 
// 	{	
// 		$argc = count($argv) - 1;
// 		var_dump($argc);
// 		if (is_dir($argv[$argc]) && $argv[1] === "-r" || is_dir($argv[$argc]) && $argv[1] === "-recursive")
// 		{
// 			if ($argc > 4)
// 			{
// 				echo "paramettre non valid\n";
// 				break;
// 			}
// 			else
// 			{
// 				list_dir_r($argv[$argc]);
// 				var_dump($key);
// 				// $find_egale = strpos($argv[1]);
// 				if ($argv[2] === "-i")
// 				{
// 					sprite_img_r($argv[$argc], $value[$key + 1]);
// 				}
// 				else
// 				{
// 					sprite_img_r($argv[$argc]);
// 				}
// 				generate_css_r($argv[$argc]);
// 				echo "Creation du sprite en recursive\n";
// 				break;
// 			}
// 	  	}
// 		elseif (is_dir($argv[$argc]))
// 		{	
// 			list_dir($argv[$argc]);
// 			sprite_img($argv[$argc]);
// 			generate_css($argv[$argc]);
// 			echo "Creation du sprite\n";
// 			break;
// 		}
// 		else
// 		{
// 			echo	"veuiller donner un  dossier en parametre.\n";
// 			break;
// 		}
		
// 	}	
// }
// argument($argv);


// cherche les image png en recursive dans le dossier donnée et mets les image png dans un tableau
function list_dir_r($file)
{
	$tab_image = array();
	if (is_dir($file))
	{
		if($img = opendir($file))
		{				
			while(false !== ($entry = readdir($img)))
			{								
				if (is_dir($file."/".$entry) && $entry != '.' && $entry != '..'  )	
				{	
					$tab_image = array_merge ($tab_image,list_dir($file."/".$entry));
				}
				elseif( $entry != '.' && $entry != '..' && preg_match('#\.(png)$#i', $entry))
				{
					$size = getimagesize($file."/".$entry);
					$tab_image [$file."/".$entry]= $size;
				}	
			}
		}
	}return($tab_image); 
}

list_dir_r("image");


//crée une image vide et la remplis avec les image trouvée avec list_dir_r en calculant les taille des image
function sprite_img_r($file,$lastname="sprite.png")
	{	
		$tab_images = list_dir_r($file);
		$max_height = 0;
		$max_width = 0;
		foreach ($tab_images as $key => $value) 
		{
			$width = $value[0];
			$height = $value[1];
			if($height > $max_height)
			{	
				$max_height = $height;
			}
			$max_width += $width ;	
		}
	 $sprite = imagecreatetruecolor($max_width,$max_height); 
	 $max_x = 0;
		foreach ($tab_images as $key => $value)
		{
			$width = $value[0];
			$height = $value[1];
			$img_1 = imagecreatefrompng($key);	 	
			imagecopy($sprite, $img_1, $max_x, 0, 0 ,0, $width, $height);
			$max_x += $width;
		}
	 imagepng($sprite,$lastname);
	 
 }
sprite_img_r("image");



// crée un fichier css et ecrit a l'interieure avec le stylsheet des image 
function generate_css_r($file,$last_css="style.css")
{	
	$open = fopen($last_css,"w+");
	$max_x = 0;
	$tab_images=list_dir_r($file); 
	foreach ($tab_images as $key => $value) 
	{	
		$nom = basename($key);
		$nom2 =  substr($nom,0,strpos($nom,".")-4);
		$width = $value[0];
		$height = $value[1];

		fwrite($open,"#".$nom2. "{\n\twidth : ".$width."px;\n\theight : ".$height."px;\n\tbackground-position: 0px -".$max_x."px;\n}\n" );
		$max_x += $width;
	}  
 }
generate_css_r("image");
 

