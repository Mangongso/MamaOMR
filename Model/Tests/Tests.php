<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

/**
 * 테스트 정보를 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Tests
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resTestsDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Tests
 */
class Tests{
	public $resTestsDB = null;
	public $objPaging = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resTestsDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resTestsDB=null){
		$this->objPaging =  new Paging();
		$this->resTestsDB = $resTestsDB;
	}
	
	/**
	 * 소멸자
	 */
	public function __destruct(){}
	
	/**
	 * 테스트를 등록한다.
	 *
	 * @param integer $intWriterSeq 테스트 등록 유저 시컨즈
	 * @param integer $intTestsType 테스트 타입
	 * @param string $strSubject 테스트 제목
	 * @param string $strContents 테스트 내용
	 * @param string $intExampleNumberingStyle 예제 넘버림 스타일
	 * @param string &$intTestsSeq 저장된 테스트의 시컨즈 번호를 담는 변수
	 * @param string $strTags 태그
	 *
	 * @return mix 테스트 저장 성공 여부. (false 또는 true) 또는 저장된 테스트의 시컨즈 번호를 반환
	 */
	public function setTests($intWriterSeq,$intTestsType,$strSubject,$strContents,$intExampleNumberingStyle,&$intTestsSeq=null,$strTags=''){
		include("Model/Tests/SQL/MySQL/Tests/setTests.php");
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
	
	/**
	 * 테스트 목록을 조회.
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intWriterSeq 테스트 등록 유저 시컨즈
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTests($intTestsSeq,$intWriterSeq=null){
		include("Model/Tests/SQL/MySQL/Tests/getTests.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$arrReturn[0]['publish'] = $this->getTestsPublishInfo($intTestsSeq);
		return($arrReturn);
	}
	
	/**
	 * 테스트 목록을 퍼블리시 시컨즈를 기준으로 조회
	 *
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTestsByPublishedSeq($intPublishedSeq){
		include("Model/Tests/SQL/MySQL/Tests/getTestsByPublishedSeq.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$arrReturn[0]['publish'] = $this->getTestsPublishInfo($arrReturn[0]['seq']);
		return($arrReturn);		
	}
	
	/**
	 * 테스트 목록을 조회
	 *
	 * @return array test table 참조
	 */
	public function getTestss(){
		include("Model/Tests/SQL/MySQL/Tests/getTestss.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}	
	
	/**
	 * 테스트 검색 쿼리문
	 *
	 * @param array $arrSearch 검색 조건 배열
	 *
	 * @return string 테스트검색 쿼리문 반환
	 */
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
	
	/**
	 * 테스트 목록을 퍼블리시 시컨즈를 기준으로 조회한 count
	 *
	 * @param integer $arrSearch 테스트 퍼블리시 시컨즈
	 * @param boolean $boolNullDateShowFlg null 보여주기 flg
	 *
	 * @return integer 테스트 목록을 퍼블리시 시컨즈를 기준으로 조회한 count를 반환
	 */
	public function getTestsFromPublishedCount($arrSearch=array(),$boolNullDateShowFlg=true){
		include("Model/Tests/SQL/MySQL/Tests/getTestsFromPublishedCount.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}
	
	/**
	 * 테스트를 퍼블리시 시컨즈를 기준으로 조회
	 *
	 * @param array $arrSearch 검색 조건 배열
	 * @param array $arrOrder order 배열
	 * @param array $arrPaging 페이징 정보 배열
	 * @param boolean $boolNullDateShowFlg null 보여주기 flg
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTestssFromPublished($arrSearch=array(),$arrOrder=array('type'=>'sp.start_date','sort'=>'DESC'),&$arrPaging=null,$boolNullDateShowFlg=true){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTestsFromPublishedCount($arrSearch,$boolNullDateShowFlg);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}		
		include("Model/Tests/SQL/MySQL/Tests/getTestssFromPublished.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}	
	
	/**
	 * 테스트 진행중인 목록 조회
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTestsIngList(){
		include("Model/Tests/SQL/MySQL/Tests/getTestsIngList.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);		
	} 
	
	/**
	 * 테스트 퍼블리시 목록 조회
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedType 테스트 퍼블리시 형식
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTestsPublishInfo($intTestsSeq,$intPublishedType=0){
		include("Model/Tests/SQL/MySQL/Tests/getTestsPublishInfo.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 테스트 퍼블리시 삭제
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return boolean 테스트 퍼블리시 삭제 성공 여부 반환
	 */
	public function deleteTestsPublish($intTestsSeq,$intPublishSeq){
		include("Model/Tests/SQL/MySQL/Tests/deleteTestsPublish.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 테스트 삭제
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return boolean 테스트 삭제 성공 여부 반환
	 */
 	public function deleteTests($intTestsSeq){
		include("Model/Tests/SQL/MySQL/Tests/deleteTests.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 테스트 상태 수정
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intSruveyStatus 테스트 상태
	 *
	 * @return boolean 테스트 상태 수정 성공 여부 반환
	 */
	public function updateTestsStatus($intTestsSeq,$intSruveyStatus){
		include("Model/Tests/SQL/MySQL/Tests/updateTestsStatus.php");
		$arrTests = $this->getTests($intTestsSeq);
		return($boolReturn);
	}
	
	/**
	 * 테스트 published 저장
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intPublishSeq 저장된 테스트 published 시컨즈를 담는 변수
	 * @param string $strStartDate 테스트 시작일시
	 * @param string $strFinishDate 테스트 종료일시
	 * @param integer $intPublishType 형식 
	 *
	 * @return boolean  테스트 published 저장 성공여부 반환 true 또는 false
	 */
	public function updateTestsPublish($intTestsSeq,$intPublishSeq,$strStartDate,$strFinishDate,$intPublishType=0){
		include("Model/Tests/SQL/MySQL/Tests/updateTestsPublish.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);		
	}

	/**
	 * 테스트 퍼블리시 기본 확인
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return boolean 테스트 퍼블리시 기본 여부 확인 반환
	 */
	public function checkDefaultTestsPublished($intTestsSeq){
		include("Model/Tests/SQL/MySQL/Tests/checkDefaultTestsPublished.php");
		$arrResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		if($arrResult[0]['cnt']>0){
			return(true);
		}else{
			return(false);
		}
	}

	/**
	 * 테스트 퍼블리시 기본 목록을 조회
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return array test_published table 참조
	 */
	public function getDefaultTestsPublished($intTestsSeq){
		include("Model/Tests/SQL/MySQL/Tests/getDefaultTestsPublished.php");
		$arrResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrResult);
	}	

	/**
	 * 테스트 퍼블리시 저장
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param string $strStartDate 시작일
	 * @param string $strFinishDate 마지막날
	 * @param integer $intPublishType 테스트 퍼블리시 형식
	 *
	 * @return boolean 테스트 퍼블리시 저장 성공 여부 반환
	 */
	public function publishTests($intTestsSeq,$strStartDate,$strFinishDate,$intPublishType=0){
		include("Model/Tests/SQL/MySQL/Tests/publishTests.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);		
	}

	/**
	 * 테스트 태그 저장
	 *
	 * @param integer $intTestsSeq 테스트 퍼블리시 시컨즈
	 * @param integer $arrTags 테스트 태그 배열
	 * @param integer $boolDeleteFlg 삭제 flg
	 *
	 * @return boolean 테스트 태그 저장 성공 여부 반환
	 */
	public function setTestsTags($intTestsSeq,$arrTags,$boolDeleteFlg = false){
		if($boolDeleteFlg){
			$boolResult = $this->deleteTestsTag($intTestsSeq);
		}
		if(is_array($arrTags) && count($arrTags)>0){
			include("Model/Tests/SQL/MySQL/Tests/setTestsTags.php");
			$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
			return($boolReturn);
		}else{
			$boolReturn = false;
		}
		return($boolReturn);
	}	

	/**
	 * 테스트 태그 목록 조회 
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return array test_tags table 참조
	 */
	public function getTestsTags($intTestsSeq){
		include("Model/Tests/SQL/MySQL/Tests/getTestsTags.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);	
	}	

	/**
	 * 테스트 태그 삭제
	 *
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return boolean 테스트 태그 삭제 성공 여부 반환
	 */
	public function deleteTestsTag($intTestsSeq,$intTagSeq=null){
		include("Model/Tests/SQL/MySQL/Tests/deleteTestsTag.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}	

	/**
	 * 테스트 참여 유저 저장
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intUserGroupSeq 우저 그룹 시컨즈
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $strStartDate 시작일
	 * @param integer $strEndDate 마지막날 
	 *
	 * @return boolean 테스트 참여유저 정보 저장 성공 여부 반환
	 */
	//set testEntry by user group
	public function setTestsEntry($intTestsSeq,$intUserGroupSeq=null,$intUserSeq,$strStartDate,$strEndDate){
		include("Model/Tests/SQL/MySQL/Tests/setTestsEntry.php");
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolResult);	
	}

	/**
	 * 테스트 참여 유저 삭제
	 *
	 * @param integer $intTestsJoinUserSeq 테스트참여유 시컨즈
	 *
	 * @return boolean 테스트 참여 유저 삭제 성공 여부 반환
	 */
	public function deleteTestsEntry($intTestsJoinUserSeq){
		include("Model/Tests/SQL/MySQL/Tests/deleteTestsEntry.php");
		$boolResult = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolResult);
	}	

	/**
	 * 테스트 문제 목록을 조회
	 *
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return array question ,test_question_list table 참조
	 */
	public function getTestsQuestionList($intTestsSeq){
		include("Model/Tests/SQL/MySQL/Tests/getTestsQuestionList.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);		
	}
}
?>