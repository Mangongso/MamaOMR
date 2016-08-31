<?php
class Map{
	public function __construct(){}
	public function __destruct(){}
	public function getDistance($fromLat,$formLng,$toLat,$toLng,$returnType= "m"){
		$theta = $formLng - $toLng;
		$dist = sin($this->deg2rad($fromLat)) * sin(deg2rad($toLat)) + cos(deg2rad($fromLat)) * cos(deg2rad($toLat)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = $this->rad2deg($dist);
		
		$dist = $dist * 60 * 1.1515;
		if($returnType=="km"){
			$dist = $dist * 1.609344;    // 단위 mile 에서 km 변환.
		}
		if($returnType=="m"){
			$dist = $dist * 1000.0;      // 단위  km 에서 m 로 변환
		}
		
		return($dist);		
	}
	private function deg2rad($deg){
		return ($deg * M_PI / 180);
	}
	
	// 주어진 라디언(radian) 값을 도(degree) 값으로 변환
	private function rad2deg($rad){
		return ($rad * 180 / M_PI);
	}	
}