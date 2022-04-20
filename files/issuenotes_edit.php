<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
	include("./includes/header.php");
    include("./includes/connection.php");

    include("./functions/issuenotes.php");
    include("./functions/items.php");
        
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}

	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = '';
	}
	
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	//get requisition id
	if(isset($_SESSION['Issue_Note']['Issue_Note_ID'])){
        $Issue_Note_ID = $_SESSION['Issue_Note']['Issue_Note_ID'];
	}else{
        $Issue_Note_ID = 0;
	}
	
	if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
		echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE</a>";
	}

	if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && $_SESSION['userinfo']['Session_Master_Priveleges'] == 'yes'){
		echo "<a href='unapprovedissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>APPROVE ISSUES</a>";
	}

    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='previousissuenoteslist.php?lform=sentData&page=issue_list' class='art-button-green'>PREVIOUS ISSUES</a>";
    }

    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>BACK</a>";
    }

    $Issue_Note_SQL = mysqli_query($conn,"SELECT rq.Requisition_Status,iss.Issue_ID,  iss.Requisition_ID, rq.Created_Date_Time, iss.Issue_Date_And_Time,
                                              storeneed.Sub_Department_Name as Store_Need, store.Sub_Department_Name as Store,
                                              storeneed.Sub_Department_ID as Store_Need_ID, store.Sub_Department_ID as Store_ID,
                                              iss_emp.Employee_Name as Issue_Employee, req_emp.Employee_Name as Request_Employee,
                                              rq.Requisition_Description, iss.Issue_Description, iss.Receiving_Officer
                                       FROM tbl_issues iss, tbl_requisition rq, tbl_sub_department store,
                                            tbl_sub_department storeneed, tbl_employee req_emp, tbl_employee iss_emp
                                       WHERE
                                            iss.Requisition_ID = rq.Requisition_ID AND
                                            store.sub_department_id = rq.Store_Issue AND
                                            storeneed.sub_department_id = rq.Store_Need AND
                                            req_emp.Employee_ID = rq.Employee_ID AND
                                            iss_emp.Employee_ID = iss.Employee_ID AND
                                            iss.Issue_ID = '$Issue_Note_ID'") or die(mysqli_error($conn));
    $Issue_Note_Num = mysqli_num_rows($Issue_Note_SQL);
    if($Issue_Note_Num > 0){
        while($Issue_Note_Row = mysqli_fetch_array($Issue_Note_SQL)){
            $Requisition_ID = $Issue_Note_Row['Requisition_ID'];
            $Issue_ID = $Issue_Note_Row['Issue_ID'];
            $Requisition_Date = $Issue_Note_Row['Created_Date_Time'];
            $Issued_Date = $Issue_Note_Row['Issue_Date_And_Time'];
            $Requesting_Store = $Issue_Note_Row['Store_Need'];
            $Issuing_Store = $Issue_Note_Row['Store'];
            $Requesting_Store_ID = $Issue_Note_Row['Store_Need_ID'];
            $Issuing_Store_ID = $Issue_Note_Row['Store_ID'];
            $Issue_Employee = $Issue_Note_Row['Issue_Employee'];
            $Request_Employee = $Issue_Note_Row['Request_Employee'];
            $Requisition_Description = $Issue_Note_Row['Requisition_Description'];
            $Issue_Description = $Issue_Note_Row['Issue_Description'];
            $Receiving_Officer = $Issue_Note_Row['Receiving_Officer'];
            $Requisition_Status = $Issue_Note_Row['Requisition_Status'];
        }
    }

    if($Issuing_Store_ID != null && isset($_POST['Update_Issue_Note']) && isset($_SESSION['Issue_Note']['Issue_Note_ID'])){

        $Quantity_Issued_Form = $_POST['Quantity_Issued']; //array values
        $Requisition_Item_ID = $_POST['Requisition_Item_ID']; //array values
        $Container_Issued = $_POST['Container_Issued']; //array values
        $Items_Issued = $_POST['Items_Issued']; //array values
        $Items_Issued = $_POST['Items_Issued']; //array values
        $Last_Buying_Price = $_POST['Last_Buying_Price']; //array values

        $Issue_Note_ID = $_SESSION['Issue_Note']['Issue_Note_ID'];
        $Array_Size = count($Requisition_Item_ID);

        $Can_Edit_Issue_Note = Can_Edit_Issue_Note($Issue_Note_ID);
        if (!$Can_Edit_Issue_Note) {
            echo "<script>
                        alert('Sorry This Issue Note Can not be edited!! GRN has already been made!!');
                        document.location = 'previousissuenoteslist.php?PreviousIssueNoteListPage=True';
                    </script>";
        }

        $error_code = 0;
        if ($Issue_Note_ID != '' && $Issue_Note_ID != 0 && $Can_Edit_Issue_Note) {
            Start_Transaction();

            $Document_Date = Get_Time_Now();
            for($i = 0; $i < $Array_Size; $i++){
                if($Issue_ID != 0){
                    $Item_Quantity_Issued = $Quantity_Issued_Form[$i];
                    $Item_Requisition_Item_ID = $Requisition_Item_ID[$i];
                    $Item_Container_Issued = $Container_Issued[$i];
                    $Item_Items_Issued = $Items_Issued[$i];
                    $Last_Buying_Price = $Last_Buying_Price[$i];

                    $Previous_Quantity_Issued = 0;
                    $Previous_Quantity_Issued_SQL = mysqli_query($conn,"SELECT Item_ID, Quantity_Issued
                                                                    FROM tbl_requisition_items where
                                                                    Requisition_Item_ID = '$Item_Requisition_Item_ID' and
                                                                    Issue_ID = '$Issue_Note_ID' and
                                                                    Status = 'active'") or die(mysqli_error($conn));
                    $Previous_Quantity_Issued_No = mysqli_num_rows($Previous_Quantity_Issued_SQL);
                    if ($Previous_Quantity_Issued_No > 0) {
                        while ($Previous_Quantity_Issued_Row = mysqli_fetch_array($Previous_Quantity_Issued_SQL)) {
                            $Previous_Quantity_Issued = $Previous_Quantity_Issued_Row['Quantity_Issued'];
                        }
                    }

                    if ($Item_Quantity_Issued != $Previous_Quantity_Issued) {
                        //update tbl_requisition_items table
                        $update_items = "UPDATE tbl_requisition_items
                                             SET
                                                Quantity_Issued = '$Item_Quantity_Issued',
                                                Container_Issued = '$Item_Container_Issued',
                                                Items_Per_Container = '$Item_Items_Issued',Last_Buying_Price='$Last_Buying_Price'
                                             WHERE Issue_ID = '$Issue_Note_ID'
                                             AND Requisition_Item_ID = '$Item_Requisition_Item_ID'";

                        $result2 = mysqli_query($conn,$update_items);
                        if(!$result2){
                            Rollback_Transaction();
                            echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?Edit_Issue_Note=True&Issue_Note_ID=".$Issue_Note_ID."';
							</script>";
                        }else{
                            $Issue_Note_Item_SQL = mysqli_query($conn,"SELECT Item_ID, Quantity_Issued
                                                                    FROM tbl_requisition_items where
                                                                    Requisition_Item_ID = '$Item_Requisition_Item_ID' and
                                                                    Issue_ID = '$Issue_Note_ID' and
                                                                    Status = 'active'") or die(mysqli_error($conn));
                            $Issue_Note_Item_No = mysqli_num_rows($Issue_Note_Item_SQL);
                            if ($Issue_Note_Item_No > 0) {
                                $Item_ID = 0; $Quantity_Issued = 0;
                                while ($Issue_Note_Item_Row = mysqli_fetch_array($Issue_Note_Item_SQL)) {
                                    $Item_ID = $Issue_Note_Item_Row['Item_ID'];
                                    $Quantity_Issued = $Issue_Note_Item_Row['Quantity_Issued'];
                                }

                                if($Item_ID != 0){

                                    $Item = Get_Item_Balance($Item_ID, $Issuing_Store_ID);
                                    if (!empty($Item)) {
                                        $Store_Balance = $Item['Item_Balance'];
                                    } else {
                                        $Store_Balance = 0;
                                    }

                                    if ($Previous_Quantity_Issued == 0){
                                        /*if ($Previous_Quantity_Issued > $Store_Balance) {
                                            $error_code = 2;
                                            break;
                                        } else {*/
                                        if (!Update_Item_Balance($Item_ID, $Issuing_Store_ID, "Issue Note", $Requesting_Store_ID, null, null, $Issue_Note_ID, $Document_Date, $Previous_Quantity_Issued, false)){
                                            $error_code = 3;Rollback_Transaction();
                                            break;
                                        }
                                        //}
                                    } else {
                                        $Quantity_Issued_Diff = $Previous_Quantity_Issued - $Quantity_Issued;
                                        if ($Quantity_Issued_Diff > 0) {
                                            if (!Update_Item_Balance($Item_ID, $Issuing_Store_ID, "Issue Note", $Requesting_Store_ID, null, null, $Issue_Note_ID, $Document_Date, $Quantity_Issued_Diff, true)){
                                                $error_code = 3;Rollback_Transaction();
                                                break;
                                            }
                                        } else if ($Quantity_Issued_Diff < 0) {
                                            $Quantity_Issued_Diff = $Quantity_Issued - $Previous_Quantity_Issued;
                                            if (!Update_Item_Balance($Item_ID, $Issuing_Store_ID, "Issue Note", $Requesting_Store_ID, null, null, $Issue_Note_ID, $Document_Date, $Quantity_Issued_Diff, false)){
                                                $error_code = 3;Rollback_Transaction();
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    echo "<script>
                            alert('Process Fail! Please Try Again');
                            document.location = 'Control_Issue_Note_Session.php?Edit_Issue_Note=True&Issue_Note_ID=".$Issue_Note_ID."';
                            </script>";
                }
            }

            if ($error_code == 0) {
                Commit_Transaction();
                echo "<script> alert('You have updated issuenote!'); </script>";
            } else {
                Rollback_Transaction();
            }
        }
    }
?>
	
<br/><br/>
<fieldset>
	<legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>Edit Issue note</b></legend>
    <table width="100%">
        <tbody>
            <tr>
                <td style="text-align: right;" width="12%">Requsition Number</td>
                <td width="13%"><input readonly="readonly" value="<?php echo $Requisition_ID; ?>" name="Requisition_Number"
                                       id="Requisition_Number" size="10" type="text"></td>
                <td style="text-align: right;" width="10%">Requisition Date</td>
                <td width="15%"><input readonly="readonly" value="<?php echo $Requisition_Date; ?>" name="Requisition_Date" id="Requisition_Date" type="text"></td>
                <td style="text-align: right;" width="12%">Issue Number</td>
                <td width="13%"><input readonly="readonly" value="<?php echo $Issue_ID; ?>" name="Issue_Number" id="Issue_Number" size="10" type="text"></td>
                <td style="text-align: right;" width="10%">Issue Date</td>
                <td width="15%"><input readonly="readonly" value="<?php echo $Issued_Date; ?>" name="Issue_Date" id="Issue_Date" type="text"></td>
            </tr>
            <tr>
                <td style="text-align: right;" width="12%">Department Requesting</td>
                <td width="13%"><input readonly="readonly" value="<?php echo $Requesting_Store; ?>" name="Requisition_Number" id="Requisition_Number" size="10" type="text"></td>
                <td style="text-align: right;" width="10%">Prepared By</td>
                <td width="15%"><input readonly="readonly" value="<?php echo $Request_Employee; ?>" name="Requisition_Date" id="Requisition_Date" type="text"></td>

                <td style="text-align: right;" width="12%">Department Issuing</td>
                <td width="13%"><input readonly="readonly" value="<?php echo $Issuing_Store; ?>" name="Issue_Number" id="Issue_Number" size="10" type="text"></td>
                <td style="text-align: right;" width="10%">Issued By</td>
                <td width="15%"><input readonly="readonly" name="Issue_By" id="Issue_By" value="<?php echo $Issue_Employee; ?>" type="text"></td>
            </tr>
            <tr>
                <td style="text-align: right;">Requisition Description</td>
                <td colspan="3"><input readonly="readonly" value="<?php echo $Requisition_Description; ?>"
                                       name="Requisition_Description" id="Requisition_Description" type="text"></td>

                <form action="#" method="post"></form>
                <td style="text-align: right;">Issue Description</td>
                <td colspan="3"><input readonly="readonly" name="Issue_Description" value="<?php echo $Issue_Description; ?>"
                                       id="Issue_Description" type="text"></td>
            </tr>
            <tr>
                <td style="text-align: right;">Receiving Officer</td>
                <td colspan="3"><input readonly="readonly" name="Receiving_Officer" value="<?php echo $Receiving_Officer; ?>"
                                       id="Receiving_Officer" type="text"></td>
            </tr>
        </tbody>
    </table>
</fieldset>

<form action='#' method='post'>
    <fieldset>
        <table width="100%">
            <tbody>
                <tr>
                    <td style="text-align: right;"><input name="Update_Issue_Note" id="Update_Issue_Note" value="UPDATE" class="art-button-green" type="submit"></td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    <fieldset style="overflow-y: scroll; height: 300px;" id="Items_Fieldset">
        <center>
            <table width="100%">
                <tbody><tr>
                </tr><tr><td colspan="10"><hr></td></tr>
                <tr><td width="3%">Sn</td>
                    <td width="30%">Item Name</td>
                    <td style="text-align: center;" width="6%"><?php echo $Requesting_Store;?> Balance</td>
                    <td style="text-align: center;" width="6%"><?php echo $Issuing_Store;?> Balance</td>
                    <td style="text-align: right;" width="6%">Quantity Required</td>
                    <td style="text-align: right;" width="6%">Units Issued</td>
                    <td style="text-align: center;" width="6%">Items per unit</td>
                    <td style="text-align: right;" width="6%">Quantity Issued</td>
                    <td style="text-align: right;" width="6%">Buying Price</td>
                </tr><tr><td colspan="10"><hr></td></tr>

                <?php
                    $Issue_Note_Items_SQL = mysqli_query($conn,"SELECT  i.Product_Name, rqi.Quantity_Issued, rqi.Quantity_Required,
                                                                 rqi.Quantity_Issued, rqi.Quantity_Received, i.Item_ID,
                                                                 rqi.Requisition_Item_ID, rqi.Item_Remark, rqi.Container_Issued,
                                                                 rqi.Items_Per_Container,rqi.Last_Buying_Price
                                                         FROM tbl_items i, tbl_requisition_items rqi
                                                         WHERE i.Item_ID = rqi.Item_ID and
                                                               rqi.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
                    $Issue_Note_Items_Num = mysqli_num_rows($Issue_Note_Items_SQL);
                    $temp = 1;
                    if($Issue_Note_Items_Num > 0){
                        while($Issue_Note_Items_Row = mysqli_fetch_array($Issue_Note_Items_SQL)){

                            $Item_ID = $Issue_Note_Items_Row['Item_ID'];

                            //get item store balance
                            $sql_get = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                            Sub_Department_ID = '$Issuing_Store_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $num_of_rows = mysqli_num_rows($sql_get);
                            if($num_of_rows > 0){
                                while($balance = mysqli_fetch_array($sql_get)){
                                    $Store_Balance = $balance['Item_Balance']; //Store balance
                                }
                            }else{
                                $Store_Balance = 0; // Store balance
                            }

                            //get requested balance
                            $sql_get_req = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                            Sub_Department_ID = '$Requesting_Store_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $num_of_rows = mysqli_num_rows($sql_get_req);
                            if($num_of_rows > 0){
                                while($balance = mysqli_fetch_array($sql_get_req)){
                                    $Store_Balance_Req = $balance['Item_Balance']; //Store balance
                                }
                            }else{
                                $Store_Balance_Req = 0; // Store balance
                            }
                            $input_read_only="";
                            if($Requisition_Status=="Received") {
                               $input_read_only="readonly='readonly'"; 
                            }
                ?>
                        <tr>
                            <td>
                                <input type='hidden' name='Array_Size' id='Array_Size' value='<?php echo ($Issue_Note_Items_Num-1); ?>'>
                                <input readonly="readonly" value="<?php echo $temp; ?>" type="text">
                                <input name="Requisition_Item_ID[]" id="Requisition_Item_ID[]"
                                       value="<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>" type="hidden">
                            </td>
                            <td><input name="Item_Name" id="Item_Name" readonly="readonly"
                                       value="<?php echo $Issue_Note_Items_Row['Product_Name']; ?>" type="text"></td>

                            <td><input name="Req_Balance" readonly="readonly"
                                       id="Req_Balance_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       value="<?php echo $Store_Balance_Req; ?>" style="text-align: right;" type="text"></td>

                            <td><input name="Store_Balance" readonly="readonly"
                                       id="Store_Balance_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       value="<?php echo $Store_Balance; ?>" style="text-align: right;" type="text"></td>

                            <td><input name="Quantity_Required" readonly="readonly" id="Quantity_Required_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       value="<?php echo $Issue_Note_Items_Row['Quantity_Required']; ?>" style="text-align: right;" type="text"></td>

                            <td><input name="Container_Issued[]" <?= $input_read_only ?>
                                       id="Container_Issued_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       value="<?php echo $Issue_Note_Items_Row['Container_Issued']; ?>" required="required" autocomplete="off"
                                       style="text-align: center;"
                                       oninput="numberOnly(this);Update_Container(<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>, <?php echo $Issue_Note_Items_Row['Quantity_Issued']; ?>)"
                                       type="text"></td>

                            <td><input name="Items_Issued[]" <?= $input_read_only ?> id="Items_Issued_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       required="required" value="<?php echo $Issue_Note_Items_Row['Items_Per_Container']; ?>" autocomplete="off" style="text-align: center;"
                                       oninput="numberOnly(this);Update_Container(<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>, <?php echo $Issue_Note_Items_Row['Quantity_Issued']; ?>)"
                                       type="text"></td>

                            <td><input name="Quantity_Issued[]" <?= $input_read_only ?> id="Quantity_Issued_<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       autocomplete="off" value="<?php echo $Issue_Note_Items_Row['Quantity_Issued']; ?>" style="text-align: center;"
                                       oninput="numberOnly(this);Update_Quantity(<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>, <?php echo $Issue_Note_Items_Row['Quantity_Issued']; ?>)"
                                       type="text"></td>
                            <td><input name="Last_Buying_Price[]" id="Last_Buying_Price<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>"
                                       autocomplete="off" value="<?php echo $Issue_Note_Items_Row['Last_Buying_Price']; ?>" style="text-align: center;"
                                       onkeyup="numberOnly(this);Update_buying_price(<?php echo $Issue_Note_Items_Row['Requisition_Item_ID']; ?>,<?= $Item_ID ?>)"
                                       type="text"></td>
                        </tr>
                <?php
                            $temp++;
                        }
                    }
                ?>

                </tbody>
            </table>
        </center>
    </fieldset>
</form>
<div id="Insufficient_Balance">
    <center>YOU DO NOT HAVE ENOUGH BALANCE FOR THIS ITEM</center>
</div>

<script>
    function Update_Container(Requisition_Item_ID, Previous_Quantity_Issued){
        Container_Issued = document.getElementById("Container_Issued_"+Requisition_Item_ID).value;
        if (Container_Issued == '') { Container_Issued = 1; document.getElementById("Container_Issued_"+Requisition_Item_ID).value = Container_Issued; }

        Items_Issued = document.getElementById("Items_Issued_"+Requisition_Item_ID).value;
        if (Items_Issued == '') { Items_Issued = 0; document.getElementById("Items_Issued_"+Requisition_Item_ID).value = Items_Issued; }

        Quantity_Issued = Container_Issued * Items_Issued;
        document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Quantity_Issued;

        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Requisition_Item_ID).value);
        if ((Store_Balance + Previous_Quantity_Issued) < Quantity_Issued) {
            $("#Insufficient_Balance").dialog("open");
            document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Store_Balance + Previous_Quantity_Issued;
            document.getElementById("Items_Issued_"+Requisition_Item_ID).value = Store_Balance + Previous_Quantity_Issued;
            document.getElementById("Container_Issued_"+Requisition_Item_ID).value = 1;
        }
    }

    function Update_Quantity(Requisition_Item_I, Previous_Quantity_Issued){
        document.getElementById("Container_Issued_"+Requisition_Item_ID).value = 1;

        Quantity_Issued = document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value;
        document.getElementById("Items_Issued_"+Requisition_Item_ID).value = Quantity_Issued;

        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Requisition_Item_ID).value);
        if ((Store_Balance + Previous_Quantity_Issued) < Quantity_Issued) {
            $("#Insufficient_Balance").dialog("open");
            document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Store_Balance + Previous_Quantity_Issued;
            document.getElementById("Items_Issued_"+Requisition_Item_ID).value = Store_Balance + Previous_Quantity_Issued;
        }
    }

    $(document).ready(function(){
        $("#Insufficient_Balance").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ ERROR!',modal: true});
    });
    function Update_buying_price(Requisition_Item_ID,Item_ID){
        var Last_Buying_Price=$("#Last_Buying_Price"+Requisition_Item_ID).val();
        $.ajax({
            type:'GET',
            url:'update_issue_note_buying_price_new.php',
            data:{Requisition_Item_ID:Requisition_Item_ID,Last_Buying_Price:Last_Buying_Price,Item_ID:Item_ID},
            success:function(data){
                console.log(data+"==>"+Requisition_Item_ID+"==>"+Last_Buying_Price);
            }
        });
    }
</script>

<?php
    include("./includes/footer.php");
?>  