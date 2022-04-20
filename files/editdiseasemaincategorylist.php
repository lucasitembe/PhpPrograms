<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
	    if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
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
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

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
    if(isset($_POST['submittedAddNewCategoryForm'])){
	$Main_Category_Name = mysqli_real_escape_string($conn,$_POST['Main_Category_Name']);
	$Insert_New_Main_Category = "insert into tbl_disease_maincategory(maincategory_name)
				    Values('$Main_Category_Name')";
				    
	if(!mysqli_query($conn,$Insert_New_Main_Category)){
                    $error = '1062yes';
                    if(mysql_errno()."yes" == $error){
                        ?>
                        <script>
                            alert("\nDISEASE MAIN CATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
                            document.location="./addnewdiseasemaincategory.php?AddNewDiseaseMainCategory=AddNewDiseaseMainCategoryThisPage";
                        </script>
                        <?php
                    }
		}
		else {
		    echo '<script>
			alert("DISEASE MAIN CATEGORY ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>
<br/><br/>
<center>
    <table width=50%>
        <tr>
            <td width=65%>
                <input type='text' style='text-align: center;' name='Main_Category_Name' autocomplete='off' id='Main_Category_Name' required='required' placeholder='Enter Main Category Name' onkeyup="Search_Disease_Main_Category()" oninput="Search_Disease_Main_Category()">
            </td> 
        </tr>
    </table>


    <fieldset style="overflow-y: scroll; height: 390px; background-color:white" id="Disease_Main_Category_List">  
        <legend align=right><b>LIST OF DISEASE MAIN CATEGORIES ~ EDIT DISEASE MAIN CATEGORY</b></legend>
        <center>
            <table width=70%>
                <tr><td colspan="2"><hr></td></tr>
                <tr>
                    <td style='text-align: right;'><b>SN</b></td>
                    <td width=95%><b>&nbsp;&nbsp;&nbsp;DISEASE MAIN CATEGORY NAME</b></td> 
                </tr>
                <tr><td colspan="2"><hr></td></tr>
            <?php
                //get all desease main categories
                $select = mysqli_query($conn,"select * from tbl_disease_maincategory order by maincategory_name limit 200") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                       <tr>
                            <td style='text-align: right;'><?php echo ++$temp; ?>.</td>
                            <td>&nbsp;&nbsp;&nbsp;
                                <a href="editdiseasemaincategory.php?Main_Category_ID=<?php echo $data['main_category_id']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['maincategory_name'])); ?></a>
                            </td>
                        </tr> 
            <?php
                    }
                }
            ?>
            <tr><td colspan="2"><hr></td></tr>
            </table>
        </center>
    </fieldset>
</center>
<br/>


<script>
    function Search_Disease_Main_Category(){
        var Main_Category_Name = document.getElementById("Main_Category_Name").value;

        if(window.XMLHttpRequest){
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Disease_Main_Category_List').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObject.open('GET','Edit_Disease_Main_Category_List.php?Main_Category_Name='+Main_Category_Name,true);
        myObject.send();
    }
</script>
<?php
    include("./includes/footer.php");
?>