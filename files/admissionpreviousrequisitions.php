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
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    	header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    } 

 
	if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
                echo "<a href='Admission_Control_Requisition_Sessions.php?New_Requisition=True' class='art-button-green'>NEW REQUISITION</a>";
        }
	}
	
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
                echo "<a href='admissionpendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage' class='art-button-green'>PENDING REQUISITIONS</a>";
        }
	}
	if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
                echo "<a href='admissionpreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage' class='art-button-green'>PREVIOUS REQUISITIONS</a>";
        }
	}
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
                echo "<a href='admissionrequisition.php?Status=PendingRequisition&Requisition=RequisitionThisPage' class='art-button-green'>BACK</a>";
        }
	}

	if(isset($_SESSION['Admission_ID'])){
        $Sub_Department_ID  = $_SESSION['Admission_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }
?>


    <!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->
    
<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date2").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
<!--    end of datepicker script-->


<?php

    if(isset($_POST['submit'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
    }else{
        $Date_From = '';
        $Date_To = '';	
    }
?>

<br/><br/>
<center>
    <table width=60%> 
	<tr> 
	    <td style='text-align: right;' width=7%><b>From</b></td>
	    <td width=30%>
		<input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: right;' width=7%><b>To</b></td>
	    <td width=30%>
		<input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Previous_Requisition()'></td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 350px;' id='Previous_Fieldset_List'>
    <legend align=right><b><?php if(isset($_SESSION['Admission_ID'])){ echo $Sub_Department_Name; }?>, Previous Requisitions Prepared By : <?php echo $Employee_Name; ?></b></legend>
	<?php
	    $temp = 1;
		echo '<center><table width = 100% border=0>';
		echo "<tr id='thead'>
			<td width=4% style='text-align: center;'><b>Sn</b></td>
			<td width=10% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
			<td width=15%><b>Created Date & Time</b></td>
			<td width=15%><b>Sent Date & Time</b></td>
			<td width=15%><b>Issuing Store</b></td>
			<td width=30%><b>Requisition Description</b></td>
			<td style='text-align: center;' width=10%><b>Action</b></td>
		    </tr>";
	    
	    //get top 50 grn open balances based on selected employee id
	    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name from
					tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
					    rq.store_issue = sd.sub_department_id and
						    emp.employee_id = rq.employee_id and
							rq.requisition_status = 'submitted' and
							    rq.Store_Need = '$Sub_Department_ID' and
								rq.Employee_ID = '$Employee_ID' order by rq.Requisition_ID desc") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
		    echo '<tr><td style="text-align: center;">'.$temp.'</td>
			<td>'.$row['Requisition_ID'].'</td>
				<td>'.$row['Created_Date_Time'].'</td>
				    <td>'.$row['Sent_Date_Time'].'</td>
					<td>'.$row['Sub_Department_Name'].'</td>	
					    <td>'.$row['Requisition_Description'].'</td> 
						<td style="text-align: center;"><a href="previousrequisitionreport.php?Requisition_ID='.$row['Requisition_ID'].'&RequisitionReport=RequisitionReportThisPage" target="_blank" class="art-button-green">&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a></td></tr>';
		$temp++;
		}
	    }
	    echo '</table>';
	?>
</fieldset>

    <!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID; ?>&Date_From=<?php //echo $Date_From; ?>&Date_To=<?php //echo $Date_To; ?>' width=100% height=380px></iframe>-->

<script>
    function Get_Previous_Requisition() {
	var Start_Date = document.getElementById("date").value;
	var End_Date = document.getElementById("date2").value;
	
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
		    
	    myObjectGetPrevious.open('GET','Admission_Get_Previous_Requisition.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
	    myObjectGetPrevious.send();
	}else{
	    
	    if (Start_Date == null || Start_Date == '') {
		document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date").focus();
	    }else{
		document.getElementById("date").style = 'border: 3px; text-align: center;';
	    }
	    
	    if (End_Date == null || End_Date == '') {
		document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date2").focus();
	    }else{
		document.getElementById("date2").style = 'border: 3px; text-align: center;';
	    }
	}
    }
</script>
<?php
    include('./includes/footer.php');
?>