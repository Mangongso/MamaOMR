<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class SMS{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resSMSDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getSMSHistoryCount($intMemberSeq){
		$strQuery = sprintf("select count(*) cnt from sms_history where member_seq=%d and send_date IS NULL ",$intMemberSeq);
		$arrResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($arrResult[0]["cnt"]);
	}
	public function setSMSHistory($intMemberSeq,$strCallNo,$strMessage){
		$strQuery = sprintf("INSERT INTO sms_history set member_seq=%d,create_date=now()",$intMemberSeq);
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
	public function updateSMSHistory($intMemberSeq,$strCallNo,$strMessage){
		$strQuery = sprintf("
			UPDATE sms_history set message='%s', target_cphone='%s', send_date=now() 
			where seq=(SELECT * FROM (SELECT MIN(seq) seq FROM sms_history WHERE member_seq=%d AND send_date IS NULL) AS sh)"
		,$strMessage,$strCallNo,$intMemberSeq);
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
	public function getSMSCronSenderList($intSendType){
		$strQuery = sprintf("SELECT * FROM sms_cron_sender_list where delete_flg=0 and send_type=%d ",$intSendType);
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
	public function getSMSCronReportTargetAllList($intAfterDay=3){
		$strQuery = sprintf("
			SELECT * FROM 
				(SELECT r.seq,r.subject,TO_DAYS(finish_date) - TO_DAYS(NOW()) AS remain_day,sender_name
					FROM 
					(SELECT * FROM report WHERE state=1 AND finish_date>=NOW() AND finish_date<=DATE_ADD(NOW(),INTERVAL +%d DAY) ) r
					,(SELECT * FROM sms_cron_sender_list WHERE delete_flg=0 AND send_type=1) sc
					WHERE writer_seq=sender_seq  
				) AS rp
				,(SELECT * FROM report_join_user WHERE user_status_flg<2 AND delete_flg=0 ) AS rj
				,(SELECT mb.member_seq,mb.name,me.cphone FROM member_basic_info mb, member_extend_info me WHERE mb.member_seq=me.member_seq AND mb.del_flg='0') mbe
			WHERE rp.seq = rj.report_seq
			AND rj.user_seq=mbe.member_seq ",$intAfterDay);
		//print_r($strQuery);
		//exit;
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
	public function getSMSCronTestTargetAllList($intAfterDay=3){
		$strQuery = sprintf("
			SELECT * FROM 
				(SELECT test.seq,test.subject,TO_DAYS(finish_date) - TO_DAYS(NOW()) AS remain_day,sender_name
					FROM (SELECT s.seq,s.writer_seq,s.subject,sp.state,sp.start_date,sp.finish_date 
						FROM test s,test_published sp 
						WHERE s.seq=sp.test_seq 
						AND sp.test_prog_flg=2
						AND sp.state=2
						AND sp.finish_date>=NOW() 
						AND sp.finish_date<=DATE_ADD(NOW(),INTERVAL +%d DAY)
						AND s.delete_flg=0
					     ) test
					,(SELECT * FROM sms_cron_sender_list WHERE delete_flg=0 AND send_type=2) sc
				  WHERE test.writer_seq=sc.sender_seq) AS sender
				,(SELECT * FROM test_join_user WHERE test_status_flg<2 AND delete_flg=0 ) AS sj
				,(SELECT mb.member_seq,mb.name,me.cphone FROM member_basic_info mb, member_extend_info me WHERE mb.member_seq=me.member_seq AND mb.del_flg='0') mbe
			WHERE sender.seq = sj.test_seq
			AND sj.user_seq=mbe.member_seq ",$intAfterDay);
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
	public function getSMSCronTicketTargetAllList($intAfterDay=3){
		$strQuery = sprintf("
			SELECT * FROM 
				(SELECT sc.sender_seq,tk.student_seq,TO_DAYS(tk.expiration_date) - TO_DAYS(NOW()) AS remain_day,sender_name FROM 
					(SELECT * FROM ticket 
					WHERE ticket_status IN (2,4) 
					AND delete_flg=0
					AND expiration_date>=NOW() 
					AND expiration_date<=DATE_ADD(NOW(),INTERVAL +%d DAY)) tk
					,(SELECT * FROM sms_cron_sender_list WHERE delete_flg=0 AND send_type=3) sc
				WHERE tk.buyer_seq=sc.sender_seq GROUP BY sender_seq) AS target
				,(SELECT mb.member_seq,mb.name,me.cphone FROM member_basic_info mb, member_extend_info me WHERE mb.member_seq=me.member_seq AND mb.del_flg='0') mbe
			WHERE target.sender_seq=mbe.member_seq ",$intAfterDay);
		$boolResult = $this->resSMSDB->DB_access($this->resSMSDB,$strQuery);
		return($boolResult);
	}
}
?>