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
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td><b>From<b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date_From' required='required' autocomplete='off'>
            </td>
            <td><b>To<b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date_To' required='required' autocomplete='off'>
            </td>
            <td><input type='submit' name='submit' value='FILTER' class='art-button-green'></td>
        </tr> 
    </table>
</form>
</center>
<br/>
<fieldset>
    <legend align=right><b><?php if(isset($_SESSION['Pharmacy_Name'])){ echo $_SESSION['Pharmacy_Name']; }?>, Pending requisitions</b></legend>
    
    <iframe src='Pharmacy_Pending_Requisitions_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>' width=100% height=380px></iframe>
    
</fieldset>



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