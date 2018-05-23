<?
namespace aero;
class YoutubeVideo{
	public static function getVideoParams($videoUrl){	
		$videoId = self::getVideoIdFromUrl($videoUrl);	
		if($videoId){
			$url = "http://gdata.youtube.com/feeds/api/videos/". $videoId;
			$doc = new \DOMDocument;
			$doc->load($url);
			$title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
			$oDuration = $doc->getElementsByTagName('duration')->item(0);
            if(!empty($oDuration)) {
                $duration = $oDuration->getAttribute('seconds');
                $duration = self::formatDuration($duration);
                return array('duration' => $duration);
            }else{
                return false;
            }
		}else{
			return false;
		}
	}
	
	public static function getVideoIdFromUrl($videoUrl){
		if(preg_match('/v=(.*)$/', $videoUrl, $m)){			
			return $m[1];
		}else{
			return false;
		}
	}
	
	public static function formatDuration($duration){
		if($duration < 60){
			return $duration.' сек';
		}else{
			$minutes = (int)($duration / 60);
			$seconds = $duration - ($minutes * 60);
			return $minutes.':'.$seconds.' мин';
		}
	}
}
?>