<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Record{
	private $resRecordDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resRecordDB = $resProjectDB;
	}
	public function __destruct(){}

	public function setUserRecord($intMemberSeq,$intTestsSeq,$strUserName,$strSex,$intUserScore,$intTotalScore,$intRightCount,$intWrongCount,&$intRecordSeq=0){
// 		$strRevisionQuery = sprintf("SELECT IFNULL(MAX(revision) ,1) FROM record WHERE user_seq=%d and test_seq=%d",$intMemberSeq,$intTestsSeq);
// 		echo $strQuery = sprintf("insert into record set user_seq=%d,revision=(%s),test_seq=%d,user_name='%s',sex='%s',user_score=%d,total_score=%d,create_date=now(),right_count=%d,wrong_count=%d",$intMemberSeq,$strRevisionQuery,$intTestsSeq,$strUserName,$strSex,$intUserScore,$intTotalScore,$intRightCount,$intWrongCount);
		$strQuery = sprintf(
				"INSERT INTO record (user_seq,revision,test_seq,user_name,sex,user_score,total_score,create_date,right_count,wrong_count)
				SELECT %d,IFNULL(MAX(revision)+1 ,1),%d,'%s','%s',%d,%d,now(),%d,%d FROM record WHERE user_seq=%d AND test_seq=%d",
				$intMemberSeq,$intTestsSeq,$strUserName,$strSex,$intUserScore,$intTotalScore,$intRightCount,$intWrongCount,$intMemberSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		$intRecordSeq = mysql_insert_id($this->resRecordDB->res_DB);
		return($arrResult);
	}
	public function getLastRecord($intMemberSeq,$intTestsSeq){
		$strQuery = sprintf("select * from record where user_seq=%d and test_seq=%d ORDER BY revision DESC",$intMemberSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function getLastRecordSeq($intMemberSeq,$intTestsSeq){
		$strQuery = sprintf("select seq from record where user_seq=%d and test_seq=%d and testing_time is null",$intMemberSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult[0]['seq']);
	}
	public function checkNotFinishedUserRecord($intMemberSeq,$intTestsSeq){
		$strQuery = sprintf("select count(*) as cnt from record where user_seq=%d and test_seq=%d and testing_time is null",$intMemberSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		if($arrResult[0]['cnt']>0){
			$boolReturn = true;
		}else{
			$boolReturn = false;
		}
		return($boolReturn);
	}
	public function getMemberRecords($mixMemberSeq,$intTestsSeq=null,$intRevisionFlg=null,$intSortFlg = 0){
		if(is_numeric($mixMemberSeq)){
			$strQuery = sprintf("select * from record where user_seq=%d ",$mixMemberSeq);
		}else{
			$strQuery = sprintf("select * from record where md5(user_seq)='%s' ",$mixMemberSeq);
		}
		
		if(!is_null($intTestsSeq)){
			$strQuery .= sprintf(" and test_seq=%d ",$intTestsSeq);
		}
		if($intRevisionFlg){
			$strQuery .= sprintf(" and revision=%d ",$intRevisionFlg);
		}
		if(!$intSortFlg){
			$strQuery .= sprintf(" order by revision");
		}else{
			$strQuery .= sprintf(" order by revision DESC");
		}
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function getMemberRecordsCount($intMemberSeq,$intTestsSeq=null){
		$strQuery = sprintf("select count(*) as cnt from record where user_seq=%d ",$intMemberSeq);
		if(!is_null($intTestsSeq)){
			$strQuery .= sprintf(" and test_seq=%d ",$intTestsSeq);
		}
		$strQuery .= sprintf(" order by revision ");
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	public function updateUserRecord($intMemberSeq,$intTestsSeq,$intUserScore,$intRightCount,$intWrongCount,$testingTime,$intStartDate=null,$intEndDate=null){
		$strQuery = sprintf("update record set user_score=%d,modify_date=now(),right_count=%d,wrong_count=%d,testing_time='%s',start_date='%s',end_date='%s' where user_seq=%d and test_seq=%d and testing_time is null",$intUserScore,$intRightCount,$intWrongCount,$testingTime,$intStartDate,$intEndDate,$intMemberSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function updateUserRecordByTeacher($intStudentSeq,$intTestsSeq,$intRecordSeq,$intUserScore,$intRightCount,$intWrongCount){
		$strQuery = sprintf("update record set user_score=%d,modify_date=now(),right_count=%d,wrong_count=%d where seq=%d and user_seq=%d and test_seq=%d",$intUserScore,$intRightCount,$intWrongCount,$intRecordSeq,$intStudentSeq,$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function initRecord($intUserSeq,$intTestsSeq,$intReportSeq=null){
		//delete user_answer
		$strQuery = sprintf("DELETE from user_answer where user_seq=%d and test_seq=%d",$intUserSeq,$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		//delete user_answer_discus
		$strQuery = sprintf("DELETE from user_answer_discus where user_seq=%d and test_seq=%d",$intUserSeq,$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		//delete record
		$strQuery = sprintf("DELETE from record where user_seq=%d and test_seq=%d and revision NOT IN(1)",$intUserSeq,$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		//update record revision=1
		$strQuery = sprintf("update record set user_score=0,right_count=0,wrong_count=0,testing_time=null,start_date=null,end_date=null,modify_date=now() where user_seq=%d and test_seq=%d and revision IN(1)",$intUserSeq,$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		//update test_join_user
		$strQuery = sprintf("update test_join_user set join_date=null,start_date=null,end_date=null,test_status_flg=0,read_flg=0 where user_seq=%d and test_seq=%d",$intUserSeq,$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		
		if(!is_null($intReportSeq)){
			$strQuery = sprintf("update report_join_user set start_date=null,end_date=null,user_status_flg=0 where user_seq=%d and report_seq=%d",$intUserSeq,$intReportSeq);
			$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		}
		return($arrResult);
	}
	public function getTotalUserRecord($intTestsSeq=null,$arrTestsSeq=array(),$strTestsSeqGroup=null,$strUserSeq=null){
		if(count($arrTestsSeq)){
			$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(distinct(user_seq)) as user_count from record where test_seq in (".join(',',$arrTestsSeq).") and revision=1");
		}else if(!is_null($strTestsSeqGroup)){
			$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(distinct(user_seq)) as user_count from record where test_seq in (".$strTestsSeqGroup.") and revision=1");
		}else{
			$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(user_seq) as user_count from record where test_seq=%d and revision=1",$intTestsSeq);
		}
		if(!is_null($strUserSeq)){
			$strQuery .= sprintf(" and md5(user_seq)='%s' ",$strUserSeq);
		}
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTotalUserRecordBySex($intTestsSeq,$strSex,$intRevision=null){
		$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(user_seq) as user_count,SUM(IF(ISNULL(end_date),0,1)) AS user_complete_count from record where test_seq=%d and sex='%s'",$intTestsSeq,$strSex);
		if($intRevision){
			$strQuery .= sprintf(" and revision=%d",$intRevision);
		}
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTotalRepeatTestCount($intTestsSeq){
		$strQuery = sprintf("select count(*) as cnt from record where test_seq=%d and revision<>1",$intTestsSeq);
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getTestsUserRecord($intTestsSeq,$intMemberSeq,$strTestsSeqGroup=null){
		// $strQuery = sprintf("select COALESCE(user_score,0) AS user_score, COALESCE(total_score,0) AS total_score,testing_time,right_count,wrong_count from record where test_seq=%d and user_seq=%d and revision = (select max(revision) from record where test_seq=%d and user_seq=%d)",$intTestsSeq,$intMemberSeq,$intTestsSeq,$intMemberSeq);
		if(!is_null($strTestsSeqGroup)){
			$strQuery = sprintf("select seq,COALESCE(user_score,0) AS user_score, COALESCE(total_score,0) AS total_score,testing_time,right_count,wrong_count from record where test_seq=%d and user_seq in ('%s') and revision = 1",$strTestsSeqGroup,$intMemberSeq);
		}else{
			$strQuery = sprintf("select seq,COALESCE(user_score,0) AS user_score, COALESCE(total_score,0) AS total_score,testing_time,right_count,wrong_count from record where test_seq=%d and user_seq=%d and revision = 1",$intTestsSeq,$intMemberSeq);
		}
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function TestsUserRecordByRecordSeq($intRecordSeq,$intTestsSeq,$intMemberSeq){
		$strQuery = sprintf("select * from record where seq=%d and test_seq=%d and user_seq=%d",$intRecordSeq,$intTestsSeq,$intMemberSeq);
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsRecord($intTestsSeq,$strOrderName=null,$strOrder='desc',$intRevision=null){
		$strQuery = sprintf("SELECT * FROM record WHERE test_seq=%d ",$intTestsSeq);
		if($intRevision){
			$strQuery .= sprintf(" AND revision=%d ",$intRevision);
		}
		if($strOrderName){
			$strQuery .= sprintf(" ORDER BY %s %s",$strOrderName,$strOrder);
		}
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsRecordRank($intTestsSeq,$intMemberSeq=null){
		$strQuery = sprintf("SELECT (SELECT COUNT(*)+1 FROM record WHERE user_score>re.user_score AND test_seq=%d AND revision=1) AS rank, re.*
							FROM record AS re
							WHERE re.test_seq=%d
							AND revision=1
							",$intTestsSeq,$intTestsSeq);
		if($intMemberSeq){
			$strQuery .= sprintf(" AND user_seq=%d ",$intMemberSeq);
		}
		$strQuery .= sprintf(" GROUP BY re.user_seq ORDER BY rank ASC");
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsLastRecordRank($intTestsSeq,$intMemberSeq=null){
		$strQuery = sprintf("SELECT (SELECT COUNT(*)+1 FROM record WHERE user_score>re.user_score AND test_seq=%d AND revision=1) AS rank, re.*
							FROM record AS re
							WHERE re.test_seq=%d
							AND revision=1
							",$intTestsSeq,$intTestsSeq);
		if($intMemberSeq){
			$strQuery .= sprintf(" AND user_seq=%d ",$intMemberSeq);
		}
		$strQuery .= sprintf(" GROUP BY re.user_seq ORDER BY rank ASC");
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	/* smart_omr tag별 결과 가져오기 */
	public function getTestsRecordReportByTags($intTestsSeq,$intRecordSeq){
		$strQuery = sprintf("SELECT *,COUNT(tag) tag_cnt,SUM(result_flg) corect_cnt,ROUND(SUM(result_flg)/COUNT(tag),2)*100 AS corect_percent FROM 
								(SELECT * FROM user_answer WHERE test_seq=%d AND delete_flg=0 AND record_seq=%d) ua_re,
								(SELECT qt.*,qsq.seq,qsq.question_type,qsq.example_type FROM question_tag qt,
									(SELECT q.* 
										FROM question q,
										(SELECT * FROM test_question_list WHERE test_seq=%d) sq
									WHERE q.seq=sq.question_seq) qsq
								WHERE qt.question_seq=qsq.seq) q_re
							WHERE ua_re.question_seq=q_re.seq GROUP BY tag
							",$intTestsSeq,$intRecordSeq,$intTestsSeq);
		//print_r($strQuery);exit;
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsFirstAnswerResult($intTestsSeq,$intQuestoinSeq){
		$strQuery = sprintf("SELECT SUM(IF(result_flg=0,1,0)) AS wrong_cnt , SUM(IF(result_flg=1,1,0)) AS right_cnt FROM user_answer
							WHERE test_seq=%d AND question_seq=%d
							AND record_seq IN (SELECT seq FROM record WHERE test_seq=%d AND revision=1)"
					,$intTestsSeq,$intQuestoinSeq,$intTestsSeq);
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsCurrentAnswerResult($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("SELECT SUM(IF(result_flg=0,1,0)) AS wrong_cnt , SUM(IF(result_flg=1,1,0)) AS right_cnt FROM user_answer
							WHERE test_seq=%d AND question_seq=%d"
					,$intTestsSeq,$intQuestionSeq);
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getQuestionExampleSelectCnt($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("SELECT SUM(IF(ISNULL(ua.user_answer),0,1)) as select_cnt,qe.example_number
							FROM (SELECT * FROM question_example WHERE question_seq=%d and delete_flg=0) qe LEFT OUTER JOIN
								(SELECT * FROM user_answer WHERE test_seq=%d AND question_seq=%d AND record_seq IN (SELECT seq FROM record WHERE test_seq=%d AND revision=1)) ua
							ON ua.user_answer = qe.seq
							GROUP BY qe.example_number"
					,$intQuestionSeq,$intTestsSeq,$intQuestionSeq,$intTestsSeq);
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function markingQuestion($intUserAnswerSeq, $intStudentSeq, $intTestSeq, $intRecordSeq, $intQuestionSeq, $intScore, $strAnswerComment){
		$boolResult = $this->updateAnswerComment($intUserAnswerSeq, $intStudentSeq,$strAnswerComment);
		if($boolResult){
			$strQuery = sprintf(
					"update user_answer set score=%d,result_flg=3 where seq=%d and user_seq=%d and test_seq=%d and record_seq=%d and question_seq=%d",
					$intScore,
					$intUserAnswerSeq,
					$intStudentSeq,
					$intTestSeq,
					$intRecordSeq,
					$intQuestionSeq
			);
			$boolReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
			return($boolReturn);
		}

	}
	public function updateAnswerComment($intUserAnswerSeq,$intStudentSeq,$strAnswerComment){
		$strQuery = sprintf(
				"update user_answer_discus set answer_comment='%s' where user_seq=%d and user_answer_seq=%d",
				trim($strAnswerComment),
				$intStudentSeq,
				$intUserAnswerSeq
		);
		$boolReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($boolReturn);
	}
	public function getTestsQuestionUserAnswer($intTestsSeq,$intMemberSeq=null,$strDivision=null){
		$strInQuery = sprintf("SELECT seq FROM record WHERE test_seq=%d AND revision=1",$intTestsSeq);
		if($intMemberSeq){
			$strInQuery .= sprintf(" AND user_seq=%d",$intMemberSeq);
		}
		if($strDivision=='tag'){
			$strQuery = sprintf("SELECT *, SUM(ua.result_flg) right_cnt,COUNT(tags) question_cnt
						FROM (SELECT * FROM user_answer WHERE record_seq IN (".$strInQuery.")) ua,
								test_question_list sq,
								question q
							WHERE ua.question_seq=q.seq
							AND sq.question_seq=q.seq
							GROUP BY tags ");
		}else{
			/*
			if($intMemberSeq){
				$strWhere = " AND ua.user_answer=qe.seq ";
			}else{
				$strWhere = " AND ua.question_answer=qe.seq ";
			}
			*/
			$strQuery = sprintf("SELECT *, COUNT(ua.question_seq) question_cnt, SUM(IF(ua.result_flg=0,1,0)) AS wrong_cnt, qe.example_number right_number
						FROM (SELECT * FROM user_answer WHERE record_seq IN (SELECT seq FROM record WHERE test_seq=%d AND revision=1)) ua
								LEFT OUTER JOIN question_example qe ON ua.question_answer=qe.seq,
								test_question_list sq,
								question q
							WHERE ua.question_seq=q.seq
							AND sq.question_seq=q.seq /* AND ua.question_answer=qe.seq */
							GROUP BY ua.question_seq
						 	ORDER BY wrong_cnt desc, question_number ASC " ,$intTestsSeq);
		}
		//print_r($strQuery);
		//print_r('</br>');
		//exit;
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsQuestionListByTopSub($intTestsSeq,$strDivison,$intLimitScore){
		if($strDivison=='top'){
			$strInQuery = sprintf("SELECT seq re_seq FROM record WHERE test_seq=%d AND revision=1 AND user_score >= %d",$intTestsSeq,$intLimitScore);
		}else{
			$strInQuery = sprintf("SELECT seq re_seq FROM record WHERE test_seq=%d AND revision=1 AND user_score <= %d",$intTestsSeq,$intLimitScore);
		}
		$strQuery = sprintf("SELECT *,COUNT(ua2.question_seq) question_cnt, SUM(IF(result_flg=0,1,0)) AS wrong_cnt, sq.question_number, q.tags, qe.example_number right_number
							FROM (SELECT * FROM user_answer ua1,
									(".$strInQuery.") re
								WHERE ua1.record_seq=re.re_seq) ua2,
								test_question_list sq,
								question q,
								question_example qe
							WHERE ua2.question_seq=q.seq
							AND sq.question_seq=q.seq
							AND ua2.question_answer=qe.seq
							GROUP BY ua2.question_seq
							ORDER BY wrong_cnt DESC ");
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	/* user answer */
	public function getTestsUserAnswer($intTestsSeq,$intMemberSeq,$intRevision=null,$intQuestionSeq=null){
		$strQuery = sprintf("SELECT * FROM user_answer ua, record r WHERE ua.record_seq=r.seq AND ua.test_seq=%d AND ua.user_seq=%d",$intTestsSeq,$intMemberSeq);
		if($intQuestionSeq){
			$strQuery .= sprintf(" and question_seq=%d",$intQuestionSeq);
		}
		if($intRevision){
			$strQuery .= sprintf(" and revision=%d",$intRevision);
		}
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	/* direct record */
	public function getDirectRecord($intTestsSeq){
		$strQuery = sprintf("SELECT dr_r.*,
								(SELECT GROUP_CONCAT(column_val SEPARATOR '||')
								FROM direct_record_cols AS dr_c
								WHERE rows_seq=dr_r.seq
								ORDER BY dr_c.head_flg DESC) AS col_val
							FROM direct_record_rows AS dr_r
							WHERE dr_r.test_seq=%d
							ORDER BY dr_r.head_flg DESC,dr_r.order_number",$intTestsSeq);
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function setDirectRecordRows($intTestsSeq,$intOrderNumber,$intHeadFlg,&$intRowSeq){
		$strQuery = sprintf("INSERT INTO direct_record_rows (test_seq, order_number, create_date, head_flg) VALUES (%d, %d, now(),%d)",$intTestsSeq, $intOrderNumber,$intHeadFlg);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		$intRowSeq = mysql_insert_id($this->resRecordDB->res_DB);
		return($intRowSeq);
	}
	public function deleteDirectRecordRows($intTestsSeq){
		$strQuery = sprintf("DELETE from direct_record_rows where test_seq=%d",$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	public function setDirectRecordCols($intTestsSeq,$intOrderNum,$intRowSeq,$arrValue=array(),$intHeadFlg=0){
		$arrSetValue = array();
		foreach($arrValue as $intKey=>$strValue){
			$strSetValue = sprintf("(%d, %d, %d, '%s', now(), %d)",$intRowSeq, $intTestsSeq, $intOrderNum, $strValue, $intHeadFlg);
			array_push($arrSetValue,$strSetValue);
		}
		$strQuery = "INSERT INTO direct_record_cols (rows_seq, test_seq, order_number, column_val, create_date, head_flg) VALUES ".join(',', $arrSetValue);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($boolResult);
	}
	public function deleteDirectRecordCols($intTestsSeq){
		$strQuery = sprintf("DELETE from direct_record_cols where test_seq=%d",$intTestsSeq);
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
}
?>