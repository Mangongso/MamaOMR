<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class NewPost{
	private $objPaging = null;
	private $resNewPostDB = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resNewPostDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getNewReportCommentList($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime,&$arrPaging){
		//report paging
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewReportCommentListCount($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		switch($strMemberType){
			case("T"):
				$strQuery = sprintf("SELECT pc.*,
										r.subject,
										r.create_date,
										r.category_seq 
									FROM (SELECT * FROM post_comment 
											WHERE post_seq IN(SELECT seq FROM report WHERE writer_seq=%d AND delete_flg=0) 
											AND bbs_seq=%d AND reg_date > '%s') pc 
									LEFT OUTER JOIN (select * from report where writer_seq=%d and delete_flg=0) r
									ON pc.post_seq = r.seq ",$intMemberSeq,$intBbsSeq,$dateCompareTime,$intMemberSeq);
				break;
			case("S"):
				$arrReportSeq = array();
				$arrReportInfo = $this->getReportSeq($intMemberSeq);
				if(count($arrReportInfo)){
					foreach($arrReportInfo as $intKey=>$arrResult){
						array_push($arrReportSeq,$arrResult['seq']);
					}
				}else{
					array_push($arrReportSeq,0);
				}
				$strQuery = "SELECT pc.*,
								r.subject,
								r.create_date,
								r.category_seq  
							FROM (SELECT * FROM post_comment 
								WHERE post_seq IN(".join(',',$arrReportSeq).") 
								AND reg_date > '".$dateCompareTime."'
								AND bbs_seq=".$intBbsSeq.") pc
							LEFT OUTER JOIN (select * from report where delete_flg=0) r
							ON pc.post_seq = r.seq ";
			break;
		}
		$strQuery .= " order by pc.reg_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewReportCommentListCount($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime){
		switch($strMemberType){
			case("T"):
				$strQuery = sprintf("SELECT count(*) as cnt FROM post_comment 
									WHERE post_seq IN(SELECT seq FROM report WHERE writer_seq=%d AND delete_flg=0) 
									AND bbs_seq=%d AND reg_date > '%s'",$intMemberSeq,$intBbsSeq,$dateCompareTime);
				break;
			case("S"):
				$arrReportSeq = array();
				$arrReportInfo = $this->getReportSeq($intMemberSeq);
				if(count($arrReportInfo)){
					foreach($arrReportInfo as $intKey=>$arrResult){
						array_push($arrReportSeq,$arrResult['seq']);
					}
				}else{
					array_push($arrReportSeq,0);
				}
				$strQuery = "SELECT count(*) as cnt
							FROM post_comment 
							WHERE post_seq IN(".join(',',$arrReportSeq).") 
							AND reg_date > '".$dateCompareTime."'
							AND bbs_seq=".$intBbsSeq;
			break;
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getNewTestCommentList($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewTestCommentListCount($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		switch($strMemberType){
			case("T"):
				$strQuery = sprintf("SELECT pc.*,
										s.subject,
										s.create_date,
										s.category_seq 
									FROM (SELECT * FROM post_comment 
										WHERE post_seq IN (SELECT seq FROM test WHERE writer_seq=%d AND delete_flg=0 AND type=1) AND bbs_seq=%d AND reg_date >'%s') pc 
									LEFT OUTER JOIN (SELECT su.seq,su.subject,sp.category_seq,su.create_date FROM test su,test_published sp WHERE su.seq=sp.test_seq AND su.writer_seq=%d AND su.delete_flg=0 AND su.type=1) s
									ON pc.post_seq = s.seq ",$intMemberSeq,$intBbsSeq,$dateCompareTime,$intMemberSeq);
				break;
			case("S"):
				$arrTestSeq = array();
				$arrTestInfo = $this->getTestSeq($intMemberSeq);
				if(count($arrTestInfo)){
					foreach($arrTestInfo as $intKey=>$arrResult){
						array_push($arrTestSeq,$arrResult['test_seq']);
					}
				}else{
					array_push($arrTestSeq,0);
				}
				$strQuery = "SELECT pc.*,
									s.subject,
									s.create_date,
									s.category_seq  
							FROM (SELECT * FROM post_comment 
								WHERE post_seq IN(".join(',',$arrTestSeq).") 
								AND reg_date > '".$dateCompareTime."'
								AND bbs_seq=".$intBbsSeq.") pc
							LEFT OUTER JOIN (SELECT su.seq,su.subject,sp.category_seq,su.create_date FROM test su,test_published sp WHERE su.seq=sp.test_seq AND su.delete_flg=0 AND su.type=1) s
							ON pc.post_seq = s.seq ";
			break;
		}
		$strQuery .= " order by pc.reg_date desc";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewTestCommentListCount($intMemberSeq,$strMemberType,$intBbsSeq,$dateCompareTime){
		switch($strMemberType){
			case("T"):
				$strQuery = sprintf("SELECT count(*) as cnt 
									FROM post_comment 
									WHERE post_seq IN (SELECT seq FROM test WHERE writer_seq=%d AND delete_flg=0 AND type=1) 
									AND bbs_seq=%d AND reg_date >'%s' ",$intMemberSeq,$intBbsSeq,$dateCompareTime);
				break;
			case("S"):
				$arrTestSeq = array();
				$arrTestInfo = $this->getTestSeq($intMemberSeq);
				if(count($arrTestInfo)){
					foreach($arrTestInfo as $intKey=>$arrResult){
						array_push($arrTestSeq,$arrResult['test_seq']);	
					}
				}else{
					array_push($arrTestSeq,0);
				}
				$strQuery = "SELECT count(*) as cnt
							FROM post_comment 
							WHERE post_seq IN(".join(',',$arrTestSeq).") 
							AND reg_date > '".$dateCompareTime."'
							AND bbs_seq=".$intBbsSeq;
			break;
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getNewCommentCount($intMemberSeq,$intMemberType,$arrBbsSeq,$dateCompareTime,$intMaxSeq=null){
		switch($intMemberType){
			case("T"):
				$strQuery = sprintf("SELECT count(*) as cnt, max(cmt_id) as max_seq FROM post_comment 
									WHERE (post_seq IN (SELECT seq FROM test WHERE writer_seq=%d AND delete_flg=0 AND type=1) 
									OR post_seq IN (SELECT seq FROM report WHERE writer_seq=%d AND delete_flg=0)) ",$intMemberSeq,$intMemberSeq);
				$strQuery .= " and bbs_seq in (".join(',',$arrBbsSeq).") ";
				$strQuery .= sprintf(" and reg_date > '%s' ",$dateCompareTime);
				if($intMaxSeq){
					$strQuery .= sprintf(" and cmt_id > %d ",$intMaxSeq);
				}
				$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
				break;
			case("S"):
				$arrTestSeq = array();
				$arrTestInfo = $this->getTestSeq($intMemberSeq);
				foreach($arrTestInfo as $intKey=>$arrResult){
					array_push($arrTestSeq,$arrResult['test_seq']);
				}
				$arrReportSeq = array();
				$arrReportInfo = $this->getReportSeq($intMemberSeq);
				if(count($arrReportInfo)){
					foreach($arrReportInfo as $intKey=>$arrResult){
						array_push($arrReportSeq,$arrResult['seq']);
					}
				}
				if(count($arrTestSeq) || count($arrReportSeq)){
					$arrMergeResult = array_merge($arrTestSeq,$arrReportSeq);
					$strQuery = "select count(*) as cnt, max(cmt_id) as max_seq from post_comment where post_seq in (".join(',',$arrMergeResult).") ";
					$strQuery .= " and bbs_seq in (".join(',',$arrBbsSeq).") ";
					$strQuery .= sprintf(" and reg_date > '%s' ",$dateCompareTime);
					if($intMaxSeq){
						$strQuery .= sprintf(" and cmt_id > %d ",$intMaxSeq);
					}
					$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
				}else{
					$arrResult[0]['cnt']=0;
				}
			break;
		}
		return($arrResult);		
	}
	public function getTestSeq($intMemberSeq,$arrTestType=array(1)){
		$strQuery = sprintf("SELECT test_seq 
							FROM test_published sp,test s WHERE sp.test_seq=s.seq   
							and (sp.group_list_seq IN (SELECT group_seq FROM group_user_list WHERE student_seq=%d AND delete_flg=0)  
							OR sp.test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)) 
							AND s.delete_flg=0 AND s.type IN(".join(',',$arrTestType).")",$intMemberSeq,$intMemberSeq);
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getReportSeq($intMemberSeq){
		$strQuery = sprintf("SELECT seq 
							FROM report WHERE 
							(user_group_seq IN (SELECT group_seq FROM group_user_list WHERE student_seq=%d AND delete_flg=0) 
							OR seq IN (SELECT report_seq FROM report_join_user WHERE user_seq=%d and delete_flg=0))
							AND delete_flg=0 ",$intMemberSeq,$intMemberSeq);
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewReportCount($intMemberSeq,$intMemberType,$dateCompareTime,$intMaxSeq=null){
		if($intMemberType=='T'){
			$strQuery = sprintf("SELECT count(*) as cnt, max(seq) as max_seq 
						FROM report WHERE writer_seq=%d 
						AND delete_flg=0 ",$intMemberSeq);
			if($intMaxSeq){
				$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
			}
			$strQuery .= sprintf(" and modify_date > '%s' AND finish_date > now()",$dateCompareTime);
		}else{
			$strQuery = sprintf("SELECT count(*) as cnt ,max(seq) as max_seq 
						FROM report WHERE 
						user_group_seq IN (SELECT group_seq FROM group_user_list WHERE student_seq=%d AND delete_flg=0) 
						
						AND state>0 AND delete_flg=0 ",$intMemberSeq);
			if($intMaxSeq){
				$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
			}
			$strQuery .= sprintf(" and modify_date > '%s' AND finish_date > now()",$dateCompareTime);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewTestCount($intMemberSeq,$intMemberType,$dateCompareTime,$intMaxSeq=null){
		if($intMemberType=='T'){
			$strQuery = sprintf("SELECT count(*) as cnt ,max(sp.seq) as max_seq 
						FROM test_published sp, 
						test s 
						WHERE sp.test_seq = s.seq 
						AND s.writer_seq=%d 
						AND s.delete_flg=0 
						AND s.type=1 ",$intMemberSeq);
			$strQuery .= sprintf(" and s.modify_date > '%s' AND finish_date > now() ",$dateCompareTime);
			if($intMaxSeq){
				$strQuery .= sprintf(" and sp.seq > %d ",$intMaxSeq);
			}
		}else{
			$strQuery = sprintf("SELECT count(*) as cnt ,max(sp.seq) as max_seq 
						FROM test_published sp,
						test s
						WHERE sp.test_seq = s.seq
						AND s.delete_flg=0
						AND s.type=1
						AND sp.state>0
						AND sp.group_list_seq IN (SELECT group_seq FROM group_user_list WHERE student_seq=%d AND delete_flg=0) ",$intMemberSeq);
			$strQuery .= sprintf(" and s.modify_date > '%s' AND finish_date > now() ",$dateCompareTime);
			if($intMaxSeq){
				$strQuery .= sprintf(" and sp.seq > %d ",$intMaxSeq);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewNoticeCount($intMemberSeq,$intMemberType,$intNoticeBbsSeq,$dateCompareTime,$intMaxSeq=null){
		if($intMemberType=='T'){
			$strQuery = sprintf("select count(*) as cnt from post where writer_seq=%d and del_flg='0' and bbs_seq=%d and modifydate > '%s' ",$intMemberSeq,$intNoticeBbsSeq,$dateCompareTime);
		}else{
			$strQuery = sprintf("select count(*) as cnt from post where del_flg='0' and bbs_seq=%d and modifydate > '%s' ",$intNoticeBbsSeq,$dateCompareTime);
			$strQuery .= " and target_user like '%".$intMemberSeq."%' ";
		}
		if($intMaxSeq){
			$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewRightWrongList($intMemberSeq,$strMemberType,$dateCompareTime,$arrTeacherSeq=null,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewRightWrongCount($intMemberSeq,$strMemberType,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount[0]['cnt'],
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		if($strMemberType=="T"){
			$strQuery = sprintf("SELECT * 
								FROM (SELECT rw.*,srw.test_seq,srw.question_seq,srw.question_example_seq FROM right_wrong_board rw,test_right_wrong_list srw
									WHERE rw.test_right_wrong_list_seq=srw.seq 
									AND rw.teacher_seq=%d 
									AND rw.delete_flg=0 
									AND create_date > '%s') rwb
								,(SELECT su.seq AS test_seq,su.subject,sp.category_seq,su.create_date
										FROM test su,test_published sp 
										WHERE su.seq=sp.test_seq 
										AND su.delete_flg=0 
										AND su.writer_seq=%d) s
								WHERE rwb.test_seq = s.test_seq ",$intMemberSeq,$dateCompareTime,$intMemberSeq);
		}else{
			$arrTestSeq = array();
			$arrTestInfo = $this->getTestSeq($intMemberSeq,array(1,2));
			if(count($arrTestInfo)){
				foreach($arrTestInfo as $intKey=>$arrResult){
					array_push($arrTestSeq,$arrResult['test_seq']);
				}
			}else{
				array_push($arrTestSeq,0);
			}
			$strQuery = sprintf("SELECT * 
								FROM (SELECT rw.*,srw.test_seq,srw.question_seq,srw.question_example_seq FROM right_wrong_board rw,test_right_wrong_list srw
									WHERE rw.test_right_wrong_list_seq=srw.seq 
									AND rw.delete_flg=0 
									AND srw.test_seq IN(".join(',',$arrTestSeq).")
									AND rw.create_date > '%s') rwb
								,(SELECT su.seq AS test_seq,su.subject,sp.category_seq,su.create_date,su.type
										FROM test su,test_published sp 
										WHERE su.seq=sp.test_seq 
										AND su.delete_flg=0 
										AND su.writer_seq IN (".join(',',$arrTeacherSeq).")) s
								WHERE rwb.test_seq = s.test_seq ",$dateCompareTime);
		}
		$strQuery .= " order by rwb.create_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);
	}
	public function getNewRightWrongCount($intMemberSeq,$strMemberType,$dateCompareTime,$intMaxSeq=null){
		if($strMemberType=="T"){
			$strQuery = sprintf("SELECT count(*) as cnt,max(seq) as max_seq FROM right_wrong_board 
								WHERE teacher_seq=%d AND delete_flg=0 AND create_date > '%s' ",$intMemberSeq,$dateCompareTime);
			if($intMaxSeq){
				$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
			}
			$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		}else{
			$arrTestSeq = array();
			$arrTestInfo = $this->getTestSeq($intMemberSeq);
			if(count($arrTestInfo)){
				foreach($arrTestInfo as $intKey=>$arrResult){
					array_push($arrTestSeq,$arrResult['test_seq']);
				}
			}
			if(count($arrTestSeq)){
				$strQuery = sprintf("SELECT COUNT(*) AS cnt,max(seq) as max_seq FROM right_wrong_board 
									WHERE delete_flg=0 
									AND create_date > '%s'
									AND test_right_wrong_list_seq IN (",$dateCompareTime);
				$strQuery .= " SELECT seq FROM test_right_wrong_list WHERE delete_flg=0 AND test_seq IN (".join(',',$arrTestSeq).")";
				$strQuery .= ")";
				if($intMaxSeq){
					$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
				}
				$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
			}else{
				$arrResult[0]['cnt']=0;
			}
		}
		return($arrResult);		
	}
	public function getNewWrongNoteCount($intMemberSeq,$dateCompareTime){
		$strQuery = sprintf("select count(*) as cnt from wrong_note_list where user_seq=%d and delete_flg=0 and create_date > '%s' ",$intMemberSeq,$dateCompareTime);
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getNewHelpCount($intMemberSeq,$intMemberType,$dateCompareTime,$intMaxSeq=null){
		if($intMemberType=='T'){
			$strQuery = sprintf("SELECT count(*) as cnt,max(seq) as max_seq FROM help_board WHERE teacher_seq=%d AND delete_flg=0 AND create_date > '%s' ",$intMemberSeq,$dateCompareTime);
		}else{
			$strQuery = sprintf("SELECT count(*) as cnt,max(seq) as max_seq FROM help_board WHERE writer_seq=%d AND delete_flg=0 AND create_date > '%s' ",$intMemberSeq,$dateCompareTime);
		}
		if($intMaxSeq){
			$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewMemberCount($intMemberSeq,$dateCompareTime,$intMaxSeq=null){
		$strQuery = sprintf("SELECT count(*) as cnt,max(seq) as max_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0 AND approve_flg=0 AND apply_date > '%s' ",$intMemberSeq,$dateCompareTime);
		if($intMaxSeq){
			$strQuery .= sprintf(" and seq > %d ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewPerfectScoreList($intMemberSeq,$dateCompareTime,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewPerfectScoreStudentCount($intMemberSeq,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount[0]['cnt'],
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = sprintf("SELECT * 
							FROM (SELECT * FROM record WHERE user_score=total_score AND end_date > '%s') r
								,(SELECT su.seq,su.subject,sp.category_seq FROM test su,test_published sp WHERE su.seq=sp.test_seq and su.delete_flg=0 AND su.writer_seq=%d) s
							WHERE r.test_seq = s.seq ",$dateCompareTime,$intMemberSeq);
		$strQuery .= " order by end_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewPerfectScoreStudentCount($intMemberSeq,$dateCompareTime,$intMaxSeq=null){
		$strQuery = sprintf("SELECT count(*) as cnt, max(r.seq) as max_seq
							FROM (SELECT * FROM record WHERE user_score=total_score AND end_date > '%s') r
								,(SELECT seq,SUBJECT FROM test WHERE delete_flg=0 AND writer_seq=%d) s
							WHERE r.test_seq = s.seq ",$dateCompareTime,$intMemberSeq);
		if($intMaxSeq){
			$strQuery .= sprintf(" and r.seq > %d ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewReportCompleteStudentList($intMemberSeq,$dateCompareTime,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewReportCompleteStudentCount($intMemberSeq,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount[0]['cnt'],
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = sprintf("SELECT * 
							FROM (SELECT * FROM report_join_user 
								WHERE report_seq IN (SELECT seq FROM report WHERE delete_flg=0 AND writer_seq=%d) 
								AND delete_flg=0 AND user_status_flg=2 AND end_date > '%s') rju
							,(SELECT * FROM report WHERE delete_flg=0 AND writer_seq=%d) r 
							WHERE rju.report_seq = r.seq ",$intMemberSeq,$dateCompareTime,$intMemberSeq);
		
		$strQuery .= " order by end_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewReportCompleteStudentCount($intMemberSeq,$dateCompareTime,$intMaxSeq=null){
		$strQuery = sprintf("SELECT count(*) as cnt,max(end_date) as max_seq FROM report_join_user 
							WHERE report_seq IN (select seq from report where delete_flg=0 and writer_seq=%d) 
							AND delete_flg=0 AND user_status_flg=2 AND end_date > '%s' ",$intMemberSeq,$dateCompareTime);
		if($intMaxSeq){
			$strQuery .= sprintf(" and end_date > '%s' ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewTestCompleteStudentList($intMemberSeq,$dateCompareTime,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getNewTestCompleteStudentCount($intMemberSeq,$dateCompareTime);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount[0]['cnt'],
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = sprintf("SELECT * 
							FROM (SELECT * FROM record WHERE revision=1 AND end_date > '%s') r
							,(SELECT su.seq,su.subject,sp.category_seq,su.type FROM test su,test_published sp WHERE su.seq=sp.test_seq AND su.delete_flg=0 AND su.writer_seq=%d) s
							WHERE r.test_seq=s.seq and s.type=1",$dateCompareTime,$intMemberSeq);
		
		$strQuery .= " order by end_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	public function getNewTestCompleteStudentCount($intMemberSeq,$dateCompareTime,$intMaxSeq=null){
		$strQuery = sprintf("SELECT count(*) as cnt , max(r.seq) as max_seq 
							FROM (SELECT * FROM record WHERE revision=1 AND end_date > '%s') r
							,(SELECT seq,type FROM test WHERE delete_flg=0 AND writer_seq=%d) s
							WHERE r.test_seq=s.seq and s.type=1 ",$dateCompareTime,$intMemberSeq);
		if($intMaxSeq){
			$strQuery .= sprintf(" and r.seq > '%s' ",$intMaxSeq);
		}
		$arrResult = $this->resNewPostDB->DB_access($this->resNewPostDB,$strQuery);
		return($arrResult);		
	}
	
}
?>
