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
            echo "<a href='pharmacyreorderlevelnotification.php?ReorderLevel=ReorderLevelthisPage' class='art-button-green'>BACK</a>";
        }
    }    
?>

<br/><br/>


<fieldset style='overflow-y: scroll; height: 350px;' id='Previous_Fieldset_List'>
    <legend align=right><b> <?php if(isset($_SESSION['Pharmacy'])){ echo $_SESSION['Pharmacy']; }?></b></legend>
    <?php
        if(isset($_SESSION['Pharmacy'])){
            $Sub_Department_Name = $_SESSION['Pharmacy'];
        }
        $sql_select = mysqli_query($conn,"select emp.Employee_Name from tbl_employee emp, tbl_quick_requisition qpo where
                                    emp.Employee_ID = qpo.Employee_ID and
                                        Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
        $no = mysqli_num_rows($sql_select);
        if($no > 0){
            while($row = mysqli_fetch_array($sql_select)){
                $Employee_Name = $row['Employee_Name'];
            }
        }else{
            $Employee_Name = '';
        }
    ?>
        <center>
            <?php
                if($Employee_Name != ''){
            ?>
                <h3>Warning!!</h3>
                <?php echo '<b>'.ucwords(strtolower($Employee_Name)).'</b>'; ?> Also Processing This Quick Purchase Order    
            <?php
                }
            ?>
            
        </center>
    <?php
        
    ?> 
</fieldset>



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
    include('./includes/footer.php');
?>