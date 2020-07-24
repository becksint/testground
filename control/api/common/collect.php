<?php
/*The cards allows one to draw any data file from plain json to html, 
the file must include 

LIST : 
TEMPLATES
DEFAULTS --> images, info etc
OFFERING --> this is the actual info

these files may be split in the future but iit's recommended they are kept as one file for easy setup
function cards($item, $request, $limit, $show, $style, $paginate, $filter)
consider adding file

This could be used universally for templating just give it a try and develop the functional side
Adding data- attributes via the content function
Also allow adding functional attrributes based on the list library (including links, etc)
*/
//Sections-->page array seection => ( template=> section info + card info/template/datafile)
function sections($item, $request, $limit, $show, $style, $paginate, $filter){
$_SESSION['system'] = json_decode(file_get_contents('../data/system.json'), true);
$content = json_decode(file_get_contents('../data/'.$item.'.json'), true);
$defaults = $content['defaults'];
$template = $content['templates'];
$offering = $content['sections'];
$_SESSION['profile'] =  $content['profile'];
$_SESSION['list'] = $content['lists'];  //user specific common list
$counter = 1;
$output = '';




if($show == 'specific'){
			$newray = array();
			foreach ($offering[$request] as $k => $v) {	$newray = array_merge_recursive($newray, $v);	}
		   $output .= disher('', $template[$style], $newray, $k);  //add filter attributes on the atricle id, data-eg
		 
	}
else {
		foreach ($offering as $k => $value) { //get all from database
		    $newray = array();
		   foreach ($value as $v) {	$newray = array_merge_recursive($newray, $v);	}
		    $output .=  disher('', $template[$style], $newray, $k);
		   if ($counter >= $limit){  break; }
		   $counter++;
		}
	}

//if pagination CLOSE tag
return $output;
}



function cards($item, $request, $limit, $show, $style, $paginate, $filter, $id){
$_SESSION['system'] = json_decode(file_get_contents('../data/system.json'), true);
$content = json_decode(file_get_contents('../data/'.$item.'.json'), true);
//$defaults = $content['defaults'];
$template = $content['templates'];
$offering = $content['offering'];
$offercount = count($offering);
$_SESSION['list'] = $content['lists'];  //user specific common list
$counter = 1;
$output = '';
$paginates = 1;
$pages = '';


//if filter bar add + template
//if pagination --get array number, calculate No of pages (array/limit) , create controls, OPEN tag
/*if($show == 'select'){$newray = array(); var_dump($request); 
		
		foreach ($request as $key => $pick) { //get specific ones from database
		   
		   foreach ($offering[$pick] as $v) {	$newray = array_merge_recursive($newray, $v);	}
		   $output .= disher('', $template[$style], $newray);  //add filter attributes on the atricle id, data-eg
		   if ($counter >= $limit){  break; }
		   $counter++;
			}
	}*/
if($show == 'specific'){
			$newray = array();
			//var_dump($request);
			//foreach ($offering[$request] as $k => $v) {	$newray = array_merge_recursive($newray, $v);	}
		    $output .= disher('', $template[$style], $offering[$request], $request);  //add filter attributes on the atricle id, data-eg
		 
	}
else {  //var_dump($show);
		if(is_numeric($show)){  if((int)$show < $offercount) { $offercount = (int)$show; } }
		if ($limit < $offercount) { $output .= '<div class="xslide">';  }
		 $output .= '<div class="threes">';
		foreach ($offering as $k => $value) { //get all from database
		    $newray = array();		    
		   foreach ($value as $v) {	$newray = array_merge_recursive($newray, $v);	}
		    //var_dump($newray);
		    $output .=  disher('', $template[$style], $value, $k);
		    if ($counter === $offercount){ $output .= '</div>';  $pages .= '<li><button data-id="'.$id.'" data-goto="'.$paginates.'">'.$paginates.'</button></li>'; $paginates++;  break; }
		     if ($counter % $limit == 0){ $output .= '</div><div class="threes">'; $pages .= '<li><button data-id="'.$id.'" data-goto="'.$paginates.'">'.$paginates.'</button></li>'; $paginates++;  }
		    
		   $counter++;

		}
		$output .= '</div>';
		if ($limit < $offercount) { $output .= '<nav class="xslidenav">
      <button class="prev" data-id="'.$id.'" data-goto="2">&#10094; Prev</button>
      <ul>'.$pages.'</ul>
      <button class="next" data-id="'.$id.'" data-goto="2" >Next &#10095;</button>
   </nav>';

		 }
	}


//if pagination CLOSE tag
return $output;
}


