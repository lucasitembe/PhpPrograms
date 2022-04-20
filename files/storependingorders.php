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

 
	if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storependingorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            echo "<a href='storesubmittedorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>VIEW / EDIT</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            echo "<a href='storepreviousorders.php?PreviousOrders=PreviousOrdersThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
        }
    }
        
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
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
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td style="text-align: right;"><b>Start Date<b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' required='required' autocomplete='off'>
            </td>
            <td style="text-align: right;"><b>End Date<b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' required='required' autocomplete='off'>
            </td>
            <td><input type='submit' name='submit' value='FILTER' class='art-button-green'></td>
        </tr> 
    </table>
</form>
</center>
<br/>

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 

<fieldset>
    <legend style="background-color:#006400;color:white;padding:5px;" align=right><b>Pending requisitions prepared by : <?php echo $Employee_Name; ?></b></legend>
    
    <iframe style="margin-top:5px; background-color:white" src='Pending_Requisitions_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>' width=100% height=380px></iframe>
    
</fieldset>



<?php
    include('./includes/footer.php');
?>