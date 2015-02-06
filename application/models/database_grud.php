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

	function video_add($title,$label1,$label2,$label3,$east,$north,$video_url,$self_id,$server_time) 
	{
		$data1 = array(
			'title' 			=> $title,
			'label1' 			=> $label1,
			'label2' 			=> $label2,
			'label3' 			=> $label3,
			'video_url'		 	=> $video_url,
			'time'				=> $server_time,
			'user_id'			=> $self_id,
			);

		@$this->db->insert('video',$data1);

		if($this->db->affected_rows() == 1)
		{
			$video_id = mysql_insert_id();

			$is_seccess1 = 1;

			$data2 = array(
				'video_id' 			=> $video_id,
				'lng' 				=> $east,
				'lat' 				=> $north,
				);

			@$this->db->insert('video_geography',$data2);

			if($this->db->affected_rows() == 1)
			{
				$is_seccess2 = 1;
			}
		}

		if($is_seccess1&&$is_seccess2)
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
		if(!$json_array)return NULL;

		$query = @$this->db->query("SELECT * From user WHERE id = '$to_id'");

		if($query->num_rows == 0) return NULL;

		$result = $query->row_array();

		$result_array = array();

		foreach ($json_array as $key => $value) 
		{
			if(!@$result[$value])continue;

			$result_array[$value] = $result[$value];
		}

		return $result_array;
	}

	   //获取指定视频id的指定信息
	function video($video_id,$json_array)
	{
		if(!$json_array)return NULL;

		$query = @$this->db->query("SELECT * From video WHERE id = '$video_id'");

		if($query->num_rows == 0) return NULL;

		$result = $query->row_array();

		$result_array = array();

		foreach ($json_array as $key => $value) 
		{

			if(!@$result[$value])continue;

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

	//删除视频操作
	function video_del($video_id,$self_id,$server_time)
	{
		$query1 = @$this->db->query("SELECT * From video WHERE id = '$video_id' AND user_id = '$self_id' AND is_del = 0");

		$query2 = @$this->db->query("SELECT * From video_geography WHERE video_id = '$video_id' AND is_del = 0");
	            
		if($query1->num_rows() == 1&&$query2->num_rows() == 1)
		{
			@$this->db->query("UPDATE video SET is_del = 1,del_time = '$server_time' WHERE id = '$video_id' AND user_id = '$self_id' AND is_del = 0");

			if($this->db->affected_rows() == 1)
				$is_seccess1 = 1;

			@$this->db->query("UPDATE video_geography SET is_del = 1 WHERE video_id = '$video_id' AND is_del = 0");

			if($this->db->affected_rows() == 1)
				$is_seccess2 = 1;

			if($is_seccess1&&$is_seccess2)
				return 0;
			else
				return 2;
		}
		else
			return 2;
	}

	//0=>获取指定视频播放次数并返回  1=>记录本次播放动作，返回播放次数
	function video_play($self_id,$video_id,$do_what,$server_time)
	{
		switch($do_what)
		{
			case 0:
			$query = @$this->db->query("SELECT * From video_play WHERE video_id = '$video_id'");
			$result = @$query->num_rows;
			return $result;
			break;
			
			case 1:
			$data = array(
			'video_id' 			=> $video_id,
			'user_id' 			=> $self_id,
			'time' 				=> $server_time,
			);

			@$this->db->insert('video_play',$data);

			if($this->db->affected_rows() == 1)
				return $this->video_play($self_id,$video_id,0,$server_time);
			else 
				return false;
			break;

			default:
			return false;
		}
	}

	//0=>对指定视频点赞  1=>对指定视频取消点赞
	function like($self_id,$video_id,$do_what,$server_time)
	{
		switch($do_what)
		{
                //点赞操作
			case 0:            

			$query = @$this->db->query("SELECT * From video_like WHERE video_id = '$video_id' AND user_id = '$self_id' AND is_del = 0");
                    //判定如果关注操作不存在或已取关，添加新的关注操作
			if($query->num_rows() == 0)
			{
				@$this->db->query("INSERT INTO video_like (video_id,user_id,time) VALUES ('$video_id','$self_id','$server_time') ");

				if($this->db->affected_rows() == 1)
					return 0;
				else
					return 2;
			}
			else
				return 2;
			break;

                //取消点赞操作
			case 1:
			$query = @$this->db->query("SELECT * From video_like WHERE video_id = '$video_id' AND user_id = '$self_id' AND is_del = 0");
                    //判定如果关注操作已存在并未取关，则添加取关操作
			if($query->num_rows() == 1)
			{
				@$this->db->query("UPDATE video_like SET is_del = 1,del_time = '$server_time' WHERE video_id = '$video_id' AND user_id = '$self_id' AND is_del = 0");

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


	//添加评论
	function comment_add($self_id,$video_id,$text,$server_time) 
	{
		$data = array(
			'video_id' 			=> $video_id,
			'user_id' 			=> $self_id,
			'text' 				=> $text,
			'time' 				=> $server_time,
			);

		@$this->db->insert('video_comment',$data);

		if($this->db->affected_rows() == 1)
			return 0;
		else
			return 2;
	}


	//删除评论!improtant:仅发布评论者与被评论视频作者有删除评论权限
	function comment_del($self_id,$comment_id,$server_time)
	{
		$query = @$this->db->query("SELECT video_comment.user_id as A,video.user_id as B
									FROM video_comment
									INNER JOIN video 
									ON video_comment.video_id=video.id 
									WHERE video_comment.id = '$comment_id'
									AND video_comment.is_del = 0");
		if($this->db->affected_rows() == 1)
		{
			$result = $query->row_array();
			//判定删除时用户的权限
			if(($result['A'] == $self_id)||($result['B'] == $self_id))
			{
				@$this->db->query("UPDATE video_comment SET is_del = 1,del_time = '$server_time' WHERE id = '$comment_id' AND is_del = 0");

				if($this->db->affected_rows() == 1)
					return 0;
				else
					return 2;
			}
			else
				return 3;
		}
		else
		{
			return 2;
		}


	}


	//添加(发送)消息
	function message_add($self_id,$user_id,$text,$server_time) 
	{
		$query = @$this->db->query("SELECT * FROM chat WHERE (user_id = '$self_id' AND user2_id = '$user_id') OR (user_id = '$user_id' AND user2_id = '$self_id')");
		

		if($query->num_rows() == 0)
		{
			$data1 = array(
				'user_id' 			=> $self_id,
				'user2_id' 			=> $user_id,
				'time' 				=> $server_time,
				);
			@$this->db->insert('chat',$data1);

			if($this->db->affected_rows() == 1)
			{
				$is_seccess1 = 1;

				$chat_id = mysql_insert_id();

				$is_first = 1;
			}
			else
				return 2;
		}
		else
		{
			$result = $query->row_array();

			if($self_id == $result['user_id'])
			{
				$chat_id = $result['id'];

				$is_first = 1;

				$is_seccess1 = 1;
			}
			else
			{
				$chat_id = $result['id'];

				$is_first = 0;

				$is_seccess1 = 1;
			}
		}

		if($is_seccess1)
		{
			$data2 = array(
				'chat_id' 			=> $chat_id,
				'is_first' 			=> $is_first,
				'text' 				=> $text,
				'time' 				=> $server_time,
				);

			@$this->db->insert('message',$data2);

			if($this->db->affected_rows() == 1)
			{
				$is_seccess2 = 1;
			}
		}

		if($is_seccess1&&$is_seccess2)
			return 0;
		else
			return 2;
	}

	//获取指定区间的评论列表，返回评论ID列表
	function get_comment_list($video_id,$start,$limit)
	{
		$data_array = array();

		$query = @$this->db->query("SELECT id From video_comment WHERE video_id = '$video_id' AND is_del = 0 LIMIT $start,$limit");
		
		foreach ($query->result_array() as $row)
		{
			array_push($data_array, $row['id']);
		}

		return $data_array;
		
	}

	//获取指定评论的信息
	function comment_get($comment_id)
	{
		$query = @$this->db->query("SELECT * From video_comment WHERE id = '$comment_id' AND is_del = 0 LIMIT 1");

		if($query->num_rows == 0) return NULL;

		$result = $query->row_array();

		$result_array = array();

		$result_array['video_id'] = $result['video_id'];

		$result_array['user_id'] = $result['user_id'];

		$result_array['time'] = $result['time'];

		$result_array['text'] = $result['text'];

		return $result_array;
	}


	//获取视频流ID
	function video_flow($start,$limit)
	{
		$data_array = array();

		$query = @$this->db->query("SELECT id From video LIMIT $start,$limit");
		
		foreach ($query->result_array() as $row)
		{
			array_push($data_array, (int)$row['id']);
		}

		return $data_array;
	}

}
?>