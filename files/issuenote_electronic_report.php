<?php
@session_start();
include("./includes/connection.php");
include("./functions/issuenotes.php");
include("./functions/department.php");
    //get sub department name
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            $row = mysqli_fetch_assoc($select);
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }else{
            $Sub_Department_Name = '';
        }
    }else{
        $Sub_Department_ID = 0;
        $Sub_Department_Name = '';
    }
if(isset($_GET['Store_Receiving_ID'])){
   $Store_Receiving_ID=$_GET['Store_Receiving_ID'];
}else{
  $Store_Receiving_ID="";  
}
if(isset($_GET['Store_Receiving_Name'])){
   $Store_Receiving_Name=$_GET['Store_Receiving_Name'];
}else{
   $Store_Receiving_Name="";  
}
if(isset($_GET['from_date'])){
   $from_date=$_GET['from_date'];
}
if(isset($_GET['to_date'])){
   $to_date=$_GET['to_date'];
}

$Date_From=$from_date;
$Date_To=$to_date;

$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        if($from_date==""){
          $Date_From=$new_Date;  
        }
        if($from_date==""){
          $Date_To=$new_Date;  
        }  
    }

$filter="";
if($Store_Receiving_ID!="All"){
   $filter="AND rq.Store_Need='$Store_Receiving_ID'"; 
}
?>



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
<fieldset style='overflow-y: scroll; height: 410px;background: #FFFFFF' id='Previous_Fieldset_List'>
    
	    <center><table style='margin-top:8px' width = 100% border=0>
		<tr id='thead'>
		    
		    <td width=4% style='text-align: center;'><b>Sn</b></td>
		    <td width=5% style='text-align: left;'><b>Issue N<u>o</u></b></td>
		    <td width=5% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
		    <td width=10% style='text-align: left;'><b>Requested Date</b></td>
		    <td width=10% style='text-align: left;'><b>Prepared By</b></td>
		    <td width=14%><b>Issue Date & Time</b></td>
		    <td width=13%><b>Store Receive</b></td>
		    <td width=15%><b>Requisition Description</b></td>
		    <td width=15%><b>Buying Price</b></td>
		    <td width=15%><b>Selling Price</b></td>
		    <td style='text-align: center;' width=30%><b>Action</b></td>
			<tr><td colspan="11"><hr></td></tr>
		</tr>
	 
	<?php 
	    $temp = 1;   
	    //get top 50 issue notes based on selected employee id
	    $Sub_Department_Name = $_SESSION['Storage'];
	    $sql_select = mysqli_query($conn,"SELECT * FROM tbl_issues iss, tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    sd.sub_department_id = rq.Store_Issue AND
                                    emp.Employee_ID = iss.Employee_ID AND
                                    rq.Store_Issue = '$Sub_Department_ID' and
                                    iss.Issue_Date between '$Date_From' and '$Date_To' $filter
                                    ORDER BY iss.Issue_ID DESC
                                    LIMIT 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
           $grand_total_selling_price=0;
           $grand_total_buying_price=0;
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
                    $Issue_ID=$row['Issue_ID'];
                    
                    $sql_select_issunote_price_result=mysqli_query($conn,"SELECT Quantity_Received,Last_Buying_Price,Selling_Price FROM tbl_requisition_items WHERE Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
                    $total_selling_price=0;
                    $total_buying_price=0;
                    if(mysqli_num_rows($sql_select_issunote_price_result)>0){
                        while($price_rows=mysqli_fetch_assoc($sql_select_issunote_price_result)){
                            $Quantity_Received=$price_rows['Quantity_Received'];
                            $Last_Buying_Price=$price_rows['Last_Buying_Price'];
                            $Selling_Price=$price_rows['Selling_Price'];
                            $total_selling_price+=$Quantity_Received*$Selling_Price;
                            $total_buying_price+=$Quantity_Received*$Last_Buying_Price;
                        }
                    }
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_buying_price+=$total_buying_price;
		    //get store need
		    $Store_Need = $row['Store_Need'];
			$Sub_Department_Name = Get_Sub_Department_Name($Store_Need);
                        
		    echo '<tr><td style="text-align: center;">'.$temp.'</td>
			    <td>'.$row['Issue_ID'].'</td>
			    <td>'.$row['Requisition_ID'].'</td>
			    <td>'.$row['Sent_Date_Time'].'</td>
			    <td>'.$row['Employee_Name'].'</td>	
			    <td>'.$row['Issue_Date_And_Time'].'</td>	
			    <td>'.$Sub_Department_Name.'</td> 	
			    <td>'.$row['Requisition_Description'].'</td> 
			    <td>'.number_format($total_buying_price).'</td> 
			    <td>'.number_format($total_selling_price).'</td> 
			    <td style="text-align: center;">';
       
			echo '<a href="previousissuenotereport.php?Issue_ID='.$row['Issue_ID'].'&PreviousIssueNote=PreviousIssueNoteThisPage" target="_blank" class="art-button-green">PREVIEW</a>
			    </td>
			</tr>';
                    $temp++;
		}
	    }
	    echo '</table>';
	?>
</fieldset>
<fieldset>
    <table class="table">
        <tr>
            <td><b>GRAND TOTAL:</b></td>
            <td><b>TOTAL BUYING PRICE:</b>  <?= number_format($grand_total_buying_price)?></td>
            <td><b>TOTAL SELLING PRICE:</b>  <?= number_format($grand_total_selling_price)?></td>
        </tr>
    </table>
</fieldset>
