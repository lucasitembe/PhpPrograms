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
        BACK
    </a>
<?php  } } ?> 


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
    
    //get disease ID
    if(isset($_GET['disease_ID'])){
        $disease_ID = $_GET['disease_ID'];
    }else{
        $disease_ID = 0;
    }
    
    //get all data from the database
    $select = "select * from tbl_disease d,tbl_disease_subcategory ds, tbl_disease_category dc where
                        dc.disease_category_ID = ds.disease_category_ID and
                            d.subcategory_ID = ds.subcategory_ID and
                                disease_id = '$disease_ID'";
    $result = mysqli_query($conn,$select) or die(mysqli_error($conn));
    $no = mysqli_num_rows($result);
    if($no > 0){
        while($row = mysqli_fetch_array($result)){
            $disease_ID = $row['disease_ID'];
            $category_discreption = $row['category_discreption'];
            $subcategory_description  = $row['subcategory_description'];
            $disease_code = $row['disease_code'];
	    $nhif_code = $row['nhif_code'];
            $disease_name = $row['disease_name'];
            $subcategory_ID = $row['subcategory_ID'];
        } 
    }else{
        $disease_ID = '';
	$nhif_code = '';
        $disease_code = '';
        $category_discreption = '';
        $subcategory_description = '';
        $disease_name = '';
        $subcategory_ID = '';
    }    
?>

<?php
    if(isset($_POST['submittedEditDiseaseForm'])){
	$disease_code = mysqli_real_escape_string($conn,$_POST['disease_code']);
	$nhif_code = mysqli_real_escape_string($conn,$_POST['nhif_code']);
	$disease_name = mysqli_real_escape_string($conn,$_POST['disease_name']); 
	$subcategory_ID = mysqli_real_escape_string($conn,$_POST['subcategory_ID']);
	
	$Update_Disease = "UPDATE tbl_disease set disease_code = '$disease_code',nhif_code = '$nhif_code',disease_name = '$disease_name',subcategory_ID = '$subcategory_ID'
					   where disease_ID = '$disease_ID'";
					
	if(!mysqli_query($conn,$Update_Disease)){
		die(mysqli_error($conn));
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				 echo  "<script>
					alert('\nDISEASE NAME ALREADY EXISTS!\nPLEASE USE ANOTHER NAME');
					document.location='./editdisease.php?disease_ID=".$disease_ID."&EditDisease=EditDiseaseThisForm';
				    </script>";
				    
				}
		}
		else {
		    echo '<script>
			alert("DISEASE UPDATED SUCCESSFULLY");
			document.location="./editdiseaselist.php?EditDiseaseList=EditDiseaseListThisPage";
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
            <legend align=center>EDIT DISEASE</legend>
        
            <table width=100%>
                <tr>
                    <td>Disease Code</td>
                    <td>
                        <input type='text' name='disease_code' id='disease_code' placeholder='Enter Disease Code' value='<?php echo $disease_code; ?>' >
                    </td>
                </tr>
		<tr>
                    <td>NHIF Code</td>
                    <td>
                        <input type='text' name='nhif_code' id='nhif_code' placeholder='Enter NHIF Code' value='<?php echo $nhif_code; ?>' >
                    </td>
                </tr>
                <tr>
                    <td>Disease Name</td>
                    <td>
                        <input type='text' name='disease_name' id='disease_name' required='required' placeholder='Enter Disease Name' value='<?php echo $disease_name; ?>'>
                    </td>
                </tr>
                <tr>
                    <td>Disease Category</td>
                    <td> 
					<select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)' required='required'>
						<option selected='selected'><?php echo $category_discreption; ?></option>
						<?php
										$data = mysqli_query($conn,"SELECT * FROM tbl_disease_category where category_discreption <> '$category_discreption'");
										while($row = mysqli_fetch_array($data)){
											echo 'option selected="selected" value='.$row['disease_category_ID'].'>'.$category_discreption.'</option>';
											echo '<option value='.$row['disease_category_ID'].'>'.$row['category_discreption'].'</option>';
										}
									?>   
					</select>&nbsp;&nbsp;&nbsp;Select Category
					</td>
                </tr>
                <tr>
                    <td>Disease Sub Category</td>
                    <td>
					<select name='subcategory_ID' id='subcategory_ID' required='required'>
						<option value='<?php echo $subcategory_ID; ?>' ><?php echo $subcategory_description; ?></option>
					</select>
					</td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                        <a href='editdiseaselist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>CANCEL</a>
                        <input type='hidden' name='submittedEditDiseaseForm' value='true'/> 
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