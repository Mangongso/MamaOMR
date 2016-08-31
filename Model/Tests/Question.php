<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Answer.php");

class Question{
	public $resQuestionDB = null;
	public $objPaging = null;
	public $objAnswer = null;
	public function __construct($resQuestionDB=null,$resAnswerDB=null){
		$this->objPaging =  new Paging();
		$this->resQuestionDB = $resQuestionDB;
		$this->objAnswer = new Answer($resAnswerDB);
	}
	public function __destruct(){}
	/***** get question ********/
	public function getQuestion($intQuestionSeq,$intPublishedSeq=null){
		//get question (with jimoon)
		include("Model/Tests/SQL/MySQL/Question/getQuestion.php");
		$arrQuestionResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		$arrQuestionResult[0]['arr_question_tag'] = $this->getQuestionTag($intQuestionSeq);
		$arrQuestionResult[0]['arr_question_example'] = $this->getQuestionExample($intQuestionSeq);
		foreach($arrQuestionResult[0]['arr_question_example']['type_1'] as $strRowKey=>$arrExampleRow){
			if(count($arrQuestionResult[0]['arr_question_example']['type_2'])>0){
				foreach($arrQuestionResult[0]['arr_question_example']['type_2'] as $strColKey=>$arrExampleCol){
					$arrQuestionResult[0]['arr_question_example']['answer_count'][$strRowKey][$strColKey] = $this->objAnswer->getAnswerCount($intQuestionSeq,$arrExampleRow['seq'],$arrExampleCol['seq'],$intPublishedSeq);
				}
			}else{
				$arrQuestionResult[0]['arr_question_example']['type_1'][$strRowKey]['answer_count'] = $this->objAnswer->getAnswerCount($intQuestionSeq,$arrExampleRow['seq'],null,$intPublishedSeq);
				if($arrQuestionResult[0]['question_type']==2){
					$arrQuestionResult[0]['arr_question_example']['type_1'][$strRowKey]['answer_group_count'] = $this->objAnswer->getAnswerGroupCount($intQuestionSeq,$arrExampleRow['seq'],$intPublishedSeq);
				}				
			}
		}	
		return($arrQuestionResult);
	}
	public function getExampleByExampleText($intQuestionSeq,$strUserAnswer=''){
		$strQuery = sprintf("select * from question_example where question_seq=%d and contents='%s'",$intQuestionSeq,trim($strUserAnswer));
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}	
	public function getQuestionByQuestionString($intTestsSeq,$strQuestion){
		$strQuery = sprintf("select question_seq from test_question_list where question_seq in (select seq from question where contents='%s') and test_seq=%d",$strQuestion,$intTestsSeq);
		$strQuery = "select * from question where seq in (".$strQuery.")";
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function getQuestions($intWriterSeq=null,$intQuestionType=null,$strQuestionTagName=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestions.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function getQuestionByType($intQuestionType,$intWriterSeq=null){
		$arrResult = $this->getQuestions($intWriterSeq,$intQuestionType);
		return($arrResult);		
	}
	public function getQuestionByTagName($strQuestionTagName,$intWriterSeq=null){
		$arrResult = $this->getQuestions($intWriterSeq,null,$strQuestionTagName);
		return($arrResult);		
	}
	public function getQuestionFromTests($intTestsSeq,$intPublishedSeq=null){
		$strQuery = sprintf("select * from test_question_list where test_seq=%d order by sort",$intTestsSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrResult as $intKey=>$arrQuestion){
			$arrDummy = $this->getQuestion($arrQuestion['question_seq'],$intPublishedSeq);
			$arrResult[$intKey]['question'] = $arrDummy[0];
		}
		return($arrResult);		
	}
	public function getQuestionResult($intTestsSeq,$intPublishedSeq=null){
		$strQuery = sprintf("select * from test_question_list where test_seq=%d",$intTestsSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrResult as $intKey=>$arrQuestion){
			$arrDummy = $this->getQuestionWithResultCount($arrQuestion['question_seq'],$intPublishedSeq);
			$arrResult[$intKey]['question'] = $arrDummy[0];
		}
		return($arrResult);
	}	
	/******** set question ********/
	public function setQuestion($intWriterSeq,$strContents,$intQuestionType,$intExampleType,$intQuestionJimoonSeq=null,&$intQuestionSeq=null,$intRequired=0,$intHiddenFlg=0){
		include("Model/Tests/SQL/MySQL/Question/setQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if(!$intQuestionSeq){
			$intQuestionSeq = mysql_insert_id($this->resQuestionDB->res_DB);
		}
		return($boolReturn);
	}
 	public function deleteQuestion($intTestsSeq,$intQuestionSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intPublishedSeq){
			$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and published_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intPublishedSeq,$intQuestionSeq);
		}else{
			$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intQuestionSeq);
		}
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	//question extend info
	public function setQuestionExtendInfo($arrInput,&$intQuestionExtendSeq=null){
		
	}
	public function updateQuestionExtendInfo($arrInput,$intQuestionExtendSeq){
		
	}
	public function deleteQuestionExtendInfo($intQuestionExtendSeq){
		
	}
	
