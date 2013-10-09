<?php
    $arr = array();
	$idArr = explode(',',$selected);
	var_dump($idArr);
	foreach($idArr as $index => $id){
		$id = strtr($id, array('["'=>'','"]'=>''));
		echo intval($id)."<br/>";
		$arr[$index] = intval($id);
	}
	print_r($arr);
?>