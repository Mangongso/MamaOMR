<?
	/* naver api 
		$client_id = "SQNkahuK304IWfC8Eb_y";
		$client_secret = "14kY3FbojG";
		$url = "https://openapi.naver.com/v1/search/book_adv.xml?query=-&display=10&start=1&d_isbn=".$strIsbnCode;
		 
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		 $headers = array();
		 $headers[] = "User-Agent: curl/7.43.0";
		*/
		 //$headers[] = "Accept: */*";
		/*
		 $headers[] = "Content-Type: application/xml";
		 $headers[] = "X-Naver-Client-Id: ".$client_id;
		 $headers[] = "X-Naver-Client-Secret: ".$client_secret;
		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 $xmlstr = curl_exec ($ch);
		 curl_close ($ch);
		
		$xmlobj = new SimpleXMLElement($xmlstr);
		$xmlobj = json_decode(json_encode((array)$xmlobj), TRUE);
		
		$strTitle = $xmlobj['item']['title']?$xmlobj['item']['title']:$xmlobj['item'][0]['title'];
		$strPubName = $xmlobj['item']['publisher']?$xmlobj['item']['publisher']:$xmlobj['item'][0]['publisher'];
		$strPubDate = $xmlobj['item']['pubdate']?$xmlobj['item']['pubdate']:$xmlobj['item'][0]['pubdate'];
		$strAuthor = $xmlobj['item']['author']?$xmlobj['item']['author']:$xmlobj['item'][0]['author'];
		$strCoverUrl = $xmlobj['item']['image']?$xmlobj['item']['image']:$xmlobj['item'][0]['image'];
		$strCoverUrl = str_replace('type=m1','type=m5',$strCoverUrl);
		$strCoverUrl = $strCoverUrl?$strCoverUrl:"/smart_omr/_images/default_cover.png";
	*/
		$strApiKey = "49769dcbb5d89eaf2d3c069ac7ca321e";
		$strIsbnUrl = "https://apis.daum.net/search/book?apikey=".$strApiKey."&q=".$strIsbnCode."&searchType=isbn&output=xml";
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
			$strCoverUrl = "/smart_omr/_images/default_cover.png";
		}
?>