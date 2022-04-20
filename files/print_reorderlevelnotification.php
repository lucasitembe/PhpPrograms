<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    @session_start();
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
                echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
        }
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

<br/><br/>

<?php
	    $temp = 1;

        $htm =  "
                <center>
                    <table width =100% height = '30px'>
                        <tr>
                            <td>
                                <img src='./branchBanner/branchBanner.png' width=100%>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'><b>LIST OF ITEMS BELOW RE-ORDER LEVEL REPORT</b></td>
                        </tr>
                    </table>
                </center>
        ";
		$htm .= "
        
                <center>
                    <table width=100% border=0>
                        <tr id='' style='background-color: #ccc;'>
                            <td width=5% style='text-align: center;'><b>Sn</b></td>
                            <td width=30% style='text-align: left;'><b>Item Name</b></td>
                            <td width=25%><b>Current Balance</b></td>
                            <td width=20%><b>Re-Order Value</b></td>
                        </tr>
        ";
	    
	    if(isset($_SESSION['Storage'])){
                $Sub_Department_Name = $_SESSION['Storage'];
                $sql_num = mysqli_query($conn,"select i.Product_Name, ib.Item_Balance, ib.Reorder_Level from tbl_items_balance ib, tbl_items i where
                                        i.Item_ID = ib.Item_ID and
                                        ib.Item_Balance < ib.Reorder_Level and
                                                Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
                $num = mysqli_num_rows($sql_num);
                if($num > 0){
                    while($row = mysqli_fetch_array($sql_num)){
        
                        $htm .="<tr>
                                    <td style='text-align: center;'>".$temp."</td>
                                    <td>".$row['Product_Name']."</td>
                                    <td>".$row['Item_Balance']."</td>
                                    <td>".$row['Reorder_Level']."</td>
                                </tr>";
      
                        $temp++;
                    }
                }
	    }
	    $htm  .= "</table>";
	?>


<script>
    function Confirm_Quick_Purchase_Order() {
        var Confirm_Message = confirm("Are you sure you want to create quick purchase order?");
        if (Confirm_Message == true) {
            //document.location = 'Control_Purchase_Order_Sessions.php?Quick_Purchase_Order=True';
	    //Check if someone is processing the same process
	    
	    if(window.XMLHttpRequest) {
		myObjectConfirm = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
		myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectConfirm.overrideMimeType('text/xml');
	    }
	    
	    myObjectConfirm.onreadystatechange = function (){
		data = myObjectConfirm.responseText;
		if (myObjectConfirm.readyState == 4) {
		    var Feedback = data;
		    if (Feedback == 'yes') {
			document.location = 'reorderlevelnotificationwarning.php?QuickPurchaseWarning=True';
		    }else if(Feedback =='yes2'){
			document.location = 'Control_Purchase_Order_Sessions.php?Self_Quick_Purchase_Order=True';
		    }else{
			document.location = 'Control_Purchase_Order_Sessions.php?Quick_Purchase_Order=True';
		    }
		}
	    }; //specify name of function that will handle server response........
	    myObjectConfirm.open('GET','Confirm_Quick_Purchase_Order.php',true);
	    myObjectConfirm.send();
        }
    }
</script>
<?php
    
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.$Employee_Name.'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    // $stylesheet = file_get_contents('mpdfstyletables.css');
    // $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    
    exit; 

    mysqli_close($conn);
?>