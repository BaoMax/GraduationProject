<?php
if( !defined('IN') )die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );
include_once( CROOT . 'lib' . DS . 'my.function.php' );

class loginController extends appController{
	function __construct(){
		parent::__construct();
	}

	function login(){
		$data = array();
		$data['title'] = $data['top_title'] = '登录';
		$data['js'] = array('login.js');
		if($_COOKIE["remberBox"] == "true"){
			//  = decrypt("login",$_COOKIE["Password"]);
			$data["Password"] = $_COOKIE["Password"];
		}
		$data["UserName"] = $_COOKIE["UserName"];
		render( $data );
	}

	function login_test(){
		$username = $_POST["UserName"];
		$password = $_POST["Password"];
		$rember = $_GET["rember"];
		// $test_password = sha1($password);
		$sql = "select * from student
                         where student_name='".$username."' and password= sha1('".$password."')";               
		if(!get_data($sql)){
			echo false;
		}else{
			if($rember == "true"){
				// $password = encrypt("login",$password);
				setcookie("Password",$password,time()+3600*24*3);
			}else{
				if($_COOKIE["Password"]){
					setcookie("Password",$password,time()-3600);
				}
			}
			setcookie("UserName",$username,time()+3600*24*3);
			setcookie("remberBox",$rember,time()+3600*24*3);
			echo true;
		}
	}
	
}
?>