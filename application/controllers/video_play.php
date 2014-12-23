<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_play extends CI_Controller {
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
            //获取操作视屏ID
		$video_id = $this->input->get('video_id');
            //获取操作类型
		$do_what = $this->input->get('flag'); 
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

			$play_times = $this->database_grud->video_play($self_id,$video_id,$do_what,$server_time);

			if($play_times === false)
			{
				$e = 2;
				$play_times = NULL;
			}
			else
			{
				$e = 0;	
			}
		}
		else
		{
			$e = 2;
		}
		
		$result = array(
			'i' => $i, 
			'e' => $e,
			'times' => $play_times
			);

		$json = json_encode($result);
		echo $json;
				
	}  
}


