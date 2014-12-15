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
        //记录关注或取关操作
        function focus($self_id,$user_id,$do_what,$server_time)
        {
            switch($do_what)
            {
                //关注操作
                case 0:            

                    $query = @$this->db->query("SELECT * From focus WHERE user2_id = '$self_id' AND user_id = '$user_id' AND is_del = 0");
                    //判定如果关注操作不存在或已取关，添加新的关注操作
                    if($query->num_rows() == 0)
                    {
                        @$this->db->query("INSERT INTO focus  (user_id,user2_id,time) VALUES ('$user_id','$self_id','$server_time') ");

                        if($this->db->affected_rows() == 1)
                            return 0;
                        else
                            return 2;
                    }
                    else
                        return 2;
                    break;

                //取关操作
                case 1:
                    $query = @$this->db->query("SELECT * From focus WHERE user2_id = '$self_id' AND user_id = '$user_id' AND is_del = 0");
                    //判定如果关注操作已存在并未取关，则添加取关操作
                    if($query->num_rows() == 1)
                    {
                        @$this->db->query("UPDATE focus SET is_del = 1,del_time = '$server_time' WHERE user2_id = '$self_id' AND user_id = '$user_id' AND is_del = 0");
                        
                        if($this->db->affected_rows() == 1)
                            return 0;
                        else
                            return 2;
                    }
                    else
                        return 2;
                    break;

                default:return 2;
                break;
            }
        }

        //获取指定区间的关注列表，返回ID列表
        function get_focus_list($self_id,$flag,$start,$limit)
        {
            $data_array = array();

            switch($flag)
            {
                case 0:
                    $query = @$this->db->query("SELECT user2_id From focus WHERE user_id = '$self_id' AND is_del = 0 LIMIT $start,$limit");
                    foreach ($query->result_array() as $row)
                    {
                       array_push($data_array, $row['user2_id']);
                    }
                    return $data_array;
                    break;
                case 1:
                    $query = @$this->db->query("SELECT user_id From focus WHERE user2_id = '$self_id' AND is_del = 0 LIMIT $start,$limit");
                    foreach ($query->result_array() as $row)
                    {
                       array_push($data_array, $row['user_id']);
                    }
                    return $data_array;
                    break;
                default:
                    return false;
            }

        }
}
?>