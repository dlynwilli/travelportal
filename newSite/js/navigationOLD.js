////////////////////////////
// NAVIGATION FUNCTIONS
////////////////////////////

$(function() {
	$("#tabs").tabs(
	{
		heightStyle: "content",
		beforeActivate: MainTabActivate
	});
	$("#configurationTabs").tabs(
	{
		heightStyle: "content",
		beforeActivate: ConfigTabActivate
	});
	
	
	$("#logoutBtn").button();
	$("#shutdownBtn").button();
	$("#startupBtn").button();
	$("#shutdownHelpBtn").button();
	$("#refreshShutdownBtn").button();
	$("#backupBtn").button();
	$("#backupHelpBtn").button();
	$("#refreshBackupBtn").button();
	$("#restoreBtn").button();
	$("#restoreHelpBtn").button();
	$("#refreshRestoreBtn").button();
	$("#submitConfigBtn").button();
	$("#resetConfigBtn").button();
	$("#configHelpBtn").button();
	$("#shutdownOrderBtn").button();
	$("#resetShutdownBtn").button();
	$("#loadServersBtn").button();
	$("#configOrderHelpBtn").button();
	$("#startupOrderBtn").button();
	$("#resetStartupOrderBtn").button();
	$("#configStartOrderHelpBtn").button();
	
	
	$("#pmDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
				$(this).dialog("option", "title", "Power Management");
			}
		}
	});
	$("#backDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});
	$("#restoreDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});
	$("#configDialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
				$(this).dialog("option", "dialogClass", "");
			}
		}
	});
	
	$("#confirmStartDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				UpdateStartupHTML();
				StartupServers();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});
	
	$("#confirmOffDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				ShutdownServers();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	
	$("#confirmReDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				RestoreServers();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	$("#confirmBupDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				BackupServers();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	$("#confirmConfigDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				SaveConfig();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	
	$("#confirmOrderDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				SaveShutdownOrder();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	
	//confirmStartOrderDialog
	$("#confirmStartOrderDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				SaveStartupOrder();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	
	$("#confirmLoadDialog").dialog({
		autoOpen:false,
		modal:true,
		buttons: {
			Ok: function() {
				//change the look of the page
				UpdateLoadServersHTML();
				LoadNewServers();
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				},
			Cancel: function() {
				$(this).dialog("close");
				$(this).dialog("option", "dialogClass", "");
				}
		}
	});	
	
	$("#loadingDialog").dialog({
		autoOpen: false,
		modal: true
	});

	$("#restoreSelectAll").click(function () {
		var checkBoxes = $('input:checkbox[id^="restore_"]');
		checkBoxes.prop("checked", $("#restoreSelectAll").is(":checked"));
	});

	$("#backupSelectAll").click(function () {
		var checkBoxes = $('input:checkbox[id^="backup_"]');
		checkBoxes.prop("checked", $("#backupSelectAll").is(":checked"));
	});	
	
	GetPowerStatus();
});

function ConfigTabActivate(event, ui) {
	switch(ui.newTab.context.innerHTML) {
	case "System Configuration":
		// TODO: Decide if this needs to be reset every time
		// or just let buttons do the functionality.  Clicking
		// Configuration will already get this once.
		break;
	case "Configure Else 2":		
		// TODO: Decide if this needs to be reset every time
		// or just let buttons do the functionality.  Clicking
		// Configuration will already get this once.
		break;
	case "Configure Else 3":
		// TODO: Decide if this needs to be reset every time
		// or just let buttons do the functionality.  Clicking
		// Configuration will already get this once.
		break;
	
	default:
		$("#pmDialog").dialog("option", "title", "JavaScript Error");
		$("#pmDialog").text("Unknown Tab Detected - Contact the System Developers");
		$("#pmDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#pmDialog").dialog("open");
	}
};

function MainTabActivate(event, ui) {
	switch (ui.newTab.context.innerHTML) {
		case "Dashboard":
			//GetPowerStatus();
			break;
		case "New Request":
			//GetBackupStatus();			
			break;
		case "MyRequests":
			//GetRestoreStatus();			
			break;
		case "Configuration":
			// For now force the configuration tab to always load the
			// first tab.
			$("#configurationTabs").tabs("option", "active", 0);
			//ResetConfig();
			//ResetShutdownOrder();
			//GetStartupOrder();			
			break;
		default:
			$("#pmDialog").dialog("option", "title", "JavaScript Error");
			$("#pmDialog").text("Unknown Tab Detected - Contact the System Developers");
			$("#pmDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#pmDialog").dialog("open");
			break;
	}
};

function Logout()
{
	//VirtualRecovery/login.html
	//window.location.href = "/VirtualRecovery/index.html";	
};

/////////////////////////////
// POWER MANAGEMENT FUNCTIONS
/////////////////////////////

function GetPowerStatus() {
	var json = new Object();
	json.command = "SERVERSTATUS";
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Power", jsonStr, function(data, status) {
		//inside the post method
	},"json")	
	.done (function (data) {
		var htmlOutput = "";
		
		var shutdownInProgress = data.shutdownInProgress;
		var otherInProgress = data.otherProcessInProgress;
		var startupInProgress = data.startupInProgress;
		
		var waitListTitleRow = "<tr class=\"dataTableRow\"><td colspan=\"3\"><b>WAIT STACK</td></tr>";
		var noWaitListTitleRow = "<tr class=\"dataTableRow\"><td colspan=\"3\"><b>NO WAIT STACK</td></tr>";
		var waitListRow = "";
		var nowaitListRow = "";
		
		var serverListTitleRow = "<tr class=\"dataTableRow\"><td colspan=\"3\"><b> </td></tr>";
		var serverListRow = "";
		
		var allRunning = 0;
		var allShutdown = 0;
		var allServers;
		
		if(isStartup || startupInProgress) {
			allServers = data.statusList.length;
			
			for(var i = 0; i < allServers; i++) {
				var svrName = data.statusList[i].serverName;
				var status = data.statusList[i].serverState;
				var tblRowInput = "";
				
				tblRowInput += "<td style=\"margin-left: 10.0em\" id=\"" + svrName + "\">" + svrName + "</td>";	
			  
				if (status == "running") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 play-icon\"></span> Running</td>";
				    allRunning++;				    
				} else if (status == "shutdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 stop-icon\"></span> Powered Off</td>";
					allShutdown++;
				} else if (status == "shuttingdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Shutting Down</td>";
					
				} else if(status == "startingup") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Starting Up</td>";
					allRunning++;					
				} else {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 question-icon\"></span> Unknown</td>";
				}
				
				serverListRow += "<tr class=\"dataTableRow ui-state-default\">";
				serverListRow += "<td style=\"margin-left: 1.0em\" id=\"" + svrName + "_order\">" + (i+1) + "</td>";
				serverListRow += tblRowInput;
				serverListRow +=	"</tr>";
				
				if(allRunning > 0 && !startupInProgress) {
					isStartup = false;
					GetPowerStatus();
				}
				
			}
			
			//Build the output
			//htmlOutput += serverListTitleRow;
			htmlOutput += serverListRow;			
			
		} else { //these VM's are shutdown command or refresh
		
			allServers = data.waitServerList.length + data.noWaitServerList.length;
	
			//Build Wait Servers output 
			for (var i = 0; i < data.waitServerList.length; i++) 
			{
				var svrName = data.waitServerList[i].serverName;
				var status = data.waitServerList[i].serverState;
				var tblRowInput = "";
				
				tblRowInput += "<td style=\"margin-left: 10.0em\" id=\"" + svrName + "\">" + svrName + "</td>";	
			  
				if (status == "running") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 play-icon\"></span> Running</td>";
					allRunning ++;
				} else if (status == "shutdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 stop-icon\"></span> Powered Off</td>";
					allShutdown++;
				} else if (status == "shuttingdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Shutting Down</td>";
					allShutdown++;
				} else if(status == "startingup") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Starting Up</td>";
				} else {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 question-icon\"></span> Unknown</td>";
				}
				
				waitListRow += "<tr class=\"dataTableRow ui-state-default\">";
				waitListRow += "<td style=\"margin-left: 1.0em\" id=\"" + svrName + "_order\">" + (i+1) + "</td>";
				waitListRow += tblRowInput;
				waitListRow +=	"</tr>";
			}	
			
			//Build no wait Server output
			
			for (var j = 0; j < data.noWaitServerList.length; j++) 
			{			
				var svrName = data.noWaitServerList[j].serverName;								
				var status = data.noWaitServerList[j].serverState;
				
				var tblRowInput = "";
				
				tblRowInput += "<td style=\"margin-left: 10.0em\" id=\""+svrName+"\">" + svrName + "</td>";	
				  
				if (status == "running") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 play-icon\"></span> Running</td>";
					allRunning++;
				} else if (status == "shutdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 stop-icon\"></span> Powered Off</td>";
					allShutdown++;
				} else if (status == "shuttingdown") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Shutting Down</td>";
				} else if (status == "startingup") {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 working-icon\"></span> Starting Up</td>";
				} else {
					tblRowInput += "<td style=\"margin-left: 30.0em\" id=\"" + svrName + "_icon\"><span class=\"custom-icon-16 question-icon\"></span> Unknown</td>";
				}
				
				nowaitListRow += "<tr class=\"dataTableRow ui-state-default\">";
				nowaitListRow += "<td style=\"margin-left: 1.0em\" id=\"" +svrName+"_order\">" + (j+1) + "</td>";
				nowaitListRow += tblRowInput;	
				nowaitListRow += "</tr>";
			}
						
			//Build the output
			htmlOutput += waitListTitleRow;
			htmlOutput += waitListRow;
			htmlOutput += noWaitListTitleRow;
			htmlOutput += nowaitListRow;	
		
		}
		
		//display to screen
		$("#virtualMachineList").html(htmlOutput);
				
		if (shutdownInProgress) {
			$("#shutdownBtn").button("option", "disabled", true);
			$("#startupBtn").button("option", "disabled", true);
			$("#refreshShutdownBtn").button("option", "disabled", true);
			$("#shutdownHelpBtn").button("option", "disabled", true);
			setTimeout(GetPowerStatus, 1000);
		} else if(startupInProgress) {
			//startupBtn
			$("#startupBtn").button("option", "disabled", true);
			$("#shutdownBtn").button("option", "disabled", true);
			$("#refreshShutdownBtn").button("option", "disabled", true);
			$("#shutdownHelpBtn").button("option", "disabled", true);
			setTimeout(GetPowerStatus, 1000);
		} else if (otherInProgress) {
			$("#shutdownBtn").button("option", "disabled", true);
			$("#startupBtn").button("option", "disabled", true);
			$("#refreshShutdownBtn").button("option", "disabled", true);
			$("#shutdownHelpBtn").button("option", "disabled", false);
		} else {
			$("#refreshShutdownBtn").button("option", "disabled", false);
			$("#shutdownHelpBtn").button("option", "disabled", false);
			if(allShutdown === 0){
				$("#startupBtn").button("option", "disabled", true);								
			} else {
				$("#startupBtn").button("option", "disabled", false);				
			}
			if(allRunning === 0) {
				$("#shutdownBtn").button("option", "disabled", true);
			} else {
				$("#shutdownBtn").button("option", "disabled", false);
			}	
		}
	},"json")
	.fail (function(data, status, error) {
		$("#pmDialog").text("There was an error getting VM power status!");  //came here when executed startup
		$("#pmDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#pmDialog").dialog("open");
	})
	.always (function(data, status) {
		$("#tabs").tabs("refresh");
	});
};

