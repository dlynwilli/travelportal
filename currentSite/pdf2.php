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
	$client = $myrow["client"];
	$state = $myrow["state"];
	$city = $myrow["city"];
	$purpose = $myrow["purpose"];
	$poc = $myrow["poc"];
	$pocphone = $myrow["pocphone"];
}
else 
{
	echo "No records available, please contact database administrator"; 
} 

mysql_free_result($result);

$q2="SELECT fullname FROM users WHERE username='$req_user'";
$result = mysql_query($q2)
or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());
if ($myrow = mysql_fetch_array($result)) {
	$fullname = $myrow["fullname"];
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


	Header('Pragma: public');
	
	$myDate = date("d F Y");
	
	$pdf=new PDF();
	$pdf->AddPage();
	
	
	$pdf->Image('images/dcgs-a.png',80,10,0,0,'PNG');
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(42,30);
	$pdf->Cell(130,4,'Travel Request Coversheet',0,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY(10,50);
	$pdf->Cell(130,3,'Government POC directing the TDY: '.$client,0,1,'L');
	$pdf->SetXY(10,60);
	$pdf->Cell(130,3,'Departure Date/Time: '.$depdate,0,1,'L');
	$pdf->SetXY(10,70);
	$pdf->Cell(130,3,'Return Date/TIme: '.$retdate,0,1,'L');
	$pdf->SetXY(10,80);
	$pdf->Cell(130,3,'Destination: '.$city.', '.$state,0,1,'L');
	$pdf->SetXY(10,90);
	$pdf->Cell(130,3,'Purpose: '.$purpose,0,1,'L');
	
	$pdf->SetXY(10,100);
	$pdf->Cell(130,3,'Agenda (attach) check: _____',0,1,'L');
	
	$pdf->SetXY(10,110);
	$pdf->Cell(130,3,'Point of Contact at TDY Location: '.$poc,0,1,'L');
	$pdf->SetXY(10,120);
	$pdf->Cell(130,3,'Point of Contact phone number: '.$pocphone,0,1,'L');
	$pdf->SetXY(10,130);
	$pdf->Cell(130,3,'COR Name: Diana Bruno',0,1,'L');
	$pdf->SetXY(10,140);
	$pdf->Cell(130,3,'COR Phone Number: (732) 532-1394',0,1,'L');
	

	$pdf->SetXY(70,30);
	$pdf->Cell(130,4,$myDate,0,1,'R');
	

	
	if ($type == "screen"){
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output(); 
	}
	else{
		$pdf->Output('documents/' . $req_user . '_'. $myid . '.pdf','F');
		$pdf->Output($req_user . '_'. $conf_num . '.pdf','D');
	}



?>