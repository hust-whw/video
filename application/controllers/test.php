<?php
//http://127.0.0.1/ci/index.php/register/regist?ti=555&nickname=asdf&constellation=123aa&sex=1&account=whw&password=111&avatar_url=4654


//http://127.0.0.1/ci/index.php/login?account=whw1&&encrypted_password=202cb962ac59075b964b07152d234b70&i=123&client_str=er3k5a


//http://127.0.0.1/ci/index.php/user?x=333e6499252e9ad17f8065aa2bfa7711b&id=3&i=456&scope=[%22id%22,%22nickname%22,%22sex%22,%22account%22]


//http://127.0.0.1/ci/index.php/video_add?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&title=%E6%A0%87%E9%A2%98&label1=%E6%A0%87%E7%AD%BE1&label2=%E6%A0%87%E7%AD%BE2&label3=%E6%A0%87%E7%AD%BE3&east=56.1247&north=23.1234&video_url=sjksjdf

//http://127.0.0.1/ci/index.php/video_del?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&video_id=2

// http://127.0.0.1/ci/index.php/video?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&id=3&scope=[%22label1%22,%22label2%22]

// http://127.0.0.1/ci/index.php/video_play?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&video_id=3&flag=1

// http://127.0.0.1/ci/index.php/like?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&video_id=3&is_cancel=0


//http://127.0.0.1/ci/index.php/comment_add?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&video_id=2&text=%E8%BF%99%E6%98%AF%E8%AF%84%E8%AE%BA2

//http://127.0.0.1/ci/index.php/message_add?x=29e3c65436c677c598a1a60ecdc50d9f1&i=123&t=4565&user_id=3&text=%E8%BF%99%E6%98%AF%E5%9B%9E%E8%AF%9D3

//http://127.0.0.1/ci/index.php/comment_del?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&comment_id=1

// http://127.0.0.1/ci/index.php/comment_list?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&video_id=4&start=0&limit=10


// http://127.0.0.1/ci/index.php/comment_get?x=333e6499252e9ad17f8065aa2bfa7711b&i=123&t=4565&comment_id=2
$a = array();
array_push($a, 'df');
var_dump($a);

// $a = $_GET['scope'];
// $b = json_decode($a);
// // echo $b[0];
// // echo count($b);
// foreach ($b as $key => $value) {
// 	echo $key;
// }
//var_dump(json_decode($a)); 
// $c=array($a);
// echo $c[1];

?>