function RefreshShutdown() {
	var json = new Object();
	json.command = "REFRESH";
	var jsonStr = JSON.stringify(json);
	
	$("#loadingDialog").dialog("open");
	
	$.post("/VirtualRecovery/Power", jsonStr, function (data, status) {
		GetPowerStatus();
		$("#loadingDialog").dialog("close");
	}, "text")
	.fail (function(data, status, error) {
		$("#pmDialog").text(error);
		$("#pmDialog").dialog("open");
	});
}

function ShutdownServers() {	
	var json = new Object();
	json.command = "SHUTDOWN";
	//json.serverList = null;
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Power", jsonStr, function (data, status) {
		$("#pmDialog").text("Shutdown request sent!");
		$("#pmDialog").dialog("open");
		$("#shutdownBtn").button("option", "disabled", true);
		$("#startupBtn").button("option", "disabled", true);
		$("#refreshShutdownBtn").button("option", "disabled", true);
		$("#shutdownHelpBtn").button("option", "disabled", true);
		GetPowerStatus();
	}, "json")
	.fail (function(data, status, error) {
		$("#pmDialog").text(error);
		$("#pmDialog").dialog("open");
	});	
};

function ConfirmShutdown()
{
	$("#confirmOffDialog").html("Warning: All running virtual machines will be shut down."
			+ "<p>Proceed with the shutdown operation?</p>");
	$("#confirmOffDialog").dialog("open");
};

function StartupServers()
{
	var json = new Object();
	json.command = "START";
	//json.serverList = null;
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Power", jsonStr, function (data, status) {
		isStartup = true;		
		$("#pmDialog").text("Startup request sent!");
		$("#pmDialog").dialog("open");		
		$("#shutdownBtn").button("option", "disabled", true);
		$("#startupBtn").button("option", "disabled", true);
		$("#refreshShutdownBtn").button("option", "disabled", true);
		$("#shutdownHelpBtn").button("option", "disabled", true);		
		GetPowerStatus();		
	}, "json")
	.fail (function(data, status, error) {
		$("#pmDialog").text(error);
		$("#pmDialog").dialog("open");
	})
	.done (function (data, status) {
		window.location.reload(true);
	});	
};

function ConfirmStartup()
{
	$("#confirmStartDialog").html("All off virtual machines will be started up."
			+ "<p>Proceed with the startup operation?</p>");
	$("#confirmStartDialog").dialog("open");
};

