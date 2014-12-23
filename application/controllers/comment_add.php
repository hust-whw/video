<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_add extends CI_Controller {
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
		$session_str = $this->input->post('x');
        //获取视频id
		$video_id = $this->input->post('video_id');
        //获取评论内容
		$text = $this->input->post('text');
        //时间戳
		$timestamp = $this->input->post('t');
        //流水号
		$i = $this->input->post('i');  
        //服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 

		if($result_verify === 0)
		{
			$self_id = $this->function_->get_self_id($session_str);

			$e =$this->database_grud->comment_add($self_id,$video_id,$text,$server_time);
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


