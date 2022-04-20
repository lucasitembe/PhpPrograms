<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    //get employee id 
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }
	
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    } 

    if(!isset($_SESSION['Storage_Info'])){
        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }
	
    $Store_Issue=$_SESSION['Storage_Info']['Sub_Department_ID'];
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
    	$counts_query=mysqli_query($conn,"SELECT COUNT(*)as count FROM tbl_requisition WHERE requisition_status in ('submitted','edited','saved','Not Approved') and Store_Issue='$Store_Issue'") ;
    	$row=mysqli_fetch_assoc($counts_query);
        if(isset($_GET['from_phamacy_works'])){
            $from_phamacy_works="&from_phamacy_works=yes";
        }else{
            $from_phamacy_works="";
        }
    	if($row['count']>0){
			echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage$from_phamacy_works' class='art-button-green'>NEW ISSUE NOTE  <span style='background-color:red;border-radius:8px;color:white;padding:6px;'>{$row['count']}</span></a>";
		}else{
			echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage$from_phamacy_works' class='art-button-green'>NEW ISSUE NOTE  </a>";
		}
	}

	if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && $_SESSION['userinfo']['Session_Master_Priveleges'] == 'yes'){
		echo "<a href='unapprovedissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>APPROVE ISSUES</a>";
	}

	if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
	    echo "<a href='previousissuenoteslist.php?lform=sentData&page=issue_list' class='art-button-green'>PROCESSED ISSUE NOTES</a>";
	}
    
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
        if(isset($_GET['from_phamacy_works'])){
                echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
            }else{
                echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
            }
	}

    //get sub departments
    $Search_Values = '';
    $select = mysqli_query($conn,"select Sub_Department_ID from tbl_employee_sub_department where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
    	while ($row = mysqli_fetch_array($select)) {
    		if($Search_Values == ''){
    			$Search_Values .= $row['Sub_Department_ID'];
    		}else{
    			$Search_Values .= ','.$row['Sub_Department_ID'];
    		}
    	}
    }
?>

<?php
    //get sub department name
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
            $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                    $row = mysqli_fetch_assoc($select);
                    $Sub_Department_Name = $row['Sub_Department_Name'];
            }else{
                    $Sub_Department_Name = '';
            }
    }else{
        $Sub_Department_ID = 0;
        $Sub_Department_Name = '';
    }
?>

<?php

    if(isset($_POST['submit'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
    }else{
        $Date_From = '';
        $Date_To = '';	
    }
?>

<style>
	table,tr,td{
		border-collapse:collapse !important;
		/* border:none !important; */
	}
	tr:hover{
		background-color:#eeeeee;
		cursor:pointer;
	}
 </style> 
 
<br/><br/>
<fieldset>
<center>
    <table width=60%> 
	<tr> 
	    <td style='text-align: right;' width=7%><b>From</b></td>
	    <td width=30%>
		<input type='text' name='Date_From' id='date_From' placeholder='Start Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: right;' width=7%><b>To</b></td>
	    <td width=30%>
		<input type='text' name='Date_To' id='date_To' placeholder='End Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='filteRequisition()'></td>
	</tr>
    </table>
</center>
</fieldset>
<br/>
<fieldset style='overflow-y: scroll; height: 600px; background-color: white;' id='Previous_Fieldset_List'>
    <legend style='background-color:#006400;color:white;padding:5px;' align=right>LIST OF ALL REQUISITIONS</legend>
	<table style="width: 100%;">
		<thead>
			<tr style="background-color: #eee;">
				<td width='10%' style="padding: 10px;"><center><b>SN</b></center></td>
				<td width='16%' style="padding: 10px;"><b>REQUISITION N<u>O</u></b></td>
				<td width='16%' style="padding: 10px;"><b>SENT DATE & TIME</b></td>
				<td width='16%' style="padding: 10px;"><b>STORE REQUESTED</b></td>
				<td width='16%' style="padding: 10px;"><b>STORE ISSUE</b></td>
				<td width='16%' style="padding: 10px;"><b>REQUISITION DESCRIPTION</b></td>
				<td width='10%' style="padding: 10px;"><center><b>ACTION</b></center></td>
			</tr>
		</thead>
		<tbody id='display_issue_electronic'></tbody>
	</table>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
	$('#date_From').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
		startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
		startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->

<script> 
	$(document).ready(() => {
		loadIssueNotes();
	});

	function loadIssueNotes() { 
		$.get('issuenote.core.php',{
			load_issue_note:'load_issue_note',
			Sub_Department_Name : '<?=$_SESSION['Storage'];?>',
			Store_Issue : '<?=$Store_Issue?>',
			Search_Values : '<?=$Search_Values?>'
		},(response) => {
			$('#display_issue_electronic').html(response);
		});
	}

	function filteRequisition() {  
		var Start_Date = document.getElementById("date_From").value;
		var End_Date = document.getElementById("date_To").value;

		if(Start_Date == '' || End_Date == ''){
			alert('Please Fill Start and End Date');
		}else{
			$.get('issuenote.core.php',{
				Start_Date:Start_Date,
				End_Date:End_Date,
				Store_Issue : '<?=$Store_Issue?>',
				Search_Values : '<?=$Search_Values?>',
				load_issue_note:'load_issue_note'
			},(data) => {
				$('#display_issue_electronic').html(data);
			});
		}
	}
</script>

<script>
    function Get_Previous_Requisition() {
		var Start_Date = document.getElementById("date_From").value;
		var End_Date = document.getElementById("date_To").value;
		
		if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
		    if(window.XMLHttpRequest) {
			    myObjectGetPrevious = new XMLHttpRequest();
		    }else if(window.ActiveXObject){ 
			    myObjectGetPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectGetPrevious.overrideMimeType('text/xml');
		    }
			    
		    myObjectGetPrevious.onreadystatechange = function (){
			    data80 = myObjectGetPrevious.responseText;
			    if (myObjectGetPrevious.readyState == 4) {
				document.getElementById('Previous_Fieldset_List').innerHTML = data80;
			    }
		    }; //specify name of function that will handle server response........
			    
		    myObjectGetPrevious.open('GET','Get_List_Of_Requisitions.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
		    //myObjectGetPrevious.open('GET','Get_Previous_Requisition.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
		    myObjectGetPrevious.send();
		}else{
		    if (Start_Date == null || Start_Date == '') {
				document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
				document.getElementById("date_From").focus();
		    }else{
				document.getElementById("date_From").style = 'border: 3px; text-align: center;';
		    }
		    
		    if (End_Date == null || End_Date == '') {
				document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
				document.getElementById("date_To").focus();
		    }else{
				document.getElementById("date_To").style = 'border: 3px; text-align: center;';
		    }
		}
    }
</script>
<?php
    include('./includes/footer.php');
?>
