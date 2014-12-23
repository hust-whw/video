<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_list extends CI_Controller {
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
            //列表起点评论ID
		$start = $this->input->get('start'); 
            //获得的评论数 
		$limit = $this->input->get('limit');                                           
            //流水号
		$i = $this->input->get('i');  
            //客户端输入时间戳
		$timestamp = $this->input->get('t');  
            //服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 

		if($result_verify === 0)
		{
			$data_array = $this->database_grud->get_comment_list($video_id,$start,$limit);

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


