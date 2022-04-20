<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./functions/issuenotes.php");
include("./functions/department.php");
include_once("./functions/issuenotemanual.php");

if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
            @session_start();
            if(!isset($_SESSION['Storage_Supervisor'])){
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
    @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<?php
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

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Date_From=$new_Date;
        $Date_To=$new_Date;
    }
?>
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
        ?>
        <a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>
            BACK
        </a>
    <?php  } } ?>


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

<center>
    <fieldset>  
        <table width='100%'> 
            <tr> 
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline'  id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <label>Select Store Received</label>
                    <select id='Store_Receiving_ID'>
                        <?php
                        echo "<option value='All'>All</option>";
                        $Sub_Department_List = Get_Sub_Department_All();
                        foreach($Sub_Department_List as $Sub_Department) {
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                            echo "{$Sub_Department['Sub_Department_Name']}";
                            echo "</option>";
                        }
                        ?> 
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filter_issue_note_by_date()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 260px;background: #FFFFFF' id='Previous_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align=right><b><?php if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){ echo $Sub_Department_Name; }?> ~ Previous Issue Notes</b>  <b style="color:yellow">From <?= $Date_From ?> To <?= $Date_To ?></b></legend>
	    <center><table style='margin-top:8px' width = 100% border=0>
		<tr id='thead'>
		    <tr><td colspan="5"><hr></td></tr>
		    <td width=4% style='text-align: left;'><b>Sn</b></td>
		    <td width=20% style='text-align: left;'><b>Issue Note Type</b></td>
                    <td width=15%><b>Store Received</b></td>
		    <td width=15% style="text-align:right"><b>Buying Price</b></td>
		    <td width=15% style="text-align:right"><b>Selling Price</b></td>
		   
			<tr><td colspan="5"><hr></td></tr>
		</tr>
	 
	<?php 
	    $temp = 1;  
            $grand_total_selling_price=0;
           $grand_total_buying_price=0;
           $manual_total_selling_price=0;
           $manual_total_buying_price=0;
           
	    //get top 50 issue notes based on selected employee id
	    $Sub_Department_Name = $_SESSION['Storage'];
	    $sql_select = mysqli_query($conn,"SELECT * FROM tbl_issues iss, tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    sd.sub_department_id = rq.Store_Issue AND
                                    emp.Employee_ID = iss.Employee_ID AND
                                    rq.Store_Issue = '$Sub_Department_ID' and 
                                     iss.Issue_Date between '$Date_From' and '$Date_To'
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
	    
        $Store_Receiving_ID="All";  
        $Issue_Note_Manual_List = List_Issue_Note_Manual($Current_Store_ID, array("submitted","saved","edited","Received"), $Date_From, $Date_To, null, $Store_Receiving_ID, 0);
        foreach($Issue_Note_Manual_List as $Issue_Note_Manual) {
            $IssueManualItem_List = Get_Issue_Note_Manual_Items($Issue_Note_Manual['Issue_ID']);
            $manual_Total_buy = 0;$manual_Total_sell = 0;
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
                    <td>All Store</td>
                    <td style="text-align:right"><b><?= number_format($grand_total_buying_price)?></td>
                    <td style="text-align:right"><b><?= number_format($grand_total_selling_price)?></b></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><b style="color: #0079AE" onclick="view_issue_note_manual()">Issue Note Manual</b></td>
                    <td>All Store</td>
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
</fieldset>
<fieldset>
    <center><span style="color: #000000;text-align: center;font-size: 17px" ><i>~~Click Issue Note Manual Or Issue Note Electronic To view Detail Report~~</i></span></center>
</fieldset>
<div id="issue_note_manual_electronic_detail">
    
</div>
<script>
    function Preview_Issue_Note_Report(Issue_ID){
        var winClose=popupwindow('previousissuenotereport.php?Issue_ID='+Issue_ID+'&PreviousIssueNote=PreviousIssueNoteThisPage', 'ISSUE NOTE REPORT', 1200, 500);
    }
    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
    function view_issue_note_electronic(){
       var Store_Receiving_ID=$("#Store_Receiving_ID").val();
       var Store_Receiving_Name1=document.getElementById("Store_Receiving_ID");
       
       var i = Store_Receiving_Name1.selectedIndex;
       var Store_Receiving_Name = Store_Receiving_Name1.options[i].text
      
       var from_date=$("#Date_From").val();  
       var to_date=$("#Date_To").val();
        $.ajax({
            type:'GET',
            url:'issuenote_electronic_report.php',
            data:{Store_Receiving_ID:Store_Receiving_ID,from_date:from_date,to_date:to_date,Store_Receiving_Name:Store_Receiving_Name},
            success:function (data){
                $("#issue_note_manual_electronic_detail").html(data);
                $("#issue_note_manual_electronic_detail").dialog({title: 'ISSUE NOTE ELECTRONIC DETAILS',width: '90%',height: 550,modal: true,});
            }
        }); 
    }
    function view_issue_note_manual(){
       var Store_Receiving_ID=$("#Store_Receiving_ID").val();
       var Store_Receiving_Name1=document.getElementById("Store_Receiving_ID");
       
       var i = Store_Receiving_Name1.selectedIndex;
       var Store_Receiving_Name = Store_Receiving_Name1.options[i].text
      
       var from_date=$("#Date_From").val();  
       var to_date=$("#Date_To").val();
        $.ajax({
            type:'GET',
            url:'issuenote_manual_report.php',
            data:{Store_Receiving_ID:Store_Receiving_ID,from_date:from_date,to_date:to_date,Store_Receiving_Name:Store_Receiving_Name},
            success:function (data){
                $("#issue_note_manual_electronic_detail").html(data);
                $("#issue_note_manual_electronic_detail").dialog({title: 'ISSUE NOTE MANUAL DETAILS',width: '90%',height: 550,modal: true,});
            }
        }); 
    }
    function filter_issue_note_by_date(){
       var Store_Receiving_ID=$("#Store_Receiving_ID").val();
       var Store_Receiving_Name1=document.getElementById("Store_Receiving_ID");
       
       var i = Store_Receiving_Name1.selectedIndex;
       Store_Receiving_Name = Store_Receiving_Name1.options[i].text
      
       var from_date=$("#Date_From").val();  
       var to_date=$("#Date_To").val();
       document.getElementById('Previous_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'GET',
           url:'all_issue_note_report_filter.php',
           data:{Store_Receiving_ID:Store_Receiving_ID,from_date:from_date,to_date:to_date,Store_Receiving_Name:Store_Receiving_Name},
           success:function (data){
               $("#Previous_Fieldset_List").html(data);
           }
       });
    }
</script>
<script type='text/javascript'>
     $(document).ready(function () {
        $('select').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        


        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 1});
        
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 1});
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
 
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>


<?php
    include('./includes/footer.php');
?>