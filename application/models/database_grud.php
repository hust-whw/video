<?php
class Database_grud extends CI_Model {
        
        function __construct() {
                parent::__construct();

                $this->load->database();

                $this->load->model('function_');
        }

        function regist($nickname, $constellation, $sex,$account,$password,$avatar_url,$encrypted_password,$server_time) 
        {
        	$data = array(
                        'nickname' 			=> $nickname,
                        'constellation' 	=> $constellation,
                        'sex' 				=> $sex,
                        'account' 			=> $account,
                        'password'		 	=> $password,
                        'avatar_url' 		=> $avatar_url,
                        'encrypted_password'=> $encrypted_password,
                        'time'				=> $server_time
                     );

			@$this->db->insert('user',$data);

            if($this->db->affected_rows() == 1)
            	return 0;
            else
            	return 2;
        }


        function login($user_id,$account,$encrypted_password,$client_str,$server_time)
        {
        	$server_str = $this->function_->get_str(6);

        	$finger = md5($account.$encrypted_password.$client_str.$server_str);

        	$session_str = $user_id.$finger;

        	@$this->db->query("UPDATE user SET session_str = '$session_str' , last_time = '$server_time' WHERE id = '$user_id'");

        	if($this->db->affected_rows() != 0)
            	return $session_str;
            else
            	return 2;
        }
        //获取指定id的指定信息
        function user($to_id,$json_array)
        {
            
            $query = @$this->db->query("SELECT * From user WHERE id = '$to_id'");

            $result = $query->row_array();

            $result_array = array();

            foreach ($json_array as $key => $value) 
            {
                $result_array[$value] = $result[$value];
            }

            return $result_array;
        }
}
?>