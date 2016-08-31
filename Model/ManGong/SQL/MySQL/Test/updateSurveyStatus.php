<?php
$strQuery = sprintf("UPDATE test_published 
						SET state=%d 
						WHERE test_seq=%d",
							$intTestsStatus,
							$intTestsSeq
				);
?>