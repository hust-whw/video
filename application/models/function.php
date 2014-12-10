<?php

class Function_time extends CI_Model {
        
        function __construct() {
                parent::__construct();
        }

        function get_timestamp() 
        {
			date_default_timezone_set("prc");  
			  
			return time(); 
        }
}
?>