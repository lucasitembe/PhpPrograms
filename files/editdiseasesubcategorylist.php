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
<br/><br/>
<center>
    <table width=50%>
        <tr>
            <td width=65%>
                <input type='text' style='text-align: center;' name='Sub_Category_Name' autocomplete='off' id='Sub_Category_Name' required='required' placeholder='Enter Sub Category Name' oninput="Search_Disease_Sub_Category()" onkeyup="Search_Disease_Sub_Category()">
            </td> 
        </tr>
    </table>
<fieldset style="overflow-y: scroll; height: 390px; background-color:white" id="Disease_Sub_Category_List">  
    <legend align=right><b>LIST OF DISEASE SUB CATEGORIES ~ EDIT DISEASE SUB CATEGORY</b></legend>
    <center>
        <table width=100%>
            <tr><td colspan="3"><hr></td></tr>
            <tr>
                <td style='text-align: left;'><b>SN</b></td>
                <td width=40%><b>DISEASE CATEGORY NAME</b></td> 
                <td><b>DISEASE SUB CATEGORY NAME</b></td> 
            </tr>
            <tr><td colspan="3"><hr></td></tr>
        <?php
            //get all desease categories
            $select = mysqli_query($conn,"select * from tbl_disease_subcategory ds, tbl_disease_category dc where
                                    ds.disease_category_ID = dc.disease_category_ID order by subcategory_description limit 200") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
                while ($data = mysqli_fetch_array($select)) {
        ?>
                   <tr>
                        <td><?php echo ++$temp; ?>.</td>
                        <td>
                            <a href="editdiseasesubcategory.php?subcategory_ID=<?php echo $data['subcategory_ID']; ?>&EditDiseaseSubCategory=EditDiseaseSubCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['category_discreption'])); ?></a>
                        </td>
                        <td>
                            <a href="editdiseasesubcategory.php?subcategory_ID=<?php echo $data['subcategory_ID']; ?>&EditDiseaseSubCategory=EditDiseaseSubCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['subcategory_description'])); ?></a>
                        </td>
                    </tr> 
        <?php
                }
            }
        ?>
        </table>
    </center>
</fieldset>
</center>
<br/>


<script>
    function Search_Disease_Sub_Category(){
        var Sub_Category_Name = document.getElementById("Sub_Category_Name").value;

        if(window.XMLHttpRequest){
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Disease_Sub_Category_List').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObject.open('GET','Edit_Disease_Sub_Category_List.php?Sub_Category_Name='+Sub_Category_Name,true);
        myObject.send();
    }
</script>
<?php
    include("./includes/footer.php");
?>