function UpdateStartupHTML() 
{
	var htmlOutput = "";
	var serverListTitleRow = "<tr class=\"dataTableRow\"><td colspan=\"3\"><b> STARTUP WILL BE PERFORMED IN THE FOLLOWING ORDER</td></tr>";
	
	//display to screen
	htmlOutput += serverListTitleRow;
	
	$("#virtualMachineList").html(htmlOutput);
};

//////////////////
//BACKUP FUNCTIONS
//////////////////
function GetBackupStatus() {
	var json = new Object();
	json.command = "GETBACKUPSTATUS";
	var jsonStr = JSON.stringify(json);
		
	$.post("/VirtualRecovery/Backup", jsonStr, function (data, status) {
		var tblRowInput = "";
		var htmlOutput = "";		
		var svrName = "";
		var currStatus = "";
		var isError = false;
		var last = "";
		
		var backupInProgress = data.backupInProgress;
		var otherInProgress = data.otherProcessInProgress;
		var numOfBackups = data.backupList.length;
		
		
		
		for (var i = 0; i < numOfBackups; i++) {
			svrName = data.backupList[i].serverName;
			currStatus = data.backupList[i].currentTask;
			isError = data.backupList[i].errorState;
			
			//the tool tip message that displays the time of the last backup if any
			last = data.backupList[i].lastBackup;
			if (last === "" || last === null) {
				last = "NONE";
			}
			//"Last Backup: "+ last
			
			tblRowInput += "<tr class=\"dataTableRow ui-state-default\"><td title=\"Last Backup completed on: " +last+ "\"><span>" + svrName + "</span></td>";
			
			if (!backupInProgress) {
				tblRowInput += "<td><span><input type=\"checkbox\" id=\"backup_" + i + "\" name=\"serverSelected\" value=\"" + svrName + "\"></span></td></tr>";
			} else {
				tblRowInput += "<td><div id=\"progressbar_" + i + "\"";
				if (isError) {
					tblRowInput += " class=\"ui-state-error\"";
				}
				tblRowInput += "><div id=\"progLbl_" + i + "\" class=\"progress-label\">" + currStatus + "</div></div></td></tr>";
			}
		}
		htmlOutput += tblRowInput;
		$("#backupList").html(htmlOutput);
		
		for (var j = 0; j < data.backupList.length; j++) {
			//the progress bar
			$("#progressbar_" + j).progressbar({
				value: data.backupList[j].progress
			});
			
			
			
			
		}
				
		if (backupInProgress) {
			$("#backupBtn").button("option", "disabled", true );
			$("#refreshBackupBtn").button("option", "disabled", true);
			$("#backupHelpBtn").button("option", "disabled", true);
			$("#backupSelectAll").hide();
			$("#backupSelectAllLbl").hide();
			$("#backup1").removeClass();
			$("#backup1").addClass("backup-vm-progress");
			$("#backup2").removeClass();
			$("#backup2").addClass("backup-progress");
			$("#backupLbl").text("Progress");
			setTimeout(GetBackupStatus, 1000);
		} else if (otherInProgress) {
			$("#backupBtn").button("option", "disabled", true );
			$("#refreshBackupBtn").button("option", "disabled", true);
			$("#backupHelpBtn").button("option", "disabled", false);
		} else {
			$("#backupSelectAll").show();
			$("#backupSelectAllLbl").show();
			$("#backupLbl").text("Backup ?");
			$("#backup1").removeClass();
			$("#backup1").addClass("backup-vm-noprogress");
			$("#backup2").removeClass();
			$("#backup2").addClass("backup-noprogress");
			
			$("#refreshBackupBtn").button("option", "disabled", false);
			$("#backupHelpBtn").button("option", "disabled", false);
			
			if(numOfBackups === 0) {
				$("#backupBtn").button("option", "disabled", true );
			} else {
				$("#backupBtn").button("option", "disabled", false );
			}
			
		}
		
	}, "json")
	.always (function(data, status) {
		$("#tabs").tabs("refresh");
	})
	.fail (function(data, status, error) {
		$("#backDialog").text("There was an error getting VM backup status!");
		$("#backDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#backDialog").dialog("open");
	});
};

function RefreshBackup() {
	var json = new Object();
	json.command = "REFRESHBACKUP";
	var jsonStr = JSON.stringify(json);
	
	$("#loadingDialog").dialog("open");
	
	$.post("/VirtualRecovery/Backup", jsonStr, function (data, status) {
		GetBackupStatus();
		$("#loadingDialog").dialog("close");
	}, "text")
	.fail (function(data, status, error) {
		$("#backDialog").text(error);
		$("#backDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#backDialog").dialog("open");
	});
};

function BackupServers() {
	var json = new Object();
	json.command = "BACKUP";
	json.backupList = [];
	var sName = "";
	var checkBoxes = $('input:checkbox[id^="backup_"]');
		
	checkBoxes.each(function (index) {
		if($(this).prop("checked")) {
			sName = $(this).val();
			json.backupList.push(
			{
				serverName: sName
			});
		}
	});
	
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Backup", jsonStr, function (data, status) {
		$("#backDialog").text("Backup request sent!");
		$("#backDialog").dialog("open");
		
		$("#backupBtn").button("option", "disabled", true );
		$("#refreshBackupBtn").button("option", "disabled", true);
		$("#backupHelpBtn").button("option", "disabled", true);
		$("#backupSelectAll").hide();
		$("#backupSelectAllLbl").hide();
		$("#backupLbl").text("Progress");
		checkBoxes.each(function (index) {
			$(this).prop("disabled", true);
		});
		$("#backup1").removeClass();
		$("#backup1").addClass("backup-vm-progress");
		$("#backup2").removeClass();
		$("#backup2").addClass("backup-progress");
		
		GetBackupStatus();
	}, "text")
	.always (function(data, status) {	
		$("#tabs").tabs("refresh");
	})
	.fail (function(data, status, error) {
		$("#backDialog").text(error);
		$("#backDialog").dialog("open");
	});
	
};

function ConfirmBackup() {
	var found = false;
	var checkBoxes = $('input:checkbox[id^="backup_"]');
	
	checkBoxes.each(function (index) {
		if($(this).prop("checked")) {
			found = true;
			return false;
		}
	});
	
	if (!found) {
		$("#backDialog").html("You must select at least one server to begin a backup operation.");
		$("#backDialog").dialog("open");
	} else {
		$("#confirmBupDialog").html("Warning: The selected virtual machine(s) will be backed up and all "
				+ "existing snapshots will be removed!<p>Proceed with the backup operation?</p>");
		$("#confirmBupDialog").dialog("open");
	}
};

