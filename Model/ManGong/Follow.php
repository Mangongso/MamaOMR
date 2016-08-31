<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Follow{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resFollowDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setFollow($intMemberSeq,$intWriterSeq){
		$strQuery = sprintf("insert into follow set user_seq=%d,writer_seq=%d,follow_date=now()",$intMemberSeq,$intWriterSeq);
		$boolResult = $this->resFollowDB->DB_access($this->resFollowDB,$strQuery);
		return($boolResult);	
	}	
	public function deleteFollow($intMemberSeq,$intWriterSeq){
		$strQuery = sprintf("delete from follow where user_seq=%d and writer_seq=%d",$intMemberSeq,$intWriterSeq);
		$arrReturn = $this->resFollowDB->DB_access($this->resFollowDB,$strQuery);
		return($arrReturn);	
	}	
	public function getFollow($arrSearch){
		$strQuery = sprintf("select * from follow ");
		include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
		$strQuery .= sprintf(" where ".join(" and ", $arrWhereQuery));
		$arrReturn = $this->resFollowDB->DB_access($this->resFollowDB,$strQuery);
		return($arrReturn);	
	}	
	public function getMakerByFollowList($intUserSeq){
		$strQuery = sprintf("SELECT * FROM 
							(SELECT mb.member_seq,mb.name,mb.nickname 
							FROM member_basic_info mb ,member_extend_info me 
							WHERE mb.member_seq=me.member_seq 
							AND me.member_type='T' 
							AND mb.auth_flg=1 
							AND mb.del_flg='0') maker 
						LEFT OUTER JOIN
							(SELECT writer_seq,
								COUNT(writer_seq) AS follow_cnt, 
								(SELECT COUNT(*) FROM follow AS sf WHERE sf.user_seq=%d AND sf.writer_seq=f.writer_seq ) AS follow_flg,
								(SELECT COUNT(*) 
								FROM test 
								WHERE delete_flg=0 
								AND writer_seq=f.writer_seq ) AS quiz_cnt 
							FROM follow f 
							GROUP BY writer_seq) fw
						ON maker.member_seq=fw.writer_seq
						ORDER BY follow_flg DESC , follow_cnt DESC",$intUserSeq);
		//print_r($strQuery);
		$arrReturn = $this->resFollowDB->DB_access($this->resFollowDB,$strQuery);
		return($arrReturn);	
	}	
	public function getUserByFollowList($intWriterSeq){
		$strQuery = sprintf("SELECT *  FROM 
							(SELECT user_seq,
								writer_seq,			
								(SELECT COUNT(*) 
								FROM record
								WHERE user_seq=f.user_seq AND revision=1 AND test_seq IN (SELECT seq FROM test WHERE writer_seq=f.writer_seq) ) AS quiz_cnt
							FROM follow f WHERE writer_seq=%d
							GROUP BY user_seq) fw
							LEFT JOIN
							(SELECT mb.member_seq,mb.name,mb.nickname 
							FROM member_basic_info mb ,member_extend_info me 
							WHERE mb.member_seq=me.member_seq 
							AND me.member_type='S' 
							AND mb.del_flg='0') mem		
						ON fw.user_seq=mem.member_seq",$intWriterSeq);
		//print_r($strQuery);
		$arrReturn = $this->resFollowDB->DB_access($this->resFollowDB,$strQuery);
		return($arrReturn);	
	}	
	
	
}
?>