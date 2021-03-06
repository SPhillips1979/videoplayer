<?php

	function directoryToArray($directory, $recursive) {
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
						}
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}

	function getJSFilesArray($videoPath,$videoUrl) {

		$output = array();

		$files = directoryToArray($videoPath,true);

		foreach($files as $file){

			$bits = array();

			$filename = "";

			$bits = explode('/',str_replace($videoPath,'',$file));
	
			$endpos = (count($bits)-1);
	
			$filename = $bits[$endpos];

			$output[] = "'".$videoUrl.$filename."'";
		}

		$output = implode(",\r\n",$output);

		return "var videos = new Array(\r\n".$output."\r\n);\r\n";
	}

	function getPlaylist($videoPath) {

		$output = "";

		$files = directoryToArray($videoPath,true);

		$count = 1;
		$v_count = 0;
		foreach($files as $file){
			$bits = array();

			$filename = "";

			$bits = explode('/',str_replace($videoPath,'',$file));

			$endpos = (count($bits)-1);
	
			$filename = $bits[$endpos];

			$output .= '<a href="javascript:setVideo('.$v_count.');" style="color:#ffffff;text-decoration:none;">'.$count.". ".$filename."</a><br/>\r\n";

			$count++;
			$v_count++;
		}

		return $output;
	}

?>
