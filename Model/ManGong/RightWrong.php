<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class RightWrong{
	private $resRightWrongDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resRightWrongDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getRightWrongBoard($intRighrWrongListSeq,$intDelFlg=null){
		if(is_null($intDelFlg)){
			$strQuery = sprintf("select * from  right_wrong_board where test_right_wrong_list_seq=%d",$intRighrWrongListSeq);
		}else{
			$strQuery = sprintf("select * from  right_wrong_board where test_right_wrong_list_seq=%d and delete_flg=%d",$intRighrWrongListSeq,$intDelFlg);
		}
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);
	}
	public function getRightWrongBoardBySeq($intRighrWrongSeq){
		$strQuery = sprintf("select * from  right_wrong_board where seq=%d and delete_flg=0",$intRighrWrongSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);
	}
	public function getRightWrongBoardByMemberSeq($intRighrWrongListSeq,$intMemberSeq){
		$strQuery = sprintf("select * from  right_wrong_board where test_right_wrong_list_seq=%d and writer_seq=%d",$intRighrWrongListSeq,$intMemberSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);
	}
	public function setRightWrongBoard($intRightWrongListSeq,$intMemberSeq,$intTeacherSeq,$strMemberName,$strMemberType,$intExampleSeq,$strContents){
		$strQuery = sprintf("insert into right_wrong_board set test_right_wrong_list_seq=%d, writer_seq=%d,teacher_seq=%d,writer_name='%s',writer_type='%s',example_seq=%d,contents='%s',create_date=now()",$intRightWrongListSeq,$intMemberSeq,$intTeacherSeq,$strMemberName,$strMemberType,$intExampleSeq,quote_smart($strContents));
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function updateRightWrongBoard($intMemberSeq,$intRightWrongSeq,$strContents){
		$strQuery = sprintf("update right_wrong_board set contents='%s',modify_date=now() where writer_seq=%d and seq=%d",quote_smart($strContents),$intMemberSeq,$intRightWrongSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function updateRightWrongBoardCommentCount($intRightWrongSeq,$intCommentCount){
		$strQuery = sprintf("update right_wrong_board set comment_count=%d where seq=%d",$intCommentCount,$intRightWrongSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function deleteRightWrongBoard($intMemberSeq,$intRightWrongSeq){
		$strQuery = sprintf("update right_wrong_board set delete_flg=1 where writer_seq=%d and seq=%d",$intMemberSeq,$intRightWrongSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function getTestsRightWrongList($intTestSeq,$intExampleSeq=0,$intDeleteFlg=0,$intQuestionSeq=0){
		if($intDeleteFlg){
			$strQuery = sprintf("select * from  test_right_wrong_list where test_seq=%d and question_example_seq=%d and delete_flg=%d",$intTestSeq,$intExampleSeq,$intDeleteFlg);
		}else{
			if(is_null($intDeleteFlg)){
				$strQuery = sprintf("select * from  test_right_wrong_list where test_seq=%d and question_example_seq=%d",$intTestSeq,$intExampleSeq);
			}else{
				$strQuery = sprintf("select * from  test_right_wrong_list where test_seq=%d and question_example_seq=%d and delete_flg=%d",$intTestSeq,$intExampleSeq,$intDeleteFlg);
			}
		}
		if($intQuestionSeq){
			$strQuery .= sprintf(" and question_seq=%d",$intQuestionSeq);
		}
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function getTestsRightWrongListByTestsSeq($intTestSeq,$intQuestionSeq=0){
		$strQuery = sprintf("select * from  test_right_wrong_list where test_seq=%d and delete_flg=0",$intTestSeq);
		if($intQuestionSeq){
			$strQuery .= sprintf(" and question_seq=%d ",$intQuestionSeq);
		}
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function getTestsRightWrongCount($intTestSeq){
		$strQuery = sprintf("select count(*) as cnt from  test_right_wrong_list where test_seq=%d and delete_flg=0",$intTestSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function setTestsRightWrongList($intMemberSeq,$intTestSeq,$intQuestionSeq,$intExampleSeq,$strContents=null){
		$strQuery = sprintf("insert into test_right_wrong_list set writer_seq=%d,test_seq=%d,question_seq=%d,question_example_seq=%d,right_wrong_contents='%s'",$intMemberSeq,$intTestSeq,$intQuestionSeq,$intExampleSeq,quote_smart($strContents));
		$boolResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($boolResult);		
	}
	public function updateTestsRightWrongList($intMemberSeq,$intTestSeq,$intQuestionSeq,$intExampleSeq,$strContents=null,$strDeleteFlg=null){
		if($strDeleteFlg!='D'){
			$strQuery = sprintf("update test_right_wrong_list set right_wrong_contents='%s',delete_flg=0 where test_seq=%d and question_example_seq=%d",quote_smart($strContents),$intTestSeq,$intExampleSeq);
		}else{
			$strQuery = sprintf("update test_right_wrong_list set right_wrong_contents='%s',delete_flg=1 where test_seq=%d and question_example_seq=%d",quote_smart($strContents),$intTestSeq,$intExampleSeq);
		}
		$boolResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($boolResult);		
	}
	public function updateTestsRightWrongListFlg($intTestSeq,$intQuestionSeq,$intExampleSeq){
		$strQuery = sprintf("update test_right_wrong_list set delete_flg=0 where test_seq=%d and question_seq=%d and question_example_seq=%d",$intTestSeq,$intQuestionSeq,$intExampleSeq);
		$boolReturn = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($boolReturn);		
	}
	public function deleteTestsRightWrongListAll($intTestSeq,$intQuestionSeq){
		$strQuery = sprintf("update test_right_wrong_list set delete_flg=1 where test_seq=%d and question_seq=%d",$intTestSeq,$intQuestionSeq);
		$boolReturn = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($boolReturn);		
	}
	/******** right wrong list ********/
	public function getRightWrongList($intStudentSeq,$mixTeacherSeq=null,$boolMD5=false,$intCategorySeq=null,&$arrPaging,$arrSearch=array()){
		//test paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getRightWrongListCount($intStudentSeq,$mixTeacherSeq,$boolMD5,$intCategorySeq,$arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = "SELECT sjp.*,srw.* 
							FROM (SELECT sp.seq AS published_seq,sp.start_date,sp.finish_date,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.category_seq,sp.group_list_seq,sp.test_prog_flg,s.* FROM test_published AS sp LEFT JOIN test AS s ON sp.test_seq=s.seq WHERE s.delete_flg=0 AND sp.state>0 ";
		if($intStudentSeq){
			$strQuery .= sprintf(" AND (sp.group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) ",$intStudentSeq);
			$strQuery .= sprintf(" OR sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)) ",$intStudentSeq);
			if($mixTeacherSeq && $boolMD5){
				$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
			}else if($mixTeacherSeq && !$boolMD5){
				$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
			}
		}else if($mixTeacherSeq){
			if($mixTeacherSeq && $boolMD5){
				$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
			}else if($mixTeacherSeq && !$boolMD5){
				$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
			}
		}
		$strQuery .= sprintf(" ) sjp
								LEFT JOIN (SELECT test_seq,COUNT(test_seq) AS right_wrong_count FROM test_right_wrong_list WHERE delete_flg=0 GROUP BY test_seq) srw
								ON sjp.seq=srw.test_seq
							WHERE srw.right_wrong_count>0 ");
		if($intCategorySeq){
			$strQuery .= " AND sjp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (sjp.subject like '%".$arrSearch['subject']."%' "." or sjp.contents like '%".$arrSearch['contents']."%')";
		}
		$strQuery .= " order by sjp.seq desc";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrReturn = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrReturn);
	}
	public function getRightWrongListCount($intStudentSeq,$mixTeacherSeq=null,$boolMD5=false,$intCategorySeq=null,$arrSearch=array()){
		$strQuery = "SELECT count(sjp.seq) as cnt 
							FROM (SELECT sp.seq AS published_seq,sp.start_date,sp.finish_date,IF(sp.finish_date < NOW(),4,sp.state) AS state,sp.category_seq,sp.group_list_seq,sp.test_prog_flg,s.* FROM test_published AS sp LEFT JOIN test AS s ON sp.test_seq=s.seq WHERE s.delete_flg=0 AND sp.state>0 ";
		if($intStudentSeq){
			$strQuery .= sprintf(" AND (sp.group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d AND delete_flg=0) ",$intStudentSeq);
			$strQuery .= sprintf(" OR sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)) ",$intStudentSeq);
			if($mixTeacherSeq && $boolMD5){
				$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
			}else if($mixTeacherSeq && !$boolMD5){
				$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
			}
		}else if($mixTeacherSeq){
			if($mixTeacherSeq && $boolMD5){
				$strQuery .= sprintf(" AND md5(s.writer_seq)='%s' ",$mixTeacherSeq);
			}else if($mixTeacherSeq && !$boolMD5){
				$strQuery .= sprintf(" AND s.writer_seq=%d ",$mixTeacherSeq);
			}
		}
		$strQuery .= sprintf(" ) sjp
								LEFT JOIN (SELECT test_seq,COUNT(test_seq) AS right_wrong_count FROM test_right_wrong_list WHERE delete_flg=0 GROUP BY test_seq) srw
								ON sjp.seq=srw.test_seq
							WHERE srw.right_wrong_count>0 ");
		if($intCategorySeq){
			$strQuery .= " AND sjp.category_seq=".$intCategorySeq;
		}
		if(count($arrSearch)>0){
			$strQuery .= " and (sjp.subject like '%".$arrSearch['subject']."%' "." or sjp.contents like '%".$arrSearch['contents']."%')";
		}
		$arrReturn = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	/******** comment ********/
	public function getRightWrongComment($intRighrWrongBoardSeq,$intDelFlg=null){
		if(is_null($intDelFlg)){
			$strQuery = sprintf("select * from  right_wrong_comment where right_wrong_board_seq=%d",$intRighrWrongBoardSeq);
		}else{
			$strQuery = sprintf("select * from  right_wrong_comment where right_wrong_board_seq=%d and delete_flg=%d",$intRighrWrongBoardSeq,$intDelFlg);
		}
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);
	}
	public function setRightWrongComment($intRightWrongBoardSeq,$intMemberSeq,$strMemberName,$strMemberType,$strContents){
		$strQuery = sprintf("insert into right_wrong_comment set right_wrong_board_seq=%d, writer_seq=%d,writer_name='%s',writer_type='%s',contents='%s',create_date=now()",$intRightWrongBoardSeq,$intMemberSeq,$strMemberName,$strMemberType,quote_smart($strContents));
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function deleteRightWrongComment($intMemberSeq,$intRightWrongCommentSeq){
		$strQuery = sprintf("update right_wrong_comment set delete_flg=1 where writer_seq=%d and seq=%d",$intMemberSeq,$intRightWrongCommentSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	public function updateRightWrongComment($intMemberSeq,$intRightWrongCommentSeq,$strContents){
		$strQuery = sprintf("update right_wrong_comment set contents='%s',modify_date=now() where writer_seq=%d and seq=%d",quote_smart($strContents),$intMemberSeq,$intRightWrongCommentSeq);
		$arrResult = $this->resRightWrongDB->DB_access($this->resRightWrongDB,$strQuery);
		return($arrResult);		
	}
	/******** end comment ********/
}
?>