<?php 
echo '<pre>';

$dir = "data-crossword";
$files = read_all_files($dir)['files'];
#shuffle($files);
$storeJson = $storeCvs = '';
$no = 1;

$level = isset($_GET['level'])?$_GET['level']:'easy';

foreach ($files as $file) {
	if(strpos($file,'json')==true){
		#echo "Open this file : " .$file."<br>";
		$jsonContent = json_decode(file_get_contents($file));
		#var_dump($jsonContent);

		$countQ = count($jsonContent->clues->across);
		
		#echo $countQ.'<br>';
		for($a=0;$a<=$countQ;$a++){
			if ($jsonContent->answers->across[$a]!=''){
				if($level=='easy' && (strlen($jsonContent->answers->across[$a])>3)) continue;
				if($level=='middle' && (strlen($jsonContent->answers->across[$a])>5 || strlen($jsonContent->answers->across[$a])<=3)) continue;
				if($level=='hard' && (strlen($jsonContent->answers->down[$a])<4 && strlen($jsonContent->answers->down[$a])>6)) continue;

				if(fixClue($jsonContent->clues->across[$a])=='..') continue;
				$storeJson .= "{word:'".$jsonContent->answers->across[$a]."', clue:'".fixClue($jsonContent->clues->across[$a])."'},\n";
				$storeCvs .= $jsonContent->answers->across[$a]."|".fixClue($jsonContent->clues->across[$a])."\n";
			}
			if($jsonContent->answers->down[$a]!=''){
				if($level=='easy' && (strlen($jsonContent->answers->down[$a])>3)) continue;
				if($level=='middle' && (strlen($jsonContent->answers->down[$a])>5 || strlen($jsonContent->answers->down[$a])<=3)) continue; 
				if($level=='hard' && (strlen($jsonContent->answers->down[$a])<4 && strlen($jsonContent->answers->down[$a])>6)) continue;

				if(fixClue($jsonContent->clues->down[$a])=='..') continue;
				$storeJson .= "{word:'".$jsonContent->answers->down[$a]."', clue:'".fixClue($jsonContent->clues->down[$a])."'},\n";
				$storeCvs .= $jsonContent->answers->down[$a]."|".fixClue($jsonContent->clues->down[$a])."\n";
			}
			
			
		}

		#var_dump($jsonContent->clues->across);
		#var_dump($jsonContent->answers->across);

		#var_dump($jsonContent->clues->down);
		#var_dump($jsonContent->answers->down);
		#echo $storeJson;
		#echo '<br>';
		if($no > 200){
			$jsJson = "var entries = [".$storeJson."]";
			echo $jsJson;
			echo '<hr>';
			echo $storeCvs; 
			die();
		}
		$no++;

		
	}
		
	// Save to file
	
}

function fixClue($string){
	$clue = explode(". ", $string);
	$clue = str_replace("'", "\'", $clue);
	return $clue[1];
}

 function read_all_files($root = '.'){ 
  $files  = array('files'=>array(), 'dirs'=>array()); 
  $directories  = array(); 
  $last_letter  = $root[strlen($root)-1]; 
  $root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR; 
  
  $directories[]  = $root; 
  
  while (sizeof($directories)) { 
    $dir  = array_pop($directories); 
    if ($handle = opendir($dir)) { 
      while (false !== ($file = readdir($handle))) { 
        if ($file == '.' || $file == '..') { 
          continue; 
        } 
        $file  = $dir.$file; 
        if (is_dir($file)) { 
          $directory_path = $file.DIRECTORY_SEPARATOR; 
          array_push($directories, $directory_path); 
          $files['dirs'][]  = $directory_path; 
        } elseif (is_file($file)) { 
          $files['files'][]  = $file; 
        } 
      } 
      closedir($handle); 
    } 
  } 
  
  return $files; 
} 