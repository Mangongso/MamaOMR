<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Category{
	private $resCategoryDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resCategoryDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getCategory($intCategoryType,$intMemberSeq){
		$strQuery = sprintf("select * from category where category_type=%d and writer_seq=%d and delete_flg=0",$intCategoryType,$intMemberSeq);
		$arrResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		return($arrResult);		
	}
	public function setCategory($intMemberSeq,$strCategoryName,$intCategoryType,&$intCategorySeq){
		$strQuery = sprintf("insert category set writer_seq=%d,title='%s',category_type=%d,create_date=now()",$intMemberSeq,$strCategoryName,$intCategoryType);
		$boolResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		$intCategorySeq = mysql_insert_id($this->resCategoryDB->res_DB);
		return($boolResult);		
	}
	public function updateCategory($intCategorySeq,$strCategoryName){
		$strQuery = sprintf("update category set title='%s',modify_date=now() where seq=%d",$strCategoryName,$intCategorySeq);
		$boolResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		return($boolResult);		
	}
	public function getCategoryBySeq($intCategorySeq){
		$strQuery = sprintf("select * from category where seq=%d",$intCategorySeq);
		$arrResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		return($arrResult);		
	}
	public function checkCategoryName($intMemberSeq,$strCategoryName,$intCategoryType){
		$strQuery = sprintf("select * from category where writer_seq=%d and title='%s' and category_type=%d and delete_flg=0",$intMemberSeq,$strCategoryName,$intCategoryType);
		$arrResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		$boolResult = false;
		if(count($arrResult)>0){
			$boolResult = true;
		}
		return($boolResult);		
	}
	public function deleteCategory($intCategorySeq,$intWriterSeq){
		$strQuery = sprintf("update category set delete_flg=1 where seq=%d and writer_seq=%d",$intCategorySeq,$intWriterSeq);
		$boolResult = $this->resCategoryDB->DB_access($this->resCategoryDB,$strQuery);
		return($boolResult);		
	}
}
?>