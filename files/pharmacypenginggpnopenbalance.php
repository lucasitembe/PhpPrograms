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
            echo "<a href='Pharmacy_Control_Grn_Open_Balance_Sessions.php?New_Grn_Open_Balance=True&Status=new' class='art-button-green'>NEW OPEN BALANCE</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
                    echo "<a href='pharmacypenginggpnopenbalance.php?PendingGrnOpenBalance=PendingGrnOpenBalanceThisPage' class='art-button-green'>PENDING OPEN BALANCES</a>";
            }
	}
   
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
            echo "<a href='Pharmacy_Control_Grn_Open_Balance_Sessions.php?Previous_Grn_Open_Balance=True&Status=Previous' class='art-button-green'>PREVIOUS OPEN BALANCES</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
            echo "<a href='pharmacygrnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>

<?php
    //get sub department details
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Sub_Department_Name = $row['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
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


<br/><br/>
<center>
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td><b>From</b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' required='required' readonly='readonly' style='text-align: center;' placeholder='Start Date'>
            </td>
            <td><b>To</b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' required='required' readonly='readonly' style='text-align: center;' placeholder='End Date'>
            </td>
            <td><input name='submit' id='submit' type='submit' value='FILTER' class='art-button-green'></td>
        </tr> 
    </table>
</form>
</center>

	<!--<iframe src='Pending_Grn_Open_Balance_Iframe.php?Employee_ID=<?php //echo $Employee_ID; ?>' width=100% height=350px></iframe>-->
	
	<fieldset style='overflow-y: scroll; height: 320px;' id='Grn_Fieldset_List'>
	<legend align=right><b><?php if(isset($_SESSION['Pharmacy_ID'])){ echo $Sub_Department_Name; }?>, Pending Grn Open Balances prepared by : <?php echo $Employee_Name; ?></b></legend>
	    <?php
		$temp = 1;
		    echo '<center><table width = 100% border=0>';
		    echo '<tr><td width=5% style="text-align: center;"><b>Sn</b></td>
				<td width=10%><b>Grn Number</b></td>
				    <td width=15%><b>Prepared By</b></td>
					<td width=15%><b>Created Date</b></td>
					    <td width=30%><b>Grn Description</b></td>
						<td width=15%><b>Prepared By</b></td>
						    <td width=7%></td></tr>';
		
		//get top 50 grn open balances based on selected employee id
		
		$sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, gob.Grn_Open_Balance_Description
					    from tbl_grn_open_balance gob, tbl_employee emp where
						emp.Employee_ID = gob.Employee_ID and
						    gob.Employee_ID = '$Employee_ID' and
							gob.Sub_Department_ID = '$Sub_Department_ID' and
							    gob.Grn_Open_Balance_Status = 'pending' order by Grn_Open_Balance_ID limit 1") or die(mysqli_error($conn));
		$num = mysqli_num_rows($sql_select);
		if($num > 0){
		    while($row = mysqli_fetch_array($sql_select)){
			echo '<tr><td style="text-align: center;">'.$temp.'</td>
			    <td>'.$row['Grn_Open_Balance_ID'].'</td>
				<td>'.$Employee_Name.'</td>
				    <td>'.$row['Created_Date_Time'].'</td>
					<td>'.$row['Grn_Open_Balance_Description'].'</td>
					    <td>'.$row['Employee_Name'].'</td>
						<td><a href="Pharmacy_Control_Grn_Open_Balance_Sessions.php?Pending_Grn_Open_Balance=True&Pharmacy_Grn_Open_Balance_ID='.$row['Grn_Open_Balance_ID'].'" class="art-button-green">Continue Process</a></td></tr>';
		    }
		}
		echo '</table>';
	    ?>
    </fieldset>





<?php
    include('./includes/footer.php');
?>