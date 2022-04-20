<?php
    session_start();
    include_once("./includes/connection.php");
    include_once("./functions/items.php");
    
    //get all submitted values
    if(isset($_GET['Classification'])){
        $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
    }else{
        $Classification = "all";
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }

    //get sub department id
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    if (strtolower($Classification) == "all") {
        $Item_Balance_List = Get_Stock_Item_By_Classification("all", $Search_Value, 100);
    } else {
        $Item_Balance_List = Get_Stock_Item_By_Classification($Classification, $Search_Value, 0);
    }

    $Title = '<tr><td colspan="2"><hr></td></tr>';
    $Title .= '<tr>';
    $Title .= '<td width="5%"><b>SN</b></td>';
    $Title .= '<td ><b>ITEM NAME</b></td>';
    //$Title .= '<td width="10%" style="text-align: right;"><b>BALANCE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>';
    $Title .= '</tr>';
    $Title .= '<tr><td colspan="2"><hr></td></tr>';

    $temp = 0;
    echo $Title;
    foreach($Item_Balance_List as $Item_Balance) {
?>      
        <tr><td><input type='radio' id='<?php echo $Item_Balance['Item_ID']; ?>' name='Item'
                       onclick='Get_Selected_Details("<?php echo $Item_Balance['Product_Name']; ?>",<?php echo $Item_Balance['Item_ID']; ?>)'></td>
<?php
        echo "<td><label for='".$Item_Balance['Item_ID']."'>".$Item_Balance['Product_Name']."</label></td>";
        //echo "<td style='text-align: right;'><label for='".$Item_Balance['Item_ID']."'>".$Item_Balance['Item_Balance']."&nbsp;&nbsp;&nbsp;&nbsp;</label></td>";
        echo "</tr>";
        $temp++;
        if(($temp%25) == 0){
            echo $Title;
        }
    }
?> 