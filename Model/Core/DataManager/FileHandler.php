<?
/**
 * 파일 핸들러 (파일 복사, 이동, 삭제 등의 기능을 수행)
 * @property		string 		$result				수행 결과
 * @property		string 		$strFileExtention		파일확장자
 * @property		string 		$strFileName			파일명
 * @category     	FileHandler
 */
class FileHandler{
	public $result;
	public $strFileExtention;
	public $strFileName = "";
	
	/**
	 * 생성자
	 * @return null
	 */
	public function __construct(){}
	
	public function __destruct(){}
	
	/**
	 * 파일 업로드
	 * @param array $arr_files 파일정보
	 * @return boolean $result 파일 업로드 성공 여부 반환
	 */
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
	
	/**
	 * 파일 업로드 로그 저장
	 * @param resource $resFileDB 리소스 형태의 DB커넥션
	 * @param string $strFileName 파일명
	 * @param integer $intOwnSeq 파일 오너 시컨즈
	 * 
	 * @return boolean $intResult 파일 업로드 로그 저장 성공 여부 반환
	 */
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
	
	/**
	 * 파일 복사
	 * @param array $arr_files 파일 정보
	 * @return boolean $result 파일 복사 성공 여부 반환
	 */
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
	
	/**
	 * 파일 Validation 확인
	 * @param string	 $strFile 파일
	 * @param array	 $arrArrowFileExtion 허용되는 파일 확장자
	 *
	 * @return boolean  허용된 확장자일 경우 true, 아니면 false
	 */
	public function fileValidation($strFile,$arrArrowFileExtion=array()){
		foreach($arrArrowFileExtion as $intKey=>$strExtend){
			$strFileName = ltrim(basename($strFile));
			$arrFileExtentionTemp = explode(".",$strFileName);
			$this->strFileExtention = $strFileExtentinon = $arrFileExtentionTemp[count($arrFileExtentionTemp)-1];
			if(in_array(strtolower(
					Extentinon), $arrArrowFileExtion, true)){ // 차후 mime type 확인으로 바꿀것
				return(true);
			}else{
				return(false);
			}
		}
	}
	
	/**
	 * 파일 이동
	 * @param array $arr_files 파일명
	 * @return boolean $intResult 파일 업로드 로그 저장 성공 여부 반환
	 */
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
	
	/**
	 * 파일 삭제
	 * @param array $arr_files 파일명
	 * @return boolean $result 파일 삭제 성공 여부 반환
	 */
	public function FileDelete($arr_files){
		if(!is_array($arr_files)){
			$arr_files = array($arr_files);
		}
		foreach($arr_files as $key=>$value){
			$this->result = unlink($value);			
		}
		return($this->result);
	}
	
	/**
	 * 파일 디렉토리 생성
	 * @param string $strFilePath 파일Path
	 * @return boolean $boolReturn 파일 디렉토리 생성 성공 여부 반환
	 */
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
	
	/**
	 * 파일 이름 가져오기 ( 중복 시 'a_' 를 붙임  )
	 * @param string $strFilePath 파일Path
	 * @return string 중복체크한 파일 Path 반환
	 */
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