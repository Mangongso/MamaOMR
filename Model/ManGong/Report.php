<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Report {
	private $objPaging = null;
	private $resReportDB = null;
	private $resSearchDB = null;
	public function __construct($resProjectDB=null,$resSearchDB=null){
		$this->objPaging =  new Paging();
		$this->resReportDB = $resProjectDB;
		$this->resSearchDB = $resSearchDB;
	}
	public function __destruct(){}
	
	public function setReport($intWriterSeq,$intTestsSeq=null,$strReportTitle=null,$intGroupSeq,$intCategorySeq,$strStartDate=null,$strFinishDate=null,$strContents=null,&$intReportSeq=null,$intMasterSeq=null){
		/*
		 * 1.$intMasterSeq 가 null인 경우는 마스터가 없는경우 - submaster null
		 * 2.$intMasterSeq 가 null이 아닐 경우는 마스터가 있으면서 선택햇을경우 - submaster = 01920102  
		 * 3.$intMasterSeq 가 ""인 경우는 마스터있으면서 선택하지 않앗을경우 - - submaster = ''
		 * */
		if(!is_null($intMasterSeq)){
			if($intMasterSeq==''){
				$intSubMasterSeq = 0;
			}else{
				$intSubMasterSeq = $intWriterSeq;
				$intWriterSeq = $intMasterSeq;
			}
		}
		
		$strQuery = sprintf("insert into report set writer_seq=%d,test_seq=%d,subject='%s',user_group_seq=%d,category_seq=%d,start_date='%s',finish_date='%s',contents='%s',create_date=now(),modify_date=now()",$intWriterSeq,$intTestsSeq,quote_smart($strReportTitle),$intGroupSeq,$intCategorySeq,$strStartDate,$strFinishDate,quote_smart($strContents));
		if(!is_null($intSubMasterSeq)){
			$strQuery .= sprintf(" ,sub_master=%d ",$intSubMasterSeq);
		}
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		$intReportSeq = mysql_insert_id($this->resReportDB->res_DB);
		return($arrResult);		
	}
	public function updateReport($intReportSeq,$intTestsSeq=null,$strReportTitle=null,$intGroupSeq,$intCategorySeq=null,$strStartDate=null,$strFinishDate=null,$strContents=null,$intMasterSeq=null){
		/*
		 * 1.$intMasterSeq 가 null인 경우는 마스터가 없는경우 - submaster null
		 * 2.$intMasterSeq 가 null이 아닐 경우는 마스터가 있으면서 선택햇을경우 - submaster = 01920102  
		 * 3.$intMasterSeq 가 ""인 경우는 마스터있으면서 선택하지 않앗을경우 - - submaster = ''
		 * */
		if(!is_null($intMasterSeq)){
			if($intMasterSeq==''){
				$intSubMasterSeq = 0;
				$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
			}else{
				$intSubMasterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
				$intWriterSeq = $intMasterSeq;
			}
		}
		if(!is_null($intSubMasterSeq)){
			$strQuery = sprintf("update report set test_seq=%d,writer_seq=%d,sub_master=%d,subject='%s',user_group_seq=%d,category_seq=%d,start_date='%s',finish_date='%s',contents='%s',modify_date=now() where seq=%d",$intTestsSeq,$intWriterSeq,$intSubMasterSeq,quote_smart($strReportTitle),$intGroupSeq,$intCategorySeq,$strStartDate,$strFinishDate,quote_smart($strContents),$intReportSeq);
		}else{
			$strQuery = sprintf("update report set test_seq=%d,subject='%s',user_group_seq=%d,category_seq=%d,start_date='%s',finish_date='%s',contents='%s',modify_date=now() where seq=%d",$intTestsSeq,quote_smart($strReportTitle),$intGroupSeq,$intCategorySeq,$strStartDate,$strFinishDate,quote_smart($strContents),$intReportSeq);
		}
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult);		
	}
	public function deleteReport($intReportSeq){
		$strQuery = sprintf("update report set delete_flg=1,modify_date=now() where seq=%d",$intReportSeq);
		$boolResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($boolResult);		
	}
	public function getReport($intReportSeq,$passCheckReport=null){
		$strQuery = sprintf("select * from report where seq=%d ",$intReportSeq);
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		if(!$passCheckReport){
			switch($_SESSION[$_COOKIE['member_token']]['member_type']){
				case('T'):
					if($arrResult[0]['writer_seq'] != $_SESSION[$_COOKIE['member_token']]['member_seq'] && $arrResult[0]['sub_master'] != $_SESSION[$_COOKIE['member_token']]['member_seq'] ){
						header("HTTP/1.1 301 Moved Permanently");
						header('location:/');
						exit;
					}  
				break;
				case('S'):
					$boolResult = $this->checkMyReport($intReportSeq,$_SESSION[$_COOKIE['member_token']]['member_seq']);
					if(!$boolResult){
						header("HTTP/1.1 301 Moved Permanently");
						header('location:/');
						exit;
					}  
				break;
			}
		}
		return($arrResult);		
	}
	public function getReportByTestSeq($intTestSeq){
		$strQuery = sprintf("select * from report where test_seq=%d and delete_flg=0",$intTestSeq);
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult);		
	}
	public function getReportNextSeq($intReportSeq,$intMemberSeq,$intCategorySeq=null){
		$strQuery = sprintf("select * from report where seq<%d and writer_seq=%d and delete_flg=0",$intReportSeq,$intMemberSeq);
		if($intCategorySeq){
			$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " ORDER BY seq DESC LIMIT 0,1";
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['seq']);		
	}
	public function getReportPrevSeq($intReportSeq,$intMemberSeq,$intCategorySeq=null){
		$strQuery = sprintf("select * from report where seq>%d and writer_seq=%d and delete_flg=0",$intReportSeq,$intMemberSeq);
		if($intCategorySeq){
			$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " LIMIT 0,1";
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['seq']);		
	}
	public function getReportList($intTeacherSeq,$intCategorySeq=null,$intGroupSeq=null,&$arrPaging,$arrSearch=array(),$arrState=array()){
		//report paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getReportListCount($intTeacherSeq,$intCategorySeq,$intGroupSeq,$arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = sprintf("select *,IF(finish_date < NOW(),2,state) AS state from report where (writer_seq=%d or sub_master=%d) and delete_flg=0",$intTeacherSeq,$intTeacherSeq);
		//categyry is
		if($intCategorySeq){
			$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
		}
		if($intGroupSeq){
			$strQuery .= sprintf(" and user_group_seq=%d",$intGroupSeq);
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (subject like '%".$arrSearch['subject']."%' "." or contents like '%".$arrSearch['contents']."%')";
		}
		if(count($arrState)>0){
			$strQuery .= " and state in (".join(',',$arrState).") ";
		}
		$strQuery .= " order by seq DESC";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);		
	}
	public function getReportListCount($intTeacherSeq,$intCategorySeq=null,$intGroupSeq=null,$arrSearch=array()){
		$strQuery = sprintf("select count(*) as cnt from report where (writer_seq=%d or sub_master=%d) and delete_flg=0",$intTeacherSeq,$intTeacherSeq);
		if($intCategorySeq){
			$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
		}
		if($intGroupSeq){
			$strQuery .= sprintf(" and user_group_seq=%d",$intGroupSeq);
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (subject like '%".$arrSearch['subject']."%' "." or contents like '%".$arrSearch['contents']."%')";
		}
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getStudentReportList($intStudentSeq,$intCategorySeq,$mixTeacherSeq,$boolMD5=false,&$arrPaging,$arrSearch=array()){
		//report paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getStudentReportListCount($intStudentSeq,$intCategorySeq,$mixTeacherSeq,$boolMD5,$arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		switch($this->resSearchDB->DB_name){
			case('sphinx'):
				/* caution! match 사용시 ()괄호의 시작과 끝부분에 공백이 들어 가면 안됨. ex)match('만공') - true , match('만공 ') - false;  */
				$strSearch = '"'.$strSearch.'"';//sphinx 검색용
				$intStudentSeq = '"'.$intStudentSeq.'"';//sphinx 검색용
				$strQuery = sprintf("SELECT * FROM mg_report_student_list WHERE match('%s @student_seq %s",$strSearch,$intStudentSeq);
				if($mixTeacherSeq && $boolMD5){
					$strQuery .= sprintf(" @teacher_seq %s",$mixTeacherSeq);
				}else if($mixTeacherSeq && !$boolMD5){
					$strQuery .= sprintf(" @teacher_seq %s",$mixTeacherSeq);
				}
				$strQuery .= "') order by id DESC";
			break;
			default:
				$strQuery = sprintf("SELECT * FROM report WHERE (user_group_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0)",$intStudentSeq);
				$strQuery .= sprintf(" OR seq IN (SELECT report_seq FROM report_join_user WHERE user_seq=%d and delete_flg=0))",$intStudentSeq);
				if($mixTeacherSeq && $boolMD5){
					$strQuery .= sprintf(" AND md5(writer_seq)='%s' ",$mixTeacherSeq);
				}else if($mixTeacherSeq && !$boolMD5){
					$strQuery .= sprintf(" AND writer_seq=%d ",$mixTeacherSeq);
				}
				$strQuery .= sprintf(" AND state>0 AND delete_flg=0");
				 
				//categyry is
				if($intCategorySeq){
					$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
				}
				if(count($arrSearch)>0){
					$strQuery .= " and (subject like '%".$arrSearch['subject']."%' "." or contents like '%".$arrSearch['contents']."%')";
				}
				$strQuery .= " order by seq DESC";
			break;
		}
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		if($this->resSearchDB){
			$arrResult = $this->resSearchDB->DB_access($this->resSearchDB,$strQuery);
		}else{
			$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		}
		return($arrResult);		
	}
	public function getStudentReportListCount($intStudentSeq,$intCategorySeq,$mixTeacherSeq,$boolMD5=false,$arrSearch=array()){
		switch($this->resSearchDB->DB_name){
			case('sphinx'):
				/* caution! match 사용시 ()괄호의 시작과 끝부분에 공백이 들어 가면 안됨. ex)match('만공') - true , match('만공 ') - false;  */
				$strQuery = sprintf("SELECT * FROM mg_report_student_list WHERE match('%s @student_seq %s",$arrSearch['subject'],$intStudentSeq);
				if($mixTeacherSeq && $boolMD5){
					$mixTeacherSeq ='"'.$mixTeacherSeq.'"';
					$strQuery .= sprintf(" @teacher_seq %s",$mixTeacherSeq);
				}else if($mixTeacherSeq && !$boolMD5){
					$mixTeacherSeq ='"'.$mixTeacherSeq.'"';
					$strQuery .= sprintf(" @teacher_seq %s",$mixTeacherSeq);
				}
				$strQuery .= "')";
			break;
			default:
				$strQuery = sprintf("SELECT count(*) as cnt FROM report WHERE (user_group_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0)",$intStudentSeq);
				$strQuery .= sprintf(" OR seq IN (SELECT report_seq FROM report_join_user WHERE user_seq=%d and delete_flg=0))",$intStudentSeq);
				if($mixTeacherSeq && $boolMD5){
					$strQuery .= sprintf(" AND md5(writer_seq)='%s' ",$mixTeacherSeq);
				}else if($mixTeacherSeq && !$boolMD5){
					$strQuery .= sprintf(" AND writer_seq=%d ",$mixTeacherSeq);
				}
				$strQuery .= sprintf("  AND state>0 AND delete_flg=0 ");
				//categyry is
				if($intCategorySeq){
					$strQuery .= sprintf(" and category_seq=%d",$intCategorySeq);
				}
				if(count($arrSearch)>0){
					$strQuery .= " and (subject like '%".$arrSearch['subject']."%' "." or contents like '%".$arrSearch['contents']."%')";
				}
			break;
		}
		if($this->resSearchDB){
			$arrResult = $this->resSearchDB->DB_access($this->resSearchDB,$strQuery);
		}else{
			$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		}
		return($arrResult[0]['cnt']);		
	}
	public function getStudentReportNextSeq($intReportSeq,$intStudentSeq,$intCategorySeq=null){
		$strQuery = sprintf("SELECT * FROM (SELECT * FROM report WHERE seq<%d AND state>0 AND delete_flg=0) r,(SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) gl WHERE r.user_group_seq=gl.group_seq",$intReportSeq,$intStudentSeq);
		if($intCategorySeq){
			$strQuery .= sprintf(" and r.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " ORDER BY r.seq DESC LIMIT 0,1";
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['seq']);		
	}
	public function getStudentReportPrevSeq($intReportSeq,$intStudentSeq,$intCategorySeq=null){
		$strQuery = sprintf("SELECT * FROM (SELECT * FROM report WHERE seq>%d AND state>0 AND delete_flg=0) r,(SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) gl WHERE r.user_group_seq=gl.group_seq",$intReportSeq,$intStudentSeq);
		if($intCategorySeq){
			$strQuery .= sprintf(" and r.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " LIMIT 0,1";
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['seq']);		
	}
	public function updateReportStatus($intReportSeq,$intReportState){
		$strQuery = sprintf("update report set state=%d where seq=%d",$intReportState,$intReportSeq);
		$boolResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($boolResult);		
	}
	public function updateReportUserStatusFlg($intMemberSeq, $intReportSeq, $intStatusFlg){
		if($intStatusFlg==2){
			//end
			$strQuery = sprintf("update report_join_user set user_status_flg=%d,end_date=now() where report_seq=%d and user_seq=%d",$intStatusFlg,$intReportSeq,$intMemberSeq);
		}else{
			//start
			$strQuery = sprintf("update report_join_user set user_status_flg=%d,start_date=now() where report_seq=%d and user_seq=%d",$intStatusFlg,$intReportSeq,$intMemberSeq);
		}
		$boolResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($boolResult);		
	}
	public function getReportJoinUser($intReportSeq,$intUserSeq=null){
		if($intUserSeq){
			$strQuery = sprintf("SELECT * FROM report_join_user WHERE report_seq=%d and user_seq=%d and delete_flg=0",$intReportSeq,$intUserSeq);
		}else{
			$strQuery = sprintf("SELECT * FROM report_join_user WHERE report_seq=%d and delete_flg=0",$intReportSeq);
		}
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult);		
	}
	public function getReportJoinUserCountToStatus($intReportSeq,$intStatusFlg=0){
		$strQuery = sprintf("SELECT count(*) as cnt FROM report_join_user WHERE report_seq=%d and user_status_flg=%d and delete_flg=0",$intReportSeq,$intStatusFlg);
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}
	public function setReportJoinUserByGroupSeq($intReportSeq,$intGroupSeq){
		//get group_user_list
		$strQuery = sprintf("select * from group_user_list where group_seq=%d and delete_flg=0",$intGroupSeq);
		$arrGroupUser = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		//set test_join_user
		$strQuery = "insert into report_join_user (report_seq,user_group_seq,user_seq) values ";
		$intGroupUserCount = count($arrGroupUser);
		foreach($arrGroupUser as $intKey=>$arrResult){
			if(($intGroupUserCount-1)==$intKey){
				$strQuery .= sprintf("(%d,%d,%d)",$intReportSeq,$intGroupSeq,$arrResult['student_seq']);
			}else{
				$strQuery .= sprintf("(%d,%d,%d),",$intReportSeq,$intGroupSeq,$arrResult['student_seq']);
			}
		}
		$boolResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($boolResult);		
	}
	public function setReportJoinUserStatusByUserSeq($intMemberSeq,$intReportSeq,$intUserGroupSeq,$intStatusFlg){
		//get group_user_list
		$strQuery = sprintf("insert into report_join_user (report_seq,user_group_seq,user_seq,user_status_flg) values (%d,%d,%d,%d)",$intReportSeq,$intUserGroupSeq,$intMemberSeq,$intStatusFlg);
		$boolResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($boolResult);		
	}
	public function getGroupSeqByStudentSeq($intStudentSeq){
		$strQuery = sprintf("SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0 ",$intStudentSeq);
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult);
	}
	public function getSampleReportSeq(){
		$strQuery = "SELECT seq FROM report where writer_seq=(select member_seq from member_extend_info where cphone='010-0000-0000') and subject like '%샘플%' order by seq desc";
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrResult[0]['seq']);
	}
	public function checkMyReport($intReportSeq,$intMemberSeq,$strReturnType='boolean'){
		$strQuery = sprintf("SELECT * 
							FROM report
							WHERE delete_flg=0 
							AND seq=%d
							AND (seq IN (SELECT report_seq FROM report_join_user WHERE user_seq=%d and delete_flg=0) 
									or user_group_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d ))",$intReportSeq,$intMemberSeq,$intMemberSeq);
		$arrResult = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		if($strReturnType=='boolean'){
			$mixResult = false;
			if(count($arrResult)){
				$mixResult = true;
			}
		}else if($strReturnType=='array'){
			$mixResult = $arrResult;
		}
		return($mixResult);
	}
	public function getReportListByDate($intMemberSeq,$strMemberType,$intTeacherSeq=null,$intGroupSeq=null,$intCategorySeq=null,$strStartDate=null,$strEndDate=null){
		switch($strMemberType){
			case('T'):
				$strQuery = sprintf(" SELECT * FROM report WHERE delete_flg=0  AND writer_seq=%d 
							AND ((start_date > '%s' and start_date < '%s') OR (start_date < '%s' and finish_date > '%s')) 
							 ",$intMemberSeq,$strStartDate,$strEndDate,$strStartDate,$strStartDate);  
				if($intGroupSeq){
					$strQuery .= sprintf(" and user_group_seq=%d ",$intGroupSeq);
				}
				if($intCategorySeq){
					$strQuery .= sprintf(" and category_seq=%d ",$intCategorySeq);
				}
			break;
			default:
				$strQuery = sprintf("SELECT * 
									FROM report 
									WHERE 
									state>0 AND delete_flg=0 
									AND (user_group_seq IN (SELECT DISTINCT(group_seq)  
														FROM group_user_list 
														WHERE student_seq=%d 
														AND delete_flg=0) 
										OR seq IN (SELECT report_seq 
												FROM report_join_user 
												WHERE user_seq=%d 
												AND delete_flg=0)) 
									AND ((start_date > '%s' AND start_date < '%s') OR (start_date < '%s' AND finish_date > '%s')) ",$intMemberSeq,$intMemberSeq,$strStartDate,$strEndDate,$strStartDate,$strStartDate); 
				if($intTeacherSeq){
					$strQuery .= sprintf(" and writer_seq=%d ",$intTeacherSeq);
				}
				if($intGroupSeq){
					$strQuery .= sprintf(" and user_group_seq=%d ",$intGroupSeq);
				}
				if($intCategorySeq){
					$strQuery .= sprintf(" and category_seq=%d ",$intCategorySeq);
				}
			break;
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromQuestionTag($intUserSeq){
		$strQuery = sprintf("SELECT R.tag,R.correct,R.incorrect,R.correct+R.incorrect AS total_cnt,R.correct/(R.correct+R.incorrect)*100 AS correct_repcent FROM (
SELECT  qt.tag,SUM(IF(ua.result_flg=1,1,0) )AS correct, SUM(IF(ua.result_flg=0,1,0)) AS incorrect FROM question_tag AS qt LEFT JOIN user_answer AS ua ON qt.question_seq=ua.question_seq WHERE ua.user_seq=%d GROUP BY tag
) AS R ORDER BY correct_repcent DESC,correct DESC,incorrect",$intUserSeq);
		//print_r($strQuery);exit;
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromQuiz($intMemberSeq,$strMemberType='T'){
		if($strMemberType=='T'){
			$strQuery = sprintf("SELECT R.test_seq,
								R.subject,
								R.question_cnt,
								R.correct,
								R.incorrect/(R.correct+R.incorrect)*100 AS incorrect_repcent ,
								R.correct+R.incorrect AS total_cnt,
								R.correct/(R.correct+R.incorrect)*100 AS correct_repcent ,
								R.user_cnt
							FROM ( SELECT s.seq AS test_seq,s.*,rc.*
								FROM test AS s 
								  LEFT OUTER JOIN 
								  (SELECT test_seq AS s_seq,
									right_count+wrong_count AS question_cnt,
									SUM(right_count)AS correct,
									SUM(wrong_count) AS incorrect,
									COUNT(user_seq) AS user_cnt
								   FROM record WHERE revision=1 GROUP BY test_seq) AS rc ON s.seq=rc.s_seq
								WHERE s.writer_seq=%d ) AS R 
							ORDER BY user_cnt DESC,
								correct DESC,
								incorrect",$intMemberSeq);
		}else{
			$strQuery = sprintf("SELECT R.test_seq,
								R.subject,
								R.writer_name,
								R.question_cnt,
								R.correct,
								R.incorrect,
								R.correct+R.incorrect AS total_cnt,
								R.correct/(R.correct+R.incorrect)*100 AS correct_repcent,
								R.nickname
							FROM ( SELECT s.seq AS test_seq,mb.name AS writer_name,mb.nickname,s.*,rc.*
								FROM 
								  (SELECT test_seq AS s_seq,
									right_count+wrong_count AS question_cnt,
									right_count AS correct,
									wrong_count AS incorrect		
								   FROM record WHERE revision=1 AND user_seq=%d) AS rc 
								   ,test AS s
								   ,member_basic_info AS mb
								  WHERE s.seq=rc.s_seq AND s.writer_seq=mb.member_seq ) AS R 
							ORDER BY correct_repcent DESC,
								correct DESC,
								incorrect",$intMemberSeq);
			
		}
		//print_r($strQuery);exit;
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromUser($intWriterSeq){
		$strQuery = sprintf("SELECT R.test_seq,
							R.user_name,
							R.correct,
							R.incorrect/(R.correct+R.incorrect)*100 AS incorrect_repcent ,
							R.correct+R.incorrect AS total_cnt,
							R.correct/(R.correct+R.incorrect)*100 AS correct_repcent ,
							R.test_cnt
						FROM ( SELECT * FROM 
							(SELECT test_seq,
								SUM(right_count)AS correct,
								SUM(wrong_count) AS incorrect,
								COUNT(test_seq) AS test_cnt,
								user_name,
								user_seq
							   FROM record WHERE revision=1 GROUP BY user_seq) rc,
							(SELECT * FROM test WHERE delete_flg=0 and writer_seq=%d) s
							WHERE rc.test_seq=s.seq ) AS R 
						ORDER BY test_cnt DESC,
							correct DESC,
							incorrect",$intWriterSeq);
		//print_r($strQuery);exit;
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromUserAdmin($arrSearch,$arrOrder=array(),&$arrPaging=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getAnswerReportScoreFromUserAdminCnt($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/TechQuiz/SQL/MySQL/Report/getAnswerReportScoreFromUserAdmin.php");
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromUserAdminCnt($arrSearch){
		include("Model/TechQuiz/SQL/MySQL/Report/getAnswerReportScoreFromUserAdminCnt.php");
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getAnswerReportScoreFromQuizAdmin($arrSearch,$arrOrder=array(),&$arrPaging=null,$intDeleteFlg=0){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getAnswerReportScoreFromQuizAdminCnt($arrSearch,$intDeleteFlg);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/TechQuiz/SQL/MySQL/Report/getAnswerReportScoreFromQuizAdmin.php");
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportScoreFromQuizAdminCnt($arrSearch,$intDeleteFlg){
		include("Model/TechQuiz/SQL/MySQL/Report/getAnswerReportScoreFromQuizAdminCnt.php");
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getUserJoinQuizList($arrSearch,$arrOrder=array(),&$arrPaging=null,$intUserSeq=null,$intQuizSeq=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getUserJoinQuizListCnt($arrSearch,$intUserSeq);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/TechQuiz/SQL/MySQL/Report/getUserJoinQuizList.php");
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getUserJoinQuizListCnt($arrSearch,$intUserSeq=null){
		include("Model/TechQuiz/SQL/MySQL/Report/getUserJoinQuizListCnt.php");
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getQuizJoinUserList($arrSearch,$arrOrder=array(),&$arrPaging=null,$intQuizSeq=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getQuizJoinUserListCnt($arrSearch,$intQuizSeq);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/TechQuiz/SQL/MySQL/Report/getQuizJoinUserList.php");
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn);
	}
	public function getQuizJoinUserListCnt($arrSearch,$intQuizSeq=null){
		include("Model/TechQuiz/SQL/MySQL/Report/getQuizJoinUserListCnt.php");
		$arrReturn = $this->resReportDB->DB_access($this->resReportDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
}
?>