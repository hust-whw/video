<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_flow extends CI_Controller {
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
            //获取用户经度
		$east = $this->input->post('east');
		    //获取用户纬度
		$north = $this->input->post('north');
            //起始视频序号 默认为0
		$start = $this->input->post('start'); 
            //获得的视频数
		$limit = $this->input->post('limit');                                           
            //流水号
		$i = $this->input->post('i');  
            //客户端输入时间戳
		$timestamp = $this->input->post('t');  
            //服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 

		if($result_verify === 0)
		{
			$self_id = $this->function_->get_self_id($session_str);

			$data_array = $this->database_grud->video_flow($start,$limit);

			if($data_array == false)$e = 2;
			else $e = 0;
		}
		else
		{
			$e = 2;
			$data_array = null;
		}
		
		$data['ids'] = $data_array;

		$data['i'] = $i;

		$data['e'] = $e;

		$json = json_encode($data,JSON_UNESCAPED_UNICODE);

		echo $json;
	}  
}


