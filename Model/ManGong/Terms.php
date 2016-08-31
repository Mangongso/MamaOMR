<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Terms{
	private $resTermsDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resTermsDB = $resProjectDB;
	}
	public function __destruct(){}
	
	/*
	private $arrITWTopic = array(
		"62076"=>"가상화"				,
		"61023"=>"개발자"				,
		"62073"=>"BYOD"				,
		"62078"=>"네트워크"			,
		"35"	=>"데이터센터"		,
		"55815"=>"디지털디바이스"		,
		"61022"=>"디지털 마케팅"		,
		"62072"=>"디지털이미지"		,
		"37"	=>"모바일"			,
		"65212"	=>"미래기술"			,
		"36"	=>"보안"				,
		"54652"=>"브라우저"			,
		"62077"=>"VDI"				,
		"65210"=>"BI/분석"			,
		"54649"=>"빅데이터"			,
		"63417"=>"사물인터넷"			,
		"62080"=>"서버"				,	
		"38"	=>"소셜미디어"		,
		"55816"=>"스마트TV"			,
		"62075"=>"스마트폰"			,
		"62084"=>"스토리지"			,
		"65209"=>"3D프린팅"			,
		"62081"=>"CIO"				,
		"62082"=>"IT관리"			,
		"54647"=>"iOS"				,
		"62079"=>"안드로이드"			,
		"39"	=>"애플리케이션"		,
		"62086"=>"오픈소스"			,
		"40"	=>"오피스＆협업"		,
		"62071"=>"웨어러블컴퓨팅"		,
		"62085"=>"웹서비스"			,
		"54650"=>"윈도우"				,
		"63355"=>"UX"				,
		"62083"=>"컨슈머라이제이션"	,
		"34"	=>"클라우드"			,
		"54653"=>"클라우드오피스"		,
		"54651"=>"태블릿"				,
		"54648"=>"특허전쟁"			,
		"62074"=>"퍼스널컴퓨팅"		,
		"65211"=>"프라이버시"			
	);
	*/
	public function changeTermTypeToVid($strTermType){
		switch($strTermType){
			case('topic'):
				$intVid = 9;
			break;
		}
		return $intVid;
	}
	public function getTerms($intMemberSeq=null,$strTermType=null,$intTid=null,$intRtnType=1){
		/*
		$arrResult = array();
		if($intVid==9){//topic
			$arrResult = $this->arrITWTopic;//default topic
		}
		*/
		$arrWhere = array();
		
		if(!is_null($intMemberSeq)){
			array_push($arrWhere, sprintf(" (writer_seq=%d or writer_seq=0) ",$intMemberSeq));
		}
		if(!is_null($strTermType)){
			$intVid = $this->changeTermTypeToVid($strTermType);
			//$strQuery .= sprintf(" and vid=%d ",$intVid);
			array_push($arrWhere, sprintf(" vid=%d ",$intVid));
		}
		if(!is_null($intTid)){
			//$strQuery .= sprintf(" and tid=%d ",$intTid);
			array_push($arrWhere, sprintf(" tid=%d ",$intTid));
		}
		
		$strQuery = sprintf( "select * from term_data where ".join(' and ', $arrWhere) );
		$arrTermsResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
		
		if($intRtnType==1){
			$arrResult = $arrTermsResult;
		}else{
			$arrResult = array();
			foreach($arrTermsResult as $intKey=>$arrTerms){
				$arrResult[$arrTerms['tid']]=$arrTerms['name'];
			}
		}
		return($arrResult);
	}
	/* getTerms 로 통합
	public function getTerm($intMemberSeq,$intTid){
		$strQuery = sprintf("select * from term_data where (writer_seq=%d or writer_seq=0) and tid=%d ",$intMemberSeq,$intTid);
		$arrResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
		return($arrResult);
	}
	*/
	public function setTerm($intMemberSeq,$strTermType,$strName,&$intTid=null){
		$intVid = $this->changeTermTypeToVid($strTermType);
		if(!is_null($intTid) && trim($intTid)!=''){
			$strQuery = sprintf("update term_data set name='%s' where tid=%d and writer_seq=%d",$strName,$intTid,$intMemberSeq);
			$boolResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
		}else{
			$strQuery = sprintf("insert into term_data set writer_seq=%d,vid=%d,name='%s'",$intMemberSeq,$intVid,$strName);
			$boolResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
			$intTid = mysql_insert_id($this->resTermsDB->res_DB);
		}
		return($boolResult);		
	}
	/* 삭제시 term_test 데이터도 삭제가되어야 하는지 확인이 필요 */
	public function deleteTerm($intMemberSeq,$intTid){
		$strQuery = sprintf("delete from term_data where tid=%d and writer_seq=%d",$intTid,$intMemberSeq);
		$boolResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
		return($boolResult);		
	}
	public function getTermTests($intTestsSeq=null,$intTid=null,$intVid=null){
		$strQuery = sprintf("select * from term_test");
		$arrWhere = array();
		if(!is_null($intTid))
			array_push($arrWhere, sprintf(" tid=%d ",$intTid));
		if(!is_null($intTestsSeq))
			array_push($arrWhere, sprintf(" test_seq=%d ",$intTestsSeq));
		if(!is_null($intVid))
			array_push($arrWhere, sprintf(" vid=%d ",$intVid));	
		if(count($arrWhere))
			$strQuery .= " where ".join(" and ", $arrWhere);
		$arrResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
		return $arrResult;
	}
	public function setTermTests($intMemberSeq,$intTid,$intTestsSeq){
		//check is my term add 
		$arrTerms = $this->getTerms($intMemberSeq,null,$intTid);
		//$intVid = $arrTerm[0]['vid'];
		if(count($arrTerms)){
			foreach($arrTerms as $intKey=>$arrResult){
				$intVid = $arrResult['vid'];
				$strQuery = sprintf("insert into term_test(tid,test_seq,vid) values(%d,%d,%d) on duplicate key update tid=%d,test_seq=%d,vid=%d",$intTid,$intTestsSeq,$intVid,$intTid,$intTestsSeq,$intVid);
				$boolResult = $this->resTermsDB->DB_access($this->resTermsDB,$strQuery);
			}
			//$intTid = mysql_insert_id($this->resTermsDB->res_DB);
		}
		return($boolResult);		
	}
	
}
?>