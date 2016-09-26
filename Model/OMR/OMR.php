<?php
require('ExternalApps/openomr/vendor/autoload.php');
use OpenOMR\PaperSheet\PaperSheet;
use OpenOMR\PaperSheet\Field;
use OpenOMR\PaperSheet\Mark;
use OpenOMR\Reader\Reader;

/**
 * OMR
 * 
 * @property	private	resource 		$paper : PaperSheet 객체
 * @property	private	string 			$fieldId : 필드 ID
 * @property	private	array 			$arrMark : 마크
 * @category    OMR
 */
class OMR{
	private $paper;
	private $fieldId=1;
	private $arrMark = array(
			'1'=>array(3,4,5,6,7),
			'2'=>array(10,11,12,13,14),
			'3'=>array(17,18,19,20,21),
			'4'=>array(24,25,26,27,28),
			'5'=>array(31,32,33,34,35)
	);
	
	/**
	 * 생성자
	 * PaperSheet 객체 생성
	 * initOMR 함수 호출
	 */
	public function __construct(){
		$this->paper = new PaperSheet(38, 54);
		$this->initOMR();
	}
	public function __destruct(){}
	
	/**
	 * OMR 초기화 
	 *
	 * @return null
	 */
	public function initOMR(){
		$fieldId = 0;
		for($j=1;$j<5;$j++){
			for ($i = 31; $i <= 50; $i++) {
				// $field = new Field(str_pad($fieldId, 2, '0', STR_PAD_LEFT));
				$field = new Field($fieldId);
				$field->addMark(new Mark($i, $this->arrMark[$j][0], '0'));
				$field->addMark(new Mark($i, $this->arrMark[$j][1], '1'));
				$field->addMark(new Mark($i, $this->arrMark[$j][2], '2'));
				$field->addMark(new Mark($i, $this->arrMark[$j][3], '3'));
				$field->addMark(new Mark($i, $this->arrMark[$j][4], '4'));
				$this->paper->addField($field);
				$fieldId++;
			}
		}
	}
	
	/**
	 * OMR 읽기
	 *
	 * @param string $strFileName : 파일명
	 * @return array 파일을 읽은 결과를 반환
	 */
	public function readOMR($strFileName){
		$boolResult = $this->resizeOMR($strFileName);
		$reader = new Reader($strFileName, $this->paper, 4);
		return($reader->getResults());		
	}
	
	/**
	 * OMR 리사이즈
	 *
	 * @param string $strFileName : 파일명
	 * @return boolean 리사이즈 성공 여부를 반환 (true | false)
	 */
	private function resizeOMR($strFileName){
		$resImageTaget = imagecreatetruecolor(559,800);
		$resImageSource = imagecreatefromjpeg($strFileName);
		$intW = imagesx($resImageSource);
		$intH = imagesy($resImageSource);		
		imagecopyresampled($resImageTaget, $resImageSource, 0, 0, 0, 0, 559, 800, $intW, $intH);
		$boolResult = imagejpeg($resImageTaget,$strFileName);
		$boolResult = imagedestroy($resImageSource);	
		$boolResult = imagedestroy($resImageTaget);
		/* PHP > v5.4
		$resImage = imagecreatefromjpeg($strFileName);
		$resImage = imagescale($resImage, 559,800);
		$boolResult = imagejpeg($resImage,$strFileName);
		$boolResult = imagedestroy($resImage);
		*/
		return($boolResult);
	}
	
	/**
	 * 유저 정답 가져오기
	 *
	 * @param array $arrOMR : OMR
	 * @param array $arrQuestion : 문제
	 * @return array $arrUserAnswer : 유저 정답을 반환한다.
	 */
	public function getUserAnswer($arrOMR,$arrQuestion){
		$arrUserAnswer = array();
		foreach($arrOMR as $intKey=>$arrResult){
			if(trim($arrResult['value'])!="" && $arrResult['status']==2){
				$arrUserAnswer[$arrQuestion[$intKey]['question_seq']] = $arrQuestion[$intKey]['example']['type_1'][$arrResult['value']]['seq'];
			}
		}
		return($arrUserAnswer);
	}
}