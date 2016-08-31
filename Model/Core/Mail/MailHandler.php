<?
class MailHandler{
	private $resMailDB;
	public function __construct($resMailDB=null){
		if(!is_null($resMailDB)){
			$this->resMailDB = $resMailDB;
		}
	}
	public function __destruct(){}
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
	public function sendMail($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strMessage,$strEncoding="UTF-8"){
		$boorReturn = $this->sendSmtp($strToMail,$strToName,$strSubject,$strMessage,$strFromMail,$strFromName);
		/*
		$arrResult = $this->makeMailInfo($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding);
		$mail=mail($arrResult['to_mail'],$arrResult['subject'],$arrResult['contents'],$arrResult['header']);
		*/
		return($boorReturn);
	}
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
	public function setMailToDB($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding="UTF-8"){
		/*
		 * 메일러를 위한 메일 정보를 DB 에 넣는다
		 * $arrResult = $this->makeMailInfo($strFromMail,$strFromName,$strToMail,$strToName,$strSubject,$strContents,$strEncoding);
		 * insert table $arrResult
		 */
	}
	public function getMailFromDB(){
		/*
		 * 메일 발송을 위한 DB 의 발송 메일 정보 가져오기
		 */
	}
}