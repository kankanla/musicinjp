<?php
//YoutubeClass.php ver 2
//youtube api 3.0
//https://www.youtube.com/watch?v=UKY3scPIMd8
// 2016/05/15 4:35:39
// VID データーベースにYoutubeIDが存在するかをチェックファンクションを追加した。
// よって 存在するばあTwitteiをしない



$g=$_GET['g'];

if($g){
	$x=new YoutubeAPI($g);
	$x->link();
}else{
		exit('88');
}


class youtubeapi {
		public $url;
		public $db_name = './vid.db';
		public function youtubeapi($key){
			$API_URL = 'https://www.googleapis.com/youtube/v3/search?';
			$para['key'] = 'AIz**********************8Ao';
			$para['part'] = 'id';
			$para['q'] = $key;
			$para['type'] = 'video';
			$para['order'] = 'relevance';
			$para['maxResults'] = 3;
			$this->url = $API_URL.http_build_query($para);
		}
		
		public function link(){
				$ll=array();
				$data = file_get_contents($this->url);
				$xdata = json_decode($data ,true);
				foreach ($xdata['items'] as $key=>$val){
						// array_unshift($ll,$val['id']['videoId']);
						array_push($ll,$val['id']['videoId']);
						// $ll[count($ll)]=$val['id']['videoId'];
				}
				unset ($key);
				unset ($val);

				if($this->chk_vid($ll[0])){
					echo 'array_key_exists';
				}else{
					echo implode('|',$ll);
					$this->insert_vid($ll[0]);
				}
				
				if(array_key_exists( 'd',$_GET)){
					echo '<br>';
					echo implode('|',$ll);
					echo '<br>';
					echo $this->url;
				}
		}

		public function chk_vid($youtube_id){
			$db = new sqlite3($this->db_name);
			$db->busyTimeout(1000);
			$sqlcmd = "select vid from vid where vid = '{$youtube_id}'";
			$temp = $db->querySingle($sqlcmd);
			$db->close();
			return $temp;
		}

		public function insert_vid($youtube_id){
			$db = new sqlite3($this->db_name);
			$db->busyTimeout(1000);
			$ins = "insert into vid(vid) values ('%s')";
			$db->exec(sprintf($ins,urldecode($youtube_id)));
			$db->close();
		}
}

?>