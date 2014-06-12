<?php
include("include/session.php");
require('fpdf/fpdf.php');

$myid = $_GET['id'];
$type = $_GET['type'];
$req_user = trim($_GET['user']);
if(!$req_user || strlen($req_user) == 0 ||
   !eregi("^([0-9a-z])+$", $req_user) ||
   !$database->usernameTaken($req_user)){
   die("Username not registered");
}

$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
mysql_select_db(DB_NAME) or die ('Unable to connect to database');
$q="SELECT * FROM requests WHERE user='$req_user' AND id='$myid'";
$result = mysql_query($q)
or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());

//$num_rows = mysql_num_rows($result);

if ($myrow = mysql_fetch_array($result)) {
	$conf_num = $myrow["conf_num"];
	$depdate = $myrow["depdate"];
	$retdate = $myrow["retdate"];
	$do = $myrow["del_order"];
}
else 
{
	echo "No records available, please contact database administrator"; 
} 

mysql_free_result($result);

$q2="SELECT firstname,lastname,middleinitial,suffix FROM users WHERE username='$req_user'";
$result = mysql_query($q2)
or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());
if ($myrow = mysql_fetch_array($result)) {
	$fullname = $myrow["firstname"];
        if (strlen($myrow["middleinitial"]) > 0){
          $fullname = $fullname.' '.$myrow["middleinitial"];
        }
        $fullname = $fullname.' '.$myrow["lastname"];
        if (strlen($myrow["suffix"]) > 0) {
          $fullname = $fullname.' '.$myrow["suffix"];
        }
}
mysql_free_result($result);
mysql_close($link);


class PDF extends FPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;

	function PDF($orientation='P',$unit='mm',$format='A4')
	{
		//Call parent constructor
		$this->FPDF($orientation,$unit,$format);
		//Initialization
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
	}

	function WriteHTML($html)
	{
		//HTML parser
		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				//Tag
				if($e{0}=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract attributes
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
						if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag,$attr)
	{
		//Opening tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		//Closing tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
	}

	function SetStyle($tag,$enable)
	{
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
				$style.=$s;
		$this->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
}

/*if ($do == "S3 DO13") {
	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-06-D-E401 0013. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Contracting Officer\'s Representative (COR) for this contract is Ms. Demetra Bruno (RDER-IWP-IE), available at 732-532-1394 or demetra.bruno@us.army.mil.  The Government Project Lead for this project is Mr. Kesny Parent, Chief-Intelligence Enterprise Branch (RDER-IWP-IE), available at 410-306-3211 or kesny.parent@us.army.mil.';
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage();
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'AMSRD-CER-IW-FD',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'KESNY PARENT',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Chief-Intelligence Enterprise Branch',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}*/
/*if ($do == "S3 DO14") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract                             . During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Contracting Officer\'s Representative (COR) for this contract is                          , available at                        .  The Government Project Lead for this project is                                    , available at                                    .';
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage();
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	//$pdf->SetXY(55,19);
	//$pdf->Cell(130,3,'PROJECT MANAGER NIGHT VISION RECONNAISSANCE & TARGET ACQUISITION',0,1,'C');
	//$pdf->SetXY(55,22);
	//$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	//$pdf->SetXY(55,25);
	//$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	//$pdf->SetXY(55,28);
	//$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	//$pdf->SetFont('Times','',12);
	//$pdf->SetXY(27,42);
	//$pdf->Cell(40,5,'RDER-IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From:',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	//$pdf->SetXY(95,150);
	//$pdf->Cell(55,5,'KESNY PARENT',0,1,'L');
	//$pdf->SetXY(95,155);
	//$pdf->Cell(55,5,'Chief-Intelligence Enterprise Branch',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}*/

if ($do == "TESS DO12" || $do == "TESS DO13" || $do == "TESS DO16") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-09-D-P014. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Government Project Lead for this project is Mr. Jean Robert Brutus Jr., Project Manager, available at 443-861-0682 or jean.robert.brutus@us.army.mil.';
	//The Contracting Officer\'s Representative (COR) for this contract is Ms. Demetra Bruno (RDER-IWP-IE), available at 732-532-1394 or demetra.bruno@us.army.mil.
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage(); 
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'RDER-IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'Jean Robert Brutus Jr.',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Project Manager',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}

if ($do == "TESS DO25") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-09--D-P014. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Contracting Officer\'s Representative (COR) for this contract is Ms. Karen J. Greenwood (RDER-IWO-AC), available at 443-861-1482 or karen.j.greenwood6.civ@mail.mil. The Government Project Lead for this project is Mr. Kevin O’Hanlon (RDER-IWR-RA), available at 443-861-1374 or kevin.k.ohanlon.civ@mail.mil.';
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage();
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	//$pdf->SetFont('Arial','',6);
	//$pdf->SetXY(47,37);
	//$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	//$pdf->SetXY(47,39);
	//$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	//$pdf->SetFont('Times','',12);
	//$pdf->SetXY(27,42);
	//$pdf->Cell(40,5,'RDER-IWR-RA',0,1,'L');
	
	//$pdf->SetFont('Times','',10);
	//$pdf->SetXY(180,45);
	//$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'Ms. Lorraine Kohler',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Radar and Combat ID Branch, Chief',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}

