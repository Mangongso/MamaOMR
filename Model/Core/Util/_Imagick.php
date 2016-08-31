<?php
class _Imagick extends imagick{
	public function getNumPagesInPDF($strFile)
	{
		if(!file_exists($strFile)){
			return null;
		}
		if (!$resFp = @fopen($strFile,"r")){
			return null;
		}
		$intMax=0;
		while(!feof($resFp)) {
			$strLine = fgets($resFp,255);
			if (preg_match('/\/Count [0-9]+/', $strLine, $arrMatches)){
				preg_match('/[0-9]+/',$arrMatches[0], $arrSubMatches);
				if ($$intMax<$arrSubMatches[0]) $intMax=$arrSubMatches[0];
			}
		}
		fclose($resFp);
		return (int)$intMax;
	}
	function rebuildArrayFileInfo(&$arrFile) {
		$arrRebuildFileInfo = array();
		$intFileCount = count($arrFile['name']);
		$intFileArrayKey = array_keys($arrFile);
	
		for ($intI=0; $intI<$intFileCount; $intI++) {
			foreach ($intFileArrayKey as $intKey) {
				$arrRebuildFileInfo[$intI][$intKey] = $arrFile[$intKey][$intI];
			}
		}
	
		return $arrRebuildFileInfo;
	}	
}
?>