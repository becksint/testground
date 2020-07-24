<?php
$_SESSION['library']['list'] = json_read('lists');
function writer($file, $name, $input){
	$data = json_read($file);
	if (!empty($data[$name])) {
		end($data[$name]);
		$new = key($data[$name]); 		
		$new++; 
	}
	else { $new = 1;  }
	$data[$name][$new] = $input; 
	//$data[$name][$new]['meta']['position'] = $new*10;
	file_write('../data/'.$file.'.json', $data);
	return $new;
}


function file_write($file, $data){
	$fp   = fopen($file, 'w'); 
	fwrite($fp, json_encode($data));  
	fclose($fp);
}


function json_read($name){
	$file = '../data/'.$name.'.json'; 
	if(file_exists($file)){
					$read = file_get_contents($file);			
					$json = json_decode( $read, true ); 
				  }
	else { $json = array(); }
	return $json;
}




?>