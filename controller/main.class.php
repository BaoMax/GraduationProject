<?php
if( !defined('IN') )die('bad request');

include_once( AROOT . 'controller'.DS.'app.class.php' );
include_once( CROOT . 'lib' . DS . 'my.function.php' );

class mainController extends appController{
	function __construct(){
		parent::__construct();
	}
	function mainPage(){
		$data["title"] = $data["top_title"] = "首页";
		$data["recommend"] = getRecommend();
		$data["type"] = getCourseType();
		for($i = 0;$i<count($data["type"]);$i++){
			$type = $data["type"][$i];
			$data["options"][$type] = getRandCourseByType($type,3);
		}
		render( $data );
	}
	function course(){
		$data["title"] = $data["top_title"] = "课程列表";
		$type = $_GET["type"];
		$data["course"] = getCourseByType($type);
		render( $data );
	}
	function catalog(){
		$data = array();
		$data["title"] = $data["top_title"] = "课程介绍";
		$courseId = $_GET["course_id"];
		$data['course'] = getCourseById($courseId);
		$data['chapter'] = getChapterByCourseId($courseId);
		$data['flag'] = hasCourseByStudentId($_COOKIE['UserName'],$courseId);
		render( $data );
	}
	function checkCourse(){
		$course = $_POST["course_id"];
		$student = $_COOKIE['UserName'];
		echo insertStudentCourse($student,$course);
	}
	function delCourse(){
		$course = $_POST["course_id"];
		$student = $_COOKIE["UserName"];
		echo deleteStudentCourse($student,$course);
	}
	function cont(){
		$course_id = $_POST["course_id"];
		$student_id = $_COOKIE["UserName"];
		$data = array();
		$data["chapter_index"] = getChapterIndexByStudentId($course_id,$student_id);
		echo json_encode($data);
	}
	function preview(){
		$data["title"] = $data["top_title"] = "课前预习";
		$course = $_GET["course_id"];
		$chapter = $_GET["chapter_index"];
		$student = $_COOKIE['UserName'];
		$data['chapter'] = getChapterByChapterIndex($course,$chapter);
		$data['preview'] = getPreview($course,$chapter);
		$data['flag'] = hasCourseByStudentId($student,$course);
		render( $data );
	}

	function practice(){
		$data["title"] = $data["top_title"] = "课堂练习";
		$course = $_GET["course_id"];
		$chapter = $_GET["chapter_index"];
		$temp = getCompletion(5,$course,$chapter);
		if( isset( $temp ) && is_array( $temp )){
			$data["completion"] = array();
			$data["completion"] = $temp;
		}
		$temp = getOption(5,$course,$chapter);
		if( isset( $temp ) && is_array( $temp )){
			$data["options"] = array();
			$data["options"] = $temp;
		}
		render( $data );
	}
	function collection(){
		$problem_id = $_POST["problem_id"];
		$problem_type = $_POST["problem_type"];
		$student_id = $_COOKIE["UserName"];
		$flag = $_POST["flag"];
		if($flag == "true"){
			echo cancleUserProblem($student_id,$problem_id,$problem_type,0);
		}else{
			echo addUserProblem($student_id,$problem_id,$problem_type,0);
		}
	}
	function insertError(){
		$problem_id = $_POST["problem_id"];
		$problem_type = $_POST["problem_type"];
		$student_id = $_COOKIE["UserName"];
		$flag = $_POST["flag"];
		if($flag == "true"){
			echo addUserProblem($student_id,$problem_id,$problem_type,1);
		}else{
			echo cancleUserProblem($student_id,$problem_id,$problem_type,1);
		}
	}
	function getMaxChapter(){
		$course_id = $_POST["course_id"];
		$result = getCourseById($course_id);
		echo $result["chapter_max"];
	}

