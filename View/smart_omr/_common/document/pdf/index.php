<?php
//============================================================+
// File name   : example_010.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 010 for TCPDF class
//               Text on multiple columns
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Text on multiple columns
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once($_SERVER['DOCUMENT_ROOT'].'/_lib/PHPPdf/examples/tcpdf_include.php');


/**
 * Extend TCPDF to work with multiple columns
 */
class MC_TCPDF extends TCPDF {

	/**
	 * Print chapter
	 * @param $num (int) chapter number
	 * @param $title (string) chapter title
	 * @param $file (string) name of the file containing the chapter body
	 * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
	 * @public
	 */
	public function PrintChapter($num, $title, $txt, $mode=false) {
		// add a new page
		//$this->AddPage();
		// disable existing columns
		$this->resetColumns();
		// print chapter title
		//$this->ChapterTitle($num, $title);
		// set columns
		$this->setEqualColumns(3, 57);
		// print chapter body
		$this->ChapterBody($txt, $mode);
	}

	/**
	 * Set chapter title
	 * @param $num (int) chapter number
	 * @param $title (string) chapter title
	 * @public
	 */
	public function ChapterTitle($num, $title) {
		$this->SetFont('helvetica', '', 14);
		$this->SetFillColor(200, 220, 255);
		$this->Cell(180, 6, 'Chapter '.$num.' : '.$title, 0, 1, '', 1);
		$this->Ln(4);
	}

	/**
	 * Print chapter body
	 * @param $file (string) name of the file containing the chapter body
	 * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
	 * @public
	 */
	public function ChapterBody($txt, $mode=false) {
		$this->selectColumn();
		// get esternal file content
		$content = $txt;
		// set font
		$this->SetFont('times', '', 11);
		$this->SetTextColor(50, 50, 50);
		// print content
		if ($mode) {
			// ------ HTML MODE ------
			$this->writeHTML($content, true, false, true, false, 'J');
		} else {
			// ------ TEXT MODE ------
			$this->Write(0, $content, '', 0, 'J', true, 0, false, true, 0);
		}
		$this->Ln();
	}
} // end of extended class

// ---------------------------------------------------------
// EXAMPLE
// ---------------------------------------------------------
// create new PDF document
$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 010');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(false);
$pdf->setFooterFont(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetPrintHeader(false);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


$viewID = "SOMR_EXERCISE_BOOK_TEST";
include($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php");
//set omr data
$txt='';

foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){
$i = $intKey+1;

//for($i=1;$i<1000;$i++){
switch($arrQuestionInfo['question_type']){
//switch(4){
case(2): //3문제
//1~9문제
if($i<=9){
$txt .= '   '.$i.'.        [1]       [2]       [3]
';
}

//10~99문제
if(10<=$i && $i<=99){
$txt .= '  '.$i.'.       [1]       [2]       [3]
';
}

//100문제
if(100<=$i){
$txt .= $i.'.       [1]       [2]       [3]
';
}
		break;
	case(3)://4문제
//1~9문제
if($i<=9){
$txt .= '   '.$i.'.      [1]      [2]      [3]      [4]
';
}

//10~99문제
if(10<=$i && $i<=99){
$txt .= '  '.$i.'.     [1]      [2]      [3]      [4]
';
}

//100문제
if(100<=$i){
$txt .= $i.'.     [1]      [2]      [3]      [4]
';
}
	break;
	case(4)://5문제
//1~9문제
if($i<=9){
$txt .= '   '.$i.'.    [1]    [2]    [3]    [4]    [5]
';
}

//10~99문제
if(10<=$i && $i<=99){
$txt .= '  '.$i.'.   [1]    [2]    [3]    [4]    [5]
';
}

//100문제
if(100<=$i){
$txt .= $i.'.   [1]    [2]    [3]    [4]    [5]
';
}
		break;
	case(11)://2문제
//1~9문제
if($i<=9){
$txt .= '   '.$i.'.            [1]              [2]
';
}

//10~99문제
if(10<=$i && $i<=99){
$txt .= '  '.$i.'.           [1]              [2]
';
}

//100문제
if(100<=$i){
$txt .= $i.'.           [1]              [2]
';
}
		break;

}//switch case end 


}

// ---------------------------------------------------------

// print TEXT
$pdf->AddPage();

$pdf->SetFont('cid0kr', 'B', 20);

$txt1 = <<<EOD
마마OMR
EOD;

// print a block of text using Write()
$pdf->Write(0, $txt1, '', 0, 'C', true, 0, false, false, 0);


$pdf->SetFont('cid0kr', '', 12);

$txt1 = " 
  책 제목   :   ".$arr_output['book_info'][0]['title']."
  OMR 명   :   ".$arr_output['test_info'][0]['subject']." 
  이름   :   ".$_SESSION['smart_omr']['name']." 

";
$pdf->Write(0, $txt1, '', 0, '', true, 0, false, false, 0);

$pdf->PrintChapter(1, 'LOREM IPSUM [TEXT]', $txt, false);


// print HTML
//$pdf->PrintChapter(2, 'LOREM IPSUM [HTML]', 'data/chapter_demo_2.txt', true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_010.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
