<?php
function getCompletion($num,$course,$chapter = null){

	if($chapter != null){
		$sql = "select * from completion where course_id='".$course."' and chapter_id = '".$chapter."'";
	}else{
		$sql = "select * from completion where course_id='".$course."'";
	}
	$result = get_data($sql);
		
	if( isset( $result ) && is_array( $result )){
		$data = array();
		$temp = array_rand($result,$num);
		$i = 0;
		foreach ( $temp as $key ) {
				# code...
			$data[$i++] = $result[$key];
		}
		return $data;
	}else{
		return false;
	}
}
function getOption($num,$course,$chapter){

	if($chapter){
		$sql = "select * from options where course_id='".$course."' and chapter_id = '".$chapter."'";
	}else{
		$sql = "select * from options where course_id='".$course."'";
	}
	$result = get_data($sql);
		
	if( isset( $result ) && is_array( $result )){
		$data = array();
		$temp = array_rand($result,$num);
		$i = 0;
		foreach ( $temp as $key ) {
			# code...
			$data[$i++] = $result[$key];
		}
		return $data;
	}else{
		return false;
	}
}
function getScore($course,$user,$type,$chapter){
	$sql = "select * from record where course_id='".$course."' and type = '" . $type ."' and student_id = '" . $user ."'";
	$result = get_data($sql);

	if($type == 2){
		$temp = array_column($result,"score");
		$sum = array_sum($temp);
		return $sum*0.8/$chapter;
	}else{
		return 100*count($result)*0.1/$chapter;
	}
}
function encrypt($key, $plain_text) {
    $plain_text = trim($plain_text);
    $iv = substr(md5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
    $c_t = mcrypt_cbc (MCRYPT_CAST_256, $key, $plain_text, MCRYPT_ENCRYPT, $iv);
    return trim(chop(base64_encode($c_t)));
}

function decrypt($key, $c_t) {
    $c_t =  trim(chop(base64_decode($c_t)));
    $iv = substr(md5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
    $p_t = mcrypt_cbc (MCRYPT_CAST_256, $key, $c_t, MCRYPT_DECRYPT, $iv);
    return trim(chop($p_t));
}