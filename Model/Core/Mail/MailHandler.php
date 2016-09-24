<?
/**
 * 메일 핸들러
 *
 * @property		private resource $resMailDB : DB 커넥션 리소스
 * @category     	MailHandler
 * 
 */
class MailHandler{
	private $resMailDB;
	/**
	 * 생성자
	 *
	 * @param resource $resMailDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMailDB=null){
		if(!is_null($resMailDB)){
			$this->resMailDB = $resMailDB;
		}
	}
	public function __destruct(){}
	
	/**
	 * 메일 발송 smtp
	 *
	 * @param string $strToMail 수신자이메일
	 * @param string $strToName 수신자명
	 * @param string $strSubject 제목
	 * @param string $strMessage 내용
	 * @param string $strFromMail 발신자이메일
	 * @param string $strFromName 발신자명
	 * @param string $strType 메일타입
	 * @param string $strHost 호스트
	 * @param integer $intPort 포트
	 *
	 * @return boolean 메일 발송 성공 여부. (false 또는 true)
	 */
	private function sendSmtp($strToMail,$strToName,$strSubject,$strMessage,$strFromMail,$strFromName,$strType="text/html",$strHost='localhost',$intPort=25){
		$strSubject = "=?"."UTF-8"."?B?".base64_encode($strSubject)."?=";
		$resFp = fsockopen($strHost, $intPort, $intErrNo, $strErrMessage,30);
		if($resFp) {
			fgets($resFp, 128);
			fputs($resFp, "helo ".$_SERVER['HTTP_HOST']."\r\n");
			fgets($resFp, 128);
			fputs($resFp, "mail from: ".$strFromMail."\r\n");
			$returnvalue[0] = fgets($resFp, 128);
			fputs($resFp, "rcpt to: ".$strToMail."\r\n");
			$returnvalue[1] = fgets($resFp, 128);
			fputs($resFp, "data\r\n");
			fgets($resFp, 128);
			fputs($resFp, "Return-Path: ".$strFromMail."\r\n");
			fputs($resFp, "From: ".$strFromName."<".$strFromMail.">\r\n");
			fputs($resFp, "To: ".$strToName."<".$strToMail.">\r\n");
			fputs($resFp, "Subject: ".$strSubject."\r\n");
			fputs($resFp, "Content-Type: ".$strType."; charset=\"utf-8\"\r\n");
			fputs($resFp, "Content-Transfer-Encoding: base64\r\n");
			fputs($resFp, "\r\n");
			$strMessage= chunk_split(base64_encode($strMessage));
			fputs($resFp, $strMessage);
			fputs($resFp, "\r\n.\r\n");
			$returnvalue[2] = fgets($resFp, 128);
			fclose($resFp);
			if (preg_match("/^250/i", $returnvalue[0]) && preg_match("/^250/i", $returnvalue[1]) && (preg_match("/^250/i", $returnvalue[2]) || preg_match("/^354/i", $returnvalue[2]))) {
				$sendmail_flag = true;
			}
		}
		if ($sendmail_flag) {
			return(true);
		} else {
			return(false);
		}
	}
	/**
	 * 파일에서 메일 내용 생성
	 *
	 * @param string $strFile 파일
	 * @param array $arrArgument :인수 
	 *
	 * @return string $strContents 생성된 내용 반환
	 */
	public function makeMailContentFromFile($strFile,$arrArgument){
		if(file_exists($strFile)){
			$strContents = join("",file($strFile));
		}
		foreach($arrArgument as $strKey=>$strValue){
			$strResolve = "[:".strtoupper($strKey).":]";
			$strContents = str_replace($strResolve,$strValue,$strContents);
		}
		return($strContents);
	}
	
	/**
	 * 메일 발송 smtp
	 *
	 * @param string $strToMail 수신자이메일
	 * @param string $strToName 수신자명
	 * @param string $strSubject 제목
	 * @param string $strMessage 내용
	 * @param string $strFromMail 발신자이메일
	 * @param string $strFromName 발신자명
	 * @param string $strEncoding 인코딩
	 *
	 * @return boolean 메일 발송 성공 여부. (false 또는 true)
	 */
	public function sendMail($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strMessage,$strEncoding="UTF-8"){
		$boorReturn = $this->sendSmtp($strToMail,$strToName,$strSubject,$strMessage,$strFromMail,$strFromName);
		/*
		$arrResult = $this->makeMailInfo($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding);
		$mail=mail($arrResult['to_mail'],$arrResult['subject'],$arrResult['contents'],$arrResult['header']);
		*/
		return($boorReturn);
	}
	
	/**
	 * 메일 발송 정보 생성
	 *
	 * @param string $strToMail 수신자이메일
	 * @param string $strToName 수신자명
	 * @param string $strSubject 제목
	 * @param string $strContents 내용
	 * @param string $strFromMail 발신자이메일
	 * @param string $strFromName 발신자명
	 * @param string $strEncoding 인코딩
	 *
	 * @return array $arrReturn 메일 발송 정보 생성 정보 반환
	 */
	private function makeMailInfo($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding="UTF-8"){
		switch(strtoupper($strEncoding)){
			default:
				$strCharset = 'UTF-8';
			break;
			case("EUC-KR"):
				$strCharset = 'EUC-KR';
			break;
		}
		$strSubject = "=?".$strCharset."?B?".base64_encode($strSubject)."?=\n";
		$strFrom = '"=?'.$strCharset.'?B?'.base64_encode($strFromName).'?='.'"<'.$strFromMail.'>';
		$strTo = $strToName.'<'.$strToMail.'>' ;
		$strHeaders  = "MIME-Version: 1.0\n";
		$strHeaders .= "Content-Type: text/html; charset=".$strCharset."\n";
		$strHeaders .= "To: ". $strTo ."\n";
		$strHeaders .= "From: ".$strFrom;
		$arrReturn = array(
		'to_mail'=>$strToMail,
		'subject'=>$strSubject,
		'contents'=>$strContents,
		'header'=>$strHeaders
		);
		return($arrReturn);
	}
	
	/**
	 * 메일러를 위한 메일 정보를 DB 에 넣는다
	 */
	public function setMailToDB($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding="UTF-8"){
	}
	
	/**
	 * 메일 발송을 위한 DB 의 발송 메일 정보 가져오기
	 */
	public function getMailFromDB(){
	}
}