<?php
$strQuery = sprintf("update test_published
						SET book_seq=%d where seq=%d",
							$strBookSeq,
							$intPublishedSeq
				);
//print_r($strQuery);
?>