///////////////////
//RESTORE FUNCTIONS
///////////////////
function GetRestoreStatus() {
	var json = new Object();
	json.command = "GETRESTORESTATUS";
	var jsonStr = JSON.stringify(json);	
	
	$.post("/VirtualRecovery/Restore", jsonStr, function (data, status) {
		
		var htmlOutput = "";		
		var tblRowInput = "";
		var size = data.restoreList.length;
		var isError = false;
		var restoreInProgress = data.restoreInProgress;
		var otherInProgress = data.otherProcessInProgress;
	
		for (var i = 0; i < size; i++) {
			var svrName = data.restoreList[i].serverName;
			var currStatus = data.restoreList[i].currentTask;
			var status = data.restoreList[i].state;
			isError = data.restoreList[i].isErrorState;
			
			tblRowInput += "<tr class=\"dataTableRow ui-state-default\"><td><span>" + svrName + "</span></td>";
			
			if (status == "running") {
				tblRowInput += "<td><span class=\"custom-icon-16 play-icon\"></span> Running</td>";
			} else if (status == "shutdown") {
				tblRowInput += "<td><span class=\"custom-icon-16 stop-icon\"></span> Powered Off</td>";
			} else if (status == "shuttingdown") {
				tblRowInput += "<td><span class=\"custom-icon-16 working-icon\"></span> Shutting Down</td>";
			} else {
				tblRowInput += "<td><span class=\"custom-icon-16 question-icon\"></span> Unknown</td>";
			}
			
			if (!restoreInProgress) {
				tblRowInput += "<td><span><input type=\"checkbox\" id=\"restore_" + i + "\" name=\"serverSelected\" value=\"" + svrName + "\"></span></td></tr>";
			} else {
				tblRowInput += "<td><div id=\"progressbar_res_" + i + "\"";
				if (isError) {
					tblRowInput += " class=\"ui-state-error\"";
				}
				tblRowInput += "><div id=\"progLbl_res_" + i + "\" class=\"progress-label\">" + currStatus + "</div></div></td></tr>";
			}
		}
		
		//Build the output		
		htmlOutput += tblRowInput;			
		$("#restoreList").html(htmlOutput);
		
		for (var j = 0; j < data.restoreList.length; j++) {
			$("#progressbar_res_" + j).progressbar({
				value: data.restoreList[j].progress
			});
		}
		
		if (restoreInProgress) {
			$("#restoreBtn").button("option", "disabled", true );
			$("#refreshRestoreBtn").button("option", "disabled", true);
			$("#restoreHelpBtn").button("option", "disabled", true);
			$("#restoreSelectAll").hide();
			$("#restoreSelectAllLbl").hide();
			$("#restore1").removeClass();
			$("#restore1").addClass("restore-vm-progress");
			$("#restore2").removeClass();
			$("#restore2").addClass("restore-progress");
			$("#restoreLbl").text("Progress");
			setTimeout(GetRestoreStatus, 1000);
		} else if (otherInProgress) {
			$("#restoreBtn").button("option", "disabled", true );
			$("#refreshRestoreBtn").button("option", "disabled", true);
			$("#restoreHelpBtn").button("option", "disabled", false);
		} else {
			$("#restoreSelectAll").show();
			$("#restoreSelectAllLbl").show();
			$("#restoreLbl").text("Restore ?");
			$("#restore1").removeClass();
			$("#restore1").addClass("restore-vm-noprogress");
			$("#restore2").removeClass();
			$("#restore2").addClass("restore-noprogress");
			
			$("#refreshRestoreBtn").button("option", "disabled", false);
			$("#restoreHelpBtn").button("option", "disabled", false);
			
			if(size === 0) {
				$("#restoreBtn").button("option", "disabled", true );
			} else {
				$("#restoreBtn").button("option", "disabled", false );
			}
		}
		
	}, "json")
	.always (function(data, status) {
		$("#tabs").tabs("refresh");
	})
	.fail (function(data, status, error) {
		$("#restoreDialog").text("There was an error getting VM restore status!");
		$("#restoreDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#restoreDialog").dialog("open");
	});
};

function RefreshRestore() {
	var json = new Object;
	json.command = "REFRESHRESTORE";
	var jsonStr = JSON.stringify(json);
	
	$("#loadingDialog").dialog("open");
	
	$.post("/VirtualRecovery/Restore", jsonStr, function (data, status) {
		GetRestoreStatus();
		$("#loadingDialog").dialog("close");
	}, "text")
	.fail (function(data, status, error) {
		$("#restoreDialog").text(error);
		$("#restoreDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#restoreDialog").dialog("open");
	});
};

function RestoreServers() {
	var json = new Object();
	json.command = "RESTORE";
	json.restoreList = [];
	var sName = "";
	var checkBoxes = $('input:checkbox[id^="restore_"]');	

	checkBoxes.each(function (index) {
		if($(this).prop("checked")) {
			sName = $(this).val();
			json.restoreList.push(
			{
				serverName: sName
			});
		}
	});
	
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Restore", jsonStr, function (data, status) {
		$("#restoreDialog").text("Restore request sent!");
		$("#restoreDialog").dialog("open");
		
		$("#restoreBtn").button("option", "disabled", true );
		$("#refreshRestoreBtn").button("option", "disabled", true);
		$("#restoreHelpBtn").button("option", "disabled", true);
		$("#restoreSelectAll").hide();
		$("#restoreSelectAllLbl").hide();
		$("#restoreLbl").text("Progress");
		$("#restore1").removeClass();
		$("#restore1").addClass("restore-vm-progress");
		$("#restore2").removeClass();
		$("#restore2").addClass("restore-progress");
		checkBoxes.each(function (index) {
			$(this).prop("disabled", true);
		});
		GetRestoreStatus();
	}, "text")
	.always (function(data, status) {			
		$("#tabs").tabs("refresh");
	})
	.fail (function(data, status, error) {
		$("#restoreDialog").text(error);
		$("#restoreDialog").dialog("open");
	});	
	
};

function ConfirmRestore() {
	var found = false;
	var checkBoxes = $('input:checkbox[id^="restore_"]');
	
	checkBoxes.each(function (index) {
		if($(this).prop("checked")) {
			found = true;
			return false;
		}
	});
	
	if (!found) {
		$("#restoreDialog").html("You must select at least one server to begin a restore operation.");
		$("#restoreDialog").dialog("open");
	} else {
		$("#confirmReDialog").html("Warning: <font color=\"red\">This action cannot be undone!</font> "
				+ "The selected virtual machine(s) will be deleted and restored from the most recent "
				+ "backup.<p>Proceed with the restore operation?</p>");
		$("#confirmReDialog").dialog("open");
	}
};


/////////////////////////
//CONFIGURATION FUNCTIONS
/////////////////////////

function SaveConfig() {
	var json = new Object();
	json.command = "SAVECONFIG";
	
	var ip = $("#vCenterIPInput").val();
	
	var isVaildIP = ValidateIPaddress(ip);
	
	if(isVaildIP){
		
		json.vCenterUser = $("#vCenterUserInput").val();
		json.vCenterPass = $("#vCenterPasswordInput").val();
		json.vCenterIPAddress = ip;
		json.vCenterVM = $("#vCenterVM").val();
		json.VRVM = $("#VRVM").val();
		
		var jsonStr = JSON.stringify(json);
		
		
		$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
			$("#configDialog").text(data);
			$("#configDialog").dialog("open");
			
			$("#submitConfigBtn").button("option", "disabled", true );
			$("#resetConfigBtn").button("option", "disabled", true );
		})
		.always (function(data, status) {
			$("#configurationTabs").tabs("refresh");
			$("#tabs").tabs("refresh");
		})
		.fail (function(data, status, error) {
			$("#configDialog").text(error);
			$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
			$("#configDialog").dialog("open");
		});
		
	} else {
		$("#configDialog").text(ip+ " is an invalid IP address! \rPlease enter the IP address in the following format:  \r[0-255].[0-255].[0-255].[0-255]");
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	}
	
	
	
};

