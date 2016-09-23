<?php
/**
 * ImageHelper
 * @category     	ImageHelper
 */
class ImageHelper{
	/**
	 * 이미지 변환
	 *
	 * @param	integer 		$intWidth : 넓이 
	 * @param 	integer 		$intHeight : 높이
	 * @param 	string 		$strNewImage : 변경 이미지
	 * @param 	string 		$strOrgImage : 원본 이미지
	 * @return boolean	boolean	이미지 변환 성공 여부 반환
	 */
	function convertPic($intWidth, $intHeight, $strNewImage, $strOrgImage){
		ini_set('memory_limit', '100M');   //  handle large images
		copy($strOrgImage,$strNewImage);
		list($intOrgWidth, $intOrgHeight, $intOrgType) = getimagesize($strOrgImage);  // create new dimensions, keeping aspect ratio
		$intRatio = $intOrgWidth/$intOrgHeight;
		if ($intWidth/$intHeight > $intRatio) {$intWidth = floor($intHeight*$intRatio);} else {$intHeight = floor($intWidth/$intRatio);}

		switch ($intOrgType)
		{
		case IMAGETYPE_GIF:   //   gif -> jpg
			$strImageSrc = imagecreatefromgif($strOrgImage);
			break;
		case IMAGETYPE_JPEG:   //   jpeg -> jpg
			$strImageSrc = imagecreatefromjpeg($strOrgImage);
			break;
		case IMAGETYPE_PNG:  //   png -> jpg
			$strImageSrc = imagecreatefrompng($strOrgImage);
			break;
		}
		$resImage = imagecreatetruecolor($intWidth, $intHeight);  //  resample
			
		imagecopyresampled($resImage, $strImageSrc, 0, 0, 0, 0, $intWidth, $intHeight, $intOrgWidth, $intOrgHeight);
		
		$arrTemp = explode(".",$strNewImage);
		$strConvertImageType = strtolower($arrTemp[count($arrTemp)-1]);
		switch($strConvertImageType){
			case('gif'):
				$boolReturn = imagegif($resImage, $strNewImage);
				break;
			case('jpg'):
				$boolReturn = imagejpeg($resImage, $strNewImage);
				break;
			case('png'):
				$boolReturn = imagepng($resImage, $strNewImage);
				break;
		}
		return($boolReturn);
	}
}