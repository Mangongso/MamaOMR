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
	private $strApiKey = OCR_API_KEY;
	private $strOCRType = OCR_TYPE;
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
	public function convert($strDocImageFile,$strDocImageUrl,$strLang="kor"){
		switch($this->strOCRType){
			case('tesseract'):
				$strReturn = $this->convertByTesseract($strDocImageFile,$strLang);
			break;
			case('ocr.space'):
				$strReturn = $this->convertByOCRSpace($strDocImageFile,$strDocImageUrl,$strLang);
			break;
			default:
			break;
		}
		return($strReturn);
	}
	
	/**
	 * convertByTesseract
	 *
	 * @param 	string 		$strDocImageFile 	: 이미지파일
	 * @param 	string 		$strLang 			: 언더
	 * @return 	string		$strReturn		  	: 변환 값을 반환
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
	public function convertByOCRSpace($strDocImageFile,$strDocImageUrl,$strLang="kor"){
		$intFileSize = filesize($strDocImageFile);
		$intFileSize = $intFileSize/1000;
		if($intFileSize>$this->intOCRSpaceMaxFileSize){
			/* convert image size by GD*/
			$realRatio = $this->intOCRSpaceMaxFileSize/$intFileSize;
			switch(exif_imagetype($strDocImageFile)){
				case(IMAGETYPE_JPEG):
					$resImageSource = imagecreatefromjpeg($strDocImageFile);
					$intW = imagesx($resImageSource);
					$intH = imagesy($resImageSource);
					
					$intX = floor($intW*$realRatio);
					$intY = floor($intH*$realRatio);
						
					$resImageTaget = imagecreatetruecolor($intX,$intY);
					imagecopyresampled($resImageTaget, $resImageSource, 0, 0, 0, 0, $intX, $intY, $intW, $intH);
					$boolResult = imagejpeg($resImageTaget,$strDocImageFile);					
				break;
				case(IMAGETYPE_PNG):
					$resImageSource = imagecreatefrompng($strDocImageFile);
					$intW = imagesx($resImageSource);
					$intH = imagesy($resImageSource);
						
					$intX = floor($intW*$realRatio);
					$intY = floor($intH*$realRatio);
					
					$resImageTaget = imagecreatetruecolor($intX,$intY);
					imagecopyresampled($resImageTaget, $resImageSource, 0, 0, 0, 0, $intX, $intY, $intW, $intH);
					$boolResult = imagepng($resImageTaget,$strDocImageFile);					
				break;
			}

			$boolResult = imagedestroy($resImageSource);
			$boolResult = imagedestroy($resImageTaget);	
			
			/* ImageMagick
			$realRatio = $intOCRSpaceMaxFileSize/$intFileSize;
			$objImage=new Imagick($strDocImageFile);
			$arrResolution=$objImage->getImageResolution();
			$intX = floor(sqrt($arrResolution['x']^2*$realRatio));
			$intY = floor(sqrt($arrResolution['y']^2*$realRatio));
			$boolResult = $objImage->setImageResolution($intX,$intY);
			*/
		}
		$resCh = curl_init();
		curl_setopt($resCh, CURLOPT_URL, $this->strOCRSpaceAPI);
		curl_setopt($resCh, 
					  CURLOPT_POSTFIELDS,
					  sprintf("apikey=%s&isOverlayRequired=true&url=%s&language=%s",$this->strApiKey,$strDocImageUrl,$strLang)
					 );
		curl_setopt ($resCh, CURLOPT_RETURNTRANSFER, 1);
		$jsonResult = curl_exec($resCh);
		curl_close ($resCh);
		$objResult = json_decode($jsonResult);
		return($objResult->ParsedResults[0]->ParsedText);
	}
}