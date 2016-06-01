<?php
// 華語單曲月榜 Top 100
// https://www.kkbox.com/tw/tc/charts/chinese-monthly-song-latest.html

// var item = document.getElementsByClassName('item');
// var j={};
// for(var i = 0; i <  item.length; i++){
// 	var at = item[i].getElementsByClassName('non-link-type')[0].innerText;
// 	var ti = item[i].getElementsByTagName('h4')[0].firstElementChild.title;
// 	j[i] = at + ' - ' + ti;
// }
// console.log(JSON.stringify(j));
?>
<?php 
start();
function start(){
	error_reporting(0);
	date_default_timezone_set('Asia/Tokyo');

	if(!array_key_exists("PHPSESSID",$_COOKIE)){
		session_start();
	}

	$_SESSION['req_url'] = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

	if(array_key_exists('you_access_token',$_COOKIE)){
		$token = $_COOKIE['you_access_token'];
	}else{
		$_SESSION['req_url'] = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		header('location: http://www.dodofei.com/google/oauth.php');
	}
}
?>
<?php
echo '華語單曲月榜 Top 100<br>';
echo 'https://www.kkbox.com/tw/tc/charts/chinese-monthly-song-latest.html<br>';


echo '<form method="POST" action="ctop100.php">';
echo '<textarea id = "lid" name="plid" cols=100 rows=2>';
echo '</textarea>';
echo '<textarea id = "array" name="array" cols=100 rows=10>';
echo 'var item = document.getElementsByClassName(\'item\');';
echo 'var j={};';
echo 'for(var i = 0; i <  item.length; i++){';
echo 'var at = item[i].getElementsByClassName(\'non-link-type\')[0].innerText;';
echo 'var ti = item[i].getElementsByTagName(\'h4\')[0].firstElementChild.title;';
echo 'j[i] = at + \' - \' + ti;';
echo '}';
echo 'console.log(JSON.stringify(j));';
echo '</textarea><br>';
echo '<input type="submit" value="送信">';
echo '</form>';
?>
<?php
	echo '<pre>';
	echo $_POST['plid'];
	echo '<br>';


?>
<?php

include_once('../google/dataapi.php');
$youtube = new dataapi;
$x= new rss2json();

class rss2json{
	public $rssurl=array();
	public function rss2json(){
		$pro_video = array ('95wekclq9lU',		//Kamov Ka-26
							'HDGtT7_XGeQ',		//Narita Airport
							'Uf0ZLwqigTw',		//Universal Studios Japan. USJ 
							'JJSWfFNVoEA',		//Bonanza Jingle Punks 
							'QS9X-efd58Q',		//開運 招財貓 まねきねこ 
							'YxI5hoRowS0',		//お願い だるま
							//'-_MG7mTnkPg',		//Drinks On The Bar Stuart Bogie 
							'MnevVdN85Qs',		//Gemini Robot
							'_Z7mtXdYwU0',		//Shibakawa
							'o35osKfHb84',		//Shibakawa
							'TJBvlhNXC6s',		//Yotsuya、Tokyo japan
							'XwfoQl54s0o',		//Yotsuya、Tokyo japan
							'fqMlRNNHd6I',		//Musashino Line
							'hTs9zzIr4_0',		//Tokyo International Airport 
							'KUVpUDFZ_Iw',		//Yunoko
							//'Roz7uiDxeaE',		//危ない遊び
							'6wZ7jBcdXSE',		//スピーカーテスト用ステレオ音源
							'uxQ4WdwvZbY',		//さくらの山公園から 着陸したボーイング787
							'DNAw_deZrpg',		//山上的蝴蝶
							'h81DOCCivtA',		//トップソング、メドレー、BGM音楽 のチャンネル 
							'98XBy38GNl4'		//Tokyo Disneyland 
							);
													
		
		global $youtube;
		$c_list_title = '華語單曲月榜 '.date("Y/m/d"); 
		$temp = json_decode($_POST['array']);

		if(array_key_exists('plid', $_POST) and $_POST['plid'] !=''){
			$list_id = $_POST['plid'];
		}else{
			if(is_object($temp)){
				$list_id = $youtube->c_play_list($c_list_title);	//Youtube List 作成し、ListIDをReturn.
			}
		}

		if($list_id){
			foreach($temp as $key => $val){
				$video_id = $youtube->search(urldecode($val));
				$youtube->c_play_item_insert($list_id,$video_id);  
				if(rand(1,6) == 3){
					$youtube->c_play_item_insert($list_id,$pro_video[array_rand($pro_video)]);  
				}
			}
		}	
	}

}

?>