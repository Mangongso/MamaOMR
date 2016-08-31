<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Comment{
	private $resCommentDB = null;
	private $objPaging = null;
	public function __construct($resCommentDB=null){
		$this->objPaging =  new Paging();
		//$this->objMember =  new Member($resCommentDB);
		$this->resCommentDB = $resCommentDB;
	}
	public function __destruct(){}
	public function getMyFollowCommentByWriterSeq($intWriterSeq=null,$intTestsSeq=null,$strUrl=null,$strCommentType='facebook'){
		$strQuery = sprintf("
					SELECT * FROM 
							(
							SELECT cm.*,s.subject FROM 
							(SELECT * FROM comment WHERE comment_type='%s' AND writer_seq=%d) cm 
							LEFT OUTER JOIN
							/* 서베이 가져오기 */
							(SELECT * FROM test WHERE delete_flg=0 AND writer_seq=%d) s 
							ON cm.test_seq=s.seq
							) cmf,
							/* 현재 메이커의 follower를 join */
							(SELECT * FROM follow WHERE writer_seq=%d) f 
						WHERE  cmf.member_seq=f.user_seq",$strCommentType,$intWriterSeq,$intWriterSeq,$intWriterSeq);
		if(!is_null($intTestsSeq)){
			$strQuery .= sprintf(" and cmf.test_seq=%d",$intTestsSeq);
		}
		if(!is_null($strUrl)){
			$strQuery .= sprintf(" and cmf.url='%s'",$strUrl);
		}
		$strQuery .= sprintf(" order by cmf.seq desc");
		//print_r($strQuery);
		$arrReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		return($arrReturn);		
	}
	public function getMyFollowCommentByUserSeq($intUserSeq=null,$intTestsSeq=null,$strUrl=null,$strCommentType='facebook'){
		$strQuery = sprintf("
					SELECT * 
					FROM (	
						SELECT cm.* FROM 
							(SELECT * FROM comment WHERE comment_type='%s') cm ,
							(SELECT * FROM follow WHERE user_seq=%d GROUP BY writer_seq ) f 
						WHERE cm.writer_seq=f.writer_seq
						) cmf
						LEFT OUTER JOIN 
						(SELECT s.* FROM 
								(SELECT * FROM record WHERE revision=1 AND user_seq=%d) r 
								LEFT JOIN test s 
								ON r.test_seq=s.seq GROUP BY writer_seq 
						) sv
						ON cmf.writer_seq=sv.writer_seq ",$strCommentType,$intUserSeq,$intUserSeq);
		if(!is_null($intTestsSeq)){
			$strQuery .= sprintf(" and cmf.test_seq=%d",$intTestsSeq);
		}
		if(!is_null($strUrl)){
			$strQuery .= sprintf(" and cmf.url='%s'",$strUrl);
		}
		$strQuery .= sprintf(" order by cmf.seq desc");
		//print_r($strQuery);
		$arrReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		return($arrReturn);		
	}
	public function setComment($intMemberSeq,$strCommentID,$strCommentType,$strUrl,$strMessage,$intTestsSeq,$strUserImgPath,$strUserName,$intWriterSeq,$strWriterNickName){
		$strQuery = sprintf("insert into comment set member_seq=%d,comment_id='%s',comment_type='%s',url='%s',message='%s',test_seq=%d,user_img_path='%s',user_name='%s',writer_seq=%d,writer_nickname='%s',create_date=now()",$intMemberSeq,$strCommentID,$strCommentType,$strUrl,quote_smart($strMessage),$intTestsSeq,$strUserImgPath,$strUserName,$intWriterSeq,$strWriterNickName);
		$boolReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		return($boolReturn);		
	}
 	public function deleteComment($intMemberSeq,$strCommentID){
 		$strQuery = sprintf("delete from comment where member_seq=%d and comment_id='%s'",$intMemberSeq,$strCommentID);
		$boolReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		return($boolReturn);
	}
}
?>