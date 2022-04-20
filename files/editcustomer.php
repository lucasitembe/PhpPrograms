<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='editcustomerlist.php?EditCustomer=EditCustomerThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDistricts.php?Region_Name='+Region_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>

<br/><br/>
<?php
    if(isset($_POST['submittedUpdateCustomerForm']) && isset($_GET['Registration_ID'])){
	    $Registration_ID = $_GET['Registration_ID'];
	    $Customer_Name = mysqli_real_escape_string($conn,$_POST['Customer_Name']);  
	    //$Postal_Address = mysqli_real_escape_string($conn,$_POST['Postal_Address']);
	    $region = mysqli_real_escape_string($conn,$_POST['region']);
	    $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
	    $Email_Address = mysqli_real_escape_string($conn,$_POST['Email_Address']);


	    $sql = mysqli_query($conn,"UPDATE tbl_patient_registration SET
			Patient_Name = '$Customer_Name',
			region = '$region',
			district= '$District',
			phone_number = '$Phone_Number',Email_Address='$Email_Address'
			WHERE Registration_ID = '$Registration_ID' AND registration_type='customer'
			") or die(mysqli_error($conn));
	    
	    if($sql){
		echo "<script type='text/javascript'>
			alert('CUSTOMER UPDATED SUCCESSFULLY');
			document.location = 'editcustomer.php?Registration_ID=$Registration_ID&EditEmployee=EditEmployeeThisForm'
			</script>"; 
	    }
	    else {
		echo "<script type='text/javascript'>
			alert('THERE WAS A PROBLEM WHILE UPDATING');
			</script>";
	    }
    }
    
    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    	$Customer_qr = "SELECT * FROM tbl_patient_registration WHERE Registration_ID= '$Registration_ID'";
    	$customer_result = mysqli_query($conn,$Customer_qr);
    	$customer_row = mysqli_fetch_assoc($customer_result);
    	$Customer_Name = $customer_row['Patient_Name'];
    	$Email_Address = $customer_row['Email_Address'];
    	$Region = $customer_row['Region'];
    	$District = $customer_row['District'];
    	$Phone_Number = $customer_row['Phone_Number'];
    }else{
    	$Customer_Name ='';
    	$Email_Address ='';
    	$Region ='';
    	$District ='';
    	$Phone_Number ='';
    }
?>
<br><br><br><br><br>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>EDIT CUSTOMER</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                        <tr>
                            <td width=40% style='text-align: right;'><b>Customer Name</b></td>
                            <td width=60%><input type='text' name='Customer_Name' required='required' size=70 id='Customer_Name' placeholder='Enter Customer Name' autocomplete='off' value="<?php echo $Customer_Name; ?>" required></td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Phone Number</b></td>
                            <td>
                                <input type='text' name='Phone_Number' id='Phone_Number' autocomplete='off' placeholder='Enter Phone Number' value="<?php echo $Phone_Number; ?>"required>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Email Address</b></td>
                            <td>
                                <input type='email' name='Email_Address' id='Email_Address' autocomplete='off' placeholder='Enter Email Address' value="<?php echo $Email_Address; ?>">
                            </td>
                        </tr>
                        <!--tr>
                            <td width=40% style='text-align: right;'><b>Postal Address</b></td>
                            <td width=60%><textarea name='Postal_Address' id='Postal_Address' cols=20 rows=1 placeholder='Enter Postal Address' autocomplete='off'></textarea></td>
                        </tr-->
                        <tr>
                            <td style='text-align: right;'><b>Region</b></td>
                            <td>
                                <select name='region' id='region' onchange='getDistricts(this.value)'>
                                    <?php
                                    if($Region !==''){
                                        echo "<option selected='selected' value='".$Region."'>".$Region."</option>";
                                    }else{
                                        echo "<option selected='selected' value='Dar es salaam'>Dar es salaam</option>";
                                    }
                                    $data = mysqli_query($conn,"select * from tbl_regions");
                                    while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                        <option value='<?php echo $row['Region_Name']; ?>'>
                                            <?php echo $row['Region_Name']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'>
                                <b>District</b>
                            </td>
                            <td>
                                <select name='District' id='District'>
                                    <?php if($District !==''){
                                    echo "<option selected='selected'>".$District."</option>";
                                    }else{
                                    echo "<option selected='selected'>Kinondoni</option>";
                                    }
                                    ?>
                                    <option>Ilala</option>
                                    <option>Temeke</option>  
                                </select>
                            </td>
                        </tr>
                        <tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedUpdateCustomerForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>
<br><br><br><br><br>
<?php
    include("./includes/footer.php");
?>