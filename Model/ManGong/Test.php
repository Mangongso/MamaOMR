<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Tests.php");
if(!class_exists('MQuestion')){
	if(file_exists(ini_get('include_path')."/Model/ManGong/MQuestion.php")){
		require_once("Model/ManGong/MQuestion.php");
	}else{
		require_once("Model/TechQuiz/MQuestion.php");
	}
}
class Test extends Tests{
	public $objPaging;
	public $resTestsDB;
	public $objQuestion;
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resTestsDB = $resMangongDB;
		$this->objQuestion = new MQuestion($resMangongDB);
	}
	public function __destruct(){}
	public function setTests($intWriterSeq,$intTestsType,$strSubject,$strContents,$intExampleNumberingStyle,&$intTestsSeq=null,$strTags='',$intMasterSeq=null){
		/*
		 * 1.$intMasterSeq 가 null인 경우는 마스터가 없는경우 - submaster null
		 * 2.$intMasterSeq 가 null이 아닐 경우는 마스터가 있으면서 선택햇을경우 - submaster = 01920102  
		 * 3.$intMasterSeq 가 ""인 경우는 마스터있으면서 선택하지 않앗을경우 - - submaster = ''
		 * */
		if(!is_null($intMasterSeq)){
			if($intMasterSeq==''){
				$intSubMasterSeq = '';
			}else{
				$intSubMasterSeq = $intWriterSeq;
				$intWriterSeq = $intMasterSeq;
			}
		}
		if($this->resTestsDB->DB_name=="db_idgkr_quiz"){
			include("Model/TechQuiz/SQL/MySQL/Test/setTests.php");	
		}else{
			include("Model/ManGong/SQL/MySQL/Test/setTests.php");
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		if($boolReturn){
			if(!$intTestsSeq){
				$mixReturn = $intTestsSeq = mysql_insert_id($this->resTestsDB->res_DB);
			}else{
				$mixReturn = $intTestsSeq;
			}
		}else{
			$mixReturn = $boolReturn;
		}
		return($mixReturn);
	}
	public function getTests($mixTestsSeq,$intWriterSeq=null,$passCheckTests=null){
		if(!$intWriterSeq){
			if(is_numeric($mixTestsSeq)){
				$strQuery = sprintf("select * from test where seq=%d",$mixTestsSeq);
			}else{
				$strQuery = sprintf("select * from test where md5(seq)='%s'",$mixTestsSeq);
			}
		}else{
			if(is_numeric($mixTestsSeq)){
				$strQuery = sprintf("select * from test where seq=%d and (writer_seq=%d or sub_master=%d) ",$mixTestsSeq,$intWriterSeq,$intWriterSeq);
			}else{
				$strQuery = sprintf("select * from test where md5(seq)='%s' and (writer_seq=%d or sub_master=%d) ",$mixTestsSeq,$intWriterSeq,$intWriterSeq);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$intTestsSeq = $arrReturn[0]['seq'];
		$arrReturn[0]['publish'] = $this->getTestsPublishInfo($intTestsSeq);
		
		//check is my sruvey
		if(!$passCheckTests && $GLOBALS['GLOBAL_APP_NAME'] != 'SMART_OMR'){
			switch($_SESSION[$_COOKIE['member_token']]['member_type']){
				case('T'):
					if($arrReturn[0]['writer_seq'] != $_SESSION[$_COOKIE['member_token']]['member_seq'] && $arrReturn[0]['sub_master'] != $_SESSION[$_COOKIE['member_token']]['member_seq']){
						header("HTTP/1.1 301 Moved Permanently");
						header('location:/');
						exit;
					}
				break;
				case('S'):
					$boolResult = $this->checkMyTests($intTestsSeq,$_SESSION[$_COOKIE['member_token']]['member_seq']);
					if(!$boolResult){
						header("HTTP/1.1 301 Moved Permanently");
						header('location:/');
						exit;
					}
				break;
			}
		}
		return ($arrReturn);
	}
	public function getTestListByDate($intMemberSeq,$strMemberType,$intTeacherSeq=null,$intGroupSeq=null,$intCategorySeq=null,$strStartDate=null,$strEndDate=null){
		switch($strMemberType){
			case('T'):
				$strQuery = sprintf(" SELECT * FROM (SELECT * FROM test WHERE delete_flg=0 AND writer_seq=%d) s
							LEFT JOIN test_published sp
							ON s.seq=sp.test_seq
							where sp.delete_flg=0
							AND ((sp.start_date > '%s' and sp.start_date < '%s')
							OR (sp.start_date < '%s' and sp.finish_date > '%s'))
							 ",$intMemberSeq,$strStartDate,$strEndDate,$strStartDate,$strStartDate);
				if($intGroupSeq){
					$strQuery .= sprintf(" and sp.group_list_seq=%d ",$intGroupSeq);
				}
				if($intCategorySeq){
					$strQuery .= sprintf(" and sp.category_seq=%d ",$intCategorySeq);
				}
			break;
			default:
				$strQuery = sprintf("SELECT *
									FROM (SELECT *
											FROM test_published
											WHERE state>0 AND delete_flg=0
											AND (group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0)
											OR test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d AND delete_flg=0))
											) sp
									LEFT JOIN test s
									ON sp.test_seq=s.seq
									WHERE ((sp.start_date > '%s' AND sp.start_date < '%s')
									OR (sp.start_date < '%s' AND sp.finish_date > '%s')) ",$intMemberSeq,$intMemberSeq,$strStartDate,$strEndDate,$strStartDate,$strStartDate);
				if($intTeacherSeq){
					$strQuery .= sprintf(" AND s.writer_seq=%d ",$intTeacherSeq);
				}
				if($intGroupSeq){
					$strQuery .= sprintf(" and sp.group_list_seq=%d ",$intGroupSeq);
				}
				if($intCategorySeq){
					$strQuery .= sprintf(" and sp.category_seq=%d ",$intCategorySeq);
				}
			break;
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getStudentTestList($intMemberSeq,$intTestsType=null,$intCategorySeq=null,$mixTeacherSeq=null,$boolMD5=false,&$arrPaging=null,$arrSearch=array(),$intTid=null){
		//test paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getStudentTestListCount($intMemberSeq,$intTestsType,$intCategorySeq,$mixTeacherSeq,$boolMD5,$arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strQuery = sprintf("SELECT sp.seq AS published_seq,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.*,s.*
								FROM test_published AS sp
								     LEFT JOIN test AS s
								     ON sp.test_seq=s.seq
								WHERE s.delete_flg=0
								     AND sp.state>0
								     AND sp.display_flg=1
								     AND (sp.group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0)",$intMemberSeq);

		$strQuery .= sprintf(" OR sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)) ",$intMemberSeq);
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
		}

		if($intTestsType){
			$strQuery .= " AND s.type=".$intTestsType;
		}
		if($intCategorySeq){
			$strQuery .= " AND sp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		if(!is_null($intTid)){
			$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM term_test WHERE tid IN (SELECT tid FROM term_data WHERE tid=%d )) ",$intTid,$mixTeacherSeq);
		}
		$strQuery .= " AND sp.state>0 order by s.seq desc";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getStudentTestListCount($intMemberSeq,$intTestsType=null,$intCategorySeq,$mixTeacherSeq=null,$boolMD5=false,$arrSearch=array()){
		$strQuery = sprintf("SELECT count(*) as cnt
								FROM test_published AS sp
								     LEFT JOIN test AS s
								     ON sp.test_seq=s.seq
								WHERE s.delete_flg=0
								     AND sp.state>0
								     AND sp.display_flg=1
								     AND (sp.group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) ",$intMemberSeq);

		$strQuery .= sprintf(" OR sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)) ",$intMemberSeq);
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
		}

		if($intTestsType){
			$strQuery .= " AND s.type=".$intTestsType;
		}
		if($intCategorySeq){
			$strQuery .= " AND sp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		$strQuery .= " AND sp.state>0 ";
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getStudentTestNextSeq($intTestSeq,$intMemberSeq,$intCategorySeq=null,$intTestsType=1){
		$strQuery = sprintf("SELECT * FROM (SELECT sp.seq AS published_seq,sp.start_date,sp.finish_date,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.category_seq,sp.group_list_seq,sp.test_prog_flg,s.* FROM test_published AS sp LEFT JOIN test AS s ON sp.test_seq=s.seq WHERE s.delete_flg=0 AND sp.state>0 AND s.seq<%d) sp,
				(SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) gl WHERE sp.group_list_seq=gl.group_seq",$intTestSeq,$intMemberSeq);
		if($intTestsType){
			$strQuery .= sprintf(" and sp.type=%d",$intTestsType);
		}
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " ORDER BY sp.seq DESC LIMIT 0,1";
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['seq']);
	}
	public function getStudentTestPrevSeq($intTestSeq,$intMemberSeq,$intCategorySeq=null,$intTestsType=1){
		$strQuery = sprintf("SELECT * FROM (SELECT sp.seq AS published_seq,sp.start_date,sp.finish_date,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.category_seq,sp.group_list_seq,sp.test_prog_flg,s.* FROM test_published AS sp LEFT JOIN test AS s ON sp.test_seq=s.seq WHERE s.delete_flg=0 AND sp.state>0 AND s.seq>%d) sp,
				(SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) gl WHERE sp.group_list_seq=gl.group_seq",$intTestSeq,$intMemberSeq);
		if($intTestsType){
			$strQuery .= sprintf(" and sp.type=%d",$intTestsType);
		}
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " LIMIT 0,1";
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['seq']);
	}
	public function getTestsIngListToTeacherSeq($intTeacherSeq,$intTestsType){
		$strQuery = sprintf("select sp.seq as published_seq,sp.start_date,sp.finish_date,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.category_seq,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where sp.start_date<=now() and sp.finish_date>=now() and s.delete_flg=0 and s.writer_seq=%d and s.type='%s' order by sp.start_date DESC,s.seq DESC",$intTeacherSeq,$intTestsType);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsNextSeq($intTestsSeq,$intMemberSeq,$intCategorySeq=null,$intTestsType=1){
		$strQuery = sprintf("select s.seq as seq from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and s.seq<%d and s.writer_seq=%d and s.type=%d",$intTestsSeq,$intMemberSeq,$intTestsType);
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " ORDER BY s.seq DESC LIMIT 0,1";
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['seq']);
	}
	public function getTestsPrevSeq($intTestsSeq,$intMemberSeq,$intCategorySeq=null,$intTestsType=1){
		$strQuery = sprintf("select s.seq as seq from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and s.seq>%d and s.writer_seq=%d and s.type=%d",$intTestsSeq,$intMemberSeq,$intTestsType);
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		$strQuery .= " LIMIT 0,1";
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['seq']);
	}
	public function getTestsListToTeacherSeq($intTeacherSeq,$intTestsType=null,$intCategorySeq=null,$intGroupSeq=null,&$arrPaging=null,$arrSearch=array(),$arrState=array(),$arrOrderFixTests=array(),$intTid=null,$mixUserSeq=null,$intUserLevel=null){
		//test paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTestsListCount($intTeacherSeq,$intTestsType,$intCategorySeq,$intGroupSeq,$arrSearch,$mixUserSeq,$intUserLevel,$arrState,$intTid);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		if(!is_null($intUserLevel) && $intUserLevel==100 && is_null($mixUserSeq)){//is_null($mixUserSeq) -> user_seq가 있을 경우는 모든퀴즈가 보이면 안되기때문임.
			$strQuery = sprintf("select sp.seq as published_seq,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.*,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 ");
		}else{
			if($intTestsType){
				$strQuery = sprintf("select sp.seq as published_seq,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.*,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (s.writer_seq=%d or s.sub_master=%d) and s.type=%d ",$intTeacherSeq,$intTeacherSeq,$intTestsType);
			}else{
				$strQuery = sprintf("select sp.seq as published_seq,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.*,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (s.writer_seq=%d or s.sub_master=%d) ",$intTeacherSeq,$intTeacherSeq);
			}
			
		}
		//is categyry
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		if($intGroupSeq){
			$strQuery .= sprintf(" and sp.group_list_seq=%d",$intGroupSeq);
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		if(count($arrState)>0){
			$strQuery .= " and sp.state in (".join(',',$arrState).") ";
		}
		if(!is_null($intTid)){
			$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM term_test WHERE tid IN (SELECT tid FROM term_data WHERE tid=%d AND (writer_seq=%d or writer_seq=0) )) ",$intTid,$intTeacherSeq);
		}
		
		if(!is_null($mixUserSeq)){
			if(is_numeric($mixUserSeq)){
				$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM record WHERE user_seq=%d and revision=1 )",$mixUserSeq);
			}else{
				$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM record WHERE md5(user_seq)='%s' and revision=1 )",$mixUserSeq);
			}
		}
		
		if(count($arrOrderFixTests)>0){
			$strQuery .= " order by CASE WHEN s.seq IN (".join(',',$arrOrderFixTests).") THEN 0 ELSE 1 END ,s.seq DESC ";
		}else{
			$strQuery .= " order by s.seq DESC";
		}
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsListCount($intTeacherSeq,$intTestsType=null,$intCategorySeq=null,$intGroupSeq=null,$arrSearch=array(),$mixUserSeq=null,$intUserLevel=null,$arrState=array(),$intTid=null){
		if(!is_null($intUserLevel) && $intUserLevel==100 && is_null($mixUserSeq)){//is_null($mixUserSeq) -> user_seq가 있을 경우는 모든퀴즈가 보이면 안되기때문임.
			$strQuery = sprintf("select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 ");
		}else{
			if(is_numeric ($intTeacherSeq)){
				if($intTestsType){
					$strQuery = sprintf("select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (s.writer_seq=%d or s.sub_master=%d) and s.type=%d",$intTeacherSeq,$intTeacherSeq,$intTestsType);
				}else{
					$strQuery = sprintf("select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (s.writer_seq=%d or s.sub_master=%d) ",$intTeacherSeq,$intTeacherSeq);
				}
			}else{
				if($intTestsType){
					$strQuery = sprintf("select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (md5(s.writer_seq)='%s' or md5(s.sub_master)='%s') and s.type=%d",$intTeacherSeq,$intTeacherSeq,$intTestsType);
				}else{
					$strQuery = sprintf("select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and (md5(s.writer_seq)='%s' or md5(s.sub_master)='%s') ",$intTeacherSeq,$intTeacherSeq);
				}
			}
		}
		if($intCategorySeq){
			$strQuery .= sprintf(" and sp.category_seq=%d",$intCategorySeq);
		}
		if($intGroupSeq){
			$strQuery .= sprintf(" and sp.group_list_seq=%d",$intGroupSeq);
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		if(count($arrState)>0){
			$strQuery .= " and sp.state in (".join(',',$arrState).") ";
		}
		if(!is_null($intTid)){
			$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM term_test WHERE tid IN (SELECT tid FROM term_data WHERE tid=%d AND (writer_seq=%d or writer_seq=0) )) ",$intTid,$intTeacherSeq);
		}
		if(!is_null($mixUserSeq)){
			if(is_numeric($mixUserSeq)){
				$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM record WHERE user_seq=%d and revision=1 )",$mixUserSeq);
			}else{
				$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM record WHERE md5(user_seq)='%s' and revision=1 )",$mixUserSeq);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function updateTestTotalScore($intTestSeq,$intPublishedSeq){
		$strQuery = sprintf("UPDATE test_published SET total_score=(SELECT SUM(question_score) FROM test_question_list WHERE test_seq=%d) WHERE test_seq=%d AND seq=%d",$intTestSeq,$intTestSeq,$intPublishedSeq);
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function getTestsQuestion($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("select SQ.test_seq,SQ.question_seq,SQ.question_number,SQ.order_number,SQ.question_score,Q.writer_seq, Q.contents, Q.question_type, Q.example_type, Q.create_date, Q.modify_date from test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq where SQ.test_seq=%d and SQ.question_seq=%d order by SQ.question_number asc",$intTestsSeq,$intQuestionSeq);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestQuestionListWithUserAnswer($intTestSeq,$intUserSeq,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11)){
		$strQuery = sprintf("SELECT
				SQ.test_seq,
				SQ.order_number,
				SQ.question_seq,
				SQ.question_number,
				SQ.question_score,
				Q.writer_seq,
				Q.contents,
				Q.question_type,
				Q.example_type,
				Q.hint,
				Q.commentary,
				Q.create_date,
				Q.modify_date,
				Q.tags,
				UA.seq as user_answer_seq,
				UA.record_seq,
				UA.result_flg,
				UA.user_answer,
				UA.score,
				UAD.discus_answer,
				UAD.answer_comment
				FROM
				test_question_list SQ
				LEFT OUTER JOIN question AS Q ON SQ.question_seq=Q.seq
				LEFT OUTER JOIN user_answer as UA ON UA.user_seq=%d AND UA.test_seq=SQ.test_seq AND UA.question_seq=Q.seq AND record_seq=(SELECT MAX(record_seq) FROM user_answer WHERE user_seq=UA.user_seq AND test_seq=UA.test_seq AND question_seq=UA.question_seq)
				LEFT JOIN user_answer_discus as UAD ON UAD.user_answer_seq=UA.seq and UAD.question_seq=Q.seq
				WHERE
				SQ.test_seq=%d and Q.question_type in (%s)
				ORDER BY SQ.order_number ASC",$intUserSeq,$intTestSeq,join(',',$arrQuestionType));
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestQuestionListWithUserAnswerLog($intTestSeq,$intUserSeq,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11)){
		$strQuery = sprintf("SELECT
				SQ.test_seq,
				SQ.order_number,
				SQ.question_seq,
				SQ.question_number,
				SQ.question_score,
				Q.writer_seq,
				Q.contents,
				Q.question_type,
				Q.example_type,
				Q.hint,
				Q.commentary,
				Q.create_date,
				Q.modify_date,
				Q.tags,
				UAL.user_answer,
				UAL.discus_answer
				FROM
				test_question_list SQ
				LEFT OUTER JOIN question AS Q ON SQ.question_seq=Q.seq
				LEFT OUTER JOIN user_answer_log as UAL ON UAL.user_seq=%d AND UAL.test_seq=SQ.test_seq AND UAL.question_seq=Q.seq
				WHERE
				SQ.test_seq=%d and Q.question_type in (%s)
				ORDER BY SQ.order_number ASC",$intUserSeq,$intTestSeq,join(',',$arrQuestionType));
		//print_r($strQuery);
		//exit;
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsQuestionList($intTestsSeq,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11)){
		$strQuery = sprintf("select
					SQ.test_seq,
					SQ.order_number,
					SQ.question_seq,
					SQ.question_number,
					SQ.question_score,
					Q.writer_seq,
					Q.contents,
					Q.question_type,
					Q.example_type,
					Q.hint,
					Q.commentary,
					Q.create_date,
					Q.modify_date,
					Q.tags,
					Q.file_name
				from
					test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq
				where
					SQ.test_seq=%d and Q.question_type in (%s)
				order by SQ.order_number asc",$intTestsSeq,join(',',$arrQuestionType));
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestQuestionListWithExample($intTestSeq,$intUserSeq=false,$arrQuestionType = array(1,2,3,4,5,6,7,8,9,11),$intExampleNumberingStyle=0,$existUserAnswerLog=false){
		if($intUserSeq && $existUserAnswerLog){
			$arrQuestions = $this->getTestQuestionListWithUserAnswerLog($intTestSeq,$intUserSeq,$arrQuestionType);
		}else if($intUserSeq){
			$arrQuestions = $this->getTestQuestionListWithUserAnswer($intTestSeq,$intUserSeq,$arrQuestionType);
		}else{
			$arrQuestions = $this->getTestsQuestionList($intTestSeq,$arrQuestionType);
		}

		foreach($arrQuestions as $intKey=>$arrQuestion){
			$intExampleCount = constant("QUESTION_TYPE_".$arrQuestion['question_type']."_EXAMPLE_COUNT");
			$arrQuestions[$intKey]['example'] = $this->objQuestion->getQuestionExample($intExampleNumberingStyle,$arrQuestion['question_seq'],$arrQuestion['example_type'],$intExampleCount);
		}
		return($arrQuestions);
	}
	public function getSubjectiveQuestionInfo($intTestSeq,$intPublichSeq = null){
		$strQuery = sprintf("select count(*) as cnt from question where seq in (select question_seq from test_question_list where test_seq=%d) and question_type in (5,6,7,8,9)",$intTestSeq);
		$arrQuestionCnt = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$strQuery = sprintf("select count(*) as cnt from question_example where question_seq in (select seq as cnt from question where seq in (select question_seq from test_question_list where test_seq=%d) and question_type in (5,6,7,8,9)) and example_type=1 and delete_flg=0",$intTestSeq);
		$arrExampleCnt = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$arrReturn = array(
				'question_cnt'=>$arrQuestionCnt[0]['cnt'],
				'example_cnt'=>$arrExampleCnt[0]['cnt']
				);
		return($arrReturn);
	}
	public function getTestPaperType($intTestSeq,$intPublishedSeq=null){
		$strQuery = sprintf("select count(*) as cnt from question where seq in (select question_seq from test_question_list where test_seq=%d) and contents=''",$intTestSeq);
		$arrQuestionCnt = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		if($arrQuestionCnt[0]['cnt']>0){
			$intResult = 0;
		}else{
			$intResult = 1;
		}
		return($intResult);
	}
	public function getTestsQuestionByQuestionNumber($intTestsSeq,$intQuestionNumber){
		$strQuery = sprintf("select SQ.test_seq,SQ.question_seq,SQ.question_number,SQ.question_score,Q.writer_seq, Q.contents, Q.question_type, Q.example_type, Q.create_date, Q.modify_date from test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq where SQ.test_seq=%d and SQ.question_number=%d order by SQ.question_number asc",$intTestsSeq,$intQuestionNumber);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsQuestionCount($intTestsSeq){
		$strQuery = sprintf("select count(*) as cnt from test_question_list where test_seq=%d",$intTestsSeq);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function publishTests($intTestsSeq,$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,&$intPublishSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg=1,$intTestViewType=1,$intDeadlineFlg=1,$intDisplayFlg=1,$intQuizType=0,$intPublishedSort=null,$intRankFlg=null,$intAnswerCheckFlg=null){
		$strQuery = sprintf("insert into test_published set test_seq=%d,start_date='%s',finish_date='%s',time='%s',category_seq=%d,group_list_seq=%d,total_score=%d,published_date=now(),test_prog_flg=%d,paper_type=%d,record_view_flg=%d,repeat_flg=%d,test_view_type=%d,deadline_flg=%d,display_flg=%d",$intTestsSeq,$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg,$intTestViewType,$intDeadlineFlg,$intDisplayFlg);
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$intPublishSeq = mysql_insert_id($this->resTestsDB->res_DB);
		return($boolReturn);
	}
	public function updateTestsPublish($intTestsSeq,$intPublishSeq,$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg=1,$intTestViewType=1,$intDeadlineFlg=1,$intDisplayFlg=1,$intQuizType=0,$intRankFlg=null,$intAnswerCheckFlg=null){
		if(file_exists(ini_get('include_path')."Model/ManGong/SQL/MySQL/MQuestion/setQuestion.php")){
			$strQuery = sprintf("update test_published set start_date='%s',finish_date='%s',time='%s',category_seq=%d,group_list_seq=%d,total_score=%d,test_prog_flg=%d,paper_type=%d,record_view_flg=%d,repeat_flg=%d,test_view_type=%d,deadline_flg=%d,display_flg=%d where test_seq=%d and seq=%d",$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg,$intTestViewType,$intDeadlineFlg,$intDisplayFlg,$intTestsSeq,$intPublishSeq);
		}else{
			$strQuery = sprintf("update test_published set start_date='%s',finish_date='%s',time='%s',category_seq=%d,group_list_seq=%d,test_prog_flg=%d,paper_type=%d,record_view_flg=%d,repeat_flg=%d,test_view_type=%d,deadline_flg=%d,display_flg=%d,quiz_type=%d",$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg,$intTestViewType,$intDeadlineFlg,$intDisplayFlg,$intQuizType);
			if(!is_null($intRankFlg)){
				$strQuery .= sprintf(" ,rank_flg=%d ",$intRankFlg);
			}
			if(!is_null($intAnswerCheckFlg)){
				$strQuery .= sprintf(" ,answer_check_flg=%d ",$intAnswerCheckFlg);
			}
			
			$strQuery .= sprintf(" where test_seq=%d and seq=%d ",$intTestsSeq,$intPublishSeq);
			
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function updateTestsStatus($intTestsSeq,$intTestsStatus){
		include("Model/ManGong/SQL/MySQL/Test/updateTestsStatus.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function getTestsJoinUserListCount($intTestsSeq,$intStstus=null,$intSex=null){
		$strQuery = sprintf("select count(*) as cnt from test_join_user where test_seq=%d and delete_flg=0",$intTestsSeq);
		if(!is_null($intStstus)){
			$strQuery .= sprintf(" and su.status=%d ",$intStstus);
		}
		if(!is_null($intSex)){
			$strQuery .= sprintf(" and me.sex=%d ",$intSex);
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getTestsJoinUserList($intTestsSeq,$intStstus=null,$intSex=null,$arrSearch=null,&$arrPaging=null,$strSort=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTestsJoinUserListCount($intTestsSeq,$intStstus,$intSex);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strQuery = sprintf("
				select su.*,mi.name,mi.nickname,me.cphone from test_join_user as su
				left join member_basic_info as mi on su.user_seq=mi.member_seq
				left join member_extend_info as me on mi.member_seq=me.member_seq
				where su.test_seq=%d and su.delete_flg=0",$intTestsSeq);
		if(!is_null($intStstus)){
			$strQuery .= sprintf(" and su.status=%d ",$intStstus);
		}
		if(!is_null($intSex)){
			$strQuery .= sprintf(" and me.sex=%d ",$intSex);
		}
		switch($strSort){
			case('create_date_D'):
				$strQuery .= " order by create_date DESC";
				break;
		}
		if(!is_null($arrPaging)){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getTestsJoinUserTotalCount($intTestsSeq=null,$arrTestsSeq=array()){
		if(count($arrTestsSeq)){
			$strQuery = sprintf("select count(*) as count from test_join_user where test_seq in (".join(',',$arrTestsSeq).") and delete_flg=0");
		}else{
			$strQuery = sprintf("select count(*) as count from test_join_user where test_seq=%d and delete_flg=0",$intTestsSeq);
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['count']);
	}
	public function getTestsJoinUserCountToStatus($intTestsSeq,$intStatusFlg){
		$strQuery = sprintf("select count(*) as count from test_join_user where test_seq=%d and test_status_flg=%d and delete_flg=0",$intTestsSeq,$intStatusFlg);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['count']);
	}
	public function getTestsJoinUser($intUserSeq,$intTestsSeq){
		$strQuery = sprintf("select *,UNIX_TIMESTAMP(start_date) as start_timestamp,UNIX_TIMESTAMP(end_date) as end_timestamp from test_join_user where user_seq=%d and test_seq=%d and delete_flg=0",$intUserSeq,$intTestsSeq);
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getJoinUserCountByTeacherSeq($mixTeacherSeq){
		if(is_numeric($mixTeacherSeq)){
			$strQuery = sprintf("select count(distinct(user_seq)) as cnt from test_join_user where test_seq in (select seq from test where writer_seq=%d and delete_flg=0)",$mixTeacherSeq);
		}else{
			$strQuery = sprintf("select count(distinct(user_seq)) as cnt from test_join_user where test_seq in (select seq from test where md5(writer_seq)='%s' and delete_flg=0)",$mixTeacherSeq);
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function setTestsJoinUser($intTestsSeq,$intPublishSeq,$intGroupSeq){
		//get group_user_list
		$strQuery = sprintf("select * from group_user_list where group_seq=%d and delete_flg=0",$intGroupSeq);
		$arrGroupUser = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		//set test_join_user
		$strQuery = "insert into test_join_user (user_group_seq,user_seq,test_published_seq,test_seq,create_date) values ";
		$intGroupUserCount = count($arrGroupUser);
		foreach($arrGroupUser as $intKey=>$arrResult){
			if(($intGroupUserCount-1)==$intKey){
				$strQuery .= sprintf("(%d,%d,%d,%d,now())",$intGroupSeq,$arrResult['student_seq'],$intPublishSeq,$intTestsSeq);
			}else{
				$strQuery .= sprintf("(%d,%d,%d,%d,now()),",$intGroupSeq,$arrResult['student_seq'],$intPublishSeq,$intTestsSeq);
			}
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function setTestsJoinUserStatusByUserSeq($intTestsSeq,$intPublishSeq,$intMemberSeq,$intUserGroupSeq,$intStatusFlg){
		if($intStatusFlg==2){
			//set test_join_user
			$strQuery = sprintf("insert into test_join_user (user_group_seq,user_seq,test_published_seq,test_seq,test_status_flg,create_date,start_date) values (%d,%d,%d,%d,%d,now(),now())"
						,$intUserGroupSeq,$intMemberSeq,$intPublishSeq,$intTestsSeq,$intStatusFlg);
		}else{
			//set test_join_user
			$strQuery = sprintf("insert into test_join_user (user_group_seq,user_seq,test_published_seq,test_seq,test_status_flg,create_date) values (%d,%d,%d,%d,%d,now())"
						,$intUserGroupSeq,$intMemberSeq,$intPublishSeq,$intTestsSeq,$intStatusFlg);
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function updateTestsJoinUser($intTestsSeq,$intPublishSeq,$intGroupSeq){
		//delete test_join_user
		$strQuery = sprintf("update test_join_user set delete_flg=1,modify_date=now() where test_seq=%d and test_published_seq=%d",$intTestsSeq,$intPublishSeq);
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		//set test_join_user
		if($boolResult){
			$boolReturn = $this->setTestsJoinUser($intTestsSeq, $intPublishSeq, $intGroupSeq);
		}
		return($boolReturn);
	}
	// $intStatusFlg: 2-테스트 진행중,3-완료, other-신청완료
	public function updateTestsJoinUserStatus($intUserSeq,$intTestsSeq,$intStatusFlg){
		if($intStatusFlg==2){
			$strQuery = sprintf("update test_join_user set test_status_flg=%d,start_date=now(),modify_date=now() where user_seq=%d and test_seq=%d and delete_flg=0",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}else if($intStatusFlg==3){
			$strQuery = sprintf("update test_join_user set test_status_flg=%d,end_date=now(),modify_date=now() where user_seq=%d and test_seq=%d and delete_flg=0",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}else{
			$strQuery = sprintf("update test_join_user set test_status_flg=%d,join_date=now(),modify_date=now() where user_seq=%d and test_seq=%d and delete_flg=0 and test_status_flg!=%d",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolResult);
	}
	/*
	 * 오답문제풀이시 시험시간을 뽑기 위해서는 start end date가 업데이트 되어야 합니다.
	 * 따라서 where 절에 test_status_flg를 제외한 함수를 위에 다시 만듬.
	public function updateTestsJoinUserStatus($intUserSeq,$intTestsSeq,$intStatusFlg){
		if($intStatusFlg==2){
			$strQuery = sprintf("update test_join_user set test_status_flg=%d,start_date=now() where user_seq=%d and test_seq=%d and delete_flg=0 and test_status_flg=1",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}else if($intStatusFlg==3){
			$strQuery = sprintf("update test_join_user set test_status_flg=%d,end_date=now() where user_seq=%d and test_seq=%d and delete_flg=0 and test_status_flg!=%d",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}else{
			$strQuery = sprintf("update test_join_user set test_status_flg=%d where user_seq=%d and test_seq=%d and delete_flg=0 and test_status_flg!=%d",$intStatusFlg,$intUserSeq,$intTestsSeq,$intStatusFlg);
		}
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolResult);
	}
	*/

	public function updateTestsJoinUserReadFlg($intTestsSeq,$intPublishSeq,$intMemberSeq){
		$strQuery = sprintf("update test_join_user set read_flg=1,modify_date=now() where test_seq=%d and test_published_seq=%d and user_seq=%d and delete_flg=0",$intTestsSeq,$intPublishSeq,$intMemberSeq);
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolResult);
	}
	public function updateTestsTags($intTestsSeq,$strTags){
		$strQuery = sprintf("update test set tags='%s' where seq=%d ",quote_smart($strTags),$intTestsSeq);
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function getSampleTestsSeq(){
		$strQuery = "SELECT seq FROM test where writer_seq=(select member_seq from member_extend_info where cphone='010-0000-0000') and subject like '%샘플%' order by seq desc";
		$arrResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrResult[0]['seq']);
	}
	public function getGroupSeqByStudentSeq($intStudentSeq){
		$strQuery = sprintf("SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0 ",$intStudentSeq);
		$arrResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrResult);
	}
	public function checkMyTests($intTestsSeq,$intMemberSeq,$strReturnType='boolean'){
		if($strReturnType=='boolean'){
			$strQuery = sprintf("SELECT *
								FROM test_published
								WHERE delete_flg=0
								AND test_seq=%d
								AND (test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)
										or group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d )) ",$intTestsSeq,$intMemberSeq,$intMemberSeq);
			$arrResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			$mixResult = false;
			if(count($arrResult)){
				$mixResult = true;
			}
		}else if($strReturnType=='array'){
			$strQuery = sprintf("SELECT *
								FROM test_published AS sp
								     LEFT JOIN test AS s
								     ON sp.test_seq=s.seq
								WHERE s.delete_flg=0
								AND sp.test_seq=%d
								AND (sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)
										or sp.group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d )) ",$intTestsSeq,$intMemberSeq,$intMemberSeq);
			$mixResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		}
		return($mixResult);
	}
	public function checkMyTechQuiz($intTestsSeq,$intWriterSeq,$strReturnType='boolean'){
		$strQuery = sprintf("select * from test where seq=%d and (writer_seq=%d or sub_master=%d) ",$intTestsSeq,$intWriterSeq,$intWriterSeq);
		$mixResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);

		if($strReturnType=='boolean'){
			$mixResult = count($mixResult)?true:false;
		}else{
			if(count($mixResult))$mixResult[0]['publish'] = $this->getTestsPublishInfo($intTestsSeq);
		}
		return $mixResult;
	}
	public function getAllTestCount($intTestsType,$intCategorySeq,$mixTeacherSeq,$boolMD5,$arrSearch,$intDisplayFlg=null,$intTid=null){
		$strQuery = "SELECT
				COUNT(*) AS cnt
				FROM test_published AS sp
				LEFT JOIN test AS s
				ON sp.test_seq=s.seq
				WHERE s.delete_flg=0
				AND sp.state>0 and sp.start_date < now() 
				";
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
		}

		if($intTestsType){
			$strQuery .= " AND s.type=".$intTestsType;
		}
		if($intCategorySeq){
			$strQuery .= " AND sp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		if(!is_null($intDisplayFlg)){
			$strQuery .= sprintf(" AND sp.display_flg=%d ",$intDisplayFlg);
		}
		if(!is_null($intTid)){
			$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM term_test WHERE tid IN (SELECT tid FROM term_data WHERE tid=%d )) ",$intTid,$mixTeacherSeq);
		}
		$strQuery .= " AND sp.state>0 order by s.seq desc";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getAllTestList($intTestsType=null,$intCategorySeq=null,$mixTeacherSeq=null,$boolMD5=false,&$arrPaging=null,$arrSearch=array(),$arrOrder=array('type'=>'sp.start_date','sort'=>'DESC'),$arrOrderFixTests=array(),$intDisplayFlg=null,$intTid=null){
		//test paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getAllTestCount($intTestsType,$intCategorySeq,$mixTeacherSeq,$boolMD5,$arrSearch,$intDisplayFlg,$intTid);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strQuery = "SELECT
				sp.seq AS published_seq,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.*,s.*
				FROM test_published AS sp
				LEFT JOIN test AS s
				ON sp.test_seq=s.seq
				WHERE s.delete_flg=0
				AND sp.state>0 AND sp.start_date < now() 
				";
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
		}

		if($intTestsType){
			$strQuery .= " AND s.type=".$intTestsType;
		}
		if($intCategorySeq){
			$strQuery .= " AND sp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (s.subject like '%".$arrSearch['subject']."%' "." or s.contents like '%".$arrSearch['contents']."%')";
		}
		if(!is_null($intDisplayFlg)){
			$strQuery .= sprintf(" AND sp.display_flg=%d ",$intDisplayFlg);
		}
		if(!is_null($intTid)){
			$strQuery .= sprintf(" AND s.seq in (SELECT test_seq FROM term_test WHERE tid IN (SELECT tid FROM term_data WHERE tid=%d )) ",$intTid,$mixTeacherSeq);
		}
		if(is_array($arrOrder) && count($arrOrderFixTests)>0){
			$strQuery .= sprintf(" order by CASE WHEN s.seq IN (".join(',',$arrOrderFixTests).") THEN 0 ELSE 1 END ,%s %s ",$arrOrder['type'],$arrOrder['sort']);
		}else if(is_array($arrOrder)){
			$strQuery .= sprintf(" order by %s %s ",$arrOrder['type'],$arrOrder['sort']);
		}else if(count($arrOrderFixTests)>0){
			$strQuery .= sprintf(" order by CASE WHEN s.seq IN (".join(',',$arrOrderFixTests).") THEN 0 ELSE 1 END ,s.seq desc ");
		}else{
			$strQuery .= " order by s.seq desc ";
		}
		
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		//print_r($strQuery);
		//exit;
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function getRelatedContents($intTestsSeq,$isNotNullImageFlg=0){
		$strQuery = sprintf("select * from idg_related_contents where test_seq=%d",$intTestsSeq);
		if($isNotNullImageFlg)
			$strQuery .= sprintf(" and image_url is not null AND image_url<>'' ");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function setRelatedContents($intTestsSeq,$arrContents=array('media_type'=>null,'nid'=>null,'title'=>null,'image_url'=>null),$intDeleteFlg=null){
		$arrContentValues = array();
		if(count($arrContents)>0){
			foreach($arrContents as $intKey=>$arrContent){
				if($arrContent['media_type'] && $arrContent['nid'] && $arrContent['title'])
				array_push($arrContentValues,sprintf("(%d,'%s',%d,'%s','%s')",$intTestsSeq,$arrContent['media_type'],$arrContent['nid'],mysql_real_escape_string($arrContent['title']),mysql_real_escape_string($arrContent['image_url'])));
			}
			if(count($arrContentValues)>0){
				$strQuery = "replace into idg_related_contents (test_seq,media_type,nid,node_title,image_url) values ".join(',',$arrContentValues);
				$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			}else{
				$boolReturn = true;
			}
		}else{
			$boolReturn = true;
		}
		return($boolReturn);
	}
	public function deleteRelatedContents($intTestsSeq,$intContentSeq=null){
		if(is_null($intContentSeq)){
			$strQuery = sprintf("delete from idg_related_contents where test_seq=%d",$intTestsSeq);
		}else{
			$strQuery = sprintf("delete from idg_related_contents where test_seq=%d and seq=%d",$intTestsSeq,$intContentSeq);
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	/****** test banner *******/
	public function getTestsBanner($intTestsSeq,$intCurrentIng=0,$intRandFlg=0){
		$strQuery = sprintf("select * from test_banner where ");
		
		if($intTestsSeq){
			$strQuery .= sprintf(" test_seq=%d ",$intTestsSeq);
			if($intCurrentIng){
				$strQuery .= sprintf(" and start_date<=now() and finish_date>=now() ");
			}
		}else{
			if($intCurrentIng){
				$strQuery .= sprintf(" start_date<=now() and finish_date>=now() ");
			}
		}
		$strQuery .= sprintf(" and banner_view_flg=1 ");
		if($intRandFlg){
			$strQuery .= sprintf(" order by RAND() ");
		}
		
		//print_r($strQuery);
		//exit;
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return ($arrReturn);
	}
	public function setTestsBanner($intTestsBannerSeq=null,$arr_input=array()){
		$strSubQuery = "";
		foreach($arr_input as $strKey=>$mixValue){
			
			if( $strKey=="test_seq" || $strKey=="writer_seq" || $strKey=="sub_master_seq" ){
				$strSubQuery.=sprintf(",%s=%d",$strKey,$mixValue);
			}else{	
				$strSubQuery.=sprintf(",%s='%s'",$strKey,$mixValue);
			}
		}
		if(is_null($intTestsBannerSeq)){
			//set
			$strQuery = sprintf("insert into test_banner set create_date=now(),modify_date=now()".$strSubQuery,$intTestsSeq);
		}else{
			//update
			$strQuery = sprintf("update test_banner set modify_date=now()".$strSubQuery." where seq=%d",$intTestsBannerSeq);
		}
		//print_r($strQuery);
		//exit;
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return ($boolReturn);
	}
	public function getTestsSocial($intTestsSeq){
		$strQuery = sprintf("select * from test_social_info where social_view_flg=1 and delete_flg=0 ");
		if($intTestsSeq){
			$strQuery .= sprintf(" and test_seq=%d ",$intTestsSeq);
		}
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return ($arrReturn);
	}
	public function getTestsResultSocialScope($intMyScore,$intScope1,$intScope2,$intScope3,$intScope4){
		if($intScope1>=$intMyScore && $intMyScore>$intScope2){
			$intReturn = 1;
		}else if($intScope2>=$intMyScore && $intMyScore>$intScope3){
			$intReturn = 2;
		}else if($intScope3>=$intMyScore && $intMyScore>$intScope4){
			$intReturn = 3;
		}else if($intScope4>=$intMyScore){
			$intReturn = 4;
		}
		return ($intReturn);
	}
	public function setTestsSocial($intTestsSocialSeq=null,$arr_input=array()){
		$strSubQuery = "";
		foreach($arr_input as $strKey=>$mixValue){
			
			if( $strKey=="test_seq" || $strKey=="writer_seq" || $strKey=="sub_master_seq" ){
				$strSubQuery.=sprintf(",%s=%d",$strKey,$mixValue);
			}else{	
				$strSubQuery.=sprintf(",%s='%s'",$strKey,$mixValue);
			}
		}
		if(is_null($intTestsSocialSeq)){
			//set
			$strQuery = sprintf("insert into test_social_info set create_date=now(),modify_date=now()".$strSubQuery,$intTestsSeq);
		}else{
			//update
			$strQuery = sprintf("update test_social_info set modify_date=now()".$strSubQuery." where seq=%d",$intTestsSocialSeq);
		}
		//print_r($strQuery);
		//exit;
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return ($boolReturn);
	}
	public function updateThumbNail($intPublishSeq,$strSavePath, $strRealFileName){
		$strQuery = sprintf("update test_published set thumb_img_path='%s',thumb_real_name='%s' where seq=%d ",$strSavePath, $strRealFileName,$intPublishSeq);
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	public function getTestsPublishedMaxSort(){
		$strQuery = sprintf("SELECT MAX(sort) AS max_sort FROM test_published ORDER BY sort DESC");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	public function updateTestsPublishedSort($intPublishSeq,$floatAboveSort=0,$floatUpdateSortCount=0.001,$intTopIndex=0,$floatUnderSort=0){
		if($intTopIndex<0){
			//get above sort
			$strQuery = sprintf("SELECT sort above_sort  FROM test_published where sort>%f order by sort asc limit 0,1",$floatUnderSort);
			$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			//if above count 
			if(count($arrReturn)){
				$floatAboveSort = $arrReturn[0]['above_sort'];
				//올림과 버림이 다르다면 intundersotr는 -1
				if(floor($floatAboveSort)==ceil($floatAboveSort)){
					$intUnderSort = ceil($floatAboveSort)-1;
				}else{
					$intUnderSort = floor($floatAboveSort);
				}
				$strQuery = sprintf("SELECT * FROM test_published where sort>%d and sort<%f ORDER BY sort DESC",$intUnderSort,$floatAboveSort);
				$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
				foreach ($arrReturn as $key => $value) {
						$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$value['sort']-$floatUpdateSortCount,$value['seq']);
						$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
					;
				}
				$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$floatAboveSort-$floatUpdateSortCount,$intPublishSeq);
			//else top quiz 
			}else{
				//get max test_publish sort 
				$arrReturn = $this->getTestsPublishedMaxSort();
				$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$arrReturn[0]['max_sort']+$floatUpdateSortCount,$intPublishSeq);
			}
		}else{
			//올림과 버림이 다르다면 intundersotr는 -1
			if(floor($floatAboveSort)==ceil($floatAboveSort)){
				$intUnderSort = ceil($floatAboveSort)-1;
			}else{
				$intUnderSort = floor($floatAboveSort);
			}
			$strQuery = sprintf("SELECT * FROM test_published where sort>%d and sort<%f ORDER BY sort DESC",$intUnderSort,$floatAboveSort);
			$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			/*
			echo "<pre>";
			var_dump($arrReturn);
			echo "</pre>";
			*/
			foreach ($arrReturn as $key => $value) {
					$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$value['sort']-$floatUpdateSortCount,$value['seq']);
					$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
				;
			}
			$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$floatAboveSort-$floatUpdateSortCount,$intPublishSeq);
			//print_r($strQuery);
		}
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	/*
	public function updateTestsPublishedSort($intPublishSeq,$intAboveSort=0,$intTopIndex=0){
		if($intTopIndex<0){
			//get max test_publish sort 
			$strQuery = sprintf("SELECT * FROM test_published where sort<%d ORDER BY sort DESC",$intAboveSort);
			$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$arrReturn[0]['max_sort']+1,$intPublishSeq);
		}else{
			$intUnderSort = floor($floatAboveSort);
			$strQuery = sprintf("SELECT * FROM test_published where sort>%d and sort<%f ORDER BY sort DESC",$intUnderSort,$floatAboveSort);
			print_r($strQuery);
			exit;
			$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			foreach ($arrReturn as $key => $value) {
					$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$value['sort']-$floatUpdateSortCount,$value['seq']);
					$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
				;
			}
			print_r($strQuery);
		}
		$strQuery = sprintf("update test_published set sort=%f where seq=%d ",$floatAboveSort-$floatUpdateSortCount,$intPublishSeq);
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	*/
}
?>