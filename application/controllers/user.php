<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
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
            //获取json字符串
            $json_str = $this->input->post('scope');
            //解密json字符串为数组
            $json_array = json_decode($json_str,true);

            //获取信息操作对象ID
            $to_id = $this->input->post('id'); 
            //客户端输入时间戳
            $timestamp = $this->input->post('t');                                           
            //流水号
            $i = $this->input->post('i');  
            //服务端目前时间戳
            $server_time = $this->function_->get_timestamp();

            $result_verify = $this->verification->verify_session($session_str); 

			if($result_verify === 0)
			{
				$data_array = $this->database_grud->user($to_id,$json_array);

				$e = 0;
			}
			else
			{
				$e = 2;
			}

			$data_array['e'] = $e;

			$data_array['i'] = $i;

			$json = json_encode($data_array);
			echo $json;

        }  
}


