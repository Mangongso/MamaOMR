<?php
if(file_exists(ini_get('include_path')."/ExternalApps/tesseract-ocr-for-php")){
	include_once('ExternalApps/tesseract-ocr-for-php/src/TesseractOCR.php');
}
/**
 * OCR 
 *
 * @property		private	string 		$strApiKey : api 키
 * @property		private 	string 		$strOCRType : ocr 타입
 * @property		private	string 		$strOCRSpaceAPI :  ocr공간 API
 * @property		private	integer 	$intOCRSpaceMaxFileSize : ocr공간 최대 사이즈
 * @category     	OCR
 */
class OCR{
	private $strApiKey = "b1e84bfdec88957";
	private $strOCRType = "tesseract";
	private $strOCRSpaceAPI = "https://api.ocr.space/Parse/Image";
	private $intOCRSpaceMaxFileSize = "1000"; // size is KB
	public function __construct(){
		
	}
	public function __destruct(){}
	
	/**
	 * convert
	 *
	 * @param 	string 		$strDocImageFile 	이미지파일
	 * @param 	string 		$strLang 				언더
	 * @return 	string		$strReturn		  : 변환 값을 반환
	 */
	public function convert($strDocImageFile,$strLang="kor"){
		switch($this->strOCRType){
			case('tesseract'):
				$strReturn = $this->convertByTesseract($strDocImageFile,$strLang);
			break;
			case('ocrspace'):
				$strReturn = $this->convertByOCRSpace($strDocImageFile,$strLang);
			break;
			default:
			break;
		}
		return($strReturn);
	}
	
	/**
	 * convertByTesseract
	 *
	 * @param 	string 		$strDocImageFile 	이미지파일
	 * @param 	string 		$strLang 				언더
	 * @return 	string		$strReturn		  : 변환 값을 반환
	 */
	private function convertByTesseract($strDocImageFile,$strLang="kor"){
		$objTesseractOCR = new TesseractOCR($strDocImageFile);
		$objTesseractOCR->lang($strLang);
		$strReturn = $objTesseractOCR->run();
		return($strReturn);
	}
	
	/**
	 * convertByOCRSpace
	 *
	 * @param 	string 		$strDocImageFile 	이미지파일
	 * @param 	string 		$strLang 				언더
	 * @return 	string		$strReturn		  : 변환 값을 반환
	 */
	private function convertByOCRSpace($strDocImageFile,$strLang="kor"){
		$strDocImageFile = "/home/zzz/zzz/2040581143_mCOVgM7Q_dd.jpg";
		$strDocImageFileName = basename($strDocImageFile);
		$strDocImageURL=QUESTION_IMAGE_URL.$strDocImageFileName;
		$intFileSize = filesize($strDocImageFile);
		if($intFileSize>$intOCRSpaceMaxFileSize){
			// convert image size by GD
			$realRatio = $intOCRSpaceMaxFileSize/$intFileSize;
			$objImage=new Imagick($strDocImageFile);
			$arrResolution=$objImage->getImageResolution();
			$intX = floor(sqrt($arrResolution['x']^2*$realRatio));
			$intY = floor(sqrt($arrResolution['y']^2*$realRatio));
			$boolResult = $objImage->setImageResolution($intX,$intY);
		}
		$resCh = curl_init();
		curl_setopt($resCh, CURLOPT_URL, $this->$strOCRSpaceAPI);
		curl_setopt($resCh, 
					  CURLOPT_POSTFIELDS,
					  sprintf("apikey=%s&isOverlayRequired=true&url=%s&language=%s",$this->$strApiKey,$strDocImageURL,$strLang)
					 );
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$jsonResult = curl_exec($resCh);
		curl_close ($resCh);
		$objResult = json_decode($jsonResult);
		return($objResult->ParsedResults[0]->ParsedText);
	}
}