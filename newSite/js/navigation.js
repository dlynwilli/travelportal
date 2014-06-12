///////INITIALIZE ALL OBJECTS
$(function() {
	$( "#form_tabs" ).tabs({
		active: 0,
		heightStyle: "content",
		beforeActivate: FormTabActivate
	}); 
	$( "#dashboard_tabs" ).tabs({
		heightStyle: "content",
		beforeActivate: DashboardTabActivate
	}); 
	
	$( "#travelerInfoButtonMenu" ).buttonset(); 
	$( "#tripInfoBtnPanel" ).buttonset();
	$( "#expenseButtonset" ).buttonset();
	$( "#tripReportRadioBtns" ).buttonset(); 
	
	$( "#Depart_Datepicker" ).datepicker();
	$( "#Return_Datepicker" ).datepicker(); 
	
	$( "#groundTransportAccordion" ).accordion();	
	
	$( "#editButton2" ).button(); 
	$( "#editButton1" ).button(); 
	
});

function FormTabActivate(event, ui) {
	switch (ui.newTab.context.innerHTML) {
		case "Traveler Information":
			$("#form_tabs").tabs("option", "disabled", [ 1, 2 ]);
			//GetPowerStatus();
			break;
		case "Trip Information":
		    $("#form_tabs").tabs("option", "disabled", [ 0, 2 ]);
			//GetBackupStatus();			
			break;
		case "Trip Estimated Cost":
			//GetRestoreStatus();	
			//$("#configurationTabs").tabs("option", "active", 0);		
			$("#form_tabs").tabs("option", "disabled", [ 0, 1 ]);
			break;
		default:
			$("#pmDialog").dialog("option", "title", "JavaScript Error");
			$("#pmDialog").text("Unknown Tab Detected - Contact the System Developers");
			$("#pmDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#pmDialog").dialog("open");
			break;
	}
};

function DashboardTabActivate(event, ui) {
	switch (ui.newTab.context.innerHTML) {
		case "My Requests":
			//GetPowerStatus();
			break;
		case "Requests Pending Approval":
			//GetBackupStatus();			
			break;
		case "Completed Requests":
			//GetRestoreStatus();			
			break;		
		default:
			$("#pmDialog").dialog("option", "title", "JavaScript Error");
			$("#pmDialog").text("Unknown Tab Detected - Contact the System Developers");
			$("#pmDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#pmDialog").dialog("open");
			break;
	}
};

function saveTravelerInfo()
{
	alert("The input value has changed. The new value is: " + val);
};

function nextTravelerInfo()
{
	$("#form_tabs").tabs( "enable", 1 );
	$("#form_tabs").tabs("option", "active", 1);	
	$("#form_tabs").tabs("option", "disabled", [ 0, 2 ]);
	//travelerInfoTab
	//costEstimateTab
};
