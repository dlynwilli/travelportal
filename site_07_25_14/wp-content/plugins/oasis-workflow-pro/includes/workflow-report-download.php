<?php
		// Download CSV Report to browser
		$file = $_GET['file'];
		$today = date("Ymd-His");
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=workflow-report-" . $today . ".csv");
		readfile($file);

		unlink($file);
?>