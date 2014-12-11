<?php

class Verification extends CI_Model {
        
        function __construct() {
                parent::__construct();
                $this->load->database();
        }
        
        //简单验证了账号和密码合法性和所有变量的非空性，返回0认可，非0即失败。
        function verify_regist($nickname, $constellation, $sex,$account,$password,$avatar_url)
        {
            if($nickname&&$constellation&&$sex&&$account&&$password&&$avatar_url)
            {
                if(preg_match('/^[A-Za-z0-9]+$/',$account)&&preg_match('/^[A-Za-z0-9]+$/',$password))
                {
                    $query = $this->db->query("SELECT * FROM user WHERE account = '$account' ");

                    if($query->num_rows() != 0)
                        return 3;
                    else
                        return 0;
                }
                else
                {
                    return 2;
                }   
            }
            else
            {
                return 1;
            }
        }


         //验证登录账号和加密密码，判断客户端字符串是否为6位，返回用户ID值
        function verify_login($account,$encrypted_password,$client_str)
        {
            if($account&&$encrypted_password&&$client_str)
            {
                if(preg_match('/^[A-Za-z0-9]+$/',$account)&&preg_match('/^[A-Za-z0-9]+$/',$encrypted_password)&&strlen($client_str) == 6)
                {
                    $query = $this->db->query("SELECT * FROM user WHERE account = '$account' AND encrypted_password = '$encrypted_password'");

                    if($query->num_rows() != 0)
                    {
                        $row = $query->row_array();
                        return $row['id'];
                    }
                    else
                        return false;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }  
        }

        //验证该id用户会话字符串的正确性
        function verify_session($session_str)
        {
            $user_id = substr($session_str,0,strlen($session_str)-32);

            $query = $this->db->query("SELECT session_str FROM user WHERE id = '$user_id' ");

            if($query->num_rows() == 1)
            {
                return 0;
            }
            else
                return 2;
        }
}
