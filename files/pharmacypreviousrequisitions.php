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
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
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
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
                echo "<a href='Pharmacy_Control_Requisition_Sessions.php?New_Requisition=True' class='art-button-green'>NEW REQUISITION</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
                echo "<a href='pharmacypendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage' class='art-button-green'>PENDING REQUISITIONS</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
                echo "<a href='pharmacypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage' class='art-button-green'>PREVIOUS REQUISITIONS</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
                echo "<a href='requisitionworks.php?RequisitionWorks=RequisitionWorksThisPage#' class='art-button-green'>BACK</a>";
        }
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
	    <td style='text-align: right;' width=7%><b>Start Date</b></td>
	    <td width=30%>
		<input type='text' name='Date_From' id='date_From' placeholder='Start Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: right;' width=7%><b>End Date</b></td>
	    <td width=30%>
		<input type='text' name='Date_To' id='date_To' placeholder='End Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Previous_Requisition()'></td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 380px;' id='Previous_Fieldset_List'>
    <legend align=right><b><?php if(isset($_SESSION['Pharmacy'])){ echo $_SESSION['Pharmacy']; }?>, Previous Requisitions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
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
	    $Sub_Department_Name = $_SESSION['Pharmacy'];
	    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name from
					tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
					    rq.store_issue = sd.sub_department_id and
						    emp.employee_id = rq.employee_id and rq.requisition_status = 'submitted'
							order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
		    echo '<tr><td style="text-align: center;">'.$temp.'</td>
                    <td>'.$row['Requisition_ID'].'</td>
                    <td>'.$row['Created_Date_Time'].'</td>
                    <td>'.$row['Sent_Date_Time'].'</td>
                    <td>'.$row['Sub_Department_Name'].'</td>	
                    <td>'.$row['Requisition_Description'].'</td> 
                    <td style="text-align: center;">
                        <input type="button" value="Preview" onclick="Preview_Requisition_Report('.$row['Requisition_ID'].');" class="art-button-green">
                    </td></tr>';
                    $temp++;
		}
	    }
	    echo '</table>';
	?>
</fieldset>

    <!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID; ?>&Date_From=<?php //echo $Date_From; ?>&Date_To=<?php //echo $Date_To; ?>' width=100% height=380px></iframe>-->

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
		    
	    myObjectGetPrevious.open('GET','Pharmacy_Get_Previous_Requisition.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
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



<script type="text/javascript">
    function Preview_Requisition_Report(Requisition_ID){
        var winClose=popupwindow('previousrequisitionreport.php?Requisition_ID='+Requisition_ID+'&RequisitionReport=RequisitionReportThisPage', 'REQUISITION DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>


<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:  'now'
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

<?php
    include('./includes/footer.php');
?>