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
    $Product_Name=$_GET['Product_Name'];
    $End_Date=$_GET['End_Date'];
    $Start_Date=$_GET['Start_Date'];
    $Item_ID=$_GET['Item_ID'];
?>
<a href="spectaclesalesreport.php" class="art-button-green">BACK</a>
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
<input type="hidden" id="Product_Name" value="<?php echo $Product_Name;?>">
<input type="hidden" id="Start_Date" value="<?php echo $Start_Date;?>">
<input type="hidden" id="End_Date" value="<?php echo $End_Date;?>">
<input type="hidden" id="Item_ID" value="<?php echo $Item_ID;?>">
<fieldset style="background-color:">
    <center>
    	<table width="90%">
        <td style='text-align: right;'><b>Patient Name</b></td>
            <td>
                <input type='text' class="form-control" class='Patient_Name' id='Patient_Name' style='text-align: center;' placeholder='~~~ Enter Patient Name ~~~' onkeyup="find_patient1();">
            </td>
            <!-- <td width="10%" style="text-align: right;"><b>Sponsor Name</b></td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" class="form-control">
                    <option selected="selected" value="0">All</option> -->
            <!--?php
                //get list of sponsors
                $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?-->
                        <!-- <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option> -->
            <!--?php
                    }
                }
            ?-->
                <!-- </select>
            </td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Select Start Date ~~~' readonly='readonly' value='' class="form-control">
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='~~~ Select End Date ~~~' readonly='readonly' value='' class="form-control">
            </td> -->
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
            <td>
                <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report1()" class="btn">
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
    <fieldset  style="text-align:center;background-color:#037CB0;" id='List_Area'>
        <span style='color:white; text-align:center;font-size:16px;font-weight:400;'>PRODUCT NAME:<?php echo ' '.strtoupper($Product_Name). ""; ?></b></span>
    </fieldset>  

<fieldset style='overflow-y: scroll; height: 380px;' id='List_Area'>
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
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>DISPENSED DATE</b></th>
                <th style="text-align:left;"><b>DISPENSED BY</b></th>
            </tr>
        </thead>
        <tbody id='search_result'>
        <?php
         if(isset($_SESSION['Optical_info'])){
            $Sub_Department_ID = $_SESSION['Optical_info'];
        }else{
            header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
        }

        
        //$depyid=$_SESSION['Sub_Department_ID'];
			echo $Title; $temp = 0;

            // $select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name,preg.Patient_Name,pp.Registration_ID,preg.Gender, preg.Phone_Number,emp.Employee_Name,ilc.Dispense_Date_Time
            //             from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration preg,tbl_item_list_cache ilc,tbl_employee emp  where
            //             i.Item_ID = ppl.Item_ID and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and 
            //             pp.Patient_Payment_ID = ppl.Patient_Payment_ID and emp.Employee_ID=ilc.Dispensor and
            //             i.Consultation_Type = 'Optical' and pp.Registration_ID=preg.Registration_ID and
            //             pp.Transaction_status <> 'cancelled' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and i.Item_ID='$Item_ID' and
            //             pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
            
            

            $select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name,preg.Patient_Name,pp.Registration_ID,preg.Gender, preg.Phone_Number,emp.Employee_Name,ilc.Dispense_Date_Time
                        from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration preg,tbl_item_list_cache ilc,tbl_employee emp  where
                        i.Item_ID = ppl.Item_ID and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and 
                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and emp.Employee_ID=ilc.Dispensor and
                        i.Consultation_Type = 'Optical' and pp.Registration_ID=preg.Registration_ID and
                        pp.Transaction_status <> 'cancelled' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and i.Item_ID='$Item_ID' and
                        pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by ilc.Dispense_Date_Time") or die(mysqli_error($conn));

                        $select_details = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
                        from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                        i.Item_ID = ppl.Item_ID and
                        i.Item_ID = '$Item_ID' and
                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        i.Consultation_Type = 'Optical' and
                        pp.Transaction_status <> 'cancelled' and
                        pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
                                    

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
                    $product_name.=$data['Product_Name'].",";
                        echo "<tr>";
                        echo "<td style='text-align:left'>".++$temp."</td>";
                        echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
                        echo "<td>".$data['Registration_ID']."</td>";
                        // echo "<td>".$data['Product_Name']."</td>";
                        echo "<td>".$data['Gender']."</td>";
                        echo "<td>".$data['Phone_Number']."</td>";
                        echo "<td>".$data['Dispense_Date_Time']."</td>";
                        echo "<td>".$data['Employee_Name']."</td>";
                        echo "</tr>";
				}
			}
		?></tbody>
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
    function Preview_Report1(){
        var Product_Name = document.getElementById("Product_Name").value;
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Item_ID = document.getElementById("Item_ID").value;
        //alert(Product_Name)

            window.open("each_spectler_report.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Product_Name="+Product_Name+"&Item_ID="+Item_ID+"&each_spectler_report=PatientSpectaclerReport2ThisPage","_blank");

    }
</script>

<?php
    include("./includes/footer.php");
?>
<script>
        function find_patient1(){
            var Patient_Name=$("#Patient_Name").val();
            var Product_Name = document.getElementById("Product_Name").value;
            var Start_Date = document.getElementById("Start_Date").value;
            var End_Date = document.getElementById("End_Date").value;
            var Item_ID = document.getElementById("Item_ID").value;
            // alert(Patient_Name);
            $.ajax({
            type: 'POST',
            url: 'search_patient_given_specific_spectler.php',
            data: {Patient_Name:Patient_Name,
                    Product_Name:Product_Name,
                    Start_Date:Start_Date,
                    End_Date:End_Date,
                    Item_ID:Item_ID
            },
            success: function(response) {
               $("#search_result").html(response);
            }
        });
        }
</script>
