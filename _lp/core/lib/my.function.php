<?php
function hasCollectionProblem($student_id,$problem_id,$problem_type,$type){
	$sql = "select * from userproblems where student_id = '".$student_id."' and problem_id = '".$problem_id."' and problem_type = '".$problem_type."' and type = '".$type."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return true;
	}
	return false;
}
function getCompletion($num,$course,$chapter = null){

	if($chapter != null){
		$sql = "select * from completion where course_id='".$course."' and chapter_id = '".$chapter."'";
	}else{
		$sql = "select * from completion where course_id='".$course."'";
	}
	$result = get_data($sql);
		
	if( isset( $result ) && is_array( $result )){
		$data = array();
		if(count($result) < $num){
			$data = $result;
		}else{
			$temp = array_rand($result,$num);
			$i = 0;
			foreach ( $temp as $key ) {
					# code...
				$data[$i++] = $result[$key];
			}
		}
		foreach ($data as $key => $value) {
			# code...
			if(hasCollectionProblem($_COOKIE['UserName'],$value['completion_id'],0,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
		}
		return $data;
	}else{
		return false;
	}
}
function getOption($num,$course,$chapter = null){

	if($chapter){
		$sql = "select * from options where course_id='".$course."' and chapter_id = '".$chapter."'";
	}else{
		$sql = "select * from options where course_id='".$course."'";
	}
	$result = get_data($sql);
		
	if( isset( $result ) && is_array( $result )){
		$data = array();
		if(count($result) < $num){
			$data = $result;
		}else{
			$temp = array_rand($result,$num);
			$i = 0;
			foreach ( $temp as $key ) {
				# code...
				$data[$i++] = $result[$key];
			}
		}
		foreach ($data as $key => $value) {
			# code...
			if(hasCollectionProblem($_COOKIE['UserName'],$value['completion_id'],1,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
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
function getRecommend(){
	$sql = "select * from course where recommend = '1'";
	return get_data($sql);
}
function getCourseType(){
	$sql = "select type from course group by type";
	$result = get_data($sql);
	$i = 0;
	if(isset($result) && is_array($result)){
		foreach ($result as $value) {
			# code...
			$result[$i++] = $value['type'];
		}
	}
	return $result;
}
function getCourseById($course_id){
	$sql = "select * from course where course_id = '".$course_id."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
function getPreview($course_id,$chapter_index){
	$sql = "select * from article where course_id='".$course_id."'and chapter_index = '".$chapter_index."'";
	$result = get_data($sql);	
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
function insertStudentCourse($student_id,$course_id){
	$sql = "select * from studentcourse where course_id = '".$course_id."' and student_id = '".$student_id."'";
	$result = get_data($sql);
	if(isset($result)&&is_array($result)){
		return false;
	}
	$sql = "insert into studentcourse (course_id,student_id) values('".$course_id."','".$student_id."')";
	return run_sql($sql);
}
function hasCourseByStudentId($student_id,$course_id){
	$sql = "select * from studentcourse where student_id = '".$student_id."' and course_id = '".$course_id."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return true;
	}
	return false;	
}
function getChapterByCourseId($course_id){
	$sql = "select * from chapters where course_id = '".$course_id."'";
	return get_data($sql);
}
function getCourse(){
	$sql = "select * from course";
	return get_data($sql);
}
function getCourseByType($type){
	$sql = "select * from course where type='".$type."'";
	return get_data($sql);
}
function getChapterByChapterIndex($course_id,$chapter_index){
	$sql = "select * from chapters where course_id = '".$course_id."' and chapter_index = '".$chapter_index."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
function getRandCourseByType($type,$num){
	$result = getCourseByType($type);
	if(isset($result) && is_array($result)){
		if(count($result) > $num){
			$temp = array_rand($result,$num);
			$i = 0;
			$data = array();
			foreach ( $temp as $key ) {
					# code...
				$data[$i++] = $result[$key];
			}
			return $data;
		}
	}
	return $result;
}
function getChapterIndexByStudentId($course_id,$student_id){
	$sql = "select * from record where course_id = '".$course_id."' and student_id = '".$student_id."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		$temp = array_column($result,"chapter_id");
		arsort($temp,SORT_NUMERIC);
		return $temp[0] + 1;
	}
	return 1;
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