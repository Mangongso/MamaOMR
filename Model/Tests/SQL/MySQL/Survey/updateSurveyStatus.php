<?php
$strQuery = sprintf("UPDATE test
						SET test_status_flg=%d 
						WHERE seq=%d",
							$intTestsStatus,
							$intTestsSeq
				);
?>