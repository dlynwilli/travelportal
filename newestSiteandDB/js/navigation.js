///////INITIALIZE ALL OBJECTS
$(function() {
	$( "#form_tabs" ).tabs({
		heightStyle: "content",
		beforeActivate: FormTabActivate
	}); 
	$( "#dashboard_tabs" ).tabs({
		heightStyle: "content",
		beforeActivate: DashboardTabActivate
	}); 
	
	$( "#travelerInfoButtonMenu" ).buttonset();
	$("#travelerInfoNextBtn").click(function () {
        $( "#form_tabs" ).tabs( "option", "active", $("#form_tabs").tabs('option', 'active')+1 );
    });
	
	 
	$( "#tripInfoBtnPanel" ).buttonset();
	$("#tripInfoPreviousBtn").click(function () {
		$( "#form_tabs" ).tabs( "option", "active", $("#form_tabs").tabs('option', 'active')-1 );
	});
	$("#tripInfoNextBtn").click(function () {
        $( "#form_tabs" ).tabs( "option", "active", $("#form_tabs").tabs('option', 'active')+1 );
    });
	
	$( "#expenseButtonset" ).buttonset();
	$("#expensePrevBtn").click(function () {
		$( "#form_tabs" ).tabs( "option", "active", $("#form_tabs").tabs('option', 'active')-1 );
	});
	
	$( "#tripReportRadioBtns" ).buttonset(); 
	
	$( "#Depart_Datepicker" ).datepicker();
	$( "#Return_Datepicker" ).datepicker(); 
	
	$( "#groundTransportAccordion" ).accordion();	
	
	$( "#editButton2" ).button(); 
	$( "#editButton1" ).button();


	$("#travReqDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});

	$("#tripInfoDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});

	$("#estimateInfoDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});	
	
});

function FormTabActivate(event, ui) {
	switch (ui.newTab.context.innerHTML) {
		case "Traveler Information":
			//$("#form_tabs").tabs("option", "disabled", [ 1, 2 ]);
			//GetPowerStatus();
			break;
		case "Trip Information":
		    //$("#form_tabs").tabs("option", "disabled", [ 0, 2 ]);
			//GetBackupStatus();			
			break;
		case "Trip Estimated Cost":
			//GetRestoreStatus();	
			//$("#configurationTabs").tabs("option", "active", 0);		
			//$("#form_tabs").tabs("option", "disabled", [ 0, 1 ]);
			break;
		default:
			$("#travReqDialog").dialog("option", "title", "JavaScript Error");
			$("#travReqDialog").text("Unknown Tab Detected - Contact the System Developers");
			$("#travReqDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#travReqDialog").dialog("open");
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
			$("#travReqDialog").dialog("option", "title", "JavaScript Error");
			$("#travReqDialog").text("Unknown Tab Detected - Contact the System Developers");
			$("#travReqDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#travReqDialog").dialog("open");
			break;
	}
};

function SaveTravelerInfo()
{
	$("#travReqDialog").text("Saved in progress Traveler Information for the Travel Request");
};

function SaveTripInfo()
{
	$("#travReqDialog").text("Saved in progress Trip Information for the Travel Request");
};

function SaveExpenseInfo()
{
	$("#travReqDialog").text("Saved in progress Expense Information for the Travel Request");
};

function SubmitTravelRequest()
{
	$("#travReqDialog").text("Submitted the Travel Request");
};
