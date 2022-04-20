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
                    echo "<a href='Pharmacy_Control_Grn_Open_Balance_Sessions.php?Pharmacy=True&Previous_Grn_Open_Balance=True&Status=Previous' class='art-button-green'>PREVIOUS OPEN BALANCES</a>";
            }
    }
    
    if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
                    echo "<a href='pharmacygoodreceivingnote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
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


<script>
    function Filter_Previous_Grn_Open_Balances() {
    	var Start_Date = document.getElementById("date").value;
    	var End_Date = document.getElementById("date2").value;
	
	if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
	    document.getElementById("date").style = 'border: 3px';
	    document.getElementById("date2").style = 'border: 3px';
	    
	    if(window.XMLHttpRequest) {
		myObjectFilter = new XMLHttpRequest();
	    }else if(window.ActiveXObject){ 
		myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectFilter.overrideMimeType('text/xml');
	    }
	    
	    myObjectFilter.onreadystatechange = function (){
		data2000 = myObjectFilter.responseText;
		if (myObjectFilter.readyState == 4) {
		    document.getElementById('Grn_Fieldset_List').innerHTML = data2000;
		}
	    }; //specify name of function that will handle server response........
	    myObjectFilter.open('GET','Pharmacy_Filter_Previous_Grn_Open_Balance.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
	    myObjectFilter.send();
	}else{
	    if (End_Date == null || End_Date == '') {
		document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date2").focus();
	    }else{
		document.getElementById("date2").style = 'border: 3px; text-align: center;';
	    }
	    
	    if (Start_Date == null || Start_Date == '') {
		document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date").focus();
	    }else{
		document.getElementById("date").style = 'border: 3px; text-align: center;';
	    }
	}
    }
</script>


<center>
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td width=7% style='text-align: right;'><b>From<b></td>
            <td width=30%>
                <input type='text' name='Start_Date' id='date' required='required' placeholder='Start Date' readonly='readonly' style='text-align: center;'>
            </td>
            <td width=7% style='text-align: right;'><b>To<b></td>
            <td width=30%>
                <input type='text' name='End_Date' id='date2' required='required' placeholder='End Date' readonly='readonly' style='text-align: center;'>
            </td>
            <td width=10% style='text-align: center;'>
		<input name='Submit' id='Submit' type='button' value='FILTER' class='art-button-green' onclick='Filter_Previous_Grn_Open_Balances()'>
	    </td>
        </tr> 
    </table>
</form>
</center>

<fieldset style='overflow-y: scroll; height: 320px;' id='Grn_Fieldset_List'>
    <legend align=right><b><?php if(isset($_SESSION['Pharmacy_ID'])){ echo $Sub_Department_Name; }?>, Previous grn open balances</b></legend>
	<?php
	    $temp = 1;
		echo '<center><table width = 100% border=0>';
		echo '<tr><td width=5% style="text-align: center;"><b>Sn</b></td>
			    <td width=10%><b>Grn Number</b></td>
				<td width=15%><b>Prepared By</b></td>
				    <td width=15%><b>Created Date</b></td>
					<td width=30%><b>Grn Description</b></td>
					    <td width=15%><b>Supervisor Name</b></td>
						<td width=7%></td></tr>';
	    
	    //get top 50 grn open balances based on selected employee id
	    $sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, gob.Grn_Open_Balance_Description, gob.Employee_ID
                                    from tbl_grn_open_balance gob, tbl_employee emp where
                                    emp.Employee_ID = gob.Supervisor_ID and 
                                    gob.Sub_Department_ID = '$Sub_Department_ID' and
                                    gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc limit 100") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
            //get employee prepared
            $Prep_Employee = $row['Employee_ID'];
            $sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
            $Pre_no = mysqli_num_rows($sel);
            if($Pre_no > 0){
                while ($dt = mysqli_fetch_array($sel)) {
                    $Created_By = $dt['Employee_Name'];
                }
            }else{
                $Created_By = '';
            }

		    echo '<tr><td style="text-align: center;">'.++$temp.'</td>
                    <td>'.$row['Grn_Open_Balance_ID'].'</td>
                    <td>'.$Created_By.'</td>
                    <td>'.$row['Created_Date_Time'].'</td>
                    <td>'.$row['Grn_Open_Balance_Description'].'</td>
                    <td>'.$row['Employee_Name'].'</td>
                    <td style="text-align: center;"><input type="button" name="Preview" id="Preview" class="art-button-green" value="Preview" onclick="Preview_Details('.$row['Grn_Open_Balance_ID'].')"></td></tr>';
		}
	    }
	    echo '</table>';
        ?>
</fieldset>

<div id="Preview_Details" style="width:50%;" >
    <center id='Details_Area'>
    <table width=100% style='border-style: none;'>
        <tr>
        <td>
            
        </td>
        </tr>
    </table>
    </center>
</div>
<script type="text/javascript">
    function Preview_Details(Grn_Open_Balance_ID){
        if(window.XMLHttpRequest){
            myObjectAddItem = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function (){
            data2922 = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data2922;
                $("#Preview_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectAddItem.open('GET','Preview_Grn_Details.php?Grn_Open_Balance_ID='+Grn_Open_Balance_ID,true);
        myObjectAddItem.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
   $(document).ready(function(){
      $("#Preview_Details").dialog({ autoOpen: false, width:'95%',height:600, title:'GRN OPEN BALANCE DETAILS',modal: true});      
   });
</script>


<script type="text/javascript">
    function Preview_Report(Grn_Open_Balance_ID){
        var winClose=popupwindow('Preview_Grn_Details_Report.php?Grn_Open_Balance_ID='+Grn_Open_Balance_ID, 'GRN DETAILS', 1200, 500);
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


<?php
    include('./includes/footer.php');
?>