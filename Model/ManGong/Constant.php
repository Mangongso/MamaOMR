<?php
class Constant{
	private $resDB;
	public function __construct($resDB=null){
		if($resDB){
			$this->resDB = $resDB;
		}
	}
	public function __destruct(){}	
	public function getGeneralSubject($mixCode=null){
		$arrReturn = array(
			//초등
			10010=>'국어',
			10020=>'영어',
			10030=>'수학',
			10040=>'제2외국어',
			10050=>'사회',
			10060=>'과학',
			//10070=>'기타',
			//중등
			20010=>'국어',
			20020=>'영어',
			20030=>'수학',
			20040=>'제2외국어',
			20050=>'사회',
			20060=>'과학',
			//20070=>'기타',
			//고등
			30010=>'국어',
			30020=>'영어',
			30030=>'수학',
			30040=>'제2외국어',
			30050=>'사회',
			30060=>'과학',
			//30070=>'기타',
			//대학/일반
			40020=>'영어',
			40080=>'한국사',
			40050=>'사회',
			40060=>'과학',
			40030=>'수학',
			40090=>'행정학',
			//40070=>'기타',
			
			99999=>'기타'
		);	
		return($this->getLabel($arrReturn,$mixCode));
	}
	private function getLabel($arrLabel,$mixCode=null){
		$mixReturn = "";
		if(is_numeric($mixCode)){
			switch($mixCode){
				case(0):
					$mixReturn = $arrLabel;
				break;
				default:
					$mixReturn = $arrLabel[$mixCode];
				break;
			}
		}else{
			foreach($arrLabel as $intKey=>$strLabel){
				if($mixCode == $strLabel){
					$mixReturn = $intKey;
					break;
				}else{
					$mixReturn = "";
				}
			}
		}
		return($mixReturn);
	}	
}