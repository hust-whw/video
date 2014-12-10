<?php
class Database_grud extends CI_Model {
        
        function __construct() {
                parent::__construct();
                $this->load->database();
        }

        function regist($nickname, $constellation, $sex,$account,$password,$avatar_url,$encrypted_password,$sever_time) 
        {
        	$data = array(
                        'nickname' 			=> $nickname,
                        'constellation' 	=> $constellation,
                        'sex' 				=> $sex,
                        'account' 			=> $account,
                        'password'		 	=> $password,
                        'avatar_url' 		=> $avatar_url,
                        'encrypted_password'=> $encrypted_password,
                        'time'				=> $sever_time
                     );

			@$this->db->insert('user',$data);

            if($this->db->affected_rows() == 1)
            	return 0;
            else
            	return 2;
        }
}
?>