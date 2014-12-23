<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Video_add extends CI_Controller {
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
		//唯一会话字符串
		$session_str = $this->input->get('x');
		//获取视频标题
		$title = $this->input->get('title');
		//如果有，则获取三个标签
		$label1 = $this->input->get('label1');

		$label2 = $this->input->get('label2');

		$label3 = $this->input->get('label3');
		//视频经度
		$east = $this->input->get('east');
		//视频纬度
		$north = $this->input->get('north');
		//视频地址
		$video_url = $this->input->get('video_url');
		//时间戳
		$timestamp = $this->input->get('t');
		//流水号
		$i = $this->input->get('i');  
		//服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 
		
		if($result_verify === 0)
		{
			$self_id = $this->function_->get_self_id($session_str);

			$e =$this->database_grud->video_add($title,$label1,$label2,$label3,$east,$north,$video_url,$self_id,$server_time);
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

