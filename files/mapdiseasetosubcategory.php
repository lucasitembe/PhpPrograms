<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
    if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
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

<br/>
<br/>  
<table width="100%">
    <tr>
        <td style="text-align: right" width="15%"><b>SOURSE DISEASE CATEGORY</b></td>
        <td style="text-align: left" width="37%">
        <select name="Disease_Category_ID" id="Disease_Category_ID" onchange="Filter_Diseases(); Display_Sub_Categories();">
        <option value="0">All</option>
            <?php
                //select disease categories
                $select = mysqli_query($conn,"select * from tbl_disease_category order by category_discreption") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                        <option value="<?php echo $data['disease_category_ID']; ?>"><?php echo ucwords(strtolower($data['category_discreption'])); ?></option>
            <?php
                    }
                }
            ?>
        </select>
        </td>


        <td style="text-align: right" width="18%"><b>DESTINATION DISEASE CATEGORY</b></td>
        <td style="text-align: left" width="32%" id="Destination_Disease_Category">
        <select name="Disease_Category_ID2" id="Disease_Category_ID2" onchange="Display_Sub_Categories2();">
            <option value="">Select Category</option>
            <?php
                //select disease categories
                $select = mysqli_query($conn,"select * from tbl_disease_category order by category_discreption") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                        <option value="<?php echo $data['disease_category_ID']; ?>"><?php echo ucwords(strtolower($data['category_discreption'])); ?></option>
            <?php
                    }
                }
            ?>
        </select>        
        </td>


    </tr>

    <tr>

        <td style="text-align: right" width="15%"><b>SOURSE DISEASE SUBCATEGORY</b></td>
        <td style="text-align: left" width="35%" id="Sourse_Disease_Subcategory">
        <select name="subcategory_ID" id="subcategory_ID" onchange="Filter_Diseases(); Display_Categories()">
        <option value="">All</option>
        </select>
        </td>


        <td style="text-align: right" width="18%"><b>DESTINATION DISEASE SUBCATEGORY</b></td>
        <td style="text-align: left" width="32%" id="Destination_Disease_Subcategory">
        <select name="subcategory_ID2" id="subcategory_ID2" onchange="">
            <option value="">Select Sub Category</option>
        </select>            
        </td>
    </tr>

</table>
<center>
    <table width="100%">
        <tr>
            <td width="50%">
                <input type="text" style="text-align: center;" autocomplete='off' name="Search_Assigned" id="Search_Assigned" placeholder='Search Disease' oninput="Filter_Assigned_Diseases()">
            </td>
            <td width="50%">
                <input type="text" style="text-align: center;" autocomplete='off' name="Search_Unassigned" id="Search_Unassigned" placeholder='Search Disease'>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <fieldset style='overflow-y: scroll; height: 380px;' id='Assigned_Fieldset'>
                <table width="100%">
                    <tr>
                        <td width="6%"><b>SN</b></td>
                        <td width="9%"><b>CODE</b></td>
                        <td width="77%"><b>DISEASE NAME</b></td>
                        <td width="8%"><b>ACTION</b></td>
                    </tr>
                    <?php
                        $temp = 0;
                        $select = mysqli_query($conn,"select d.disease_code, d.disease_ID, d.disease_name from
                            tbl_disease_category dc, tbl_disease_subcategory ds, tbl_disease d where
                            dc.disease_category_ID = ds.disease_category_ID and
                            d.subcategory_ID = ds.subcategory_ID order by d.disease_name, d.disease_code limit 200") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                                <tr>
                                    <td><?php echo ++$temp; ?></td>
                                    <td><?php echo $data['disease_code']; ?></td>
                                    <td><?php echo ucwords(strtolower($data['disease_name'])); ?></td>
                                    <td>
                                        <input type="button" name="Remove" id="Remove" value=">>" onclick="Remove_Disease(<?php echo $data['disease_ID']; ?>)">
                                    </td>
                                </tr>
                    <?php
                            }
                        }
                    ?>
                    </table>
                </fieldset>
            </td>
            <td width="50%">
                <fieldset style='overflow-y: scroll; height: 380px;' id='Unassigned_Fieldset'>
                </fieldset>
            </td>
        </tr>
    </table>

<div id="Alert_Message" style="width:50%;" >
    <center>
        Please select destination disease category and destination disease subcategory first
    </center>
