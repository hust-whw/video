<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_del extends CI_Controller {
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
            //获取操作对象ID
		$video_id = $this->input->get('video_id');
            //客户端输入时间戳
		$timestamp = $this->input->get('t');                                           
            //流水号
		$i = $this->input->get('i');  
            //服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 

		if($result_verify === 0)
		{
			$self_id = $this->function_->get_self_id($session_str);

			$is_success = $this->database_grud->video_del($video_id,$self_id,$server_time);

			if($is_success === 0)
				$e = 0;
			else
				$e = 2;
		}
		else
		{
			$e = 2;
		}

		$result = array(
			'i' => $i, 
			'e' => $e,
			);

		$json = json_encode($result);
		echo $json;

	}  
}


