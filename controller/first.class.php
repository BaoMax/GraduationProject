<?php
if( !defined('IN') )die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class firstController extends appController{
	function __construct(){
		parent::__construct();
	}

	function index(){
		$data['title'] = $data['top_title'] = 'Welcome';
		render( $data );
	}

	// // function login(){
	// // 	$data = array();
	// // 	$data['title'] = $data['top_title'] = '登录';
	// // 	$data['js'] = array('login.js');
	// // 	render( $data );
	// // }

	// // function login_test(){
	// // 	$username = $_POST["UserName"];
	// // 	$password = $_POST["Password"];
	// // 	$sql = "select * from student
 // //                         where student_name='".$username."'
 // //                         and password = sha1('".$password."')";
	// // 	if(!get_data($sql)){
	// // 		echo false;
	// // 	}else{
	// // 		echo true;
	// // 	}
	// // }

	// function register(){
	// 	$data = array();
	// 	$data['title'] = $data['top_title'] = '注册';
	// 	$data['js'] = array('login.js');
	// 	render( $data );
	// }

	// // function 
	
}
?>