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


    if(isset($_SESSION['Departmental_Requisition_Control'])){
        echo "<a href='Control_Departmental_Requisition_Sessions.php?New_Requisition=True' class='art-button-green'>NEW REQUISITION</a>";
    }
    
    if(isset($_SESSION['Departmental_Requisition_Control'])){
        echo "<a href='reagentspendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage' class='art-button-green'>PENDING REQUISITIONS</a>";
    }
    
    if(isset($_SESSION['Departmental_Requisition_Control'])){
        echo "<a href='reagentspreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage' class='art-button-green'>PREVIOUS REQUISITIONS</a>";
    }
    
    if(isset($_SESSION['Departmental_Requisition_Control'])){
        echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
    }
    
    //get sub department name
    if(isset($_SESSION['Departmental_Requisition_Control'])){
        $Sub_Department_ID = $_SESSION['Departmental_Requisition_Control'];
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0 ){
            while($row = mysqli_fetch_array($select)){
                $Sub_Department_Name = $row['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
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
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td><b>From<b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' required='required' autocomplete='off'>
            </td>
            <td><b>To<b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' required='required' autocomplete='off'>
            </td>
            <td><input type='submit' name='submit' value='FILTER' class='art-button-green'></td>
        </tr> 
    </table>
</form>
</center>
<br/>
<fieldset>
    <legend align=right><b><?php if(isset($_SESSION['Departmental_Requisition_Control'])){ echo $Sub_Department_Name; } ?>, Previous Reagent requisitions prepared by : <?php echo $Employee_Name; ?></b></legend>
    
    <iframe src='Reagent_Previous_Requisitions_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>' width=100% height=380px></iframe>
    
</fieldset>



<?php
    include('./includes/footer.php');
?>