function disher($output, $templat, $data, $k){
foreach ($templat as $key => $value) {
	if(!isset($value['tag'])){ 
		$templat[$key] = $_SESSION['templates'][$value['template']][$value['style']];
		$value = $templat[$key];
	}
	$tag = $value['tag']; 
	if(isset($value['from'])){ $from = $value['from']; }
	else {$from = ''; }
	$select = $value['content'];

	$allcontent = content('', $data, $select, $from, $tag, $k); 	//add template to limit data and so forth
	$content = $allcontent['content'];
	$attributes= $allcontent['attributes']; 
	//var_dump($attributes);
	//if(isset($value['attributes']['data-id'])){ $value['attributes']['data-id'] = $k; }
	if(isset($value['filter'])){
		foreach ($value['filter'] as $dataname => $take) {
			$attvalue = '';
			switch (TRUE) {
				case $take['content'] == 'id': $attvalue = $k;	break;
				case isset($data[$take['from']][$take['content']]) : 
				if(!is_array($data[$take['from']][$take['content']])){ $attvalue = $data[$take['from']][$take['content']]; }
				else { 
					foreach ($data[$take['from']][$take['content']] as $k3) {
						$val[] = $_SESSION['list'][$take['content']][$k3]['name'];
					}
					$attvalue = implode('___', $val);
				}
					//$attvalue = implode('___', $data[$take['from']][$take['content']]);  }
				 break;
				default:  $attvalue =  '';  break;
			}
			//var_dump($data[$take['from']]);
			 $value['attributes']['data-'.$dataname] =   $attvalue;
		}
	}

	if(isset($value['attributes'])){   $attributes = array_merge_recursive($attributes, $value['attributes']);  }
	if(isset($value['openid'])){ $attributes['href'] = $value['openid'].'.php?pick='.$k.'';  }
	if(isset($attributes[0])) { $attributes = ''; }
	else { $attributes =  attributer($attributes);  }  
	if(isset($value['prefix']) AND !empty($content)){ $output .=  '<'.$value['prefix'][1].'>'.$value['prefix'][0].'</'.$value['prefix'][1].'>';  }
	if(!empty($attributes) || !empty($content)){  $output .= tagged('', $tag, $attributes, $content); 	  }    
	}
return $output;
}

function tagged($output, $tag, $attributes, $content){
	switch(  $_SESSION['system']['tags'][$tag]['type']){
		case 'wrap':  return '<'.$tag.$attributes.'>'.$content.'</'.$tag.'>';    break;
		case 'self':  return '<'.$tag.$attributes.'/>';    break;
		case 'empty': return '<'.$tag.$attributes.'>';    break;
	}
}


//prep the content first 
function content($content, $data, $select, $from, $tag, $k){



	//move 
	$attributes = array();
	if($tag == 'figure'){ 	
							if(isset($data[$from][$select])){ if(!is_array($data[$from][$select])){ $attributes = array("class"=> $data[$from][$select] ); $select = ''; } }
							//else {  } place  content for multiple images with class (tag div)
						}

	if(!is_array($select)){ 	
										
		if(isset($data[$from][$select])){
				if(!is_array($data[$from][$select])){ $content .= $data[$from][$select]; }  //string data comes here
				else {  //if(isset($data['prefix'])){  $content .= 'prefix';  }
						if(isset($_SESSION['list'][$select])) {  foreach ($data[$from][$select] as $k2 => $val) {
										if(isset($_SESSION['list'][$select][$k2]['image'])){ $content .= '<img src="gallery/'.$_SESSION['list'][$select][$val]['image'].'" >'; }
										else {	$content .= '<span>'.$_SESSION['list'][$select][$val]['name'].'</span>';				 }
										}
									}
						else {  foreach ($data[$from][$select] as $k2 => $val) { $content .= '<span>'.$val.'</span>';  } }
				     }
				}
		else{ if(empty($from)){$content .= $select; }  /*$select.'uio';*/ }
	}

	else{ $content .=disher('', $select, $data, $k); }

	$output = array('content' =>$content , 'attributes'=> $attributes);
	return $output;
}


function attributer($attributes){
	//check list of attributes and incorporate into settings/imports
	$output = '';
	foreach ($attributes as $attribute => $value) {
	 	if(is_array($value)){ //breaks down any array into a string
	 		switch ($attribute) {
	 			case 'class':  	$output .= 'class="'.implode(' ', $value).'" '; break;
	 			case 'onclick': $output .= $value.'()'; break;
	 			case 'data':  	foreach ($value as $name=>$val) { $output .= 'data-'.$key.'= "'.$val.'" '; } break;	 			
	 			default: 		$output .= $attribute.' '; break;
	 		}	 		 
	 	}
	 	else{ $output .= ' '.$attribute.'="'.$value.'" ';	} 	
	}
	return $output;
}





?>