<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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
    
      $Current_Username = $_SESSION['userinfo']['Given_Username'];
    
    $sql_check_prevalage="SELECT add_diseases FROM tbl_privileges WHERE add_diseases='yes' AND "
            . "Given_Username='$Current_Username'";
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                      
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }  
    
    //get employee id
    if(isset($_SESSION['userinfo'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewdiseasecategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        ADD DISEASE CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        ADD DISEASE SUBCATEGORY
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='editdiseaselist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>
        EDIT DISEASE
    </a>
<?php  } } ?>

<a href="diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>

<script type="text/javascript" language="javascript">
    function getSubcategory(disease_category_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDiseaseSubcategory.php?disease_category_ID='+disease_category_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('subcategory_ID').innerHTML = data;	
    }
</script>

<script type="text/javascript" language="javascript">
	function setDhis2ID(chbox){
		if(chbox.checked == true){
			document.getElementById('disease_Form_Id').removeAttribute('disabled');
			document.getElementById('disease_Form_Id').setAttribute('required','required');
		}else{
			document.getElementById('disease_Form_Id').setAttribute('disabled','disabled');
		}
	}
</script>
<script src="js/functions.js"></script>
<br/><br/>
<center>

<?php
    if(isset($_POST['submittedAddNewItemForm'])){
	$disease_code = mysqli_real_escape_string($conn,$_POST['disease_code']);
	$nhif_code = mysqli_real_escape_string($conn,$_POST['nhif_code']); 
	$disease_name = mysqli_real_escape_string($conn,$_POST['disease_name']); 
	$subcategory_ID = mysqli_real_escape_string($conn,$_POST['subcategory_ID']);
	
	
	$Insert_New_Item = "INSERT INTO tbl_disease(disease_code,disease_name,subcategory_ID,Employee_ID,Date_And_Time)
					VALUES ('$disease_code','$disease_name','$subcategory_ID','$Employee_ID',(select now()))";
	if(!mysqli_query($conn,$Insert_New_Item)){
		die(mysqli_error($conn));
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nDISEASE NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./adddisease.php?AddNewItemCategory=AddNewItemCategoryThisPage";
				    </script>
				    <?php
				}
		}
		else {
		    echo '<script>
			alert("DISEASE ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>
<br/>   
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=60%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>ADD DISEASE</b></legend>
        
            <table width=100%>
                <tr>
                    <td style='text-align: right;'>Disease Code</td>
                    <td>
                        <input type='text' name='disease_code' id='disease_code' placeholder='Enter Disease Code' required='required' autocomplete='off'>
                    </td>
                </tr>
		<tr>
                    <td style='text-align: right;'>NHIF Code</td>
                    <td>
                        <input type='text' name='nhif_code' id='nhif_code' placeholder='Enter NHIF Code' autocomplete='off'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Disease Name</td>
                    <td>
                        <input type='text' name='disease_name' id='disease_name' required='required' placeholder='Enter Disease Name' autocomplete='off'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Disease Category</td>
                    <td> 
			<select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)' required='required'>
			    <option selected='selected' value=''>Select Category</option>
			    <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_disease_category");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['disease_category_ID'].'>'.$row['category_discreption'].'</option>';
                                }
                            ?>   
			</select>
		    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Disease Sub Category</td>
                    <td>
			<select name='subcategory_ID' id='subcategory_ID' required='required'>
			    
			</select>
		    </td>
        </tr>
		<tr>
			<td colspan=2 style='text-align: right;'>
				<input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
				<input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
				<input type='hidden' name='submittedAddNewItemForm' value='true'/> 
			</td>
		</tr>
    </table>
</fieldset>
        </td>
    </tr>
</table>
 </form>
        </center>
<br/>
<?php
    include("./includes/footer.php");
?>