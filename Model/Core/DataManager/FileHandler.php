<?
class FileHandler{
	public $result;
	public $strFileExtention;
	public $strFileName = "";
	public function __construct(){}
	public function __destruct(){}
	public function FileUpload($arr_files){
		if(count($arr_files)>0){
			$arr_file_info = array();
			foreach($arr_files as $key=>$value){
				if($value[error]==0){
					$arrDirectory = explode(DIRECTORY_SEPARATOR,$value[save_dir]);
					$strFillDirectory = "";
					foreach($arrDirectory as $intKey=>$strDirectory){
						if(trim($strDirectory)){
							if($intKey==0 && eregi("^windows",getenv("OS"))){
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
					array_push($arr_file_info,array(source=>$value[tmp_name],target=>sprintf("%s".DIRECTORY_SEPARATOR."%s",$value[save_dir],$value[save_name])));
				}
			}
			$this->result = $this->FileMove($arr_file_info);			
		}
		return($this->result);
	}
	public function setFileUploadLogs($resFileDB,$strFileName,$intOwnSeq=null){
		$strFileName = ltrim(basename(" " . $strFileName));
		$strQuery = sprintf("insert into file_upload_logs set reg_date=now(),orignal_file_name='%s',own_seq=%d",$strFileName,$intOwnSeq);
		if($resFileDB->DB_access($resFileDB,$strQuery)){
			$intResult = mysql_insert_id();
		}else{
			$intResult = 0;
		}
		return($intResult);			
	}
	public function FileCopy($arr_files){
		foreach($arr_files as $value){	
			$this->makeFileDirectory($value['target']);
			$value['target'] = $this->getFileNameWithDupCheck($value['target']);			
			if(!$this->result = copy($value['source'],$value['target'])){
				break;
			}
		}
		return($this->result);
	}
	public function fileValidation($strFile,$arrArrowFileExtion=array()){
		foreach($arrArrowFileExtion as $intKey=>$strExtend){
			$strFileName = ltrim(basename($strFile));
			$arrFileExtentionTemp = explode(".",$strFileName);
			$this->strFileExtention = $strFileExtentinon = $arrFileExtentionTemp[count($arrFileExtentionTemp)-1];
			if(in_array(strtolower($strFileExtentinon), $arrArrowFileExtion, true)){ // 차후 mime type 확인으로 바꿀것
				return(true);
			}else{
				return(false);
			}
		}
	}
	public function FileMove($arr_files){
		foreach($arr_files as $value){
			$this->makeFileDirectory($value['target']);
			$this->getFileNameWithDupCheck($value['target']);
			if(!$this->result = move_uploaded_file($value['source'],$value['target'])){
				break;
			}
		}
		return($this->result);
	}
	public function FileDelete($arr_files){
		if(!is_array($arr_files)){
			$arr_files = array($arr_files);
		}
		foreach($arr_files as $key=>$value){
			$this->result = unlink($value);			
		}
		return($this->result);
	}
	public function makeFileDirectory($strFilePath){
		$arrDirectory = explode(DIRECTORY_SEPARATOR,$strFilePath);
		unset($arrDirectory[0]);
		unset($arrDirectory[count($arrDirectory)]);
		$strDirChecker = ""; 
		foreach($arrDirectory as $intKey=>$strDirName){
			if(trim($strDirName) || $intKey==0){
				if($intKey==0 && preg_match ("/^windows/i",getenv("OS"))){
					$strDirChecker = $strDirName;
				}else{
					$strDirChecker = $strDirChecker.DIRECTORY_SEPARATOR.$strDirName;
				}
				if(!file_exists($strDirChecker)){
					$old = umask(0);
					$boolReturn = mkdir($strDirChecker, 0777, true);
					umask($old);					
				}
			}else{
				$boolReturn = false;
				break;
			}
		}
		return($boolReturn);
	}
	public function getFileNameWithDupCheck($strFilePath){
		$arrDirectory = explode(DIRECTORY_SEPARATOR,$strFilePath);
		$strFileName = $arrDirectory[count($arrDirectory)-1];
		unset($arrDirectory[count($arrDirectory)-1]);		
		$strDir = join(DIRECTORY_SEPARATOR,$arrDirectory);
		while(file_exists($strDir.DIRECTORY_SEPARATOR.$strFileName)){
			$strFileName = "a_".$strFileName;
		}
		$this->strFileName = $strFileName;
		return($strDir.DIRECTORY_SEPARATOR.$strFileName);
	}
}
?>