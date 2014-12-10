<?php
/**
 * 计算模型，类名首字母必须大写，所有的模型必须继承自CI_Model类
 */
class Calculate_model extends CI_Model {
        
        function __construct() {
                parent::__construct();
        }
        
        /*
         * 计算函数
         */
        function count($num1, $num2, $op) {
                if ($op == "+") {
                        return $num1 + $num2;
                }else if ($op == "-") {
                        return $num1 - $num2;
                }else if ($op == "x") {
                        return $num1 * $num2;
                }else if ($op == "÷" && $num2 != 0) {
                        return $num1 / 1.0 / $num2; 
                }else {
                        return FALSE;
                }
        }
}

/* End of file calculate_model.php */
/* Location: ./application/models/calculate_model.php */
