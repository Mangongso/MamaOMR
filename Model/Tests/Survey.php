<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
//require_once("Model/Member/Member.php");

class test{
	public $restestDB = null;
	public $objPaging = null;
	public function __construct($restestDB=null){
		$this->objPaging =  new Paging();
		//$this->objMember =  new Member($restestDB);
		$this->restestDB = $restestDB;
	}
	public function __destruct(){}
	public function settest($intWriterSeq,$inttestType,$strSubject,$strContents,$intExampleNumberingStyle,&$inttestSeq=null,$strTags=''){
		include("Model/test/SQL/MySQL/test/settest.php");
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		if($boolReturn){
			if(!$inttestSeq){
				$mixReturn = $inttestSeq = mysql_insert_id($this->restestDB->res_DB);
			}else{
				$mixReturn = $inttestSeq;
			}
		}else{
			$mixReturn = $boolReturn;
		}
		return($mixReturn);		
	}
	public function gettest($inttestSeq,$intWriterSeq=null){
		if(!$intWriterSeq){
			$strQuery = sprintf("select * from test where seq=%d",$inttestSeq);
		}else{
			$strQuery = sprintf("select * from test where seq=%d and writer_seq=%d",$inttestSeq,$intWriterSeq);
		}
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		$arrReturn[0]['publish'] = $this->gettestPublishInfo($inttestSeq);
		return($arrReturn);
	}
	public function gettestByPublishedSeq($intPublishedSeq){
		// $strQuery = sprintf("select * from test where seq in (select test_seq from test_published where seq=%d)",$intPublishedSeq);
		$strQuery = sprintf("select s.*,sp.seq as publish_seq,sp.start_date,sp.finish_date,unix_timestamp(sp.start_date) as start_time,unix_timestamp(sp.finish_date) as finish_time from test_published as sp join test as s on sp.test_seq=s.seq where sp.seq=%d",$intPublishedSeq);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		$arrReturn[0]['publish'] = $this->gettestPublishInfo($arrReturn[0]['seq']);
		return($arrReturn);		
	}
	public function gettests(){
		$strQuery = "select * from test";
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);
	}	
	public function getSurverSearchQuery($arrSearch){
		$arrWhere = array();
		if(count($arrSearch)>0){
			foreach($arrSearch as $intKey=>$arrResult){
				switch($arrResult['type']){
					default:
						array_push($arrWhere,"(s.subject like '%".$arrResult['keyword']."%' or s.contents like '%".$arrResult['keyword']."%'");
					break;
				}
			}
		}
		if(count($arrWhere)>0){
			$strQuery = join(' and ',$arrWhere);
		}else{
			$strQuery = '';
		}
		return($strQuery);
	}
	public function gettestFromPublishedCount($arrSearch=array(),$boolNullDateShowFlg=true){
		$strQuery = "select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and sp.delete_flg=0";
		if(!$boolNullDateShowFlg){
			$strQuery = $strQuery." and (start_date!='0000-00-00 00:00:00' and finish_date!='0000-00-00 00:00:00')";
		}
		$strWhere = $this->getSurverSearchQuery($arrSearch);
		if(trim($strWhere)){
			$strQuery .= " and (".$strWhere.")";
		}
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}
	public function gettestsFromPublished($arrSearch=array(),$arrOrder=array('type'=>'sp.start_date','sort'=>'DESC'),&$arrPaging=null,$boolNullDateShowFlg=true){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->gettestFromPublishedCount($arrSearch,$boolNullDateShowFlg);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}		
		$strQuery = "select sp.seq as published_seq,start_date,finish_date,state,unix_timestamp(sp.start_date) as start_time,unix_timestamp(sp.finish_date) as finish_time,s.* from test_published as sp left join test as s on sp.test_seq=s.seq  where s.delete_flg=0 and sp.delete_flg=0";
		if(!$boolNullDateShowFlg){
			$strQuery = $strQuery." and (start_date!='0000-00-00 00:00:00' and finish_date!='0000-00-00 00:00:00')";
		}		
		$strWhere = $this->getSurverSearchQuery($arrSearch);
		if(trim($strWhere)){
			$strQuery .= " and (".$strWhere.")";
		}
		$strQuery .= " order by ".$arrOrder['type']." ".$arrOrder['sort'].",s.seq DESC";
		if($arrPaging){
			$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
		}	
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);
	}	
	public function gettestIngList(){
		$strQuery = "select sp.seq as published_seq,start_date,finish_date,state,UNIX_TIMESTAMP(start_date) as start_time,UNIX_TIMESTAMP(finish_date) as finish_time,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where sp.start_date<=now() and sp.finish_date>=now() order by sp.start_date DESC,s.seq DESC";
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);		
	} 
	public function gettestPublishInfo($inttestSeq,$intPublishedType=0){
		$strQuery = sprintf("select *,UNIX_TIMESTAMP(start_date) as start_unix_time,UNIX_TIMESTAMP(finish_date) as finish_unix_time from test_published where test_seq=%d and published_type=%d and delete_flg=0 order by start_date",$inttestSeq,$intPublishedType);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);
	}
	public function deletetestPublish($inttestSeq,$intPublishSeq){
		$strQuery = sprintf("update test_published set delete_flg=1 where test_seq=%d and seq=%d and published_type=0",$inttestSeq,$intPublishSeq);
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($boolReturn);		
	}
 	public function deletetest($inttestSeq){
		include("Model/test/SQL/MySQL/test/deletetest.php");
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($boolReturn);
	}
	public function updatetestStatus($inttestSeq,$intSruveyStatus){
		include("Model/test/SQL/MySQL/test/updatetestStatus.php");
		$arrtest = $this->gettest($inttestSeq);
		return($boolReturn);
	}
	public function updatetestPublish($inttestSeq,$intPublishSeq,$strStartDate,$strFinishDate,$intPublishType=0){
		$strQuery = sprintf("update test_published set start_date='%s',finish_date='%s',published_type=%d where test_seq=%d and seq=%d",$strStartDate,$strFinishDate,$inttestSeq,$intPublishSeq,$intPublishType);
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($boolReturn);		
	}
	public function checkDefaulttestPublished($inttestSeq){
		$strQuery = sprintf("select count(*) as cnt from test_published where test_seq=%d and published_type=1",$inttestSeq);
		$arrResult = $this->restestDB->DB_access($this->restestDB,$strQuery);
		if($arrResult[0]['cnt']>0){
			return(true);
		}else{
			return(false);
		}
	}
	public function getDefaulttestPublished($inttestSeq){
		$strQuery = sprintf("select * from test_published where test_seq=%d and published_type=1",$inttestSeq);
		$arrResult = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrResult);
	}	
	public function publishtest($inttestSeq,$strStartDate,$strFinishDate,$intPublishType=0){
		$strQuery = sprintf("insert into test_published (test_seq,start_date,finish_date,state,delete_flg,published_date,published_type) values (%d,'%s','%s',0,0,now(),%d)",$inttestSeq,$strStartDate,$strFinishDate,$intPublishType);
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($boolReturn);		
	}
	public function settestTags($inttestSeq,$arrTags,$boolDeleteFlg = false){
		if($boolDeleteFlg){
			$boolResult = $this->deletetestTag($inttestSeq);
		}
		if(is_array($arrTags) && count($arrTags)>0){
			$arrValues = array();
			foreach($arrTags as $intKey=>$strTags){
				if(trim($strTags)){
					array_push($arrValues,sprintf("(%d,'%s',now())",$inttestSeq,$strTags));
				}
			}
			$strQuery = "insert into test_tags (test_seq,tag,create_date) values ".join(',',$arrValues);
			$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
			return($boolReturn);
		}else{
			$boolReturn = false;
		}
		return($boolReturn);
	}	
	public function gettestTags($inttestSeq){
		$strQuery = sprintf("select * from test_tags where test_seq=%d",$inttestSeq);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);	
	}	
	public function deletetestTag($inttestSeq,$intTagSeq=null){
		if($intTagSeq){
			$strQuery = sprintf("delete from test_tags where test_seq=%d and seq",$intTagSeq);
		}else{
			$strQuery = sprintf("delete from test_tags where test_seq=%d",$inttestSeq);
		}
		$boolReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($boolReturn);
	}	
	//set testEntry by user group
	public function settestEntry($inttestSeq,$intUserGroupSeq=null,$intUserSeq,$strStartDate,$strEndDate){
		$strQuery = sprintf("INSERT INTO test_join_user (test_seq, user_group_seq, user_seq, start_date, end_date, test_user_status_flg, delete_flg) 
					  				VALUES (%d, %d, %d, '%s', '%s', default, default)",$inttestSeq,$intUserGroupSeq,$intUserSeq,$strStartDate,$strEndDate);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);	
	}
	public function deletetestEntry($inttestJoinUserSeq){
		$strQuery = sprintf("UPDATE test_join_user set delete_flg=1 where seq=%d",$inttestJoinUserSeq);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);
	}	
	public function gettestQuestionList($inttestSeq){
		$strQuery = sprintf("select SQ.test_seq,SQ.question_seq,Q.writer_seq, Q.contents, Q.question_type, Q.example_type, Q.create_date, Q.modify_date from test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq where SQ.test_seq=%d",$inttestSeq);
		$arrReturn = $this->restestDB->DB_access($this->restestDB,$strQuery);
		return($arrReturn);		
	}
}
?>