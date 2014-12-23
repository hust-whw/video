<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_get extends CI_Controller {
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
            //获取评论操作对象ID
		$comment_id = $this->input->get('comment_id');
            //客户端输入时间戳
		$timestamp = $this->input->get('t');
            //流水号
		$i = $this->input->get('i');  
            //服务端目前时间戳
		$server_time = $this->function_->get_timestamp();

		$result_verify = $this->verification->verify_session($session_str); 

		if($result_verify === 0)
		{
			$data_array = $this->database_grud->comment_get($comment_id);
			
			if($data_array == NULL)
				$e = 2;
			else 
				$e = 0;
		}
		else
		{
			$e = 2;

			$data_array = null;
		}

		$data_array['e'] = $e;

		$data_array['i'] = $i;

		$json = json_encode($data_array,JSON_UNESCAPED_UNICODE);
		echo $json;

	}  
}