function ResetConfig() {
	var json = new Object();
	json.command = "GETCONFIG";
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		$("#vCenterUserInput").val(data.vCenterUser);
		$("#vCenterPasswordInput").val(data.vCenterPass);
		$("#vCenterIPInput").val(data.vCenterIPAddress);
		$("#vCenterVM").val(data.vCenterVM);
		$("#VRVM").val(data.VRVM);
	}, "json")
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});
};

function ConfirmConfig()
{
	//confirmConfigDialog
	$("#confirmConfigDialog").html("The system configuration will be updated.<p>Proceed with the update?</p>");
	$("#confirmConfigDialog").dialog("open");
};

function SaveShutdownOrder() {
	var json = new Object();
	json.command = "SAVESHUTDOWNORDER";
	json.serverShutdownOrder = [];
	
	var name;
	var type;
	var order;

	if(isLoadedNewServers) {
		
		var wait_sortedOrder = $("#waitSortedOrder").data("sortedArray");
		
		for(var i = 0; i < wait_sortedOrder.length; i++)
		{
			for(var j = 0; j < newServersMapping.length; j++) {
				if(newServersMapping[j].sortableID === wait_sortedOrder[i])
				{
					var index = j; 
					if(index !== -1) {
						//console.log("index: "+index);
						name = newServersMapping[index].server;
						type = "wait";
						order = i;
						
						//add to the list to return
						json.serverShutdownOrder.push( 
						{
							serverName:name,
							shutdownOrder:order,
							shutdownType:type
						});
					}
				}
				
			}
		}
	
	
		var nowait_sortedOrder = $("#nowaitSortedOrder").data("sortedArray");
		
		for(var i = 0; i < nowait_sortedOrder.length; i++)
		{
			for(var j = 0; j < newServersMapping.length; j++)
			{
				if(newServersMapping[j].sortableID === nowait_sortedOrder[i]) {
					var index = j; 
					if(index !== -1) {
						//console.log("index: "+index);
						name = newServersMapping[index].server;
						type = "nowait";
						order = i;
						
						//add to the list to return
						json.serverShutdownOrder.push( 
						{
							serverName:name,
							shutdownOrder:order,
							shutdownType:type
						});
					}
					
				}
				
			}
			
		}
		
	} else {		
		var wait_sortedOrder = $("#waitSortedOrder").data("sortedArray");
		
		for(var i = 0; i < wait_sortedOrder.length; i++)
		{
			for(var j = 0; j < shutdownMapping.length; j++) {
				if(shutdownMapping[j].sortableID === wait_sortedOrder[i])
				{
					var index = j; 
					if(index !== -1) {
						//console.log("index: "+index);
						name = shutdownMapping[index].server;
						type = "wait";
						order = i;						
						
						//add to the list to return
						json.serverShutdownOrder.push( 
						{
							serverName:name,
							shutdownOrder:order,
							shutdownType:type
						});
					}
				}
				
			}
		}
	
	
		var nowait_sortedOrder = $("#nowaitSortedOrder").data("sortedArray");
		
		for(var i = 0; i < nowait_sortedOrder.length; i++)
		{
			for(var j = 0; j < shutdownMapping.length; j++)
			{
				if(shutdownMapping[j].sortableID === nowait_sortedOrder[i]) {
					var index = j; 
					if(index !== -1) {
						//console.log("index: "+index);
						name = shutdownMapping[index].server;
						type = "nowait";
						order = i;
												
						//add to the list to return
						json.serverShutdownOrder.push( 
						{
							serverName:name,
							shutdownOrder:order,
							shutdownType:type
						});
					}
					
				}
				
			}
			
		}
	}
	
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		$("#configDialog").text(data);
		$("#configDialog").dialog("open");	
	})
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");		
		
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});
};

function ResetShutdownOrder() {
	var json = new Object();
	json.command = "GETSHUTDOWNORDER";
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		shutdownMapping = [];
		var htmlOutput = "";		
		var maxSize = data.serverShutdownOrder.length;
		
		var nowaitArray = [];
		var waitArray = [];
		
		var waitRow = "";
		var nowaitRow = "";
		var waitListTitleRow = "<li id=\"titleRow\" class=\"ui-state-disabled ui-state-default\"><b>WAIT STACK</li>";
		var noWaitListTitleRow = "<li id=\"titleRow\" class=\"ui-state-disabled ui-state-highlight\"><b>NO WAIT STACK</li>";
		var omittedServersTitleRow = "<li id=\"titleRow\" class=\"recycleBin\"><span class=\"ui-icon ui-icon-trash\"></span><b><strong>  Recycle</strong></li>";
		
		for (var i = 0; i < maxSize;  i++) {	
			
			var type = new String;	
			var svrName = new String;
			var order;
			
			type = new String(data.serverShutdownOrder[i].shutdownType);
			svrName = new String(data.serverShutdownOrder[i].serverName);								
			order = data.serverShutdownOrder[i].shutdownOrder;
			
			if(type == "wait"){					
				waitArray.push([order+1, svrName]);				
			} else if(type=="nowait") {				
				nowaitArray.push([order+1, svrName]);
			}
			
		}
		
		waitArray.sort((function(index) {
				return function(a, b) {
					return(a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1)); 
					};		
		})(0));
		
		nowaitArray.sort((function(index) {
			return function(a, b) {
				return(a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1)); 
				};
		})(0));
		
			
		var waitSize = waitArray.length;		
		for(var j = 0; j < waitSize; j++) {
				var svrName = new String;
				var order;
				
				svrName = waitArray[j][1];
				order = waitArray[j][0];				
				
				waitRow +=  "<li id=\"waitListItem_"+j+"\" class=\"ui-state-default\" title=\""
					+svrName+ "\"><span class=\"ui-icon ui-icon-arrow-4\"></span>[" +order+ "] "+ svrName + "</li>";
				
				shutdownMapping.push({server:svrName, sortableID:"waitListItem_"+j, typeSelectID:"type_" +j});
			} 
		
		//Build the output for the wait list of servers	
		htmlOutput = "";
		htmlOutput += waitListTitleRow;
		htmlOutput += waitRow;
		$("#waitSortable").html(htmlOutput);
		
		var nowaitSize = nowaitArray.length;
		for(var k = 0; k < nowaitSize; k++) {
			var svrName = new String;
			var order;
			
			svrName = nowaitArray[k][1];
			order = nowaitArray[k][0];
			
			   				
				nowaitRow += "<li id=\"nowaitListItem_"+k+"\" class=\"ui-state-highlight\" title=\""
					+svrName+ "\"><span class=\"ui-icon ui-icon-arrow-4\"></span>[" +order+ "] "+ svrName + "</li>";
				
				shutdownMapping.push({server:svrName, sortableID:"nowaitListItem_"+k, typeSelectID:"type_" +k});
				
			}		
		
		//build the output for the no wait list of servers
		htmlOutput = "";
		htmlOutput += noWaitListTitleRow;
		htmlOutput += nowaitRow;				
		$("#nowaitSortable").html(htmlOutput);
		
		//build the output for the omitted servers 
		$("#newSortable").html(omittedServersTitleRow);

		 /* constructor and storing for the sortable */
		$("#waitSortable").sortable({
		      connectWith: ".connectedSortable",
		      items: "> li:not(#titleRow)",		  
		      placeholder: "sortable-placeholder",
		      opacity: 0.7,	
		      containment: "document",
		      revert: true,
		      tolerance: "pointer",
		      deactivate: function (event, ui) {
		    	 var content = $("#waitSortable").sortable("toArray");
		    	 $("#waitSortedOrder").data({ sortedArray: content});
		      }
		});
		
		$("#nowaitSortable").sortable({
			connectWith: ".connectedSortable",
		    items: "> li:not(#titleRow)",
		    placeholder: "sortable-placeholder",
		    opacity: 0.7,	
		    containment: "document",
		    revert: true,
		    tolerance: "pointer",
	        deactivate: function(event, ui) { 
	        	var content = $("#nowaitSortable").sortable("toArray");
	        	$("#nowaitSortedOrder").data({ sortedArray: content});	        	
	          }
		});
		
		$("#newSortable").sortable({
			connectWith: ".connectedSortable",
		    items: "> li:not(#titleRow)",
		    placeholder: "sortable-placeholder",
		    opacity: 0.7,	
		    containment: "document",
		    revert: true,
		    tolerance: "pointer"
	        
		});
		
		isLoadedNewServers = false;
				
		
	}, "json")
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");
		
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});
};
function ConfirmShutdownOrder() {
	
	//confirmOrderDialog
	$("#confirmOrderDialog").html("The Virtual Machine shutdown order will be updated.<p>Proceed with the update?</p>");
	$("#confirmOrderDialog").dialog("open");	
	
};

