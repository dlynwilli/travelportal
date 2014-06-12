<?php

	<!--INCLUDED css FILES-->

    <link href="css/jquery_ui_custom/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" href="css/introjs.css">
	
	<!-- INCLUDED javascript FILES -->
	<script src="js/jquery-1.10.4.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>    
    <script type="text/javascript" src="js/navigation.js"></script>
    <script type="text/javascript" src="js/intro.js"></script>
	
//-- BAH Added Frameworks
	
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');

$doc->addScript('templates/' .$this->template. '/js/jquery-1.10.4.js');
$doc->addScript('templates/' .$this->template. '/js/jquery-ui-1.10.4.custom.js');
$doc->addScript('templates/' .$this->template. '/js/navigation.js');
$doc->addScript('templates/' .$this->template. '/js/intro.js');

