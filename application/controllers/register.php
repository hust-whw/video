<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Register extends CI_Controller {
	// 构造方法
	function __construct() 
	{
		parent::__construct();
		// 加载计算模型
		$this->load->model('verification');

		$this->load->model('function_');

		$this->load->model('database_grud');
	}
	
	// 默认方法
	function index() 
	{
		$nickname = $this->input->post('nickname'); 				    //昵称
		
		$constellation = $this->input->post('constellation');        //星座
		
		$sex = $this->input->post('sex');							//性别	
		
		$account = $this->input->post('account');					//账号
		
		$password = $this->input->post('password');					//密码

		$encrypted_password = md5($password);						//md5加密密码
		
		$avatar_url = $this->input->post('avatar_url');				//头像标识符
		
		$timestamp = $this->input->post('t');						//时间戳

		$i = $this->input->post('i');								//流水号

		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_regist($nickname, $constellation, $sex, $account, $password, $avatar_url);
		
		if($result_verify === 0)
		{
			$e =$this->database_grud->regist($nickname, $constellation, $sex, $account, $password, $avatar_url,$encrypted_password,$server_time);
		}
		else
		{
			$e = 2;
		}

		
		$result = array(
						'i' => $i, 
						'e' => $e
						);
		$json = json_encode($result);
		echo $json;
	}
}

