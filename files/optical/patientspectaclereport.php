<style>
    th{
        text-align:left;
    }
</style>
<?php
	include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			@session_start();
			if(!isset($_SESSION['Optical_Supervisor'])){ 
			    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
			}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

    }

    
         if(isset($_SESSION['Optical_info'])){
            $Sub_Department_ID = $_SESSION['Optical_info'];
        }else{
            header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
        }
?>
<a href="opticalreports.php?OpticalReports=OpticalReportsThisPage" class="art-button-green">BACK</a>
<br/><br/>
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

<fieldset style="background-color:">
    <center>
    	<table width="90%">
        <td style='text-align: right;'><b>Patient Name</b></td>
            <td>
                <input type='text' class="form-control" class='Patient_Name33' id='Patient_Name33' style='text-align: center;' placeholder='~~~ Enter Patient Name ~~~' onkeyup="find_patient(<?= $Sub_Department_ID;?>);">
            </td>
            <td width="10%" style="text-align: right;"><b>Sponsor Name</b></td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" class="form-control">
                    <option selected="selected" value="0">All</option>
            <?php
                //get list of sponsors
                $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                        <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
            <?php
                    }
                }
            ?>
                </select>
            </td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Select Start Date ~~~' readonly='readonly' value='' class="form-control">
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='~~~ Select End Date ~~~' readonly='readonly' value='' class="form-control">
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
            <td>
                <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report()" class="btn">
            </td>
    	</table>
    </center>
</fieldset>
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
        $('#Date_From').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value:'',step:01});
        $('#Date_To').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value:'',step:01});
    </script>
    <!--End datetimepicker-->    

<fieldset style='overflow-y: scroll; height: 650px;' id='List_Area'>
    <legend align="right">PATIENT SPECTACLES REPORT</legend>
    <table width="100%" class="table table-striped table-hover">
        <thead style="background-color:bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;"><b>PATIENT NUMBER</b></th>
                <th style="text-align:left;"><b>SPONSOR</b></th>
                <th style="text-align:left;"><b>AGE</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>CLINIC</b></th>
                <th style="text-align:left;"><b>ITEM</b></th>
                <th style="text-align:left;"><b>DISPENSED DATE</b></th>
                <th style="text-align:left;"><b>DISPENSED BY</b></th>
            </tr>
        </thead>
        <tbody id='search_result'>
        
        <?php
         

        
        //$depyid=$_SESSION['Sub_Department_ID'];
			echo $Title; $temp = 0;
			$select = mysqli_query($conn,"SELECT pc.Registration_ID,it.Product_Name,ilc.Payment_Item_Cache_List_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
									preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
									preg.Member_Number, ilc.Transaction_Type,ilc.Dispense_Date_Time,emp.Employee_Name from
									tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd,tbl_items it,tbl_employee emp where
									pc.payment_cache_id = ilc.payment_cache_id and
									sd.Sub_Department_ID = ilc.Sub_Department_ID and
									preg.registration_id = pc.registration_id and
									sp.Sponsor_ID = preg.Sponsor_ID and emp.Employee_ID=ilc.Dispensor and
									ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and
									ilc.Check_In_Type = 'Optical' and 
                                    ilc.Sub_Department_ID = '$Sub_Department_ID' and (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and (ilc.Transaction_Type = 'Cash' or ilc.Transaction_Type = 'Credit') group by ilc.Dispense_Date_Time order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));

           
            
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
                    
					//calculate patient age
					$date1 = new DateTime($Today);
					$date2 = new DateTime($data['Date_Of_Birth']);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";
                    $Registration_ID = $data['Registration_ID'];
                    $Dispense_date = $data['Dispense_Date_Time'];
                    $Payment_Cache_ID = $data['Payment_Cache_ID'];
                    $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                    
                    $select_item = mysqli_query($conn,"SELECT it.Product_Name from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sub_department sd,tbl_items it where
                    pc.payment_cache_id = ilc.payment_cache_id and  sd.Sub_Department_ID = ilc.Sub_Department_ID and ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and  ilc.Check_In_Type = 'Optical' and 
                    ilc.Sub_Department_ID = '$Sub_Department_ID' AND pc.Registration_ID='$Registration_ID' AND DATE(Dispense_Date_Time)=DATE('$Dispense_date') AND ilc.Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    
                   $product ="";
                   while($row_item=mysqli_fetch_assoc($select_item)){

                        $pname=$row_item['Product_Name'];
                        $product.=$pname."</br> ";
                    }
                        echo "<tr>";
                        echo "<td style='text-align:left'>".++$temp."</td>";
                        echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
                        echo "<td>".$data['Registration_ID']."</td>";
                        echo "<td>".$data['Guarantor_Name']."</td>";
                        echo "<td>".$age."</td>";
                        echo "<td>".$data['Gender']."</td>";
                        echo "<td>".$data['Phone_Number']."</td>";
                        echo "<td>".$data['Sub_Department_Name']."</td>";
                        echo "<td>".$product."</td>";
                        echo "<td>".$data['Dispense_Date_Time']."</td>";
                        echo "<td>".$data['Employee_Name']."</td>";
                        echo "</tr>";
				}
			}
        ?>
        </tbody>
	</table>
</fieldset>



<script type="text/javascript">
    function filter_list(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Date_From").value;
        var End_Date = document.getElementById("Date_To").value;    

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            if(window.XMLHttpRequest){
                mySalesObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                mySalesObject = new ActiveXObject('Micrsoft.XMLHTTP');
                mySalesObject.overrideMimeType('text/xml');
            }
            mySalesObject.onreadystatechange = function (){
                dataSales = mySalesObject.responseText;
                if (mySalesObject.readyState == 4) {
                    document.getElementById('List_Area').innerHTML = dataSales;
                }
            }; //specify name of function that will handle server response........
            
            mySalesObject.open('GET','spectacle_search.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
            mySalesObject.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

            if(End_Date == null || End_Date == ''){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }    
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Date_From").value;
        var End_Date = document.getElementById("Date_To").value;

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            window.open("patientspectaclerreport2.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID+"PatientspectaclerReport2=PatientSpectaclerReport2ThisPage","_blank");
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

            if(End_Date == null || End_Date == ''){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>
<script>
        function find_patient(Sub_Department_ID){
            var Patient_Name=$("#Patient_Name33").val();
        //    alert(Patient_Name);
            $.ajax({
            type: 'POST',
            url: 'search_patient_spectacle.php',
            data: {Patient_Name:Patient_Name, Sub_Department_ID:Sub_Department_ID},
            success: function(response) {
               $("#search_result").html(response);                
            }
        });
        }
</script>
