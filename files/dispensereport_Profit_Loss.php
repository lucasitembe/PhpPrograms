<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
		    header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
 
<?php
    if(isset($_SESSION['Pharmacy_ID'])){
	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
    
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0 ){
	while($data = mysqli_fetch_array($select)){
	    $Sub_Department_Name = $data['Sub_Department_Name'];
	}
    }else{
	$Sub_Department_Name = '';
    }
    
     $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $dataSponsor='';
    $dataSponsor.='<option value="All">All Sponsors</option>';
    
    while ($row = mysqli_fetch_array($query)) {
         $dataSponsor.= '<option value="'.$row['Sponsor_ID'].'">'.$row['Guarantor_Name'].'</option>';
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>

<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
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

<?php
    //get current date and time
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = $Current_Date_Time;
?>
<center>
    <table width=100%>
        <tr>
           <!-- <td style='text-align: right;'><b>Bill Type</b></td>
            <td>
                <select name='Bill_Type' id='Bill_Type' required='required'>
                    <option selected='selected'>All</option>
                    <option>Outpatient Cash</option>
                    <option>Outpatient Credit</option>
                    <option>Inpatient Cash</option>
                    <option>Inpatient Credit</option>
                </select>
            </td> -->
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='dates_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Filter_Value; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='dates_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Filter_Value; ?>'>
            </td>
            <td>
                <select id="sponsorID" style='text-align: center;padding:5px; width:100%;display:inline'>
                    <?php echo $dataSponsor ?>
                </select>
            </td>
            <td width=35%>
                <input type='text' name='Search_Patient' id="Search_Value" style='text-align: center;' autocomplete='off' onkeyup='Filter_Dispense_List()' onkeypress='Filter_Dispense_List()' placeholder='~~~~~ Search Item Name ~~~~~~'>
            </td>
            <td style='text-align: center;' width=7%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Dispense_List()'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset style='overflow-y: scroll; height: 410px;background-color:white;margin-top:20px;' id='Items_Fieldset'>
     <legend align='right'><b>DISPENSE SUMMARY ~ <?php if(isset($_SESSION['Pharmacy_ID'])){ echo strtoupper($Sub_Department_Name);  }?></b></legend>
        <?php
            if(isset($_SESSION['Storage'])){
                $Sub_Department_Name = $_SESSION['Storage'];
            }else{
                $Sub_Department_Name = '';
            }
        ?>    <center>
             <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            
            <table width=100% border=1>
                
		<!--<iframe width='100%' height=350px src='Pharmacy_List_Iframe.php?Billing_Type=<?php //echo $Billing_Type; ?>'></iframe>-->
                    <!-- get sub department id -->
                   <?php
					    $temp = 1; $total_items = 0;
					    if(isset($_SESSION['Pharmacy'])){
						$Sub_Department_Name = $_SESSION['Pharmacy'];
					    }else{
						$Sub_Department_Name = '';
					    } 
                                            
//                                             echo"<tr>
//                                            <td width=5%><b>SN</b></td>
//                                            <td width=50%><b>ITEM NAME</b></td>
//                                            <td ><b>QUANTITY DISPENSED</b></td>
//                                            <td><b>BALANCE</b></td>
//                                            </tr>";
                                            
					    $result = mysqli_query($conn,"select i.Item_ID, i.Product_Name FROM tbl_items i,tbl_item_list_cache ilc
                                                                    where i.Item_ID = ilc.Item_ID and 
                                                                        ilc.Payment_Date_And_Time between '$Filter_Value' and '$Filter_Value' and
                                                                            ilc.Check_In_Type = 'pharmacy' and
                                                                                ilc.Status = 'dispensed' and
										    ilc.Sub_Department_ID = '$Sub_Department_ID'
											group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                                            //$num = mysqli_num_rows($result); echo $num; 
                                            while($row = mysqli_fetch_array($result)){
                                                $Item_ID = $row['Item_ID'];
						$Product_Name = $row['Product_Name'];
                                                $Individual_Details = mysqli_query($conn,"select i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                                                    FROM tbl_items i,tbl_item_list_cache ilc
                                                                                        where i.Item_ID = ilc.Item_ID and 
                                                                                            ilc.Payment_Date_And_Time between '$Filter_Value' and '$Filter_Value' and
                                                                                                ilc.Check_In_Type = 'pharmacy' and
                                                                                                    ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                                                
                                                while($row2 = mysqli_fetch_array($Individual_Details)){
						    $Quantity = $row2['Quantity'];
						    $Edited_Quantity = $row2['Edited_Quantity'];
						    if($Edited_Quantity != 0){
							$total_items = $total_items + $Edited_Quantity;
						    }else{
							$total_items = $total_items + $Quantity;
						    }
                                                }
						$sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
									    Item_ID = '$Item_ID' and
										Sub_Department_ID = '$Sub_Department_ID'
										    ") or die(mysqli_error($conn));
						$num_balance = mysqli_num_rows($sql_balance);
						if($num_balance > 0){
						    while($sd = mysqli_fetch_array($sql_balance)){
							$Item_Balance = $sd['Item_Balance'];
						    }
						}else{
						    $Item_Balance = 0;
						}
						
						
						echo "<tr><td style='text-align: left;' width=6%>".$temp."</td>";
						echo "<td style='text-align: left;' width=53%><a href='dispensereportdetails.php?Start_Date=".$Filter_Value."&End_Date=".$Filter_Value."&Item_ID=".$Item_ID."&DetailsReport=DetailsReportThisPage' style='text-decoration: none;'>".$Product_Name."</a></td>";
						echo "<td style='text-align: right;'><a href='dispensereportdetails.php?Start_Date=".$Filter_Value."&End_Date=".$Filter_Value."&Item_ID=".$Item_ID."&DetailsReport=DetailsReportThisPage' style='text-decoration: none;'>".$total_items."</a></td>";
						echo "<td style='text-align: right;'><a href='dispensereportdetails.php?Start_Date=".$Filter_Value."&End_Date=".$Filter_Value."&Item_ID=".$Item_ID."&DetailsReport=DetailsReportThisPage' style='text-decoration: none;'>".$Item_Balance."</a></td></tr>";
						$temp++;
						$Edited_Quantity = 0;
						$Quantity = 0;
						$total_items = 0;
                                            }
					    
					?> 
                 
       
            </table>
        </center>
</fieldset>
<table width="100%">
<tr>     
  <td style="text-align:right;padding-right: 17px"><a id="previewDispensedList" style="float:right;" href="previewDispensedList.php?Start_Date=<?php echo $Today?>'&End_Date=<?php echo $Today ?>'&Sponsor=All" target="_blank" class="art-button-green">Preview</a></td>
</tr>
    
</table>
<!--<fieldset>
       
        
                            <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Dispense_List()'>
			</td>
			    
                    <tr>
			<td colspan=4>
			    <table width=100%>
				<tr>
				    <td width=7%><b>SN</b></td>
				    <td width=51%><b>ITEM NAME</b></td>
				    <td><b>QUANTITY DISPENSED</b></td>
				    <td><b>BALANCE</b></td>
				</tr>
			    </table>
				<fieldset style='overflow-y: scroll; height: 300px;' id='Items_Fieldset'>
				    <table width=100%>
					
				    </table>
				</fieldset>		
			</td>
                    </tr>
            </table>
</fieldset>-->


<script>
	function Filter_Dispense_List() {
	    var Start_Date = document.getElementById("dates_From").value;
	    var End_Date = document.getElementById("dates_To").value;
	    var Search_Value = document.getElementById("Search_Value").value;
            var Sponsor=document.getElementById('sponsorID').value;
	    
	    if (Start_Date != null && Start_Date != '' && End_Date != '' && End_Date != null){
		if(window.XMLHttpRequest) {
		    myObjectFilterDispensed = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectFilterDispensed = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectFilterDispensed.overrideMimeType('text/xml');
		}
		
		myObjectFilterDispensed.onreadystatechange = function (){
			data20 = myObjectFilterDispensed.responseText;
			if (myObjectFilterDispensed.readyState == 4) {
			    document.getElementById('Items_Fieldset').innerHTML = data20;
                            $("#progressStatus").hide();
			}
		}; //specify name of function that will handle server response........
		if (Search_Value != '' && Search_Value != null) {
                    $("#progressStatus").show();
                    $("#previewDispensedList").attr('href','printProfitLosss.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Search_Patient='+Search_Value+ '&Sponsor=' + Sponsor);
         
		    myObjectFilterDispensed.open('GET','Get_Filtered_Items_Dispensed_Profit_Loss.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Search_Value='+Search_Value+'&Sponsor=' + Sponsor,true);
		}else{
                    $("#previewDispensedList").attr('href','printProfitLosss.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor=' + Sponsor);
                    $("#progressStatus").show();
		    myObjectFilterDispensed.open('GET','Get_Filtered_Items_Dispensed_Profit_Loss.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor=' + Sponsor,true);
		}
		myObjectFilterDispensed.send();
	    }else{
		if (Start_Date == null || Start_Date == '') {
		    document.getElementById("date").style = 'border: 3px solid red';
		    document.getElementById("date").focus();
		}else{
		    document.getElementById("date").style = 'border: 3px';
		}
		if (End_Date == null || End_Date == '') {
		    document.getElementById("date2").style = 'border: 3px solid red';
		    document.getElementById("date2").focus();
		}else{
		    document.getElementById("date2").style = 'border: 3px';
		}
	    }
	}
        
        
         
        $('#print_document').click(function(){
          var fromDate=$('#date').val();
          var toDate=$('#date2').val();
          var Printlink='printProfitLosss.php?DispenseReport=DispenceReportThisPage&fromDate='+fromDate+'&toDate='+toDate;
          window.open(Printlink,'_blank');
        });
</script>
<script type="text/javascript">
    $('#dates_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_From').datetimepicker({value: '', step: 30});
    $('#dates_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_To').datetimepicker({value: '', step: 30});

</script>

</center>
<br/>
<?php
    include("./includes/footer.php");
?>