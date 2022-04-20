<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
            $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
            $Employee_Name = 'Unknown Consumptioner Officer';
    }


    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
            $Employee_ID = 0;
    }


    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }


    

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
            echo "<a href='Laboratory_Control_Consumption_Sessions.php?New_Consumption=True' class='art-button-green'>NEW CONSUMPTION</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
            echo "<a href='laboratorypendingConsumptions.php?PendingConsumptions=PendingConsumptionsThisPage' class='art-button-green'>PENDING CONSUMPTIONS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
            echo "<a href='laboratorypreviousConsumptions.php?PreviousConsumptions=PreviousConsumptionsThisPage' class='art-button-green'>PREVIOUS CONSUMPTIONS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
            echo "<a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>

<?php
    //get sub department id & name
    if(isset($_SESSION['Laboratory_ID'])){
        $Sub_Department_ID = $_SESSION['Laboratory_ID'];

        //get sub department name
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($data = mysqli_fetch_array($select)){
                $Sub_Department_Name = $data['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
        }
    }
?>


<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
      if(window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
      }
      else if(window.ActiveXObject){
    mm = new ActiveXObject('Micrsoft.XMLHTTP');
    mm.overrideMimeType('text/xml');
      }
      //getPrice();
      var ItemListType = document.getElementById('Type').value;
      getItemListType(ItemListType);
     document.getElementById('BalanceNeeded').value ='';
     document.getElementById('BalanceStoreIssued').value = '' ;
      mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
      mm.open('GET','GetItemList.php?Item_Category_Name='+Item_Category_Name,true);
      mm.send();
  }
    function AJAXP() {
  var data1 = mm.responseText;
  document.getElementById('Item_Name').innerHTML = data1;
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
    var Item_Category_Name = document.getElementById('Item_Category').value;
     if(window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
     }
       else if(window.ActiveXObject){
       mm = new ActiveXObject('Micrsoft.XMLHTTP');
       mm.overrideMimeType('text/xml');
     }

    //   //getPrice();
    document.getElementById('BalanceNeeded').value ='a';
    document.getElementById('BalanceStoreIssued').value = 'v' ;
    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
      mm.open('GET','GetItemListType.php?Item_Category_Name='+Item_Category_Name+'&Type='+Type,true);
      mm.send();
  }
    function AJAXP2() {
  var data2 = mm.responseText;
  document.getElementById('Item_Name').innerHTML = data2;
    }
</script>
<!-- end of filtering-->


