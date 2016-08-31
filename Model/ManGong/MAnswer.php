<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Answer.php");

class MAnswer extends Answer{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resAnswerDB = $resProjectDB;
	}
	public function setUserAnswer($intMemberSeq,$intTestsSeq,$intQuestionSeq,$questionAnswer,$userAnswer,$result_flg,$strUserName,$sex,$intScore){
		$strQuery = sprintf("
			INSERT INTO user_answer
			(user_seq,test_seq,question_seq,question_answer,user_answer,result_flg,create_date,user_name,sex,score,record_seq)
			select %d,%d,%d,'%s','%s',%d,now(),'%s','%s',%d,seq from record where test_seq=%d and user_seq=%d and testing_time is null
			",$intMemberSeq,$intTestsSeq,$intQuestionSeq,$questionAnswer,quote_smart($userAnswer),$result_flg,$strUserName,$sex,$intScore,$intTestsSeq,$intMemberSeq);
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		if($boolResult){
			$mixReturn = mysql_insert_id($this->resAnswerDB->res_DB);
		}else{
			$mixReturn = $boolResult;
		}
		return($mixReturn);		
	}
	public function setUserAnswerDiscus($intUserAnswerSeq,$intTestSeq,$intRecordSeq,$intQuestionSeq,$intMemberSeq,$strUserAnswer){
		$strQuery = sprintf(
				"insert into user_answer_discus set user_answer_seq=%d,test_seq=%d,record_seq=%d,question_seq=%d,user_seq=%d,discus_answer='%s'",
				$intUserAnswerSeq,
				$intTestSeq,
				$intRecordSeq,
				$intQuestionSeq,
				$intMemberSeq,
				quote_smart($strUserAnswer));
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);		
	}
	public function getUserWrongAnswerInTests($intMemberSeq,$intTestsSeq,$strQuestoins=null){
// 		$strQuery = sprintf("SELECT ua.* FROM user_answer AS ua WHERE ua.user_seq=%d and ua.test_seq=%d AND ua.result_flg=0 GROUP BY ua.question_seq ORDER BY ua.record_seq DESC,ua.seq",$intMemberSeq,$intTestsSeq);
		if($this->resTestsDB->DB_name=="db_idgkr_quiz"){
			if($strQuestoins){
				$strQuery = sprintf(" SELECT * FROM (SELECT ua.* FROM user_answer AS ua WHERE ua.user_seq=%d AND ua.test_seq=%d AND ua.question_seq IN (%s) ORDER BY ua.record_seq DESC) AS al GROUP BY question_seq ORDER BY record_seq DESC,question_seq asc ",$intMemberSeq,$intTestsSeq,$strQuestoins);
			}else{
				$strQuery = sprintf(" SELECT ua.* 
									FROM user_answer AS ua 
									WHERE ua.user_seq=%d
									AND ua.test_seq=%d 
									AND (ua.result_flg=0 OR ua.score<(SELECT question_score 
													FROM test_question_list 
													WHERE test_seq=ua.test_seq 
													AND question_seq=ua.question_seq)
									AND ua.record_seq = (SELECT record_seq FROM user_answer WHERE user_seq=%d AND test_seq=%d ORDER BY record_seq DESC LIMIT 1 ) ",$intMemberSeq,$intTestsSeq,$intMemberSeq,$intTestsSeq);
			}
		}else{
			if($strQuestoins){
				$strQuery = sprintf("SELECT * FROM (SELECT ua.* FROM user_answer AS ua WHERE ua.user_seq=%d AND ua.test_seq=%d AND ua.question_seq IN (%s) ORDER BY ua.record_seq DESC) AS al GROUP BY question_seq ORDER BY record_seq DESC,question_seq asc",$intMemberSeq,$intTestsSeq,$strQuestoins);
			}else{
				$strQuery = sprintf("SELECT * FROM (SELECT ua.* FROM user_answer AS ua WHERE ua.user_seq=%d AND ua.test_seq=%d AND (ua.result_flg=0 or ua.score<(select question_score from test_question_list where test_seq=ua.test_seq and question_seq=ua.question_seq)) ORDER BY ua.record_seq DESC) AS al GROUP BY question_seq ORDER BY record_seq DESC,question_seq asc",$intMemberSeq,$intTestsSeq);
			}
		}
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function getUserLastAnswer($intMemberSeq,$intTestsSeq){
		$strQuery = sprintf("select * from user_answer where user_seq=%d and test_seq=%d and record_seq in (select seq from record where user_seq=%d and test_seq=%d",$intMemberSeq,$intTestsSeq);
	}
	public function getUserAnswerByTestsAndQuestion($intMemberSeq,$intRevision,$intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("select * from user_answer where user_seq=%d and revision=%d and test_seq=%d and question_seq=%d",$intMemberSeq,$intRevision,$intTestsSeq,$intQuestionSeq);
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function getUserAnswer($intMemberSeq,$intTestsSeq,$intQuestionCount=null,$intRecordSeq=null,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11,10,20)){
		$arrWhere = array();
		array_push($arrWhere,sprintf('ua.user_seq=%d',$intMemberSeq));
		array_push($arrWhere,sprintf('ua.test_seq=%d',$intTestsSeq));
		if($intRecordSeq){
			array_push($arrWhere,sprintf('ua.record_seq=%d',$intRecordSeq));
		}else{
			array_push($arrWhere,sprintf('ua.record_seq=(SELECT MIN(record_seq) FROM user_answer WHERE user_seq=%d AND test_seq=%d and delete_flg=0)',$intMemberSeq,$intTestsSeq));
		}
		if($intQuestionCount){
			$strQuery = sprintf("select ua.*,ql.order_number from user_answer as ua left join test_question_list as ql on ua.test_seq=ql.test_seq and ua.question_seq=ql.question_seq LEFT JOIN question q ON ql.question_seq=q.seq  where ".join(" and ",$arrWhere)." and q.question_type in (".join(",",$arrQuestionType).") order by ql.order_number asc limit 0,%d",$intQuestionCount);
		}else{
			$strQuery = sprintf("select ua.*,ql.order_number from user_answer as ua left join test_question_list as ql on ua.test_seq=ql.test_seq and ua.question_seq=ql.question_seq LEFT JOIN question q ON ql.question_seq=q.seq  where ".join(" and ",$arrWhere)." and q.question_type in (".join(",",$arrQuestionType).") order by ql.order_number asc");
		}
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function getUserAnswerByAnswerSeq($strMemberSeq,$mixAnswerSeq){
		include("Model/ManGong/SQL/MySQL/MAnswer/getUserAnswerByAnswerSeq.php");
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function getUserAnswerTotal($intMemberSeq,$intTestsSeq,$intRecodeSeq=0,$intQuestionSeq=null){
		/*
		if($intRecodeSeq){
			$strQuery = sprintf("select *,SUM(result_flg*score) AS user_score, SUM(score) AS total_score, count(seq) as total_count, SUM(result_flg) as right_count from user_answer where user_seq=%d and test_seq=%d and record_seq=%d",$intMemberSeq,$intTestsSeq,$intRecodeSeq);
		}else{
			$strQuery = sprintf("select *,SUM(result_flg*score) AS user_score, SUM(score) AS total_score, count(seq) as total_count, SUM(result_flg) as right_count from user_answer where user_seq=%d and test_seq=%d",$intMemberSeq,$intTestsSeq);
		}
		*/
		if($intRecodeSeq){
			//$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.score=QL.question_score,1,0)) AS right_count
			$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.result_flg,1,0)) AS right_count
					FROM user_answer AS ua
					LEFT JOIN test_question_list AS QL ON ua.test_seq=QL.test_seq AND ua.question_seq=QL.question_seq
					WHERE ua.user_seq=%d AND ua.test_seq=%d AND record_seq=%d",$intMemberSeq,$intTestsSeq,$intRecodeSeq);
		}else{
			//$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.score=QL.question_score,1,0)) AS right_count
			$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.result_flg=1,1,0)) AS right_count
					FROM user_answer AS ua
					LEFT JOIN test_question_list AS QL ON ua.test_seq=QL.test_seq AND ua.question_seq=QL.question_seq
					WHERE ua.user_seq=%d AND ua.test_seq=%d",$intMemberSeq,$intTestsSeq);			
		}		
		if($intQuestionSeq){
			$strQuery .= sprintf(" AND ua.question_seq=%d ",$intQuestionSeq);
		}
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function setUserAnswerLog($intMemberSeq,$intTestsSeq,$intQuestionSeq,$userAnswer,$userAnswerDiscus,$intUpdateFlg=0){
		if($intUpdateFlg){
			$strQuery = sprintf("update user_answer_log set user_answer='%s',discus_answer='%s' where user_seq=%d and test_seq=%d and question_seq=%d",quote_smart($userAnswer),quote_smart($userAnswerDiscus),$intMemberSeq,$intTestsSeq,$intQuestionSeq);
		}else{
			$strQuery = sprintf("INSERT INTO user_answer_log(user_seq,test_seq,question_seq,create_date,user_answer,discus_answer) values(%d,%d,%d,now(),'%s','%s')",$intMemberSeq,$intTestsSeq,$intQuestionSeq,quote_smart($userAnswer),quote_smart($userAnswerDiscus));
		}
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);		
	}
	public function getUserAnswerLog($intMemberSeq,$intTestsSeq=null,$intQuestionSeq=null,$intDistinctFlg=0){
		if($intDistinctFlg){
			$strQuery = sprintf("select distinct(test_seq),* from user_answer_log where user_seq=%d ",$intMemberSeq,$intTestsSeq);
		}else{
			$strQuery = sprintf("select * from user_answer_log where user_seq=%d ",$intMemberSeq);
		}
		
		if(!is_null($intTestsSeq)){
			$strQuery .= sprintf(" and test_seq=%d ",$intTestsSeq);
		}
		if(!is_null($intQuestionSeq)){
			$strQuery .= sprintf(" and question_seq=%d ",$intQuestionSeq);
		}
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	public function deleteUserAnswerLog($intMemberSeq,$intTestsSeq){
		$strQuery = sprintf("delete from user_answer_log where user_seq=%d and test_seq=%d",$intMemberSeq,$intTestsSeq);
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);		
	}
}
?>