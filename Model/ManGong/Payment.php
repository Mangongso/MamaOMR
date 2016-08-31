<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Payment{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resPaymentDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setPayment($intMemberSeq,$strMemberType,$strSiteCd,$strRegTx,$strUsePayMethod,$strBSucc,$strResCd,$strResMsg,$strOrdrIdxx,$intGoodMny,$strGoodName,$strBuyrName,$strCphone,$strBuyrMail){
		$strQuery = sprintf("INSERT INTO payment (member_seq,member_type,site_cd, req_tx, use_pay_method, bSucc, res_cd, res_msg, ordr_idxx, good_mny, good_name, buyr_name, cphone, buyr_mail) 
					 		VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', '%s','%s')"
							,$intMemberSeq,$strMemberType,$strSiteCd,$strRegTx,$strUsePayMethod,$strBSucc,$strResCd,$strResMsg,$strOrdrIdxx,$intGoodMny,$strGoodName,$strBuyrName,$strCphone,$strBuyrMail);
		$boolResult = $this->resPaymentDB->DB_access($this->resPaymentDB,$strQuery);
		$intPaymentSeq = mysql_insert_id($this->resPaymentDB->res_DB);
		return($boolResult);
	}
	public function getPayment($intPaymentSeq){
		$strQuery = sprintf("select * from payment where seq=%d",$intPaymentSeq);
		$arrResult = $this->resPaymentDB->DB_access($this->resPaymentDB,$strQuery);
		return($arrResults);
	}
	public function getPayments($intMemberSeq){
		$strQuery = sprintf("select * from payment where member_seq=%d",$intPaymentSeq);
		$arrResult = $this->resPaymentDB->DB_access($this->resPaymentDB,$strQuery);
		return($arrResults);
	}
}
?>