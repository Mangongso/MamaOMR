<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
class Book{
	public $objPaging;
	public $resBookDB;
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resBookDB = $resMangongDB;
	}
	public function __destruct(){}
	public function setBook($intWriterSeq,$intSubWriterSeq,$strTitle,$strPubName=null,$strPubDate=null,$strCoverUrl=null,$strIsbnCode=null,&$intBookSeq=null,$intCategorySeq=null,$strAuthor=null){
		include("Model/ManGong/SQL/MySQL/Book/setBook.php");
		$boolReturn = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		$intBookSeq = mysql_insert_id($this->resBookDB->res_DB);
		return($boolReturn);
	}
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
	public function getBookCnt($arrSearch=array()){
		include("Model/ManGong/SQL/MySQL/Book/getBookCnt.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	public function getUserJoinBookList($strMemberSeq){
		include("Model/ManGong/SQL/MySQL/Book/getUserJoinBookList.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	
	public function getTestListByBook($strBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/getTestListByBook.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	public function getTestSeqByBook($intBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/getTestSeqByBook.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['group_seq']);
	}
	public function updatePublishedBookSeq($intPublishedSeq,$strBookSeq){
		include("Model/ManGong/SQL/MySQL/Book/updatePublishedBookSeq.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult);
	}
	public function getBookSeqFromTestSeq($intTestSeq){
		include("Model/ManGong/SQL/MySQL/Book/getBookSeqFromTestKey.php");
		$arrResult = $this->resBookDB->DB_access($this->resBookDB,$strQuery);
		return($arrResult[0]['book_seq']);		
	}
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