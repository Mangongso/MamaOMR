<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Ticket{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resTicketDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getTicketInfo($intTicketType,$intPurchaseNumber=10){
		$price = TICKET_PRICE;
		switch($intTicketType){
			case(TICKET_VOUCHER):
				if($intPurchaseNumber<10){
					$discount = 0;
				}else if($intPurchaseNumber<50){
					$discount = 0.1;
				}else if($intPurchaseNumber<100){
					$discount = 0.2;
				}else if($intPurchaseNumber>=100){
					$discount = 0.3;
				}
				$price = $price - ($price*$discount);
				$arrTicketResult = array(
					'price'=>$price,
					'day'=>30,
					'discount'=>$discount
				);
			break;
			/*
			case(TICKET_YEAR):
				$discount = 0.4;
				$price = $price - ($price*$discount);
				$arrTicketResult = array(
					'price'=>$price,
					'day'=>365,
					'discount'=>$discount
				);
			break;
			*/
		}
		return $arrTicketResult;
	}
	public function setTicket($intPurchaseSeq, $intTicketType, $intBuyerSeq, $strBuyerType, $intTeacherSeq, $intStudentSeq, $intPrice, $floatDiscount, $intTicketOrignalDay, $intTicketRemainingTimestamp, &$intTicketSeq, $intCouponSeq=0){
		$strQuery = sprintf("INSERT INTO ticket (purchase_seq, ticket_type, buyer_seq, buyer_type, teacher_seq, student_seq, price, discount, purchase_date, ticket_orignal_day, ticket_remaining_timestamp, coupon_seq) VALUES (%d,%d,%d,'%s',%d,%d,%d,%f,now(),%d,%d,%d)",$intPurchaseSeq, $intTicketType, $intBuyerSeq, $strBuyerType, $intTeacherSeq, $intStudentSeq, $intPrice, $floatDiscount, $intTicketOrignalDay, $intTicketRemainingTimestamp, $intCouponSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		$intTicketSeq = mysql_insert_id($this->resTicketDB->res_DB);
		return($boolResult);
	}
	public function updateTicket($intTeacherSeq=null,$intStudentSeq=null,$strUseDate=null,$intUseFlg=null,$intTicketStatus=null,$intTicketOrignalDay=null,$intTicketRemainingTimestamp=null,$strStartDate=null,$strExpirationDate=null,$intGiverTicketSeq=null,$intReceverTickerSeq=null,$intTicketSeq,$intBuyerSeq){
		$strQuery = sprintf("update ticket set modify_date=now()");
		if(!is_null($intTeacherSeq)){
			$strQuery .= sprintf(" ,teacher_seq=%d",$intTeacherSeq);
		}
		if(!is_null($intStudentSeq)){
			$strQuery .= sprintf(" ,student_seq=%d",$intStudentSeq);
		}
		if($strUseDate){
			$strQuery .= sprintf(" ,use_date='%s'",$strUseDate);
		}
		if(!is_null($intUseFlg)){
			$strQuery .= sprintf(" ,use_flg=%d",$intUseFlg);
		}
		if($intTicketStatus){
			$strQuery .= sprintf(" ,ticket_status=%d",$intTicketStatus);
		}
		if($intTicketOrignalDay){
			$strQuery .= sprintf(" ,ticket_orignal_day=%d",$intTicketOrignalDay);
		}
		if($intTicketRemainingTimestamp){
			$strQuery .= sprintf(" ,ticket_remaining_timestamp=%d",$intTicketRemainingTimestamp);
		}
		if(!is_null($strStartDate)){
			if($strStartDate===0){
				$strQuery .= sprintf(" ,start_date=null ");
			}else{
				$strQuery .= sprintf(" ,start_date='%s'",$strStartDate);
			}
		}
		if(!is_null($strExpirationDate)){
			if($strExpirationDate===0){
				$strQuery .= sprintf(" ,expiration_date=null ");
			}else{
				$strQuery .= sprintf(" ,expiration_date='%s'",$strExpirationDate);
			}
		}
		if($intGiverTicketSeq){
			$strQuery .= sprintf(" ,giver_ticket_seq=%d",$intGiverTicketSeq);
		}
		if($intReceverTickerSeq){
			$strQuery .= sprintf(" ,recever_ticket_seq=%d",$intReceverTickerSeq);
		}
		$strQuery .= sprintf(" where seq=%d and buyer_seq=%d ",$intTicketSeq,$intBuyerSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function updateTicketStatus($intTeacherSeq=null,$intStudentSeq=null,$intTicketSeq,$intBuyerSeq,$intUseFlg=null,$intTicketStatus,$intTicketRemainingTimestamp=null,$strStartDate=null,$strExpirationDate=null){
		$this->updateTicket($intTeacherSeq,$intStudentSeq,null,$intUseFlg,$intTicketStatus,null,$intTicketRemainingTimestamp,$strStartDate,$strExpirationDate,null,null,$intTicketSeq,$intBuyerSeq);
		return($boolResult);
	}
	public function updateGiverTicket($intTicketSeq,$intBuyerSeq,$intReceverTicketSeq){
		$strQuery = sprintf("update ticket set modify_date=now(), use_date=now(), use_flg=1, ticket_status=5, ticket_remaining_timestamp=0, start_date=null, expiration_date=null, recever_ticket_seq=%d  where seq=%d and buyer_seq=%d",$intReceverTicketSeq,$intTicketSeq,$intBuyerSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function updateReceverTicket($intTicketRemainingTimestamp,$strStartDate,$strExpirationDate,$intGiverTicketSeq,$intTicketSeq,$intBuyerSeq,$intTicketStatus){
		$strQuery = sprintf("update ticket set modify_date=now(), ticket_remaining_timestamp=%d, start_date='%s', expiration_date='%s', giver_ticket_seq=%d, ticket_status=%d  where seq=%d and buyer_seq=%d",$intTicketRemainingTimestamp,$strStartDate,$strExpirationDate,$intGiverTicketSeq,$intTicketStatus,$intTicketSeq,$intBuyerSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function applyTicketToStudent($intStudentSeq,$srtStartDate,$strExpirationDate,$intTicketSeq,$intBuyerSeq){
		$strQuery = sprintf("update ticket set student_seq=%d,modify_date=now(),use_date=now(),use_flg=1,ticket_status=2,start_date='%s',expiration_date='%s' where seq=%d and buyer_seq=%d and delete_flg=0",$intStudentSeq,$srtStartDate,$strExpirationDate,$intTicketSeq,$intBuyerSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function deleteTicket($intTicketSeq){
		$strQuery = sprintf("update ticket set delete_flg=1,modify_date=now() where seq=%d",$intTicketSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function getTicket($intTicketSeq=null,$intBuyerSeq=null,$intStudentSeq=null){
		$strQuery = sprintf("select * from ticket where delete_flg=0 ");
		if($intTicketSeq){
			$strQuery .= sprintf(" and seq=%d",$intTicketSeq);
		}
		if($intBuyerSeq){
			$strQuery .= sprintf(" and buyer_seq=%d",$intBuyerSeq);
		}
		if($intStudentSeq){
			$strQuery .= sprintf(" and Student_seq=%d",$intStudentSeq);
		}
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);
	}
	public function getTickets($intBuyerSeq=null,$intTicketType=null,$intUseFlg=null,$arrSort=null,$intLatestFlg=null,$intTicketStatus=null){
		$strQuery = sprintf("select * from ticket where delete_flg=0 ");
		if($intBuyerSeq){
			$strQuery .= sprintf(" and buyer_seq=%d",$intBuyerSeq);
		}
		if($intTicketType){
			$strQuery .= sprintf(" and ticket_type=%d",$intTicketType);
		}
		if($intTicketStatus){
			$strQuery .= sprintf(" and ticket_status=%d",$intTicketStatus);
		}
		if(!is_null($intUseFlg)){
			$strQuery .= sprintf(" and use_flg=%d",$intUseFlg);
		}
		if($intLatestFlg){
			$strQuery .= sprintf(" and purchase_date >= DATE_ADD(NOW(), INTERVAL -7 DAY) ");
		}
		if(is_array($arrSort)){
			$strQuery .= " order by ";
			while ($sortValue = current($arrSort)) {
				$strQuery .= sprintf(" %s %s ",key($arrSort),$sortValue);
			    next($arrSort);
			}
		}
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);
	}
	public function getLatestTickets($intBuyerSeq=null,$intTicketType=null,$intUseFlg=null,$arrSort=null){
		$this->getTickets($intBuyerSeq,$intTicketType,$intUseFlg,$arrSort,1);
	}
	public function getAppliedTicket($intMemberSeq,$strMemberType,$arrTicketStatus=null,$intUseableFlg=0,$intDeleteFlg=0){
		if($strMemberType=='T'){
			$strQuery = sprintf("select * from ticket where teacher_seq=%d ",$intMemberSeq);
		}else{
			$strQuery = sprintf("select * from ticket where student_seq=%d ",$intMemberSeq);
		}
		if(is_array($arrTicketStatus)){
			$strQuery .= " and ticket_status IN (".join(',', $arrTicketStatus).") ";
		}
		if($intUseableFlg){
			$strQuery .= " and expiration_date>now() ";
		}
		$strQuery .= sprintf(" and delete_flg=%d ",$intDeleteFlg);
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);
	}
	public function getAppliedTicketToStudent($intStudentSeq,$intDeleteFlg=0,$intBuyerSeq=null){
		$strQuery = sprintf("select * from ticket where student_seq=%d and delete_flg=%d ",$intStudentSeq,$intDeleteFlg);
		if($intBuyerSeq){
			$strQuery .= sprintf(" and buyer_seq=%d ",$intBuyerSeq);
		}
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);
	}
	public function getTicketsByMemberSeq($intMemberSeq,$strMemberType){
		$strQuery = sprintf("select * from ticket where student_seq=%d and delete_flg=%d ",$intStudentSeq,$intDeleteFlg);
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);
	}
	public function getTicketsCount($intMemberSeq,$intUseFlg=null,$intDeleteFlg=null,$intTicketType=null){
		$strQuery = sprintf("select count(*) cnt from ticket where buyer_seq=%d ",$intMemberSeq);
		if(!is_null($intUseFlg)){
			$strQuery .= sprintf(" and use_flg=%d ",$intUseFlg);
		}
		if(!is_null($intDeleteFlg)){
			$strQuery .= sprintf(" and delete_flg=%d ",$intStudentSeq);
		}
		if(!is_null($intTicketType)){
			$strQuery .= sprintf(" and ticket_type=%d ",$intTicketType);
		}
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	/*
	public function setTicketHistory($intMemberSeq,$intPurchaseSeq,$intTicketSeq,$intHistoryStatus,$intStudentSeq=null,$intTeacherSeq=null,$intTicketOrignalDay,$intTicketRemainingTimestamp,$strStartDate=null,$strExpiratoinDate=null,$intGiverTicketSeq=null,$intReceverTicketSeq=null){
		$strQuery = sprintf("INSERT INTO ticket_history (action_user_seq, purchase_seq, ticket_seq, history_status, create_date, student_seq, teacher_seq, ticket_orignal_day, ticket_remaining_timestamp, start_date, expiration_date, giver_ticket_seq, recever_ticket_seq)
					VALUES (%d, %d, %d, %d, now(), %d, %d, %d, %d, '%s', '%s', %d, %d, %d, %d)",$intMemberSeq,$intPurchaseSeq,$intTicketSeq,$intHistoryStatus,$intStudentSeq,$intTeacherSeq,$intTicketOrignalDay,$intTicketRemainingTimestamp,$strStartDate,$strExpiratoinDate,$intGiverTicketSeq,$intReceverTicketSeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		$intTicketPurchaseHistorySeq = mysql_insert_id($this->resTicketDB->res_DB);
		return($boolResult);
	}
	*/
	public function setTicketHistory($intActionUserSeq,$intTicketSeq,$intHistoryStatus){
		$strQuery = sprintf("INSERT INTO ticket_history (action_user_seq, purchase_seq, ticket_seq, history_status, create_date, student_seq, teacher_seq, ticket_orignal_day, ticket_remaining_timestamp, start_date, expiration_date, giver_ticket_seq, recever_ticket_seq) 
							 SELECT %d,purchase_seq, seq, %d, now(), student_seq, teacher_seq, ticket_orignal_day, ticket_remaining_timestamp, start_date, expiration_date, giver_ticket_seq, recever_ticket_seq 
							 FROM ticket WHERE seq=%d",$intActionUserSeq,$intHistoryStatus,$intTicketSeq); 
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function setTicketPurchaseHistory($intBuyerSeq,$strBuyerType,$intTicketType,$intTicketCount,$intPrice,$floatDiscount,&$intTicketPurchaseHistorySeq=null){
		$strQuery = sprintf("INSERT INTO ticket_purchase_history (buyer_seq, buyer_type, ticket_type, purchase_date, ticket_count, price, discount) VALUES (%d, '%s', %d, now(), %d, %d, %f)",$intBuyerSeq,$strBuyerType,$intTicketType,$intTicketCount,$intPrice,$floatDiscount);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		$intTicketPurchaseHistorySeq = mysql_insert_id($this->resTicketDB->res_DB);
		return($boolResult);
	}
	public function updateTicketPurchaseHistory($intTicketPurchaseHistorySeq){
		$strQuery = sprintf("update ticket_purchase_history set modify_date=now() where modify_date=now()");
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function deleteTicketPurchaseHistory($intTicketPurchaseHistorySeq){
		$strQuery = sprintf("update ticket_purchase_history set modify_date=now(),delete_flg=1 where seq=%d",$intTicketPurchaseHistorySeq);
		$boolResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($boolResult);
	}
	public function getTicketPurchaseHistory($intBuyerSeq=null,$intTicketPurchaseHistorySeq=null,$intTicketType=null,$intLatestFlg=null,$intLatestDay=1,$intDeleteFlg=0,$arrSort=null){
		$strQuery = sprintf("select * from ticket_purchase_history where delete_flg=%d ",$intDeleteFlg);
		if($intBuyerSeq){
			$strQuery .= sprintf(" and buyer_seq=%d ",$intBuyerSeq);
		}
		if($intTicketPurchaseHistorySeq){
			$strQuery .= sprintf(" and seq=%d ",$intTicketPurchaseHistorySeq);
		}
		if($intTicketType){
			$strQuery .= sprintf(" and ticket_type=%d ",$intTicketType);
		}
		if($intLatestFlg){
			$strQuery .= sprintf(" and purchase_date >= DATE_ADD(NOW(), INTERVAL -".$intLatestDay." DAY) ");
		}
		if(is_array($arrSort)){
			$strQuery .= " order by ";
			while ($sortValue = current($arrSort)) {
				$strQuery .= sprintf(" %s %s ",key($arrSort),$sortValue);
			    next($arrSort);
			}
		}
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		return($arrResult);		
	}
	public function getLatestPurchaseTicket($intBuyerSeq=null,$intTicketType=null,$intLatestFlg=null,$intLatestDay=1,$intDeleteFlg=0,$arrSort=null){
		$arrResult = $this->getTicketPurchaseHistory($intBuyerSeq,null,$intTicketType,$intLatestFlg,$intLatestDay,0,$arrSort);
		return $arrResult;
	}
	public function checkTicketUseAble($intTicketSeq){
		$strQuery = sprintf("select * from ticket where delete_flg=0 and seq=%d and use_flg=0",$intTicketSeq);
		$arrResult = $this->resTicketDB->DB_access($this->resTicketDB,$strQuery);
		$boolResult = false;
		if(count($arrResult)){
			$boolResult = true;
		}
		return($boolResult);		
	}
}
?>