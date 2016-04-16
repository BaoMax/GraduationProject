<?php
if( !defined('IN') )die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class registerController extends appController{
	function __construct(){
		parent::__construct();
	}

	function register(){
		$data = array();
		$data['title'] = $data['top_title'] = '注册';
		$data['js'] = array('login.js');
		render( $data );
	}

	function register_test(){
		$username = $_POST["UserName"];
		$password = $_POST["Password"];
		$sql = "select * from student where student_name='".$username."'";
        // echo get_data($sql);
                        
		if(get_data($sql)){
			echo false;
		}else{
			$sql = "insert into student (student_name,password) values
                         ('".$username."', sha1('".$password."'))";
			run_sql($sql);            
			echo true;
		}
	}
	
}
?>