	//question tag
	public function getQuestionTag($intQuestionSeq){
		//get tags
		include("Model/Tests/SQL/MySQL/Question/getQuestionTag.php");
		$arrQuestoinTagResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return ($arrQuestoinTagResult);
	}
	public function seqQuestionTag($intQuestionSeq,$strTagName){
		include("Model/Tests/SQL/MySQL/Question/setQuestionTag.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function deleteQuestionTag($intQuestionTagSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionTag.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	//question jimoon
	public function setQuestionJimoon($strContents,$intQuestionJimoonSeq=null){
		include("Model/Tests/SQL/MySQL/Question/setQuestionJimoon.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function deleteQuestionJimoon($intQuestionJimoonSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionJimoon.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/* question example */
	public function getQuestionExample($intQuestionSeq,$intExampleType=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestionExample.php");
		if($intExampleType){
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryRows)
			);			
		}else{
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryRows),
					'type_2'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryCols)
					);
		}
		return($arrResult);		
	}
	public function setQuestionExample($intQuestionSeq,$strContents,$intAnswerFlg=0,$strExampleType=null,$intGotoQuestionSeq=0,&$intQuestionExampleSeq=null){
		if($strContents){
			// example seq 에 해당되는 example 가 있는지 확인 해서 있을 경우 update 없을 경우 insert 한다.
			if(!$this->checkQuestionExampleByExampleSeq($intQuestionSeq,$intQuestionExampleSeq)){
				$intQuestionExampleSeq = null;
			}
			include("Model/Tests/SQL/MySQL/Question/setQuestionExample.php");
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
			if(!$intQuestionExampleSeq){
				$intQuestionExampleSeq = mysql_insert_id($this->resQuestionDB->res_DB);
			}
		}else{
			$boolReturn = true;
		}
		return($boolReturn);		
	}
	public function checkQuestionExampleByExampleSeq($intQuestionSeq,$intExampleSeq){
		$strQuery = sprintf("select count(*) as cnt from question_example where question_seq=%d and seq=%d and delete_flg=0",$intQuestionSeq,$intExampleSeq);
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}	
	public function deleteQuestionExample($intQuestionExampleSeq,$strExampleType=null){
		switch($strExampleType){
			case("1"):
				include("Model/Tests/SQL/MySQL/Question/deleteQuestionExampleType1.php");
				$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
				return($boolReturn);
				break;
		}
	}
	public function deleteQuestionExampleExceptExampleSeq($intQuestionSeq,$arrExceptExampleSeq){
		if(count($arrExceptExampleSeq)>0){
			$strQuery = sprintf("update question_example set delete_flg=1 where question_seq=%d and seq not in (%s)",$intQuestionSeq,join(",",$arrExceptExampleSeq));
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		}else{
			$boolReturn = true;
		}
		return($boolReturn);
	}
	public function setQuestionToTests($intTestsSeq,$intQuestionSeq,$intSetType=null,$intPosition=null){
		if(is_null($intPosition)){
			$strQuery = sprintf("insert into test_question_list (test_seq,question_seq,sort) select %d,%d,max(sort)+1 from test_question_list where test_seq=%d",$intTestsSeq,$intQuestionSeq,$intPosition,$intTestsSeq);
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);			
		}else{
// 			var_dump($intSetType);
			switch($intSetType){
				case(2):
					$strQuery = sprintf("update test_question_list set sort=sort+1 where test_seq=%d and sort>%d",$intTestsSeq,$intPosition+1);
					$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
					break;
				case(1):
					$strQuery = sprintf("update test_question_list set sort=sort+1 where test_seq=%d and sort>=%d",$intTestsSeq,$intPosition);
					$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
					break;
			}
			$strQuery = sprintf("insert into test_question_list set test_seq=%d,question_seq=%d,sort=%d",$intTestsSeq,$intQuestionSeq,$intPosition);
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);			
		}
		return($boolReturn);		
	}
	public function checkQuestionInTests($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("select count(*) as cnt from test_question_list where test_seq=%d and question_seq=%d",$intTestsSeq,$intQuestionSeq);
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function setQuestionResultToTests($intTestsSeq,$intPublishedSeq,$intQuestionSeq){
		$strQuery = sprintf("SELECT user_email,question_seq,question_answer,user_answer,result_flg,create_date,%d,%d,example_seq,matrix_example_seq,0,1 FROM user_answer WHERE delete_flg=0 AND answer_type=0 AND question_seq=%d",$intPublishedSeq,$intTestsSeq,$intQuestionSeq);
		$strQuery = "insert into user_answer (user_email,question_seq,question_answer,user_answer,result_flg,create_date,published_seq,test_seq,example_seq,matrix_example_seq,delete_flg,answer_type) ".$strQuery;
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function deleteQuestionInTests($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("delete from test_question_list where test_seq=%d and question_seq=%d",$intTestsSeq,$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function updateQuestionPosition($intTestsSeq,$arrSortData){
		foreach($arrSortData as $intKey=>$arrSort){
			$strQuery = sprintf("update test_question_list set sort=%d where test_seq=%d and question_seq=%d",$arrSort['position'],$intTestsSeq,$arrSort['seq']);
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		}
		return($boolReturn);
	}
	private function buildQuestionGroupSearchQuery($arrSearch){
		$arrWhere = array();
		foreach($arrSearch as $intKey=>$arrResult){
			switch($arrResult['search_type']){
				case('all'):
					array_push($arrWhere,"group_name like '%".sprintf('%s',$arrResult['search_keyword'])."%'");
				break;
				default:
					array_push($arrWhere,$arrResult['search_type']." like '%".sprintf('%s',$arrResult['search_keyword'])."%'");
				break;
			}
		}
		if(count($arrWhere)>0){
			$strWhere = join(' and ',$arrWhere);
		}
	}
	public function getQuestionGroupListCount($arrSearch){
		$strQuery = "select count(*) as cnt from question_group where delete_flg=0";
		$strWhere = $this->buildQuestionGroupSearchQuery($arrSearch);
		if($strWhere){
			$strQuery = $strQuery.' and '.$strWhere;
		}
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	public function getQuestionGroupList($arrSearch=array(),$arrOrder,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getQuestionGroupListCount($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strWhere = $this->buildQuestionGroupSearchQuery($arrSearch);
		$strQuery = "select * from question_group where delete_flg=0";
		if($strWhere){
			$strQuery = $strQuery." AND ".$strWhere;
		}
		$strQuery .= " order by ".$arrOrder['type']." ".$arrOrder['sort'];
		if($arrPaging){
			$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
		}
		$arrQuestionGroup = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrQuestionGroup as $intKey=>$arrResult){
			$arrQuestionGroup[$intKey]['node_cnt'] = $this->getNodeByQuestionGroupSeq($arrResult['seq'],1);
			$arrQuestionGroup[$intKey]['test_cnt'] = $this->getTestsByQuestionGroupSeq($arrResult['seq'],1);
			$arrQuestionGroup[$intKey]['question_cnt'] = $this->getQuestionByQuestionGroupSeq($arrResult['seq'],1);
		}
		return($arrQuestionGroup);		
	}
	public function getQuestionByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		if($intContFlg){
			$strQuery = sprintf("select count(*) as cnt from question_group_list where group_seq=%d",$intGroupSeq);
		}else{
			$strQuery = sprintf("select q.contents as question, qgl.* from question_group_list as qgl left join question as q on qgl.question_seq=q.seq where qgl.group_seq=%d",$intGroupSeq);
		}
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	public function getNodeByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		if($intContFlg==0){
			$strColumn = "*";
		}else{
			$strColumn = "count(*) as cnt";
		}
		$strQuery = sprintf("select ".$strColumn." from idg_related_contents 
					 where test_seq in (
					 	select test_seq from test_question_list where question_seq in (
					 		select question_seq from question_group_list where group_seq=%d
					 	)
					 )",$intGroupSeq);
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	public function getTestsByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		if($intContFlg==0){
		$strQuery = sprintf("select *,(select max(seq) from test_published where delete_flg=0 and test_seq=s.seq) as test_published_seq from test as s
				where seq in (
				select test_seq from test_question_list where question_seq in (
				select question_seq from question_group_list where group_seq=%d
		)
		)",$intGroupSeq);
		}else{
		$strQuery = sprintf("select count(*) as cnt from test
				where seq in (
				select test_seq from test_question_list where question_seq in (
				select question_seq from question_group_list where group_seq=%d
		)
		)",$intGroupSeq);
		}

		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	public function getQuestionListFormQuestionGroup($intQuestionGroupSeq){
		
	}
	public function setQuestionGroup($strGroupName,$arrQuestionSeq){
		$strQuery = sprintf("insert into question_group set group_name='%s',create_date=now(),delete_flg=0",$strGroupName);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($boolReturn && count($arrQuestionSeq)>0){
			$intGroupSeq = mysql_insert_id($this->resQuestionDB->res_DB);
			$boolReturn = $this->setQuestionToGroup($intGroupSeq,$arrQuestionSeq);
		}
		return($boolReturn);
	}
	public function setQuestionToGroup($intGroupSeq,$arrQuestionSeq){
		$arrValues = array();
		foreach($arrQuestionSeq as $intKey=>$intQuestionSeq){
			array_push($arrValues,sprintf("(%d,%d,now())",$intGroupSeq,$intQuestionSeq));
		}
		$strQuery = "insert into question_group_list (group_seq,question_seq,create_date) values ".join(',',$arrValues);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function updateQuestionGroup($intGroupSeq,$strGroupName){
		$strQuery = sprintf("update question_group set group_name='%s' where seq=%d",$strGroupName,$intGroupSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function getQuestionGroup($intGroupSeq){
		$strQuery = sprintf("select * from question_group where seq=%d",$intGroupSeq);
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn);
	}
	public function deleteQuestionGroup($arrGroupSeq){
		if(!is_array($arrGroupSeq)){
			$arrGroupSeq = array($arrGroupSeq);
		}
		$strQuery = sprintf("update question_group set delete_flg=1 where seq in (%s)",join(',',$arrGroupSeq));
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function deleteQuestionInGroup($intGroupSeq,$intQuestionSeq){
		$strQuery = sprintf("delete from question_group_list where group_seq=%d and question_seq=%d",$intGroupSeq,$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
}
?>