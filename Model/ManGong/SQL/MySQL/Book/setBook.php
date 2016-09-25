<?php
$strQuery = sprintf("INSERT INTO book_info
						SET writer_seq=%d, 
							sub_writer_seq=%d,
							isbn_code='%s', 
							title='%s', 
							pub_name='%s',
							pub_date='%s',
							cover_url='%s',
							category_seq=%d,
							author='%s',
							create_date=now() ",
							$intWriterSeq,
							$intSubWriterSeq,
							trim($strIsbnCode),
							quote_smart(trim($strTitle)),
							quote_smart(trim($strPubName)),
							quote_smart(trim($strPubDate)),
							quote_smart(trim($strCoverUrl)),
							$intCategorySeq,
							quote_smart(trim($strAuthor))
				);
?>