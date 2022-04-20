<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $branchID= '';
    $Date_From = '';
    $Date_To = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    } 
?>

<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date_From").datepicker({ 
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
        
        $(function () { 
            $("#date_To").datepicker({ 
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

<script type='text/javascript'>
    /*function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }*/
</script>
<script type="text/javascript">
    function validateForm() {
	//function to validate the create bank form
	var x=document.forms["myForm"]["account_Name"].value;
	if (x==null || x=="") {
	    alert("Please,enter the bank name.");
	    document.getElementById("account_Name").focus();
	    return false;
	}
	
	var x=document.forms["myForm"]["id"].value;
	if (x==null || x=="Select account category") {
	    alert("Please,you must select the bank category");
	    document.getElementById("id").focus();
	    return false;
	}
	var x=document.forms["myForm"]["subCatID"].value;
	if (x==null || x=="Select sub category") {
	    alert("Please,select the account subcategory.");
	    document.getElementById("subCatID").focus();
	    return false;
	}else{
	    return true;
	}
    }//ajax function to display user details when the name is selected
			function showCustomer(str)
				{
				var xmlhttp;    
				if (str=="")
				  {
				  document.getElementById("txtHint").innerHTML="";
				  return;
				  }
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp.onreadystatechange=function()
				  {
				  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
					}
				  }
				xmlhttp.open("GET","getAccountSubcategory.php?id="+str,true);
				xmlhttp.send();
				}
</script>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./financeworks.php?FinanceWork=FinanceWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
<!-- get current date-->
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
	$original_Date = $row['today'];
	$new_Date = date("Y-m-d", strtotime($original_Date));
	$Today = $new_Date; 
    }
?>

<!-- get employee details-->
<?php
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID']; 
    }else{
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
    
    $select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
    while($row = mysqli_fetch_array($select_Employee_Details)){
	$Employee_Name = $row['Employee_Name'];
	$Employee_ID = $row['Employee_ID'];
    }
?>
<br/><br/>
<?php
//if the form ois submitted,start proccessing
if(isset($_POST['submit'])){
    $account_Name=trim(mysqli_real_escape_string($conn,$_POST['account_Name']));
    $account_Category_ID=$_POST['id'];
    $accountSubcategoryID=$_POST['subCatID'];
    
    //run the query to insert the data
    $query="INSERT INTO tbl_accounts SET
            account_Name='$account_Name',
	    account_Category_ID='$account_Category_ID',
	    account_Subcategory_ID='$accountSubcategoryID'";
	//execute the query
	$result=mysqli_query($conn,$query);
	if($result){
	    $message=("<b style='color:blue;font-size:14;font-family:verdana;'>The bank account is successifully created.</b>");
	}else{
	    $message=("<b style='color:red;font-size:14;font-family:verdana;'>Oops,something went wrong.</b>".mysqli_error($conn));
	}
    
}
?>




<center>
    <fieldset>
    <legend>Create bank account</legend>
    <form action="createBank.php" name="myForm" onsubmit="return validateForm()" method="POST">
    <div>
	<?php
		    if(!empty($message)){
			echo $message;
		    }
		?>
    </div >
    <div style="width: 200px;"><b>Enter account name</b>
	<input type="text" name="account_Name" id="account_Name"/>
    </div>
    <br>
    <div style="width: 200px;"><b>Select account category</b>
    <select name="id" id="id" onchange="showCustomer(this.value)">
		    <option selected>Select account category</option>
		    <?php
			//run the query to select data from the database
			$query="SELECT * FROM tbl_account_category";
			//execute the query
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
			    $accountCategoryID=$row['account_Category_ID'];
			    $accountCategoryName=$row['account_Category_Name'];
			    $branchID=$row['Branch_ID'];
			    echo "<option value='$accountCategoryID'>$accountCategoryName</option>";
			}
		    ?>
    </select>
    </div>
    <br>
	<div style="width: 200px"><b>Select sub category</b>
						    <div id="txtHint"></div>
					</div>
    <br>
    <div>
	<input type="submit" name="submit" value="Create account"/>
    </div>
</form>
</fieldset>
</center>

<?php
    include("./includes/footer.php");
?>