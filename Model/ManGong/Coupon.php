<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Coupon{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resCouponDB = $resProjectDB;
	}
	public function __destruct(){}
	
	//get coupon
	public function setCoupon($intTeacherSeq,$strCouponAuthKey,$intType,$intUseDay,$strStartDate=null,$strEndDate=null){
		$strQuery = sprintf("INSERT INTO coupon (auth_key, teacher_seq, type, use_day, create_date, start_date, end_date) VALUES ('%s', %d, %d, %d, now(), '%s', '%s')",$strCouponAuthKey,$intTeacherSeq,$intType,$intUseDay,$strStartDate,$strEndDate);
		$boolResult = $this->resCouponDB->DB_access($this->resCouponDB,$strQuery);
		return($boolResult);
	}
	public function updateCouponUseflg($strCouponAuthKey){
		$strQuery = sprintf("update coupon set use_flg=1,modify_date=now() where auth_key='%s' and use_flg=0 ",$strCouponAuthKey);
		$boolResult = $this->resCouponDB->DB_access($this->resCouponDB,$strQuery);
		return($boolResult);
	}
	public function applyCouponToStudent($strCouponAuthKey,$intStudentSeq){
		$strQuery = sprintf("update coupon set student_seq=%d,modify_date=now() where auth_key='%s' and use_flg=0 ",$intStudentSeq,$strCouponAuthKey);
		$boolResult = $this->resCouponDB->DB_access($this->resCouponDB,$strQuery);
		return($boolResult);
	}
	public function checkCouponDuplicatoin($strCouponAuthKey){
		$strQuery = sprintf("select count(*) from coupon where auth_key='%s'",$strCouponAuthKey);
		$intResultCnt = $this->resCouponDB->DB_access($this->resCouponDB,$strQuery);
		return($intResultCnt);
	}
	public function getCoupon($strCouponAuthKey=null,$intTeacherSeq=null,$intStudentSeq=null,$intType=1,$intUseFlg=0){
		$strQuery = sprintf("select * from coupon where delete_flg=0 ");
		if($strCouponAuthKey){
			$strQuery .= sprintf(" and auth_key='%s' ",$strCouponAuthKey);
		}
		if($intTeacherSeq){
			$strQuery .= sprintf(" and teacher_seq=%d ",$intTeacherSeq);
		}
		if(!is_null($intType)){
			$strQuery .= sprintf(" and type=%d ",$intType);
		}
		if($intType==2){
			if(is_null($intStudentSeq)){
				$strQuery .= sprintf(" and student_seq IS NULL ");
			}else{
				$strQuery .= sprintf(" and student_seq=%d ",$intStudentSeq);
			}
		}
		$strQuery .= sprintf(" and use_flg=%d ",$intUseFlg);
		$arrResult = $this->resCouponDB->DB_access($this->resCouponDB,$strQuery);
		return($arrResult);
	}
}
?>