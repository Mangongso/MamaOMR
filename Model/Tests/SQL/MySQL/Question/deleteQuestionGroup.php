<?php
if(!is_array($arrGroupSeq)){
			$arrGroupSeq = array($arrGroupSeq);
		}
		$strQuery = sprintf("update question_group set delete_flg=1 where seq in (%s)",join(',',$arrGroupSeq));
?>