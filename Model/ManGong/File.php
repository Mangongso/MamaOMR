<?
require_once("Model/Core/DataManager/DataHandler.php");

class File{
	private $resFileDB = null;
	public function __construct($resProjectDB=null){
		$this->resFileDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getTestsFile($intTestsSeq,$intPublishSeq=null){
		$strQuery = sprintf("select * from test_upload_file where test_seq=%d ",$intTestsSeq);
		if($intPublishSeq){
			$strQuery .= sprintf(" and test_published_seq=%d ",$intPublishSeq);
		}
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult);		
	}
	public function getTestsFileSize($intTestsSeq,$intPublishSeq=null){
		$strQuery = sprintf("select sum(file_size) as file_size from test_upload_file where test_seq=%d ",$intTestsSeq);
		if($intPublishSeq){
			$strQuery .= sprintf(" and test_published_seq=%d ",$intPublishSeq);
		}
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult[0]['file_size']?$arrResult[0]['file_size']:0);		
	}
	public function getTestsFileBySeq($intFileSeq){
		$strQuery = sprintf("select * from test_upload_file where seq=%d ",$intFileSeq);
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult);		
	}
	public function setTestsFile($intTestsSeq,$intPublishSeq,$intMemberSeq,$fileName,$fileRename,$fileType,$fileSize,$fileSaveDir){
		//get file list
		$strQuery = sprintf("insert into test_upload_file set test_seq=%d,test_published_seq=%d,member_seq=%d,upload_file_name='%s',upload_file_rename='%s',file_type='%s',file_size=%d,save_dir='%s',reg_date=now()",$intTestsSeq,$intPublishSeq,$intMemberSeq,$fileName,$fileRename,$fileType,$fileSize,quote_smart($fileSaveDir));
		$boolResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($boolResult);		
	}
	public function deleteTestsFile($intFileSeq,$intTestsSeq,$intPublishSeq){
		if($intFileSeq){
			$strQuery = sprintf("delete from test_upload_file where seq=%d and test_seq=%d and test_published_seq=%d",quote_smart($intFileSeq),quote_smart($intTestsSeq),quote_smart($intPublishSeq));
			$boolResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		}else{
			$boolResult = false;
		}
		return($boolResult);		
	}
	public function getReportFile($intReportSeq){
		$strQuery = sprintf("select * from report_upload_file where report_seq=%d",$intReportSeq);
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult);		
	}
	public function getReportFileSize($intReportSeq){
		$strQuery = sprintf("select sum(file_size) as file_size  from report_upload_file where report_seq=%d",$intReportSeq);
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult[0]['file_size']?$arrResult[0]['file_size']:0);		
	}
	public function getReportFileBySeq($intFileSeq){
		$strQuery = sprintf("select * from report_upload_file where seq=%d",$intFileSeq);
		$arrResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($arrResult);
	}
	public function setReportFile($intReportSeq,$intMemberSeq,$fileName,$fileRename,$fileType,$fileSize,$fileSaveDir){
		//get file list
		$strQuery = sprintf("insert into report_upload_file set report_seq=%d,member_seq=%d,upload_file_name='%s',upload_file_rename='%s',file_type='%s',file_size=%d,save_dir='%s',reg_date=now()",$intReportSeq,$intMemberSeq,$fileName,$fileRename,$fileType,$fileSize,quote_smart($fileSaveDir));
		$boolResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		return($boolResult);		
	}
	public function deleteReportFile($intFileSeq,$intReportSeq){
		if($intFileSeq){
			$strQuery = sprintf("delete from report_upload_file where seq=%d and report_seq=%d",quote_smart($intFileSeq),quote_smart($intReportSeq));
			$boolResult = $this->resFileDB->DB_access($this->resFileDB,$strQuery);
		}else{
			$boolResult = false;
		}
		return($boolResult);		
	}
	public function moveUploadFile($fileName,$tmpFileName,&$fileRename,$fileSize,$arrFileType,$intMaxFileSize,$fileSaveDir='',$fileNameMd5=false){
		//check file size
		if((int)$fileSize > (int)$intMaxFileSize){
			$intCheckFileFlg = 1; 
		}
		//check extention
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if(!in_array(strtolower($ext),$arrFileType) && $arrFileType[0]!="all") { 
			$intCheckFileFlg = 2;
		}
		switch($intCheckFileFlg){
			case(FILE_SIZE_OVER):
				//echo '업로드 최대 허용 size는 '.($maxfilesize/100000).'MB 입니다.';
			break;
			case(FILE_EXTENSION_NOT_ARROWED):
				//echo $_POST['file_type'].' 해당 확장자만 업로드 할수 있습니다.';
			break;
			default:
				if($fileSize > 0){
					if(is_uploaded_file($tmpFileName)){
						//set rename
						if($fileNameMd5){
							$fileRename = md5($_SESSION[$_COOKIE['member_token']]['member_seq']).'_'.date("YmdHis").'.'.$ext;
						}else{
							$fileRename = date("YmdHis").'_'.$fileName;
						}
						if(!$fileSaveDir){
							$fileSaveDir = POST_FILE_UPLOAD_DIR;
						}
						if(!is_dir($fileSaveDir)){
							$this->makeDir($fileSaveDir);
						}
						if(move_uploaded_file($tmpFileName,$fileSaveDir.DIRECTORY_SEPARATOR.iconv("UTF-8","cp949",$fileRename))){
							$boolResult = true;
						}
					}
				}else{
					//echo '파일 업로드 오류!';
				}
			break;
		}
		return $boolResult;
	}
	function makeDir($strDir){
		$arrDirectory = explode(DIRECTORY_SEPARATOR,$strDir);
		$strFillDirectory = "";
		foreach($arrDirectory as $intKey=>$strDirectory){
			if(trim($strDirectory)){
				if($intKey==0 && preg_match ("/^windows/i",getenv("OS"))){
					$strFillDirectory = $strDirectory;
				}else{
					$strFillDirectory = $strFillDirectory.DIRECTORY_SEPARATOR.$strDirectory;
				}
				if(!file_exists($strFillDirectory)){
					$old = umask(0);
					mkdir($strFillDirectory, 0777, true);
					umask($old);
				}
			}
		}
	}
}
?>