function UpdateLoadServersHTML() {
	var waitListTitleRow = "<li id=\"titleRow\" class=\"ui-state-disabled ui-state-default\"><b><strong>WAIT STACK</strong></li>";
	var noWaitListTitleRow = "<li id=\"titleRow\" class=\"ui-state-disabled\"><b><strong>NO WAIT STACK</strong></li>";	
	
	$("#waitSortable").html(waitListTitleRow);
	$("#nowaitSortable").html(noWaitListTitleRow);
	
	$("#waitSortedOrder").removeData("sortedArray");
	$("#nowaitSortedOrder").removeData("sortedArray");
	
	//$("#nowaitSortedOrder").after("<div><ul id=\"newSortable\" class=\"connectedSortable\"></ul></div>");
	
};

function LoadNewServers() {
	var json = new Object();
	json.command = "LOADSERVERS";
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		newServersMapping = [];
		var htmlOutput = "";		
		
		var listRow = "";
		var newListTitleRow = "<li id=\"titleRow\" class=\"recycleBin\"><span class=\"ui-icon ui-icon-trash\"></span><b><strong>  Recycle</strong></li>";
		
		var svrName = new String;
		
		var maxSize = data.serverShutdownOrder.length;
		
		for (var i = 0; i < maxSize; i++) {
			svrName = data.serverShutdownOrder[i].serverName;	
			
			listRow +=  "<li id=\"loadNew_"+i+"\" class=\"ui-state-default\" title=\""
			+svrName+ "\"><span class=\"ui-icon ui-icon-arrow-4\"></span>"+ svrName + "</li>";
				
			newServersMapping.push({server:svrName, sortableID:"loadNew_"+i, typeSelectID:"nType_" +i});
			
		}
		
		//Build HTML output		
		htmlOutput += newListTitleRow;
		htmlOutput += listRow;
				
		$("#newSortable").html(htmlOutput);
		
		/* constructor and storing for the sortable */
		$("#waitSortable").sortable({
		      connectWith: ".connectedSortable",
		      items: "> li:not(#titleRow)",		  
		      placeholder: "sortable-placeholder",
		      opacity: 0.7,	
		      containment: "document",
		      revert: true,
		      tolerance: "pointer",
		      deactivate: function (event, ui) {
		    	 var content = $("#waitSortable").sortable("toArray");
		    	 $("#waitSortedOrder").data({ sortedArray: content});
		      }
		});
		
		$("#nowaitSortable").sortable({
			connectWith: ".connectedSortable",
		    items: "> li:not(#titleRow)",
		    placeholder: "sortable-placeholder",
		    opacity: 0.7,	
		    containment: "document",
		    revert: true,
		    tolerance: "pointer",
	        deactivate: function(event, ui) { 
	        	var content = $("#nowaitSortable").sortable("toArray");
	        	$("#nowaitSortedOrder").data({ sortedArray: content});	        	
	          }
		});
		
		$("#newSortable").sortable({
			connectWith: ".connectedSortable",
		    items: "> li:not(#titleRow)",
		    placeholder: "sortable-placeholder",
		    opacity: 0.7,	
		    containment: "document",
		    revert: true,
		    tolerance: "pointer"
	        
		});
		isLoadedNewServers = true;
		
		$("#configDialog").text("New virtual machines have been loaded!!");
		$("#configDialog").dialog("open");
		
	},  "json")
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");
		
		$("#loadServersBtn").button("option", "disabled", true );
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});
	
};

function ConfirmLoadNewServers() {	
	//confirmLoadDialog
	$("#confirmLoadDialog").html("This page will be updated with new virtual machines if they exist.<p>Proceed with the update?</p>");
	$("#confirmLoadDialog").dialog("open");	
	
};

function ConfirmStartupOrder() {
	//confirmOrderDialog
	var start_sortedOrder = $("#startSortedOrder").data("sortedArray");
	
	if(start_sortedOrder === undefined || start_sortedOrder.length === 0) {
	    	//#configDialog
		$("#configDialog").html("You must order at least one server to begin save a new startup order");
		$("#configDialog").dialog("open");
	} else {
		$("#confirmStartOrderDialog").html("The virtual machine startup order will be updated.<p>Proceed with the update?</p>");
		$("#confirmStartOrderDialog").dialog("open");
	}
	
	
};

