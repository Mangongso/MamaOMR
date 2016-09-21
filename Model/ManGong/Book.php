<?
/**
 * 문제집을 등록, 수정, 삭제, 조회하고 문제집에 종속되는 테스트를 조회한다. 
 *
 * @package      	Mangong/Book
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resBookDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Book
 */

require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Book{
	public $objPaging;
	public $resBookDB;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resBookDB = $resMangongDB;
	}
	public function __destruct(){}
	
	/**
	 * 문제집을 등록한다.
	 *
	 * @param integer $intWriterSeq 마마OMR 대표 선생님 시컨즈 ("/Controller/_Lib/Constant.php" 파일에 define되어 있음)
	 * @param integer $intSubWriterSeq 문제집 등록 유저시컨즈
	 * @param string $strTitle 문제집 제목
 	 * @param string $strPubName 출판사명
	 * @param string $strPubDate 출판일
	 * @param string $strCoverUrl 커버이미지 Url
	 * @param string $strIsbnCode ISBN 코드
	 * @param integer &$intBookSeq 문제집 Insert 시컨스 담는 변수 
	 * @param integer $intCategorySeq 문제집 카테고리
	 * @param string $strAuthor 저자명
	 * 
	 * @return boolean 문제집 저장 성공 여부. (false 또는 true)
	 */
	public function setBook($intWriterSeq,$intSubWriterSeq,$strTitle,$strPubName=null,$strPubDate=null,$strCoverUrl=null,$strIsbnCode=null,&$intBookSeq=null,$intCategorySeq=null,$strAuthor=null){
		include("Model/ManGong/SQL/MySQL/Book/setBook.php");
		$boolReturn = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		$intBookSeq = mysql_insert_id($this->resBookDB->res_DB);
		return($boolReturn);
	}
	
	/**
	 * 문제집을 검색 조건에 맞게 가져온다.
	 *
	 * @param array $arrSearch 검색 조건
	 * @param array $arrQueId 문제집 시컨즈 
	 * @param array $arrPaging 페이징 조건
	 *
	 * @return array book_info table 참조
	 */
	public function getBook($arrSearch=array(),$arrQueId=array(),&$arrPaging=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getBookCnt($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/ManGong/SQL/MySQL/Book/getBook.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 문제집을 검색 개수 조회
	 *
	 * @param array $arrSearch 검색 조건
	 *
	 * @return integer 문제집 검색 개수 반환
	 */
	public function getBookCnt($arrSearch=array()){
		include("Model/ManGong/SQL/MySQL/Book/getBookCnt.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	
	/**
	 * 유저가 참여한 문제집 테스트 목록 조회
	 *
	 * @param string $strMemberSeq md5 암호화 된 유저 시컨즈
	 *
	 * @return array book_info,test_join_user table 참조
	 */
	public function getUserJoinBookList($strMemberSeq){
		include("Model/ManGong/SQL/MySQL/Book/getUserJoinBookList.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 문제집에 속한 테스트 목록 조회
	 *
	 * @param string $strBookSeq md5 암호화 된 문제집 시컨즈
	 *
	 * @return array test,,test_published table 참조
	 */
	public function getTestListByBook($strBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/getTestListByBook.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 문제집에 속한 테스트 시컨즈를 조회
	 *
	 * @param integer $intBookSeq 문제집 시컨즈
	 *
	 * @return string 테스트시쿼즈를 반환
	 */
	public function getTestSeqByBook($intBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/getTestSeqByBook.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['group_seq']);
	}
	
	/**
	 * test_published 테이블의 book_seq를 업데이트 한다.
	 *
	 * @param integer $intPublishedSeq test_published 테이블의 시컨즈
	 * @param string $strBookSeq md5 암호화 된 문제집 시컨즈
	 *
	 * @return boolean 업데이트 성공 여부를 반환 false 또는 true
	 */
	public function updatePublishedBookSeq($intPublishedSeq,$strBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/updatePublishedBookSeq.php");
		$boolResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($boolResult);
	}
	
	/**
	 * 테스트 시컨즈 번호를 기준으로 문제집의 시컨즈 번호를 가져온다.
	 *
	 * @param integer $intTestSeq 테스트 시컨즈
	 *
	 * @return integer 문제집 시컨즈 번호를 반환
	 */
	public function getBookSeqFromTestSeq($intTestSeq){
		include("Model/ManGong/SQL/MySQL/Book/getBookSeqFromTestKey.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['book_seq']);		
	}
	
	/**
	 * curl을 통해 문제집 정보를 가져온다.
	 *
	 * @param string $url 문제집 API Url
	 *
	 * @return string 문제집 정보
	 */
	public function get_xml_from_url($url){
	    $ch = curl_init();
	
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	
	    $xmlstr = curl_exec($ch);
	    curl_close($ch);
	
	    return $xmlstr;
	}
}
?>