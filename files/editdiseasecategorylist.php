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
    
    $Current_Username = $_SESSION['userinfo']['Given_Username'];
    
    $sql_check_prevalage="SELECT edit_diseases FROM tbl_privileges WHERE edit_diseases='yes' AND "
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
                <input type='text' style='text-align: center;' name='Disease_Category_Name' autocomplete='off' id='Disease_Category_Name' required='required' placeholder='Enter Category Name' oninput="Search_Disease_Category()" onkeyup="Search_Disease_Category()">
            </td> 
        </tr>
    </table>


            <fieldset style="overflow-y: scroll; height: 390px; background-color:white" id="Disease_Category_Area">  
                <legend align=right><b>LIST OF DISEASE CATEGORIES ~ EDIT DISEASE CATEGORY</b></legend>
                <center>
                    <table width=100%>
                        <tr><td colspan="3"><hr></td></tr>
                        <tr>
                            <td style='text-align: left;'><b>SN</b></td>
                            <td width=40%><b>DISEASE MAIN CATEGORY NAME</b></td> 
                            <td><b>DISEASE CATEGORY NAME</b></td> 
                        </tr>
                        <tr><td colspan="3"><hr></td></tr>
                    <?php
                        //get all desease categories
                        $select = mysqli_query($conn,"select * from tbl_disease_maincategory dm, tbl_disease_category dc where
                                                dm.main_category_id = dc.main_category_id order by maincategory_name limit 200") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                               <tr>
                                    <td><?php echo ++$temp; ?>.</td>
                                    <td>
                                        <a href="editdiseasecategory.php?disease_category_ID=<?php echo $data['disease_category_ID']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['maincategory_name'])); ?></a>
                                    </td>
                                    <td>
                                        <a href="editdiseasecategory.php?disease_category_ID=<?php echo $data['disease_category_ID']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['category_discreption'])); ?></a>
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
    function Search_Disease_Category(){
        var Disease_Category_Name = document.getElementById("Disease_Category_Name").value;

        if(window.XMLHttpRequest){
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Disease_Category_Area').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObject.open('GET','Edit_Disease_Category_List.php?Disease_Category_Name='+Disease_Category_Name,true);
        myObject.send();
    }
</script>

<?php
    include("./includes/footer.php");
?>