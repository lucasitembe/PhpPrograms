<?php 
    include("./includes/connection.php");
    session_start();
    if(isset($_POST['opendialogue'])){
        $Registration_ID = $_POST['Registration_ID'];
        $patient_detail = mysqli_query($conn,"SELECT Patient_Name, Registration_ID, Date_of_Birth, Tribe, Region, Email_Address, Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            while($patient_preview_infor_row = mysqli_fetch_assoc($patient_detail)){
                $patient_name = $patient_preview_infor_row['Patient_Name'];
                $patient_number = $patient_preview_infor_row['Registration_ID'];
                $date_of_birth = $patient_preview_infor_row['Date_of_Birth'];
                $tribe = $patient_preview_infor_row['Tribe'];
                $region = $patient_preview_infor_row['Region'];
                $Email_Address = $patient_preview_infor_row['Email_Address'];
                $Phone_Number = $patient_preview_infor_row['Phone_Number'];
            } 
            echo "<table class='table table-hover table-stripped'>
                <tr><td>#</td><td>SAVED BY</td><td>DATE</td><td>ACTION</td></tr>";
            $assement_result = mysqli_query($conn,"SELECT Employee_Name,pa.created_at ,therapist,Registration_ID, Psychatric_assessment_ID FROM tbl_psychatric_assement pa, tbl_employee e WHERE Registration_ID = $Registration_ID AND e.Employee_ID = therapist") or die(mysqli_error($conn));
            $num=1;
            if(mysqli_num_rows($assement_result)>0){
                while($assrow = mysqli_fetch_assoc($assement_result)){
                    $created_at = $assrow['created_at'];
                    $Psychatric_assessment_ID = $assrow['Psychatric_assessment_ID'];
                    $Employee_Name = $assrow['Employee_Name'];
                    $therapist = $assrow['therapist'];
                    $Registration_ID = $assrow['Registration_ID'];
                    echo "<tr><td>$num</td><td>$Employee_Name</td><td>$created_at</td><td>";
                    
                    echo '<a target="_blank" href="psychatric_assement_pdf_preview.php?Registration_ID='.$Registration_ID.'&therapist='.$therapist.'&Psychatric_assessment_ID='.$Psychatric_assessment_ID.'"><input type="button"  class="art-button-green pull-right" value="Preview Assement Informasions In PDF"></a>
                    <a  href="psychatric_form.php?Registration_ID='.$Registration_ID.'&therapist='.$therapist.'&Psychatric_assessment_ID='.$Psychatric_assessment_ID.'"><input type="button"  class="art-button-green pull-left" value="UPDATE NOTES"></a>
                    ';
                   

                   echo "</td></tr>";
                    $num++;
                }
            }else{
                echo "<tr><td colspan='4'>No result found</td></tr>";
            }
            echo "</table>";
    }

    if(isset($_POST['procedure_dialog'])){
        if(isset($_POST['consultation_ID'])){
            $consultation_ID = $_POST['consultation_ID'];
        }else{
            $consultation_ID = '';
        }
    
        if(isset($_POST['Section'])){
            $Section = $_POST['Section'];
        }else{
            $Section = '';
        }
    
        if(isset($_POST['Registration_ID'])){
            $Registration_ID = $_POST['Registration_ID'];
        }else{
            $Registration_ID = '';
        }
        //get sponsor id & sponsor name
        $select = mysqli_query($conn,"SELECT sp.Sponsor_ID, sp.Guarantor_Name, payment_method from tbl_patient_registration pr, tbl_sponsor sp where   pr.Sponsor_ID = sp.Sponsor_ID and   pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                $Sponsor_ID = $data['Sponsor_ID'];
                $Guarantor_Name = $data['Guarantor_Name'];
                $payment_method = $data['payment_method'];
            }
        }else{
            $Sponsor_ID = 'dd';
            $Guarantor_Name = '';
            $payment_method='';
        }
    
    ?>
    
    <table width=100% style='border-style: none;'>
    <input type="text" style="display: none;" value="<?= $Sponsor_ID ?>" id="Sponsor_ID"> 
    <input type="text" style="display: none;" value="<?= $Guarantor_Name ?>" id="Guarantor_Name"> 
        <tr>
            <td width=40%>
                <table width=100% style='border-style: none;'>
                    <tr>
                        <td>
                            <b>Category : </b>
                            <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList_Laboratory(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                <option selected='selected' value="0">All</option>
                                <?php
                                $data = mysqli_query($conn,"select cat.Item_Category_Name, cat.Item_Category_ID
                                                        from tbl_item_category cat, tbl_item_subcategory isub, tbl_items i
                                                        WHERE cat.Item_category_ID = isub.Item_category_ID and
                                                        isub.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                                        i.Consultation_Type = 'Procedure' and
                                                        i.Can_Be_Sold = 'yes'
                                                        group by cat.Item_Category_ID") or die(mysqli_error($conn));
                                while ($row = mysqli_fetch_array($data)) {
                                    echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='ajax_search_procedure(this.value)' placeholder='Enter Item Name'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <fieldset style='overflow-y: scroll; height: 330px;' id='Items_Fieldset'>
                                <table width=100%>
                                    
                                    <?php
                                        $result = mysqli_query($conn,"SELECT Product_Name, Item_ID from tbl_items where Consultation_Type = 'Procedure' and Status = 'Available' and Can_Be_Sold = 'yes' order by Product_Name limit 150");
                                        while ($row = mysqli_fetch_array($result)) {
                                            $Item_ID= $row['Item_ID'];
                                            echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                    ?>
                                            <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name('<?php echo $row['Product_Name']; ?>','<?php echo $row['Item_ID']; ?>'); Get_Item_Price('<?php echo $Item_ID; ?>');">
                                    <?php
                                            echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;' Get_Item_Price($Item_ID)><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                                        }
                                    ?>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table width="100%">
                    <tr>
                        <td style='text-align: right;' width="30%">Item Name</td>
                        <td width="70%">
                            <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
                            <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'>Price</td>
                        <td>
                            <input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'>Quantity</td>
                        <td>
                            <input type='text' name='Quantity' id='Quantity' autocomplete='off' placeholder='Quantity'>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'>Location</td>
                        <td>
                            <select name='Sub_Department_ID' id='Sub_Department_ID'>
                                <?php
                                    $select = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from tbl_department dep, tbl_sub_department sdep    where dep.Department_ID = sdep.Department_ID and    Department_Location = 'Procedure'") or die(mysqli_error($conn));
                                    $num = mysqli_num_rows($select);
                                    if($num > 1){
                                        echo "<option selected='selected'></option>";
                                    }
                                    while($row = mysqli_fetch_array($select)){
                                        echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                                    }
    
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <textarea name='Comment' id='Comment' placeholder='Comment'></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'>Bill Type
                            <select id="Transaction_Type" name="Transaction_Type">
                            <?php
                                if(strtolower($payment_method) == 'cash'){
                                    echo "<option>Cash</option>";
                                }else{
                                    echo "<option selected='selected'>Credit</option>";
                                    echo "<option>Cash</option>";
                                }
                            ?>
                            </select>
                        </td>
                        <td style='text-align: right;'>
                            <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD PROCEDURE' onclick='Get_Selected_Item()'>
                        </td>
                    </tr>
                </table><br/>
                <fieldset style='overflow-y: scroll; height: 180px;' id='Selected_Investigation_Area'>
                <?php
                   
    
                    //display medications ordered
                    $select = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i, tbl_payment_cache pc where  consultation_ID = '$consultation_ID' and Order_Type = 'Therapy' AND  i.Item_ID = ilc.Item_ID and pc.Registration_ID='$Registration_ID'  AND    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and     Check_In_Type = 'Procedure'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    if($no > 0){
                        $temp = 0;
                ?>
                    <table width="100%">
                        <tr>
                            <td width="5%"><b>SN</b></td>
                            <td><b>INVESTIGATION NAME</b></td>
                            <td width="10%"><b>TYPE</b></td>
                            <td width="10%"><b>PRICE</b></td>
                            <td width="10%"><b>QTY</b></td>
                            <td width="4%"></td>
                        </tr>
                <?php
                        while ($row = mysqli_fetch_array($select)) {
                ?>
                        <tr>
                            <td><?php echo ++$temp; ?></td>
                            <td><?php echo $row['Product_Name']; ?></td>
                            <td><?php echo $row['Transaction_Type']; ?></td>
                            <td><?php echo $row['Price']; ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                <?php
                    if(strtolower($row['Status']) != 'active'){
                        echo '<td><input type="button" name="Remove_Investigation" id="Remove_Investigation" value="X" onclick="Remove_Investigation_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                    }else{
                        echo '<td><input type="button" name="Remove_Inv" id="Remove_Inv" value="X" onclick="Remove_Investigation('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                    }
                ?>
                        </tr>
                <?php
                        }
                    }else{
                        echo "<br/><br/><br/><br/><center><h3>NO PROCEDURE ORDERED</h3></center>";
                    }
                ?>
                
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="6">
            <input type="button" value="DONE" class="art-button-green pull-right" onclick="closeDialog()">
            </td>
        </tr>
    </table>
     
        <?php }
        
        if(isset($_POST['search_procedure'])){
            $Product_Name=mysqli_real_escape_string($conn,$_POST['Product_Name']);
            $Registration_ID = $_GET['Registration_ID'];
            $select = mysqli_query($conn,"SELECT sp.Sponsor_ID, sp.Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where   pr.Sponsor_ID = sp.Sponsor_ID and   pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
                while ($data = mysqli_fetch_array($select)) {
                    $Sponsor_ID = $data['Sponsor_ID'];
                    $Guarantor_Name = $data['Guarantor_Name'];
                }
            }else{
                $Sponsor_ID = '';
                $Guarantor_Name = '';
            }
            $sql_search_procedure=mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name LIKE '%$Product_Name%' AND (Consultation_Type='Procedure' OR Consultation_Type='Surgery') LIMIT 50") or die(mysqli_error($conn));
            echo "<tr><th>Item Name</th></tr>";
            if(mysqli_num_rows($sql_search_procedure)>0){ 
                $count_sn=1;
                while($rows=mysqli_fetch_assoc($sql_search_procedure)){
                    $Item_ID=$rows['Item_ID'];
                    $Product_Name=$rows['Product_Name'];
                    echo "<tr class='rows_list' onclick='Get_Item_Price(\"$Item_ID\"); Get_Item_Name(\"$Product_Name\",\"$Item_ID\" )'>
        
                            <td >";
                            ?>
                            <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $Product_Name; ?>' onclick="Get_Item_Name('<?php echo $Product_Name; ?>','<?php echo $row['Item_ID']; ?>'); Get_Item_Price('<?php echo $Item_ID; ?>');">
                            <?php echo $Product_Name; ?>
                        </td>
                    <?php
                           
                            
                       echo " </tr>";  
                        $count_sn++;
                }
            }else{
                echo "<tr style='color:red;'><td colspan='2'>No result found for $Product_Name</td></tr>";
            }
        }
        
        if(isset($_GET['save_procedure'])){
                    //get employee name
            if (isset($_SESSION['userinfo']['Employee_Name'])) {
                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
            } else {
                $Employee_Name = '';
            }

            //get employee id
            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            } else {
                $Employee_ID = '';
            }
            //get branch id
            if (isset($_SESSION['userinfo']['Branch_ID'])) {
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            } else {
                $Branch_ID = '';
            }

            if(isset($_GET['Registration_ID'])){
                $Registration_ID = $_GET['Registration_ID'];
            }else{
                $Registration_ID = '';
            }

            if(isset($_GET['Sub_Department_ID'])){
                $Sub_Department_ID = $_GET['Sub_Department_ID'];
            }else{
                $Sub_Department_ID = null;
            }

            if(isset($_GET['Item_ID'])){
                $Item_ID = $_GET['Item_ID'];
            }else{
                $Item_ID = '';
            }	

            if(isset($_GET['Quantity'])){
                $Quantity = $_GET['Quantity'];
            }else{
                $Quantity = '';
            }	

            if(isset($_GET['Billing_Type'])){
                $Billing_Type = $_GET['Billing_Type'];
            }else{
                $Billing_Type = '';
            }	

            if(isset($_GET['Guarantor_Name'])){
                $Guarantor_Name = $_GET['Guarantor_Name'];
            }else{
                $Guarantor_Name = '';
            }

            if(isset($_GET['Sponsor_ID'])){
                $Sponsor_ID = $_GET['Sponsor_ID'];
            }else{
                $Sponsor_ID = '';
            }	

            if(isset($_GET['Comment'])){
                $Comment = $_GET['Comment'];
            }else{
                $Comment = '';
            }

            if(isset($_GET['Transaction_Type'])){
                $Transaction_Type = $_GET['Transaction_Type'];
            }else{
                $Transaction_Type = '';
            }

            if(isset($_GET['consultation_ID'])){
                $consultation_ID = $_GET['consultation_ID'];
            }else{
                $consultation_ID = 0;
            }

            if(isset($_GET['Price'])){
                $Price = $_GET['Price'];
            }else{
                $Price=0;
            }

            $Price= intval(preg_replace('/[^\d. ]/', '', $Price));
            if(isset($_GET['Check_in_type'])){
                $Check_in_type = $_GET['Check_in_type'];
            }else{
                $Check_in_type ='Others';
            }

            //generate payment_type
            $payment_type = 'post';
            if(strtolower($Guarantor_Name) != 'cash' && $Billing_Type == 'Inpatient Cash'){
                $payment_type = 'pre';
            }

            if($consultation_ID != 0 && $consultation_ID != ''){
                //select Payment_Cache_ID
                $select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'Therapy'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
                        $Payment_Cache_ID = $data['Payment_Cache_ID'];
                    }
                }else{
                   
                    $insert = mysqli_query($conn,"INSERT into tbl_payment_cache(Registration_ID, Employee_ID, consultation_ID,    Payment_Date_And_Time, Sponsor_ID, Sponsor_Name,     Billing_Type, Receipt_Date, Order_Type, branch_id)    values('$Registration_ID','$Employee_ID','$consultation_ID',   (select now()),'$Sponsor_ID','$Guarantor_Name',     '$Billing_Type',(select now()),'Therapy','$Branch_ID')") or die(mysqli_error($conn));
                    if($insert){
                        $select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'Therapy'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                                $Payment_Cache_ID = $data['Payment_Cache_ID'];
                            }
                        }else{
                            $Payment_Cache_ID = 0;
                        }
                    }else{
                        $Payment_Cache_ID = 0;
                    }
                }

                //get price	
                $Item_ID = $_GET['Item_ID'];
                $Billing_Type = $_GET['Billing_Type'];
                $Guarantor_Name = $_GET['Guarantor_Name'];
			
                
                //insert selected item
                if($Payment_Cache_ID != 0){
                    $insert = mysqli_query($conn,"INSERT into tbl_item_list_cache(Check_In_Type, Item_ID, Discount,    Price, Quantity, Patient_Direction,     Consultant, Consultant_ID, Payment_Cache_ID,     Transaction_Date_And_Time, Doctor_Comment, Sub_Department_ID,     Transaction_Type, payment_type)    values('$Check_in_type','$Item_ID','0',    '$Price','$Quantity','Others',     '$Employee_Name','$Employee_ID','$Payment_Cache_ID',    (select now()),'$Comment','$Sub_Department_ID',   '$Transaction_Type','$payment_type')") or die(mysqli_error($conn));
                }
            }

            //display Laboratorys ordered
            $select = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where     i.Item_ID = ilc.Item_ID and     ilc.Payment_Cache_ID = '$Payment_Cache_ID' and     Check_In_Type = 'Procedure'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                $temp = 0;
        ?>
                <table width="100%">
                    <tr>
                        <td width="5%"><b>SN</b></td>
                        <td><b>INVESTIGATION NAME</b></td>
                        <td width="10%"><b>TYPE</b></td>
                        <td width="10%"><b>PRICE</b></td>
                        <td width="10%"><b>QTY</b></td>
                        <td width="4%"></td>
                    </tr>
        <?php
                while ($row = mysqli_fetch_array($select)) {
        ?>
                    <tr>
                        <td><?php echo ++$temp; ?></td>
                        <td><?php echo $row['Product_Name']; ?></td>
                        <td><?php echo $row['Transaction_Type']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
                    <?php
                        if(strtolower($row['Status']) != 'active'){
                            echo '<td><input type="button" name="Remove_Investigation" id="Remove_Investigation" value="X" onclick="Remove_Investigation_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                        }else{
                            echo '<td><input type="button" name="Remove_Inv" id="Remove_Inv" value="X" onclick="Remove_Investigation('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                        }
                    ?>
                    </tr>
                    
        <?php
                }
            }else{
                echo "<br/><br/><br/><br/><center><h3>NO PROCEDURE ORDERED</h3></center>";
            }
            echo ' <tr>
                <td colspan="6">
                <input type="button" value="DONE" class="art-button-green pull-right" onclick="closeDialog()">
                </td>
            </tr>';
        }
        if(isset($_GET['removeorder'])){
            if(isset($_GET['consultation_ID'])){
                $consultation_ID = $_GET['consultation_ID'];
            }else{
                $consultation_ID = '';
            }
            
            if(isset($_GET['Payment_Item_Cache_List_ID'])){
                $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
            }else{
                $Payment_Item_Cache_List_ID = '';
            }
        
            $delete = mysqli_query($conn,"delete from tbl_item_list_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Status = 'active'") or die(mysqli_error($conn));
        
            //display other items
            $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'Therapy'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            
            if($num > 0){
                while ($data = mysqli_fetch_array($select)) {
                    $Payment_Cache_ID = $data['Payment_Cache_ID'];
                }
            }else{
                $Payment_Cache_ID = '';
            }
        
            //display Laboratorys ordered
            $select = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where     i.Item_ID = ilc.Item_ID and     ilc.Payment_Cache_ID = '$Payment_Cache_ID' and     Check_In_Type = 'Procedure'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                $temp = 0;
        ?>
                <table width="100%">
                    <tr>
                        <td width="5%"><b>SN</b></td>
                        <td><b>INVESTIGATION NAME</b></td>
                        <td width="10%"><b>TYPE</b></td>
                        <td width="10%"><b>PRICE</b></td>
                        <td width="10%"><b>QTY</b></td>
                        <td width="4%"></td>
                    </tr>
        <?php
                while ($row = mysqli_fetch_array($select)) {
        ?>
                    <tr>
                        <td><?php echo ++$temp; ?></td>
                        <td><?php echo $row['Product_Name']; ?></td>
                        <td><?php echo $row['Transaction_Type']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
                     <?php
                        if(strtolower($row['Status']) != 'active'){
                            echo '<td><input type="button" name="Remove_Investigation" id="Remove_Investigation" value="X" onclick="Remove_Investigation_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                        }else{
                            echo '<td><input type="button" name="Remove_Inv" id="Remove_Inv" value="X" onclick="Remove_Investigation('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                        }
                    ?>
                    </tr>
                   
        <?php
                }
            }else{
                echo "<br/><br/><br/><br/><center><h3>NO PROCEDURE ORDERED</h3></center>";
            }

            echo ' <tr>
            <td colspan="6">
            <input type="button" value="DONE" class="art-button-green pull-right" onclick="closeDialog()">
            </td>
        </tr>';
        }
        
        
       
        
        if(isset($_POST['display_procedure'])){
            if(isset($_POST['Registration_ID'])){
                $Registration_ID= $_POST['Registration_ID'];
            }else{
                $Registration_ID="";  
            } 
            if(isset($_POST['consultation_ID'])){
                $consultation_ID= $_POST['consultation_ID'];
            }else{
                $consultation_ID="";  
            } 
            $added_procedure="";  
            
            $procedure_result = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i, tbl_payment_cache pc where  consultation_ID = '$consultation_ID' and Order_Type = 'Therapy' AND  i.Item_ID = ilc.Item_ID and pc.Registration_ID='$Registration_ID'  AND    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and     Check_In_Type = 'Procedure'") or die(mysqli_error($conn));
            if(mysqli_num_rows($procedure_result)>0){
            $count_sn=1;
            while($procedure_row=mysqli_fetch_assoc($procedure_result)){
                $Payment_Item_Cache_List_ID=$procedure_row['Payment_Item_Cache_List_ID'];
                $Product_Name=$procedure_row['Product_Name'];
                $added_procedure .= "$Product_Name, ";
            }
            }
            echo $added_procedure; 
        }