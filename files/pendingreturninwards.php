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
            echo "<a href='Control_Return_Inward_Sessions.php?New_Return_Inward=True&Status=new' class='art-button-green'>NEW RETURN INWARD</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='pendingreturninwards.php?PendingReturnInward=PendingReturnInwardThisPage' class='art-button-green'>PENDING RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='previousreturninwards.php?PreviousReturnInward=PreviousReturnInwardThisPage' class='art-button-green'>PREVIOUS RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>
 
<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<center>
    <table width="60%" style="background-color: white;"> 
        <tr> 
            <td style="text-align: right;"><b>Start Date</b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date_From' required='required' readonly='readonly' style='text-align: center;' placeholder='Start Date'>
            </td>
            <td style="text-align: right;"><b>End Date</b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date_To' required='required' readonly='readonly' style='text-align: center;' placeholder='End Date'>
            </td>
            <td style="text-align: center;"><input name='submit' id='submit' type='button' value='FILTER' class='art-button-green' onclick="Filter_Grn();"></td>
        </tr> 
    </table>
</center>
<br/>	
<fieldset style='overflow-y: scroll; height: 390px; background-color: white;' id='Grn_Fieldset_List'>
	<legend align=right><b>Pending Return Inwards prepared by : <?php echo ucwords(strtolower($Employee_Name)); ?></b></legend>
	    <?php
            $temp = 0;
		    echo '<center><table width = 100% border=0>';
            echo "<tr><td colspan='7'><hr></td></tr>";
            echo '<tr>
                    <td width=5% style="text-align: center;"><b>SN</b></td>
                    <td width=10% style="text-align: center;"><b>TRANSACTION NUMBER</b></td>
                    <td width=15%><b>PREPARED BY</b></td>
                    <td width=15%><b>RETURNED FROM</b></td>
                    <td width=15%><b>STORE RECEIVING</b></td>
                    <td width=15%><b>CREATED DATE</b></td>
                    <td width=7%></td></tr>';
            echo "<tr><td colspan='7'><hr></td></tr>";
		
		//get top 50 grn open balances based on selected employee id
		
		$sql_select = mysqli_query($conn,"SELECT
                                      Inward_ID, Inward_Date, ssd.Sub_Department_Name as ssd, rsd.Sub_Department_Name as rsd, Employee_Name
                                   FROM
                                      tbl_return_inward ri, tbl_sub_department ssd, tbl_sub_department rsd, tbl_employee emp
                                   WHERE
                                      ssd.Sub_Department_ID = ri.Store_Sub_Department_ID AND
                                      rsd.Sub_Department_ID = ri.Return_Sub_Department_ID AND
                                      emp.Employee_ID = '$Employee_ID' AND
                                      Inward_Status = 'pending'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($sql_select);
		if($num > 0){
		    while($row = mysqli_fetch_array($sql_select)){
			echo '<tr><td style="text-align: center;">'.++$temp.'</td>
                        <td style="text-align: center;">'.$row['Inward_ID'].'</td>
                        <td>'.ucwords(strtolower($row['Employee_Name'])).'</td>
                        <td>'.ucwords(strtolower($row['rsd'])).'</td>
                        <td>'.ucwords(strtolower($row['ssd'])).'</td>
                        <td>'.$row['Inward_Date'].'</td>
                        <td><a href="Control_Return_Inward_Sessions.php?Pending_Return_Inward=True&Return_Inward_ID='
                        .$row['Inward_ID'].'" class="art-button-green">Process</a></td></tr>';
		    }
		}
		echo '</table>';
	    ?>
    </fieldset>

<script type="text/javascript">
    function Filter_Grn(){
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            if(window.XMLHttpRequest) {
                myObjectFilterGrn = new XMLHttpRequest();
            }else if(window.ActiveXObject){ 
                myObjectFilterGrn = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilterGrn.overrideMimeType('text/xml');
            }
                
            myObjectFilterGrn.onreadystatechange = function (){
                data8099 = myObjectFilterGrn.responseText;
                if (myObjectFilterGrn.readyState == 4) {
                    document.getElementById('Grn_Fieldset_List').innerHTML = data8099;
                }
            }; //specify name of function that will handle server response........
                
            myObjectFilterGrn.open('GET','Filter_Grn_Open_Balance.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
            myObjectFilterGrn.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("date_From").style = 'border: 2px solid red; text-align: center;';
            }
            
            if(End_Date == null || End_Date == ''){
                document.getElementById("date_To").style = 'border: 2px solid red; text-align: center;';
            }
        }
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