	function courseManage(){
		$data["title"] = $data["top_title"] = "课程管理";
		$data["myCourse"] = getStudentCourse($_COOKIE["UserName"]);
		$data["course"] = getOtherCourse($_COOKIE["UserName"]);
		render( $data );
	}
	function errorProblems(){
		$data["title"] = $data["top_title"] = "我的错题";
		$result = getProblems($_COOKIE["UserName"],"1");
		$temp = array();
		if(isset($result["completion"]) && is_array($result["completion"])){
			foreach ($result["completion"] as $key => $value) {
				# code...
				$temp[$value["course_id"]]["course_id"] = $value["course_id"];
				$temp[$value["course_id"]]["course_name"] = $value["course_name"];
				$temp[$value["course_id"]]["completion"] = $value["num"];
				$temp[$value["course_id"]]["sum"] = $value["num"];
			}
		}
		if(isset($result["options"]) && is_array($result["options"])){
			foreach ($result["options"] as $key => $value) {
				# code...
				$temp[$value["course_id"]]["options"] = $value["num"];
				$temp[$value["course_id"]]["course_id"] = $value["course_id"];
				$temp[$value["course_id"]]["course_name"] = $value["course_name"];
				$temp[$value["course_id"]]["sum"] = $temp[$value["course_id"]]["sum"] + $value["num"];
			}
		}
		$data["problem"] = $temp;
		render( $data );
	}
	function collectionProblems(){
		$data["title"] = $data["top_title"] = "我的收藏";
		$result = getProblems($_COOKIE["UserName"],"0");
		$temp = array();
		if(isset($result["completion"]) && is_array($result["completion"])){
			foreach ($result["completion"] as $key => $value) {
				# code...
				$temp[$value["course_id"]]["course_id"] = $value["course_id"];
				$temp[$value["course_id"]]["course_name"] = $value["course_name"];
				$temp[$value["course_id"]]["completion"] = $value["num"];
				$temp[$value["course_id"]]["sum"] = $value["num"];
			}
		}
		if(isset($result["options"]) && is_array($result["options"])){
			foreach ($result["options"] as $key => $value) {
				# code...
				$temp[$value["course_id"]]["options"] = $value["num"];
				$temp[$value["course_id"]]["course_id"] = $value["course_id"];
				$temp[$value["course_id"]]["course_name"] = $value["course_name"];
				$temp[$value["course_id"]]["sum"] = $temp[$value["course_id"]]["sum"] + $value["num"];
			}
		}
		$data["problem"] = $temp;
		render( $data );
	}
	function errorPractice(){
		$data["title"] = $data["top_title"] = "错题练习";
		$course_id = $_GET["course_id"];
		$type = $_GET["type"];
		if($type == "0"){
			$data["completion"] = getProblemsCompletion($_COOKIE["UserName"],$course_id,"1");
		}else{
			$data["options"] = getProblemsOptions($_COOKIE["UserName"],$course_id,"1");
		}
		render( $data );
	}
	function collectionPractice(){
		$data["title"] = $data["top_title"] = "收藏题练习";
		$course_id = $_GET["course_id"];
		$type = $_GET["type"];
		if($type == "0"){
			$data["completion"] = getProblemsCompletion($_COOKIE["UserName"],$course_id,"0");
		}else{
			$data["options"] = getProblemsOptions($_COOKIE["UserName"],$course_id,"0");
		}
		render( $data );
	}
	function changePassword(){
		$data['title'] = $data['top_title'] = '修改密码';
		$data['js'] = array('login.js');
		render( $data );
	}
	function change_test(){
		$username = $_POST["UserName"];
		$oldpassword = $_POST["oldPassword"];
		$newpassword = $_POST["newPassword"];
		$sql = "select * from student where student_name='".$username."' and password= sha1('".$oldpassword."')";
             
		if(!get_data($sql)){
			echo false;
		}else{
			$sql = "update student set password = sha1('".$newpassword."') where student_name = '".$username."'";
			run_sql($sql); 
			setcookie("Password",$password,time()-3600);
			echo true;
		}
	}
	function logout(){
		setcookie("Password",$password,time()-3600);
		setcookie("UserName",$password,time()-3600);
		setcookie("remberBox",$password,time()-3600);
		echo true;
	}
	function exam(){
		$data["title"] = $data["top_title"] = "期末考试";
		$course = $_GET["course_id"];
		$data["user"] = $_GET["student_id"];
		$data["course"] = $_GET["course_name"];

		$temp = getCompletion(10,$course);
		if( isset( $temp ) && is_array( $temp )){
			$data["completion"] = array();
			$data["completion"] = $temp;
		}

		$temp = getOption(10,$course,$chapter);
		if( isset( $temp ) && is_array( $temp )){
			$data["options"] = array();
			$data["options"] = $temp;
		}
		render( $data );
	}

	function record(){
		$course_id = $_POST["course_id"];
		$type = $_POST["type"];
		$chapter_id = $_POST["chapter_id"];
		$student_id = $_COOKIE["UserName"];

		if($type == "preview"){
			$type = 0;
		}else if($type == "practice"){
			$type = 1;
		}else if ($type == "test") {
			$type = 2;
		}
		$sql = "select * from record where course_id='".$course_id."' and chapter_id = '".$chapter_id ."' and type = '" . $type ."' and student_id = '" . $student_id ."'";
		$result = get_data($sql);
		if(get_data($sql)){
			if ($type != 0 && $type != 1) {
				$score = $_POST['score'];
				$recordScore = $result[0]["score"];
				if($score > $recordScore){
					$sql = "update record set score = '" . $score ."' where course_id='" . $course_id . "' and chapter_id = '" . $chapter_id ."' and type = '" . $type ."' and student_id = '" . $student_id ."'";
				}
			}
		}else{
			if($type == 0 || $type == 1){
				$sql = "insert into record (course_id,chapter_id,score,type,student_id) values('".$course_id."','".$chapter_id."',100,'".$type."','".$student_id."')";
			}else{
				$score = $_POST['score'];
				$sql = "insert into record (course_id,chapter_id,score,type,student_id) values('".$course_id."','".$chapter_id."','". $score ."','".$type."','".$student_id."')";
			}
		}
		run_sql($sql);
		echo  true;
	}

	function score(){
		
		$course_id = $_POST["course_id"];
		$student_id = $_POST["student_id"];
		$score = $_POST['score'];

		$sql = "select * from course where course_id='".$course_id."'";
		$result = get_line($sql);
		$chapters = $result["chapter_max"];
		$course_name = $result["course_name"];

		$preview_score = getScore($course_id,$student_id,0,$chapters);
		$practice_score = getScore($course_id,$student_id,1,$chapters);
		$test_score = getScore($course_id,$student_id,2,$chapters);
		
		$common_score = $preview_score + $practice_score + $test_score;
		$result_score = $common_score*0.3 + $score*0.7;
		$data["common_score"] = number_format($common_score,2);
		$data["fail_score"] = number_format($score,2);
		$data["score"] = number_format($result_score,2);
		echo json_encode($data);
	}

	function userCenter(){
		$data["title"] = $data["top_title"] = "个人中心";
		render( $data );
	}	
}
?>