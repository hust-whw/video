<?php

class Verification extends CI_Model {
        
        function __construct() {
                parent::__construct();
                $this->load->database();
        }
        
        /*
         * 验证函数
         */
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
}

/* End of file calculate_model.php */
/* Location: ./application/models/calculate_model.php */