function GetStartupOrder() {
	var json = new Object();
	json.command = "GETSTARTUPORDER";
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		startupMapping = [];		
		var htmlOutput = "";		
		var maxSize = data.statusList.length;				
		var statusRow = "";
		
		var statusListTitleRow = "<li id=\"titleRow\" class=\"ui-state-disabled\"><b> </li>";
		
		//var omittedServersTitleRow = "<li id=\"titleRow\" class=\"recycleBin\"><span class=\"ui-icon ui-icon-trash\"></span><b><strong>  Recycle</strong></li>";
		
		for (var i = 0; i < maxSize;  i++) {	
						
			var svrName = new String;	
			var order = i+1;
						
			svrName = new String(data.statusList[i].serverName);		
			
			statusRow +=  "<li id=\"startupItem_"+i+"\" class=\"ui-state-default\" title=\""
						+ svrName + " Original Order: "+order
						+ "\"><span class=\"ui-icon ui-icon-arrow-4\"></span>[" +order+ "] "+ svrName + "</li>";
		
			startupMapping.push({server:svrName, sortableID:"startupItem_"+i});						
		}
		
		//Build the output for the wait list of servers	
		htmlOutput = "";
		htmlOutput += statusListTitleRow;
		htmlOutput += statusRow;
		
		$("#startupSort").html(htmlOutput);
				
		//build the output for the omitted servers 
		//$("#newSortable").html(omittedServersTitleRow);

		 /* constructor and storing for the sortable */
		//connectWith: ".startConnectedSortable",
		//var content = $("#startupSort").sortable("toArray");
		//$("#startSortedOrder").data({ sortedArray: content});
		//var content = $("#startupSort").sortable("toArray");
		$("#startupSort").sortable({		      
		      items: "> li:not(#titleRow)",		  
		      placeholder: "sortable-placeholder",
		      opacity: 0.7,	
		      containment: "document",
		      revert: true,
		      tolerance: "pointer",
		      update: function (event, ui) {		   
		    	 var content = $("#startupSort").sortable("toArray");
		    	 $("#startSortedOrder").data({ sortedArray: content});
		      }
		});
		
		
		/**
		$("#newSortable").sortable({
			connectWith: ".connectedSortable",
		    items: "> li:not(#titleRow)",
		    placeholder: "sortable-placeholder",
		    opacity: 0.7,	
		    containment: "document",
		    revert: true,
		    tolerance: "pointer"
	        
		});**/	
		
	}, "json")
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");
		
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});
	
	
};


function SaveStartupOrder() {
	var json = new Object();
	json.command = "SAVESTARTUPORDER";
	json.serverShutdownOrder = [];
	
	var name;		
	var start_sortedOrder = $("#startSortedOrder").data("sortedArray");
	
	for(var i = 0; i < start_sortedOrder.length; i++)
	{
		for(var j = 0; j < startupMapping.length; j++) {
			
			if(startupMapping[j].sortableID === start_sortedOrder[i])
			{
				var index = j; 
				if(index !== -1) {
					
					console.log("index: "+index);
					
					name = startupMapping[index].server;
					
					console.log("name: "+name);
					
					type = "wait";
					order = i;
					//add to the list to return
					json.serverShutdownOrder.push( 
					{
						serverName:name,
						shutdownOrder:order,
						shutdownType:type		
						
					});
				}
			}
			
		}
	}
	
	var jsonStr = JSON.stringify(json);
	
	$.post("/VirtualRecovery/Config", jsonStr, function (data, status) {
		$("#configDialog").text(data);
		$("#configDialog").dialog("open");	
	})
	.always (function(data, status) {
		$("#configurationTabs").tabs("refresh");
		$("#tabs").tabs("refresh");	
	})
	.fail (function(data, status, error) {
		$("#configDialog").text(error);
		$("#configDialog").dialog("option", "dialogClass", "ui-state-error");
		$("#configDialog").dialog("open");
	});

	
};

//////////////////////////
// HELP FUNCTIONS
//////////////////////////

function ShutdownHelp() {
	var intro = introJs();
	
	intro.setOptions({
		showStepNumbers: false,
		skipLabel: "Close",
		doneLabel: "Close",
		steps: [
		    {
		    	element: "#shutdownTable",
		    	intro: "<p style=\"font-size:1.2em\"><b>Shutdown Table</b></p>"
		    		+ "This table displays the current virtual machine shutdown sequence. The "
		    		+ "sequence shuts down the wait list first followed by the no wait list."
		    		+ "<p><b>Wait List</b> - Virtual Machines that will be shut down in order. Each "
		    		+ "machine will wait for the previous one to finish before shutting down.</p>"
		    		+ "<p><b>No Wait List</b> - Virtual Machines that will be shut down all at once "
		    		+ "without waiting.</p>",
		    	position: "right"
		    },
		    {
		    	element: "#shutdownBtn",
		    	intro: "<p style=\"font-size:1.2em\"><b>Shutdown Button</b></p>"
		    		+ "This button will begin the shutdown procedure. While the process is running "
		    		+ "the page will periodically update with the current status. If all listed "
		    		+ "virtual machines are currently shut down or a shutdown is in progress then "
		    		+ "this button will be disabled."
		    		+ "<p>Note - Only one server process may run at any given time.</p>",
		    	position: "top"
		    },
		    {
		    	element: "#startupBtn",
		    	intro: "<p style=\"font-size:1.2em\"><b>Startup Button</b></p>"
		    		+ "This button will begin the startup procedure. While the process is running "
		    		+ "the page will periodically update with the current status. If all listed "
		    		+ "virtual machines are currently running or a startup is in progress then "
		    		+ "this button will be disabled."
		    		+ "<p>Note - Only one server process may run at any given time.</p>",
		    	position: "top"
		    },
		    {
		    	element: "#refreshShutdownBtn",
		    	intro: "<p style=\"font-size:1.2em\"><b>Refresh Button</b></p>"
		    		+ "This button will refresh the stored server list by querying each server "
		    		+ "in the list for its current power status. If a shutdown is in progress "
		    		+ "then this button will be disabled."
		    		+ "<p>Select this Refresh button after the completion of re-ordering the shutdown"
		    		+ "sequence from the configuration tab to see the changes show up in this table.</p>"
		    		+ "<p>Note - Depending on the number of servers in the list this process "
		    		+ "may take some time to complete.</p>",
		    	position: "top"
		    }
		]
	});
	
	intro.start();
};

function BackupHelp() {
var intro = introJs();
	
	intro.setOptions({
		showStepNumbers: false,
		skipLabel: "Close",
		doneLabel: "Close",
		steps: [
			    {
			    	element: "#backupTable",
			    	intro: "<p style=\"font-size:1.2em\"><b>Backup Table</b></p>"
			    		+ "This table displays all of the current virtual machines that are managed "
			    		+ "within the VMware vCenter Server. Select a single or multiple virtual machines to "
			    		+ "backup by clicking the checkbox next to the virtual machine name.  "
			    		+ "<p><b><u>Caution</u> - Once this process starts any existing snapshots "
			    		+ "residing on a virtual machine within vCenter will be deleted at once!</b></p>"
			    		+ "<p>Note - Depending on the number of virtual machines that are selected to be "
			    		+ "backed up this process may take some time to complete.</p>",
			    	position: "right"
			    },
			    {
			    	element: "#backupSelectAll",
			    	intro: "<p style=\"font-size:1.2em\"><b>Select All</b></p>"
			    		+ "This checkbox allows the user to toggle between selecting and de-selecting "
			    		+ "all of the virtual machines listed in the table.</p>",
			    	position: "right"
			    },			   
			    {
			    	element: "#backupBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Backup Button</b></p>"
			    		+ "This button will begin the backup process. While the process is running "
			    		+ "the page will periodically update with the current status. If all listed "
			    		+ "virtual machines are currently backed up or a backup is in progress then "
			    		+ "this button will be disabled."
			    		+ "<p>Note - Only one server process may run at any given time.</p>",
			    	position: "top"
			    },
			    {
			    	element: "#refreshBackupBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Refresh Button</b></p>"
			    		+ "This button will refresh the server list by querying each server "
			    		+ "in the list for its current status. If a backup is in progress "
			    		+ "then this button will be disabled."
			    		+ "<p>Note - Depending on the number of servers in the list this process "
			    		+ "may take some time to complete.</p>",
			    	position: "top"
			    }
			]
		});
		
		intro.start();
};