if ($do == "TESS DO18") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-09-D-P014. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Government Project Lead for this project is Mr. Kesny Parent, Chief-Intelligence Enterprise Branch (RDER-IWP-IE), available at 443-861-0765 or kesny.parent@us.army.mil.';
	//The Contracting Officer\'s Representative (COR) for this contract is Ms. Demetra Bruno (RDER-IWP-IE), available at 732-532-1394 or demetra.bruno@us.army.mil.
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage();
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'RDER-IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'KESNY PARENT',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Chief-Intelligence Enterprise Branch',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}


if ($do == "S3 DO37") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-06-D-E401. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Government Project Lead for this project is Mr. Kesny Parent, Chief-Intelligence Enterprise Branch (RDER-IWP-IE), available at 443-861-0765 or kesny.parent@us.army.mil.';
	
	//The Contracting Officer\'s Representative (COR) for this contract is Mr. Ben Yau (RDER-IWP-IE), available at 732-532-1113 or benjamin.yau@us.army.mil.
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage();
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'RDER-IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'KESNY PARENT',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Chief-Intelligence Enterprise Branch',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}

if ($do == "S3 DO49") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-06-D-E401. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	$html2='2. The Government Project Lead for this project is Mr. Larry Lashine, AMSRD-CER-SE-IEW-DCGS, available at 443-861-4976 or larry.lashine.civ@mail.mil.';
	//The Contracting Officer\'s Representative (COR) for this contract is Ms. Demetra Bruno (RDER-IWP-IE), available at 732-532-1394 or demetra.bruno@us.army.mil.
	
	$pdf=new PDF();
	//First page
	$pdf->AddPage(); 
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'RDER-IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'Mr. Larry Lashine',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'AMSRD-CER-SE-IEW-DCGS Project Lead',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}

if ($do == "R23G DO2") {

	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$html1='<br>1. <u>'. $fullname . '</u>, the bearer of this letter, is an employee of Booz Allen Hamilton (BAH) Inc. or a BAH subsidiary company (or a BAH subcontractor), which is under this agency\'s Government contract W15P7T-10-D-D415. During the period of <u>' . $depdate . ' through ' . $retdate .'</u>, this employee is eligible and authorized to use available discount rates in accordance with your contract or agreement with the Federal Government.<br><br>';
	
	$html2='2. The Contracting Officer\'s Representative (COR) for this contract is Mr. Ben Yau (RDER IWP-IE), available at 410-306-3211. The Government Project Lead for this project is Mr. Kesny Parent, RDER IWP-IE, available at 443-861-0765 or kesny.parent@us.army.mil.<br><br>';

	
	$pdf=new PDF();
	//First page
	$pdf->AddPage(); 
	//$pdf->SetFont('Arial','',20);
	//$pdf->Write(5,'To find out what\'s new in this tutorial, click ');
	
	$pdf->Image('images/logo.png',10,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(55,15);
	$pdf->Cell(130,4,'DEPARTMENT OF THE ARMY',0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(55,19);
	$pdf->Cell(130,3,'RESEARCH, DEVELOPMENT AND ENGINEERING COMMAND',0,1,'C');
	$pdf->SetXY(55,22);
	$pdf->Cell(130,3,'COMMUNICATIONS-ELECTRONICS RESEARCH, DEVELOPMENT AND ENGINEERING CENTER',0,1,'C');
	$pdf->SetXY(55,25);
	$pdf->Cell(130,3,'INTELLIGENCE AND INFORMATION WARFARE DIRECTORATE',0,1,'C');
	$pdf->SetXY(55,28);
	$pdf->Cell(130,3,'ABERDEEN PROVING GROUNDS, MARYLAND 21005-5001',0,1,'C');
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(47,37);
	$pdf->Cell(20,2,'REPLY TO',0,1,'L');
	$pdf->SetXY(47,39);
	$pdf->Cell(20,2,'ATTENTION OF',0,1,'L');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(27,42);
	$pdf->Cell(40,5,'RDER IWP-IE',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetXY(180,45);
	$pdf->Cell(20,4,'4650',0,1,'R');
	$pdf->SetXY(180,49);
	$pdf->Cell(20,4,$myDate,0,1,'R');
	
	$pdf->SetFont('Times','',12);
	$pdf->SetXY(10,63);
	$pdf->Cell(170,5,'From: Commander, US Army Research, Development and Engineering Command (RDECOM)',0,1,'L');
	$pdf->SetXY(10,68);
	$pdf->Cell(170,5,'To:     Service Vendors',0,1,'L');
	$pdf->SetXY(10,78);
	$pdf->Cell(170,5,'Subject: OFFICIAL TRAVEL OF GOVERNMENT CONTRACTORS',0,1,'L');
	
	$pdf->WriteHTML($html1);
	$pdf->WriteHTML($html2);
	
	$pdf->SetXY(95,150);
	$pdf->Cell(55,5,'Mr. Kesny Parent',0,1,'L');
	$pdf->SetXY(95,155);
	$pdf->Cell(55,5,'Chief Intelligence Enterprise Branch',0,1,'L');
	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}
}
?>