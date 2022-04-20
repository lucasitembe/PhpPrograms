<?php
@session_start();
include("./includes/connection.php");
include("./functions/issuenotes.php");
include("./functions/department.php");
include_once("./functions/issuenotemanual.php");


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
?>
<!--    <script>
        $(function () {
            addDatePicker($("#date"));
            addDatePicker($("#date2"));
        });
    </script>-->

<?php
    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }
?>

    <legend style="background-color:#006400;color:white;padding:5px;" align=right><b><?php if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){ echo $Sub_Department_Name; }?> ~ Previous Issue Notes</b>  <b style="color:yellow">From <?= $Date_From ?> To <?= $Date_To ?></b></legend>
	    <center><table style='margin-top:8px' width = 100% border=0>
		<tr id='thead'>
		    <tr><td colspan="5"><hr></td></tr>
		    <td width=4% style='text-align: left;'><b>Sn</b></td>
		    <td width=20% style='text-align: left;'><b>Issue Note Type</b></td>
                    <td width=15%><b>Store Received></b></td>
		    <td width=15% style="text-align:right"><b>Buying Price</b></td>
		    <td width=15% style="text-align:right"><b>Selling Price</b></td>
		   
			<tr><td colspan="5"><hr></td></tr>
		</tr>
	 
	<?php 
	   $temp = 1;  
           $grand_total_selling_price=0;
           $grand_total_buying_price=0;
           
           
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
		}
	    }
	    
        //$Store_Receiving_ID="All";
        $manual_total_selling_price=0;
        $manual_total_buying_price=0;
        $Issue_Note_Manual_List = List_Issue_Note_Manual($Current_Store_ID, array("submitted","saved","edited","Received"), $Date_From, $Date_To, null, $Store_Receiving_ID, 0);
        foreach($Issue_Note_Manual_List as $Issue_Note_Manual) {
            $IssueManualItem_List = Get_Issue_Note_Manual_Items($Issue_Note_Manual['Issue_ID']);
            $manual_Total_sell = 0;$manual_Total_buy = 0;
            foreach($IssueManualItem_List as $IssueManualItem) {
                $manual_Total_buy += ($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']);
                $manual_Total_sell += ($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued']);
                
            } 
           $manual_total_selling_price+=$manual_Total_sell;
           $manual_total_buying_price+=$manual_Total_buy;
        }
	?>
                <tr>
                    <td>1.</td>
                    <td><b style="color: #0079AE" onclick="view_issue_note_electronic()">Issue Note Electronic</b></td>
                    <td><?= $Store_Receiving_Name ?></td>
                    <td style="text-align:right"><b><?= number_format($grand_total_buying_price)?></td>
                    <td style="text-align:right"><b><?= number_format($grand_total_selling_price)?></b></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><b style="color: #0079AE" onclick="view_issue_note_manual()">Issue Note Manual</b></td>
                    <td><?= $Store_Receiving_Name ?></td>
                    <td style="text-align:right"><b><?= number_format($manual_total_buying_price)?></td>
                    <td style="text-align:right"><b><?= number_format($manual_total_selling_price)?></b></td>
                </tr>
                <tr><td colspan="5"><hr></td></tr>
                <tr>
                    <td colspan="3"><b>GRAND TOTAL:</b></td>
                    <td style="text-align:right"><b><?= number_format($grand_total_buying_price+$manual_total_buying_price)?></b></td>
                    <td style="text-align:right"><b><?= number_format($grand_total_selling_price+$manual_total_selling_price)?></b></td>
                </tr>
        </table>