<form action='#' method='post' name='myForm' id='myForm' >
<br/>
<fieldset> <legend align='right'><b><?php if(isset($_SESSION['Laboratory_ID'])){ echo strtoupper($Sub_Department_Name); } ?> ~ CONSUMPTION NOTE</b></legend>
        <table width=100%>
        <tr>
                    <td width='12%' style='text-align: right;'>Consumption Number</td>
                    <td width=5%>
                        <?php if(isset($_SESSION['Laboratory_Consumption_ID'])){ ?>
                                <input type='text' name='Consumption_Number' size=6 id='Consumption_Number' readonly='readonly' value='<?php echo $_SESSION['Laboratory_Consumption_ID']; ?>'>
                        <?php }else{ ?>
                                <input type='text' name='Consumption_Number' size=6  id='Consumption_Number' value='New Consumption' readonly="readonly">
                        <?php } ?>
                    </td>
                    <td width='12%' style='text-align: right;'>Consumption Date</td>
                    <td width='16%'>
                            <?php if(isset($_SESSION['Laboratory_Consumption_ID'])){
                                    //get Consumption date
                                    $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
                                    $get_details = mysqli_query($conn,"select Created_Date_Time from tbl_Consumption where Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
                                    $num = mysqli_num_rows($get_details);
                                    if($num > 0){
                                        while($row = mysqli_fetch_array($get_details)){
                                            $Created_Date_Time = $row['Created_Date_Time'];
                                        }
                                    }else{
                                        $Created_Date_Time = '';
                                    }
                            ?>
                                    <input type='text' readonly='readonly' name='Consumption_Date' id='Consumption_Date' value='<?php echo $Created_Date_Time; ?>'>
                            <?php }else{ ?>
                                    <input type='text' readonly='readonly' name='Consumption_Date' id='Consumption_Date'>
                            <?php } ?>
                    </td>
                    <td style='text-align: right;'>Consumptioner Officer</td>
                    <td>
                        <input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
                    </td>
        </tr>
        <tr>
            <td width='10%' style='text-align: right;'>Employee Receives</td>
            <td width='16%'>
                <select name='Employee_Receive' id='Employee_Receive' required='required'>
                <?php
                    if(isset($_SESSION['Laboratory_Consumption_ID'])){
                        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];

                        //get employee need
                        $select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_Consumption c
                                            where emp.Employee_ID = c.Employee_Need and
                                            c.Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));

                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while (($row = mysqli_fetch_array($select))) {
                ?>
                                <option value="<?php echo $row['Employee_ID']; ?>"><?php echo $row['Employee_Name']; ?></option>
                <?php
                            }
                        }
                    }else{
                ?>
                <option value="" selected="selected">Select Employee</option>
                <?php
                    //get list of employee who assigned to this department only
                    $select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_employee_sub_department sd where
                                            sd.Employee_ID = emp.Employee_ID and
                                            sd.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                <?php
                        }
                    }
                }
                ?>
                </select>
            </td>
            <td width='13%' style='text-align: right;'>
                        Consumption Description
            </td>
            <td colspan="3">
                <?php if(isset($_SESSION['Laboratory_Consumption_ID'])){
                    //get Consumption description
                    $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
                    $get_details = mysqli_query($conn,"select Consumption_Description from tbl_Consumption where Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($get_details);
                    if($num > 0){
                        while($row = mysqli_fetch_array($get_details)){
                            $Consumption_Description = $row['Consumption_Description'];
                        }
                    }else{
                        $Consumption_Description = '';
                    }
                ?>
                <input type='text' name='Consumption_Description' id='Consumption_Description' value='<?php echo $Consumption_Description; ?>' onclick='updateConsumptionDesc()' onkeyup='updateConsumptionDesc()'>
                <?php }else{ ?>
                        <input type='text' name='Consumption_Description' id='Consumption_Description'>
                <?php } ?>
            </td>
        </tr>
        </table> 
</center>
</fieldset>

<script>
    function getItemsList(Item_Category_ID){
        document.getElementById("Search_Value").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //alert(data);

        myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
        myObject.open('GET','Get_List_Of_Requisition_Items_List.php?Item_Category_ID='+Item_Category_ID,true);
        myObject.send();
    }

    function getItemsListFiltered(Item_Name){
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
        myObject.open('GET','Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
        myObject.send();
    }
</script>

<script>
    function Get_Selected_Item_Warning() {
        var Item_Name = document.getElementById("Item_Name").value;
        if (Item_Name != '' && Item_Name != null) {
            alert("Process Fail!!\n"+Item_Name+" already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
        }else{
            alert("Process Fail!!\nSelected item already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
        }
    }

</script>

<script>
    function Get_Item_Name(Item_Name,Item_ID){
        var Temp = '';
        if(window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        myObjectGetItemName.onreadystatechange = function (){
            data22 = myObjectGetItemName.responseText;
            if (myObjectGetItemName.readyState == 4) {
                Temp = data22;
                if (Temp == 'Yes'){
                document.getElementById("Item_Name").style.backgroundColor = '#037CB0';

                document.getElementById("Quantity").style.backgroundColor = '#037CB0';
                document.getElementById("Quantity").value = '';
                document.getElementById("Quantity_Label").style.color = '#037CB0';
                document.getElementById("Quantity_Label").innerHTML = '<b>Quantity</b>';
                document.getElementById("Quantity").setAttribute("ReadOnly","ReadOnly");

                document.getElementById("Balance").style.backgroundColor = '#037CB0';
                document.getElementById("Balance").style.backgroundColor = '#037CB0';
                document.getElementById("Balance_Label").style.color = '#037CB0';
                document.getElementById("Balance_Label").innerHTML = '<b>Balance</b>';

                document.getElementById("Item_Remark").style.backgroundColor = '#037CB0';
                document.getElementById("Item_Remark").value = '';
                document.getElementById("Remark_Label").style.color = '#037CB0';
                document.getElementById("Remark_Label").innerHTML = '<b>Item Remark</b>';
                document.getElementById("Item_Remark").setAttribute("ReadOnly","ReadOnly");

                document.getElementById("Item_Name_Label").style.color = '#037CB0';
                document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added. Change the quantity / remark when needed</b>';

                //change add button to warning add button
                document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item_Warning()'>";
                }else{
                document.getElementById("Item_Name").style.backgroundColor = 'white';
                document.getElementById("Item_Name_Label").style.color = 'black';
                document.getElementById("Item_Name_Label").innerHTML = 'Item Name';

                document.getElementById("Quantity").style.backgroundColor = 'white';
                document.getElementById("Quantity").value = '';
                document.getElementById("Quantity").focus();
                document.getElementById("Quantity").removeAttribute("ReadOnly");
                document.getElementById("Quantity_Label").innerHTML = 'Quantity';
                document.getElementById("Quantity_Label").style.color = 'black';

                document.getElementById("Balance").style.backgroundColor = 'white';
                document.getElementById("Balance_Label").innerHTML = 'Balance';
                document.getElementById("Balance_Label").style.color = 'black';

                document.getElementById("Item_Remark").style.backgroundColor = 'white';
                document.getElementById("Item_Remark").value = '';
                document.getElementById("Item_Remark").removeAttribute("ReadOnly");
                document.getElementById("Remark_Label").innerHTML = 'Item Remark';
                document.getElementById("Remark_Label").style.color = 'black';

                //change warning add button to add button
                document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>";
                }
            }
        }; //specify name of function that will handle server response........
        myObjectGetItemName.open('GET','Laboratory_Consumption_Check_Item_Selected.php?Item_ID='+Item_ID,true);
        myObjectGetItemName.send();


        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").focus();

        if (Item_ID != null && Item_ID != '') {
            Get_Balance();
        }
    }
</script>


<script>
    function Get_Balance(){
        var Item_ID = document.getElementById("Item_ID").value;

        if(window.XMLHttpRequest) {
            myObjectGetBalance = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetBalance.overrideMimeType('text/xml');
        }

        myObjectGetBalance.onreadystatechange = function (){
            data80 = myObjectGetBalance.responseText;
            if (myObjectGetBalance.readyState == 4) {
                document.getElementById('Balance').value = data80;
            }
        }; //specify name of function that will handle server response........

        myObjectGetBalance.open('GET','Laboratory_Consumption_Get_Item_Expected_Balance.php?Item_ID='+Item_ID+'&ControlValue=Laboratory',true);
        myObjectGetBalance.send();
    }
</script>


<?php
    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }
?>
<script>
    function updateConsumptionNumber(){
        if(window.XMLHttpRequest){
            myObjectUpdateConsumption = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateConsumption = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateConsumption.overrideMimeType('text/xml');
        }
        myObjectUpdateConsumption.onreadystatechange = function (){
            data25 = myObjectUpdateConsumption.responseText;
            if (myObjectUpdateConsumption.readyState == 4) {
                document.getElementById('Consumption_Number').value = data25;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateConsumption.open('GET','Laboratory_Update_Consumption_Number.php',true);
        myObjectUpdateConsumption.send();
    }
</script>

<script>
    function updateConsumptionDesc(){
        var Consumption_Description = document.getElementById("Consumption_Description").value;

        if(window.XMLHttpRequest){
            myObjectUpdateDescription = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateDescription.overrideMimeType('text/xml');
        }
        myObjectUpdateDescription.onreadystatechange = function (){
            data27 = myObjectUpdateDescription.responseText;
            if (myObjectUpdateDescription.readyState == 4) {
                //document.getElementById('Consumption_Description').value = data27;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateDescription.open('GET','Laboratory_Change_Consumption_Description.php?Consumption_Description='+Consumption_Description,true);
        myObjectUpdateDescription.send();
    }
</script>

<script>
    function Update_Item_Remark(Consumption_Item_ID,Item_Remark){
        if(window.XMLHttpRequest){
            myObjectUpdateItemRemark = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateItemRemark = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateItemRemark.overrideMimeType('text/xml');
        }
        myObjectUpdateItemRemark.onreadystatechange = function (){
            data35 = myObjectUpdateItemRemark.responseText;
            if (myObjectUpdateItemRemark.readyState == 4) {
                //alert(Consumption_Item_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateItemRemark.open('GET','Laboratory_Update_Item_Remark.php?Consumption_Item_ID='+Consumption_Item_ID+'&Item_Remark='+Item_Remark,true);
        myObjectUpdateItemRemark.send();
    }
</script>


<script>
    function updateConsumptionCreatedDate(){
        if(window.XMLHttpRequest){
            myObjectUpdateCreatedDate = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateCreatedDate.overrideMimeType('text/xml');
        }
        myObjectUpdateCreatedDate.onreadystatechange = function (){
            data28 = myObjectUpdateCreatedDate.responseText;
            if (myObjectUpdateCreatedDate.readyState == 4) {
                document.getElementById('Consumption_Date').value = data28;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateCreatedDate.open('GET','Laboratory_Update_Consumption_Created_Date.php',true);
        myObjectUpdateCreatedDate.send();
    }
</script>


<script>
    function updateSubmitArea(){
        if(window.XMLHttpRequest){
            myObjectUpdateSubmitArea = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateSubmitArea = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateSubmitArea.overrideMimeType('text/xml');
        }
        myObjectUpdateSubmitArea.onreadystatechange = function (){
            data29 = myObjectUpdateSubmitArea.responseText;
            if (myObjectUpdateSubmitArea.readyState == 4) {
                document.getElementById('Submit_Button_Area').innerHTML = data29;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateSubmitArea.open('GET','Laboratory_Consumption_Update_Submit_Area.php',true);
        myObjectUpdateSubmitArea.send();
    }
</script>

<script>
    function Get_Selected_Item(){
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Item_Remark = document.getElementById("Item_Remark").value;
        var Employee_Receive = document.getElementById("Employee_Receive").value;
        var Consumption_Description = document.getElementById("Consumption_Description").value;

        if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity!= '' && Employee_Receive != '' && Employee_Receive != null) {
            if(window.XMLHttpRequest){
                my_Object_Get_Selected_Item = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                my_Object_Get_Selected_Item.overrideMimeType('text/xml');
            }
            my_Object_Get_Selected_Item.onreadystatechange = function (){
                data = my_Object_Get_Selected_Item.responseText;
                if (my_Object_Get_Selected_Item.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data;
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Item_Remark").value = '';
                    alert("Item Added Successfully");
                    //updateStoreIssueMenu2();
                    updateConsumptionNumber();
                    updateConsumptionDesc();
                    updateConsumptionCreatedDate();
                    updateSubmitArea();
                    updateEmployeeReceives();
                }
            }; //specify name of function that will handle server response........

            my_Object_Get_Selected_Item.open('GET','Laboratory_Consumption_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Employee_Receive='+Employee_Receive+'&Consumption_Description='+Consumption_Description,true);
            my_Object_Get_Selected_Item.send();

        }else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null){
            alertMessage();
        }else{
            if(Employee_Receive=='' || Employee_Receive == null){
                document.getElementById("Employee_Receive").focus();
                document.getElementById("Employee_Receive").style = 'border: 3px solid red';
            }else{
                document.getElementById("Employee_Receive").style = 'border: 3px';
            }
            if(Quantity=='' || Quantity == null){
                document.getElementById("Quantity").focus();
                document.getElementById("Quantity").style = 'border: 3px solid red';
            }else{
                document.getElementById("Quantity").style = 'border: 3px';
            }
        }
    }
</script>
<script>
    function alertMessage(){
        alert("Please Select Item First");
        document.getElementById("Quantity").value = '';
    }
</script>


<script>
    function Confirm_Remove_Item(Item_Name,Consumption_Item_ID){
        var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);

        if (Confirm_Message == true) {
            if(window.XMLHttpRequest) {
                My_Object_Remove_Item = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Remove_Item.overrideMimeType('text/xml');
            }

            My_Object_Remove_Item.onreadystatechange = function (){
                data6 = My_Object_Remove_Item.responseText;
                if (My_Object_Remove_Item.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data6;
                    //update_total(Registration_ID);
                    //update_Billing_Type();
                    //Update_Claim_Form_Number();
                }
            }; //specify name of function that will handle server response........

            My_Object_Remove_Item.open('GET','Laboratory_Consumption_Remove_Item_From_List.php?Consumption_Item_ID='+Consumption_Item_ID,true);
            My_Object_Remove_Item.send();
        }
    }
</script>


<script>
    function updateEmployeeReceives(){
        if(window.XMLHttpRequest) {
            myObjectUpdateEmployeeReceives = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateEmployeeReceives = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateEmployeeReceives.overrideMimeType('text/xml');
        }

        myObjectUpdateEmployeeReceives.onreadystatechange = function (){
            data699 = myObjectUpdateEmployeeReceives.responseText;
            if (myObjectUpdateEmployeeReceives.readyState == 4) {
                document.getElementById('Employee_Receive').innerHTML = data699;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateEmployeeReceives.open('GET','Laboratory_Consumption_Update_Employee_Receives.php',true);
        myObjectUpdateEmployeeReceives.send();
    }

</script>

<fieldset>
        <center>
            <table width=100%>
                <tr>
            <td width=25%>
                <table width=100%>
                    <tr>
                        <td style='text-align: center;'>
                            <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                <option selected='selected'></option>
                                <?php
                                $data = mysqli_query($conn,"
                                    select Item_Category_Name, Item_Category_ID
                                    from tbl_item_category WHERE Category_Type = 'Pharmacy'
                                    ") or die(mysqli_error($conn));
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
                                }
                                ?>
                            </select>
                        </td>
                        </tr>
                    <tr>
                        <td>
                            <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                            <table width=100%>
                                <?php
                                $result = mysqli_query($conn,"SELECT * FROM tbl_items where Item_Type = 'Pharmacy' order by Product_Name");
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>
                                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>

                                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">

                                       <?php
                                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
                                }
                                ?>
                            </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table width=100%>
                    <tr>
                        <td>
                            <table width = 100%>
                                <tr>
                                    <td id='Item_Name_Label'>Item Name</td>
                                    <td id='Quantity_Label'>Quantity</td>
                                    <td id='Balance_Label'>Balance</td>
                                    <td id='Remark_Label'>Remark</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                        <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                    </td>
                                    <td width="10%">
                                        <input type='text' name='Quantity' id='Quantity' autocomplete='off' size=10 placeholder='Quantity' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)' required='required'>
                                    </td>
                                    <td width="10%">
                                        <input type='text' name='Balance' id='Balance' size=10 placeholder='Balance' readonly='readonly'>
                                    </td>
                                    <td width="20%">
                                        <input type='text' name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark'>
                                    </td>
                                    <td style="text-align: center; width: 5%;" id='Add_Button_Area'>
                                        <!--<input type='submit' name='submit' id='submit' value='Add' class='art-button-green'>-->
                                        <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
                                        <input type='hidden' name='Add_Consumption_Form' value='true'/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--<iframe width='100%' src='Consumption_items_Iframe.php?Consumption_ID=<?php echo $Consumption_ID; ?>' width='100%' height=250px></iframe>-->
                            <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset_List'>
                                <?php
                                    echo '<center><table width = 100% border=0>';
                                    echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                            <td>Item Name</td>
                                            <td width=7% style="text-align: center;">Quantity</td>
                                                <td width=25% style="text-align: center;">Remark</td>
                                                    <td style="text-align: center;">Remove</td></tr>';


                                    $select_Transaction_Items = mysqli_query($conn,"select ci.Consumption_Item_ID, itm.Product_Name, ci.Quantity, ci.Item_Remark, ci.Consumption_Item_ID
                                                                                from tbl_Consumption_items ci, tbl_items itm where
                                                                                itm.Item_ID = ci.Item_ID and
                                                                                ci.Consumption_ID ='$Consumption_ID'") or die(mysqli_error($conn));

                                    $Temp=1;
                                    while($row = mysqli_fetch_array($select_Transaction_Items)){
                                        echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                        echo "<td><input type='text' value='".$row['Quantity']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                                        echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Consumption_Item_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Consumption_Item_ID'].",this.value)'></td>";
                                    ?>
                                        <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Consumption_Item_ID']; ?>)'></td>
                                    <?php
                                        echo "</tr>";
                                        $Temp++;
                                    }
                                    echo '</table>';
                                ?>
                            </fieldset>
                        </td>
                    </tr>
                    <script type='text/javascript'>
                        function Submit_Consumption_Function(Consumption_ID){
                            document.location = 'Laboratory_Submit_Consumption.php?Consumption_ID='+Consumption_ID+'?&Laboratory=True';
                        }
                    </script>
                    <script>
                        function Confirm_Submit_Consumption(){
                            var Consumption_ID = <?php echo $Consumption_ID; ?>;
                            //var r = confirm("Are you sure you want to submit this Consumption?\n\nClick OK to proceed");
                            //if(r == true){
                            //check if Consumption contain at least one item then submit
                            if(window.XMLHttpRequest){
                                myObjectCheckItemNumber = new XMLHttpRequest();
                            }else if(window.ActiveXObject){
                                myObjectCheckItemNumber = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectCheckItemNumber.overrideMimeType('text/xml');
                            }

                            myObjectCheckItemNumber.onreadystatechange = function (){
                                data200 = myObjectCheckItemNumber.responseText;
                                if (myObjectCheckItemNumber.readyState == 4) {
                                    var feedback = data200;
                                    if (feedback == 'Yes'){
                                        var r = confirm("Are you sure you want to submit this Consumption?\n\nClick OK to proceed");
                                        if(r == true){
                                            Submit_Consumption_Function(Consumption_ID);
                                        }
                                    }else{
                                        alert("This Consumption May Either Already Submitted or\n Consumption Contains No Items\n");
                                    }
                                }
                            }; //specify name of function that will handle server response........

                            myObjectCheckItemNumber.open('GET','Consumption_Check_Number_Of_Items.php?Laboratory=True',true);
                            myObjectCheckItemNumber.send();
                            //}
                        }
                    </script>
                    <tr>
                        <td id='Submit_Button_Area' style='text-align: right;'>
                            <?php
                                if(isset($_SESSION['Laboratory_Consumption_ID'])){
                                    ?>
                                        <input type='button' class='art-button-green' value='SUBMIT CONSUMPTION' onclick='Confirm_Submit_Consumption()'>
                                    <?php
                                }
                            ?>
                        </td>
                    </tr>
                </table>

            </td>
                </tr>
        </table>
        </center>
</fieldset>


<?php
  include("./includes/footer.php");
?>