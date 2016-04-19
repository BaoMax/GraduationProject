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
	function selectCourse(){
		$data = array();
		$data["title"] = $data["top_title"] = "选择课程";
		$result = getCourse();
		$data["course"] = $result;
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

	function preview(){
		$data["title"] = $data["top_title"] = "课前预习";
		$course = $_GET["course_id"];
		$chapter = $_GET["chapter_index"];
		$student = $_COOKIE['UserName'];
		$flag = $_GET['flag'];
		$data['chapter'] = getChapterByChapterIndex($course,$chapter);
		$data['preview'] = getPreview($course,$chapter);
		$data['css'] = array('play.css');
		if ($flag == "true") {
			# code...
			if(!insertStudentCourse($student,$course)){
				return false;
			}
		}
		render( $data );
	}

	function practice(){
		$data["title"] = $data["top_title"] = "课堂练习";
		$course = $_GET["course_id"];
		$chapter = $_GET["chapter_id"];

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


	function test(){
		$data["title"] = $data["top_title"] = "课后测试";
		$course = $_GET["course_id"];
		$chapter = $_GET["chapter_id"];

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
		$student_id = $_POST["student_id"];

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
		$data["user"] = $_GET["student_id"];
		$data["course"] = $_GET["course_name"];
		if($data["course"] == undefined){
			$data["course"] = "未选择课程";
		}
		render( $data );
	}	
}
?>