</div>
<script type="text/javascript">
    function Filter_Diseases(){
        var Disease_Category_ID = document.getElementById("Disease_Category_ID").value;
        document.getElementById("Search_Assigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilter = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }
        myObjectFilter.onreadystatechange = function (){
            data262 = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Assigned_Fieldset').innerHTML = data262;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilter.open('GET','Filter_Disease_From_Category.php?Disease_Category_ID='+Disease_Category_ID,true);
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Filter_Diseases2(){
        var Disease_Category_ID2 = document.getElementById("Disease_Category_ID2").value;
        document.getElementById("Search_Unassigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilter2 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilter2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter2.overrideMimeType('text/xml');
        }
        myObjectFilter2.onreadystatechange = function (){
            data2612 = myObjectFilter2.responseText;
            if (myObjectFilter2.readyState == 4) {
                document.getElementById('Unassigned_Fieldset').innerHTML = data2612;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilter2.open('GET','Filter_Disease_From_Category2.php?Disease_Category_ID='+Disease_Category_ID2,true);
        myObjectFilter2.send();
    }
</script>

<script type="text/javascript">
    function Display_Sub_Categories(){
        var Disease_Category_ID = document.getElementById("Disease_Category_ID").value;
        document.getElementById("Search_Assigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilterSubCategory = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterSubCategory = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterSubCategory.overrideMimeType('text/xml');
        }
        myObjectFilterSubCategory.onreadystatechange = function (){
            data = myObjectFilterSubCategory.responseText;
            if (myObjectFilterSubCategory.readyState == 4) {
                document.getElementById('Sourse_Disease_Subcategory').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterSubCategory.open('GET','Display_Disease_Sub_Category.php?Disease_Category_ID='+Disease_Category_ID,true);
        myObjectFilterSubCategory.send();
    }
</script>


<script type="text/javascript">
    function Display_Sub_Categories2(){
        var Disease_Category_ID2 = document.getElementById("Disease_Category_ID2").value;
        document.getElementById("Search_Assigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilterSubCategory2 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterSubCategory2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterSubCategory2.overrideMimeType('text/xml');
        }
        myObjectFilterSubCategory2.onreadystatechange = function (){
            data122 = myObjectFilterSubCategory2.responseText;
            if (myObjectFilterSubCategory2.readyState == 4) {
                document.getElementById('Destination_Disease_Subcategory').innerHTML = data122;
                document.getElementById('Unassigned_Fieldset').innerHTML = '';
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterSubCategory2.open('GET','Display_Disease_Sub_Category2.php?Disease_Category_ID='+Disease_Category_ID2,true);
        myObjectFilterSubCategory2.send();
    }
</script>

<script type="text/javascript">
    function Filter_Sub_Category_Diseases(){
        var subcategory_ID = document.getElementById("subcategory_ID").value;
        var Disease_Category_ID = document.getElementById("Disease_Category_ID").value;
        var subcategory_ID2 = document.getElementById("subcategory_ID2").value;
        document.getElementById("Search_Assigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilterSuDisease = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterSuDisease = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterSuDisease.overrideMimeType('text/xml');
        }
        myObjectFilterSuDisease.onreadystatechange = function (){
            data262 = myObjectFilterSuDisease.responseText;
            if (myObjectFilterSuDisease.readyState == 4) {
                document.getElementById('Assigned_Fieldset').innerHTML = data262;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterSuDisease.open('GET','Filter_Disease_From_Sub_Category.php?subcategory_ID='+subcategory_ID+'&Disease_Category_ID='+Disease_Category_ID+'&subcategory_ID2='+subcategory_ID2,true);
        myObjectFilterSuDisease.send();
    }
</script>

<script type="text/javascript">
    function Filter_Assigned_Diseases(){
        var Search_Assigned_Value = document.getElementById("Search_Assigned").value;
        var Disease_Category_ID = document.getElementById("Disease_Category_ID").value;
        var subcategory_ID = document.getElementById("subcategory_ID").value;
        var subcategory_ID2 = document.getElementById("subcategory_ID2").value;
        if(window.XMLHttpRequest){
            myObjectFilterAssigned = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterAssigned = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterAssigned.overrideMimeType('text/xml');
        }
        myObjectFilterAssigned.onreadystatechange = function (){
            data262 = myObjectFilterAssigned.responseText;
            if (myObjectFilterAssigned.readyState == 4) {
                document.getElementById('Assigned_Fieldset').innerHTML = data262;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterAssigned.open('GET','Filter_Assigned_Diseases.php?subcategory_ID='+subcategory_ID+'&Disease_Category_ID='+Disease_Category_ID+'&subcategory_ID2='+subcategory_ID2+'&Search_Assigned_Value='+Search_Assigned_Value,true);
        myObjectFilterAssigned.send();
    }
</script>

<script type="text/javascript">
    function Filter_Sub_Category_Diseases2(){
        var subcategory_ID2 = document.getElementById("subcategory_ID2").value;
        var Disease_Category_ID2 = document.getElementById("Disease_Category_ID2").value;
        document.getElementById("Search_Assigned").value = '';
        if(window.XMLHttpRequest){
            myObjectFilterSuDisease2 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterSuDisease2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterSuDisease2.overrideMimeType('text/xml');
        }
        myObjectFilterSuDisease2.onreadystatechange = function (){
            data262 = myObjectFilterSuDisease2.responseText;
            if (myObjectFilterSuDisease2.readyState == 4) {
                document.getElementById('Unassigned_Fieldset').innerHTML = data262;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterSuDisease2.open('GET','Filter_Disease_From_Sub_Category2.php?subcategory_ID='+subcategory_ID2+'&Disease_Category_ID='+Disease_Category_ID2,true);
        myObjectFilterSuDisease2.send();
    }
</script>

<script type="text/javascript">
    function Remove_Disease(disease_ID){
        var Disease_Category_ID = document.getElementById("Disease_Category_ID2").value;
        var subcategory_ID2 = document.getElementById("subcategory_ID2").value;
        if(Disease_Category_ID != '' && Disease_Category_ID != null && subcategory_ID2 != '' && subcategory_ID2 != null){
            if(window.XMLHttpRequest){
                myObjectFilterSuDisease2 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectFilterSuDisease2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilterSuDisease2.overrideMimeType('text/xml');
            }
            myObjectFilterSuDisease2.onreadystatechange = function (){
                data2692 = myObjectFilterSuDisease2.responseText;
                if (myObjectFilterSuDisease2.readyState == 4) {
                    Filter_Sub_Category_Diseases();
                    Filter_Sub_Category_Diseases2();
                }
            }; //specify name of function that will handle server response........
            
            myObjectFilterSuDisease2.open('GET','Update_Disease_Sub_Category.php?subcategory_ID='+subcategory_ID2+'&disease_ID='+disease_ID,true);
            myObjectFilterSuDisease2.send();
        }else{
            $("#Alert_Message").dialog("open");
        }
   }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
   $(document).ready(function(){
      $("#Alert_Message").dialog({ autoOpen: false, width:'45%',height:150, title:'eHMS 2.0 ~ Alert Message',modal: true});      
   });
</script>

<?php
    include("./includes/footer.php");
?>