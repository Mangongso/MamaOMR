<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
/**
 * 오답노트 정보를 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Mangong/Test
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resWrongDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Tests
 */

class WrongNote{
	private $resWrongDB = null;
	private $objPaging = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resWrongDB = $resMangongDB;
	}
	public function __destruct(){}
	
	
	/**
	 * 오답노트 목록을 조회.
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $intNoteSeq 오답노트 시쿼즈
	 *
	 * @return array wrong_note table 참조
	 */
	public function getWrongNote($intUserSeq,$intNoteSeq=null){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongNote.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 문제에 종속된 오답노트 조회.
	 *
	 * @param integer $strMemberSeq 유저 시컨즈
	 * @param integer $intRecordSeq 성적 시쿼즈
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intQuestionSeq 문제 시쿼즈
	 *
	 * @return array wrong_note,question table 참조
	 */
	public function getWrongNoteFromQuestion($strMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongNoteFromQuestion.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 오답노트 조회 개수
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param array $arrSearch 오답노트 검색 조건
	 * @param mixed $mixTeacherSeq md5암호화 선생님 시컨즈 또는 integer 선생님 시컨즈
	 * @param boolean $boolMD5 암호화된 $mixTeacherSeq인지 확인
	 *
	 * @return integer 오답노트 조회 개수를 반환
	 */
	public function getWrongNoteListCount($intUserSeq,$arrSearch,$mixTeacherSeq,$boolMD5){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongNoteListCount.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	
	/**
	 * 오답노트 조회 개수
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param array $arrSearch 오답노트 검색 조건 배열
	 * @param array $arrOrder 순서 조건 배열
	 * @param mixed $mixTeacherSeq md5암호화 선생님 시컨즈 또는 integer 선생님 시컨즈
	 * @param boolean $boolMD5 암호화된 $mixTeacherSeq인지 확인
	 * @param array $arrPaging 페이징 정보 배열
	 *
	 * @return array 오답노트 조회 결과를 반환
	 */
	public function getWrongNoteList($intUserSeq,$arrSearch,$arrOrder,$mixTeacherSeq=null,$boolMD5=false,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getWrongNoteListCount($intUserSeq,$arrSearch,$mixTeacherSeq,$boolMD5);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
					);
		}
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongNoteList.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 오답노트를 가져온다
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $intNoteSeq 오답노트 시컨즈
	 *
	 * @return array 오답노트 결과를 반환
	 */
	public function getWrongAnswer($intUserSeq,$intNoteSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongAnswer.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 오답노트 저장
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param string $strNoteTitle 오답노트 검색 조건 배열
	 *
	 * @return boolean  오답노트 저장 성공여부 반화 ( treu | false )
	 */
	public function setWrongNote($intUserSeq,$strNoteTitle){
		include("Model/ManGong/SQL/MySQL/WrongNode/setWrongNote.php");
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		if($boolResult){
			$mixResult = mysql_insert_id($this->resWrongDB->res_DB);
		}else{
			$mixResult = $boolResult;
		}
		return($mixResult);		
	}
	
	/**
	 * 오답노트 리스트  저장 
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intNoteSeq 오답노트 시컨즈
	 * @param integer $intRecordSeq 성적 시컨즈
	 * @param integer $intTestSeq 테스트 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intUserAnswer 유저 선택 답
	 * @param string $strWrongNoteFileName 첨부파일 명
	 * @param string $strQuestion 문제내용
	 *
	 * @return boolean 오답노트 리스트 성공여부 반화 ( treu | false )
	 */
	public function setWrongNoteQuestion($intNoteSeq,$intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq,$intUserAnswer,$strWrongNoteFileName,$strQuestion){
		$arrWrongNote = $this->getWrongNoteFromQuestion($intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq);
		if(count($arrWrongNote)>0){
			include("Model/ManGong/SQL/MySQL/WrongNode/setWrongNoteQuestionUpdate.php");
		}else{
			include("Model/ManGong/SQL/MySQL/WrongNode/setWrongNoteQuestionInsert.php");
		}
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}	
	
	/**
	 * 오답노트 삭제
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $intWrongNoteSeq 오답노트 시컨즈
	 *
	 * @return boolean 오답노트 삭제 성공여부 반화 ( treu | false )
	 */
	public function deleteWrongNote($intUserSeq,$intWrongNoteSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/deleteWrongNote.php");
		$strQuery = sprintf("update wrong_note set delete_flg=1 where user_seq=%d and seq=%d",$intUserSeq,$intWrongNoteSeq);
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);
	}	
	
	/**
	 * 오답노트 수정
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $intWrongNoteSeq 오답노트 시컨즈
	 * @param string $strNoteTitle 오답노트 제목
	 *
	 * @return boolean 오답노트 수정 성공여부 반화 ( treu | false )
	 */
	public function updateWrongNote($intUserSeq,$strNoteTitle,$intWrongNoteSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/updateWrongNote.php");
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 오답노트와 유저선택 답을 테스트 시컨즈를 기준으로 조회
	 *
	 * @param integer $intTestSeq 테스트 시컨즈
	 * @param integer $intRecordSeq 성적 시컨즈
	 * @param string $intStudentSeq 유저 시컨즈
	 *
	 * @return array user_answer,wrong_note_list table 참조
	 */
	public function getWrongAnswerNoteFromTest($intTestSeq,$intRecordSeq=null,$intStudentSeq=null){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongAnswerNoteFromTest.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);						
	}
	
	/**
	 * 오답노트 리스트 삭제
	 *
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param integer $intWrongNoteListSeq 오답노트 리스트 시컨즈
	 *
	 * @return boolean 오답노트 리스트 삭제 성공여부 반화 ( treu | false )
	 */
	public function deleteWrongNoteList($intUserSeq,$intWrongNoteListSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/deleteWrongNoteList.php");
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
}
?>