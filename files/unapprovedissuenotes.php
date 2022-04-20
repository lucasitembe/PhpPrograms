<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include_once("./functions/department.php");
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
    
   	if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
	    echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE</a>";
    }
    
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && $_SESSION['userinfo']['Session_Master_Priveleges'] == 'yes'){
		echo "<a href='unapprovedissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>APPROVE ISSUES</a>";
	}
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
	    echo "<a href='previousissuenoteslist.php?lform=sentData&page=issue_list' class='art-button-green'>PREVIOUS ISSUES</a>";
	}
    
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
	    echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
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
	$Store_Issue=$_SESSION['Storage_Info']['Sub_Department_ID'];
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

<style>
	table,tr,td{
		border-collapse:collapse !important;
		
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
			<td width=30%>
				<input type='text' name='Date_From' id='date_From' placeholder='Start Date' style='text-align: center;'>
			</td>
			<td width=30%>
				<input type='text' name='Date_To' id='date_To' placeholder='End Date' style='text-align: center;'>
			</td>
			<td width='35%'>
				<select id="store_need_id" style="width: 100%;">
				<option value="all">All</option>
				<?php 
					$Sub_Department_List = Get_Sub_Department_By_Department_Nature('Storage And Supply');
					foreach ($Sub_Department_List as $Sub_Department) {
							echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
							echo "{$Sub_Department['Sub_Department_Name']}";
							echo "</option>";
						
					}
				?>
				</select>
			</td>
			<td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='filterUnprocessed()'></td>
		</tr>
		</table>
	</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 500px; background-color: white;' id='Previous_Fieldset_List'>
    <legend style='background-color:#006400;color:white;padding:5px;' align=right>LIST OF UNAPPROVED REQUISITION</legend>
	<center>
		<table width = 100% border=0>
			<thead>
				<tr id='thead' style='background-color:#eee'>
					<td width=4% style='text-align: center;padding:8px'><b>SN</b></td>
					<td width=10% style='text-align: left;padding:8px'><b>REQUISITION N<u>O</u></b></td>
					<td width=15% style='padding:8px'><b>SENT DATE & TIME</b></td>
					<td width=15% style='padding:8px'><b>STORE NEED</b></td>
					<td width=15% style='padding:8px'><b>STORE ISSUE</b></td>
					<td width=30% style='padding:8px'><b>REQUISITION DESCRIPTION</b></td>
					<td style='text-align: center;padding:8px' width=10%><b>ACTION</b></td>
				</tr>
			</thead>

			<tbody id="display_data"></tbody>
		</table>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/select2.min.js"></script>



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
		loadUnprovedRequisition();
		$('select').select2();
	});

	function loadUnprovedRequisition() {  
		$.get('issuenote.core.php',{
			Sub_Department_Name : '<?=$_SESSION['Storage']?>',
			load_unapproved_issue_note:'load_unapproved_issue_note',
			Search_Values : '<?=$Search_Values?>',
			Store_Issue : '<?=$Store_Issue?>'
		},(data)=>{
			$('#display_data').html(data);
		});
	}

	function filterUnprocessed() {  
		var Start_Date = document.getElementById("date_From").value;
		var End_Date = document.getElementById("date_To").value;
		var store_need_id = document.getElementById('store_need_id').value;

		if(Start_Date == "" || End_Date == ""){
			alert('Enter Date Range ... ');
		}else{
			$.get('issuenote.core.php',{
				Sub_Department_Name : '<?=$_SESSION['Storage']?>',
				load_unapproved_issue_note:'load_unapproved_issue_note',
				Search_Values : '<?=$Search_Values?>',
				Store_Issue : '<?=$Store_Issue?>',
				Start_Date:Start_Date,
				End_Date:End_Date,
				store_need_id :store_need_id
			},(data) => {
				$('#display_data').html(data);
			});
		}
	}
</script>

<?php
    include('./includes/footer.php');
?>