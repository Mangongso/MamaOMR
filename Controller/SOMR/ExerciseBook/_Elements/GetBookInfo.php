<?
		/**
		 * ISBN 
		 * */
		$strApiKey = BOOK_SEARCH_API_KEY;
		$strIsbnUrl = $_SERVER['REQUEST_SCHEME']."://apis.daum.net/search/book?apikey=".$strApiKey."&q=".$strIsbnCode."&searchType=isbn&output=xml";
		//get book info
		$xmlstr = $objBook->get_xml_from_url($strIsbnUrl);
		$xmlobj = new SimpleXMLElement($xmlstr);
		$xmlobj = json_decode(json_encode((array)$xmlobj), TRUE);
		
		$strTitle = $xmlobj['item']['title']?$xmlobj['item']['title']:$xmlobj['item'][0]['title'];
		$strPubName = $xmlobj['item']['pub_nm']?$xmlobj['item']['pub_nm']:$xmlobj['item'][0]['pub_nm'];
		$strPubDate = $xmlobj['item']['pub_date']?$xmlobj['item']['pub_date']:$xmlobj['item'][0]['pub_date'];
		$strAuthor = $xmlobj['item']['author']?$xmlobj['item']['author']:$xmlobj['item'][0]['author'];
		$strCoverUrl = $xmlobj['item']['cover_l_url']?$xmlobj['item']['cover_l_url']:$xmlobj['item'][0]['cover_l_url'];
		if($strCoverUrl && $strCoverUrl!=''){
			$arrCoverUrl = explode('fname=',$strCoverUrl);
			$strCoverUrl = str_replace('%2F','/',$arrCoverUrl[1]);
			$strCoverUrl = str_replace('%3A',':',$strCoverUrl);
			$strCoverUrl = str_replace('%3F','?',$strCoverUrl);
		}else{
			$strCoverUrl = "/smart_omr/_images/no_cover.png";
		}
?>