function RestoreHelp() {
	var intro = introJs();
	
	intro.setOptions({
		showStepNumbers: false,
		skipLabel: "Close",
		doneLabel: "Close",
		steps: [
			    {
			    	element: "#restoreTable",
			    	intro: "<p style=\"font-size:1.2em\"><b>Restore Table</b></p>"
			    		+ "This table displays all of the virtual machines that currently have a backup file  "
			    		+ "associated and stored on the vCenter Server. <p>Select a single or "
			    		+ "multiple virtual machines to restore by clicking the checkbox next to the  "
			    		+ "virtual machine name.</p>  "
			    		+ "<p><b><u>Caution</u> - Once started this process cannot be reversed. </b></p>",
			    	position: "right"
			    },
			    {
			    	element: "#restoreSelectAll",
			    	intro: "<p style=\"font-size:1.2em\"><b>Select All</b></p>"
			    		+ "This checkbox allows the user to toggle between selecting and de-selecting "
			    		+ "all of the virtual machines listed in the table.",
			    	position: "right"
			    },			   
			    {
			    	element: "#restoreBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Restore Button</b></p>"
			    		+ "This button will begin the restore procedure. While the process is running "
			    		+ "the page will periodically update with the current status. If all listed "
			    		+ "virtual machines are currently restored or a restore is in progress then "
			    		+ "this button will be disabled."
			    		+ "<p>Note - Only one server process may run at any given time.</p>",
			    	position: "top"
			    },
			    {
			    	element: "#refreshRestoreBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Refresh Button</b></p>"
			    		+ "This button will refresh the server list by querying each server "
			    		+ "in the list for its current status. If a restore is in progress "
			    		+ "then this button will be disabled."
			    		+ "<p>Note - Depending on the number of servers in the list this process "
			    		+ "may take some time to complete.</p>",
			    	position: "top"
			    }
			]
		});
		
		intro.start();
};
function ConfigHelp() {
	var intro = introJs();
	
	intro.setOptions({
		showStepNumbers: false,
		skipLabel: "Close",
		doneLabel: "Close",
		steps: [
			    {
			    	element: "#vCenterUserInput",
			    	intro: "<p style=\"font-size:1.2em\"><b>Username</b></p>"
			    		+ "Enter the username that has sufficient privileges to manage virtual machines "
			    		+ "within the vCenter Server. ",
			    	position: "right"
			    },
			    {
			    	element: "#vCenterPasswordInput",
			    	intro: "<p style=\"font-size:1.2em\"><b>Password</b></p>"
			    		+ "Enter the password for access to vCenter Server. ",
			    	position: "right"
			    },
			    {
			    	element: "#vCenterIPInput",
			    	intro: "<p style=\"font-size:1.2em\"><b>IP Address</b></p>"
			    		+ "Enter the Host IP Address for the vCenter Server."
			    		+ "<p>Format as: [0-255].[0-255].[0-255].[0-255]</p>",
			    	position: "right"
			    },	
			    {
			    	element: "#vCenterVM",
			    	intro: "<p style=\"font-size:1.2em\"><b>vCenter VM Name</b></p>"
			    		+ "Enter the host name of the virtual machine for the vCenter Server."
			    		+ "<p><b>Note:</b> This must be the exact name of the VM.  VM names are "
			    		+ "case sensitive.</p>",
			    	position: "right"
			    },	
			    {
			    	element: "#VRVM",
			    	intro: "<p style=\"font-size:1.2em\"><b>Virtual Recovery Web App VM Name</b></p>"
			    		+ "Enter the host name of the virtual machine for this Virtual Recovery Web App."
			    		+ "<p><b>Note:</b> This must be the exact name of the VM.  VM names are "
			    		+ "case sensitive.</p>",
			    	position: "right"
			    },	
			    {
			    	element: "#submitConfigBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Submit Button</b></p>"
			    		+ "This button will submit the new settings to be saved in the temporary output directory "
			    		+ "for this Virtual Recovery Web App. ",
			    	position: "top"
			    },
			    {
			    	element: "#resetConfigBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Reset Button</b></p>"
			    		+ "This button will reset the text boxes on the current form to the default values. ",
			    	position: "top"
			    }
			]
		});
		
		intro.start();
};
function ConfigureOrderHelp() {
	var intro = introJs();
	
	intro.setOptions({
		showStepNumbers: false,
		skipLabel: "Close",
		doneLabel: "Close",
		steps: [
			    {
			    	element: "#shutdownOrderTable",
			    	intro: "<p style=\"font-size:1.2em\"><b>Shutdown Order Table</b></p>"
			    		+ "This table displays all of the virtual machines that either currently managed or available to be managed"
			    		+ "in vCenter Server. <p>To re-order a server; drag the virtual machine name to the appropriate list based on the Shutdown Type (Wait or No-Wait).</p>"
			    		+ "<p>To remove a virtual machine from either list drag and drop it to the <b>Recycle</b> area. </p>" 
			    		+ "<p><b>Wait Stack</b> - Virtual Machines that will be shut down in order. Each "
			    		+ "machine will wait for the previous one to finish before shutting down.</p>"
			    		+ "<p><b>No Wait Stack</b> - Virtual Machines that will be shut down all at once "
			    		+ "without waiting.</p>"
			    		+ "<p>Once the ordering is complete; refresh the page to see the changes applied.</p>",
			    	position: "right"
			    },
			    {
			    	element: "#shutdownOrderBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Submit Button</b></p>"
			    		+ "This button will submit the settings to be saved in the temporary output directory "
			    		+ "for this Virtual Recovery Web App. ",
			    	position: "top"
			    },			   
			    {
			    	element: "#resetShutdownBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Reset Button</b></p>"
			    		+ "This button will reset the Shutdown Order Table on the current form to the default values.",
			    	position: "top"
			    },
			    {
			    	element: "#loadServersBtn",
			    	intro: "<p style=\"font-size:1.2em\"><b>Load New Servers Button</b></p>"
			    		+ "This button will reload the Shutdown Order Table with new servers by querying vCenter for "
			    		+ "any servers that may not already be included in the list.  These new servers will be displayed "
			    		+ "along with the existing servers to be configured.",
			    	position: "top"
			    }
			]
		});
		
		intro.start();
};
function ConfigureStartOrderHelp() {
	
};

////////////////////////////
/// UTILITY FUNCTIONS
///////////////////////////

function ValidateIPaddress(ipaddress)   
{  
	var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
 if (ipaddress.match(ipformat))  
  {  
    return (true);  
  }     
    return (false);  
};  


var shutdownMapping = [];
var newServersMapping = [];
var startupMapping = [];

var isLoadedNewServers = false;
var isStartup = false;