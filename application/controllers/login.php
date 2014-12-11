<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
        // 构造方法
        function __construct() 
        {
                parent::__construct();   

                $this->load->model('verification');

                $this->load->model('function_');

                $this->load->model('database_grud');
        }
        
        // 默认方法
        function index() 
        {
            //用户账号
            $account = $this->input->post('account');
            //加密密码32位md5
            $encrypted_password = $this->input->post('encrypted_password');
            //客户端6为随机字符串
            $client_str = $this->input->post('client_str'); 
            //客户端输入时间戳
            $timestamp = $this->input->post('t');                                           
            //流水号
            $i = $this->input->post('i');  
            //服务端目前时间戳
            $server_time = $this->function_->get_timestamp();

            $user_id = $this->verification->verify_login($account,$encrypted_password,$client_str); 

            if($user_id)
            {
                $session_str = $this->database_grud->login($user_id,$account,$encrypted_password,$client_str,$server_time);
                if($session_str === 2)
                {
                	$e = 2;
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
            if(!isset($session_str))
            	$session_str = "";
            $result = array(
                    'i' => $i, 
                    'e' => $e,
                    'x' => $session_str
                    );
            $json = json_encode($result);
            echo $json;
        }  
}


