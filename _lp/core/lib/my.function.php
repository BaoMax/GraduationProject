<?php
/**是否是收藏的或者错误的题目**/
function hasProblem($student_id,$problem_id,$problem_type,$type){
	$sql = "select * from userproblems where student_id = '".$student_id."' and problem_id = '".$problem_id."' and problem_type = '".$problem_type."' and type = '".$type."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return true;
	}
	return false;
}
/**删除收藏或错题**/
function cancleUserProblem($student_id,$problem_id,$problem_type,$type){
	$sql = "delete from userproblems where student_id = '".$student_id."' and problem_id = '".$problem_id."' and problem_type = '".$problem_type."' and type = '".$type."'";
	return run_sql($sql);
}
/**添加收藏或错题**/
function addUserProblem($student_id,$problem_id,$problem_type,$type){
	$sql = "insert into userproblems (student_id,problem_id,problem_type,type) values ('".$student_id."','".$problem_id."','".$problem_type."','".$type."')";
	return run_sql($sql);
}
/**获取填空题**/
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
			if(hasProblem($_COOKIE['UserName'],$value['completion_id'],0,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
			if(hasProblem($_COOKIE['UserName'],$value['completion_id'],0,1))
				$data[$key]["error"] = "true";
			else
				$data[$key]["error"] = "false";
		}
		return $data;
	}else{
		return false;
	}
}
/**获取选择题**/
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
			if(hasProblem($_COOKIE['UserName'],$value['option_id'],1,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
			if(hasProblem($_COOKIE['UserName'],$value['option_id'],1,1))
				$data[$key]["error"] = "true";
			else
				$data[$key]["error"] = "false";
		}
		return $data;
	}else{
		return false;
	}
}
/**计算成绩**/
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
/**获取热门推荐**/
function getRecommend(){
	$sql = "select * from course where recommend = '1'";
	return get_data($sql);
}
/**获取课程的类型**/
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
/**通过课程id获取课程**/
function getCourseById($course_id){
	$sql = "select * from course where course_id = '".$course_id."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
/**获取预习的内容**/
function getPreview($course_id,$chapter_index){
	$sql = "select * from article where course_id='".$course_id."'and chapter_index = '".$chapter_index."'";
	$result = get_data($sql);	
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
/**插入学生的选课**/
function insertStudentCourse($student_id,$course_id){
	$sql = "select * from studentcourse where course_id = '".$course_id."' and student_id = '".$student_id."'";
	$result = get_data($sql);
	if(isset($result)&&is_array($result)){
		return false;
	}
	$sql = "insert into studentcourse (course_id,student_id) values('".$course_id."','".$student_id."')";
	return run_sql($sql);
}
/**删除学生的选课**/
function deleteStudentCourse($student_id,$course_id){
	$sql = "delete from studentcourse where course_id = '".$course_id."' and student_id = '".$student_id."'";
	return run_sql($sql);
}
/**学生是否选择的此课程**/
function hasCourseByStudentId($student_id,$course_id){
	$sql = "select * from studentcourse where student_id = '".$student_id."' and course_id = '".$course_id."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return true;
	}
	return false;	
}
/**通过课程id获取课程章节**/
function getChapterByCourseId($course_id){
	$sql = "select * from chapters where course_id = '".$course_id."'";
	return get_data($sql);
}
/**获取所有课程**/
function getCourse(){
	$sql = "select * from course";
	return get_data($sql);
}
/**通过类型获取课程**/
function getCourseByType($type){
	$sql = "select * from course where type='".$type."'";
	return get_data($sql);
}
/**获取某一课程的某一章节**/
function getChapterByChapterIndex($course_id,$chapter_index){
	$sql = "select * from chapters where course_id = '".$course_id."' and chapter_index = '".$chapter_index."'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		return $result[0];
	}
	return $result;
}
/**获取随机课程**/
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
/**获取学生学习到第几章节**/
function getChapterIndexByStudentId($course_id,$student_id){
	$sql = "select chapter_id from record where course_id = '".$course_id."' and student_id = '".$student_id."' and type = '1'";
	$result = get_data($sql);
	if(isset($result) && is_array($result)){
		$temp = $result;
		arsort($temp,SORT_NUMERIC);
		$sql = "select chapter_max from course where course_id = '".$course_id."'";
		$result = get_data($sql);
		if(isset($result) && is_array($result)){
			if($temp[0] == $result[0] && count($temp) == $result[0]){
				return 0;
			}
		}
		if($temp[0] == count($temp)){
			return $temp[0]+1;
		}
		for($i=1;$i<=count($temp);$i++){
			if($temp[$i] != $i){
				return $i;
			}
		}
	}
	return 1;
}
/**获取学生学习的所有课程**/
function getStudentCourse($student_id){
	$sql = "select * from studentcourse,course where student_id = '".$student_id."' and studentcourse.course_id = course.course_id";
	return get_data($sql);
}
function getOtherCourse($student_id){
	$sql = "select course.course_id,course.course_name from course left join studentcourse on course.course_id=studentcourse.course_id where studentcourse.course_id is null";
	return get_data($sql);
}
function getProblems($student_id,$type){
	$sql = "select course.course_id,course.course_name,count(completion.completion_id) as num from (select * from userproblems where student_id = '".$student_id."' and type = '".$type."' and problem_type = '0') as A,completion,course where A.problem_id = completion.completion_id and completion.course_id = course.course_id group by course_id";
	$data["completion"] = get_data($sql);
	$sql = "select course.course_id,course.course_name,count(options.option_id) as num from (select * from userproblems where student_id = '".$student_id."' and type = '".$type."' and problem_type = '1') as A,options,course where A.problem_id = options.option_id and options.course_id = course.course_id group by course_id";
	$data["options"] = get_data($sql);
	return $data; 
}
function getProblemsCompletion($student_id,$course_id,$type){
	$sql = "select completion.* from (select * from userproblems where student_id = '".$student_id."' and type = '".$type."' and problem_type = '0') as A,completion where A.problem_id = completion.completion_id";
	$data = get_data($sql);
	if( isset( $data ) && is_array( $data )){
		foreach ($data as $key => $value) {
			# code...
			if(hasProblem($_COOKIE['UserName'],$value['completion_id'],0,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
			if(hasProblem($_COOKIE['UserName'],$value['completion_id'],0,1))
				$data[$key]["error"] = "true";
			else
				$data[$key]["error"] = "false";
		}
	}
	return $data;
}
function getProblemsOptions($student_id,$course_id,$type){
	$sql = "select options.* from (select * from userproblems where student_id = '".$student_id."' and type = '".$type."' and problem_type = '1') as A,options where A.problem_id = options.option_id";
	$data = get_data($sql);
	if( isset( $data ) && is_array( $data )){
		foreach ($data as $key => $value) {
			# code...
			if(hasProblem($_COOKIE['UserName'],$value['option_id'],0,0))
				$data[$key]["collection"] = "true";
			else
				$data[$key]["collection"] = "false";
			if(hasProblem($_COOKIE['UserName'],$value['option_id'],0,1))
				$data[$key]["error"] = "true";
			else
				$data[$key]["error"] = "false";
		}
	}
	return $data;
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