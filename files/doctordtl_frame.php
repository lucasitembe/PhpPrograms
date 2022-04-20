<link rel="stylesheet" href="table33.css" media="screen">
<style>
    .itemhover{
        background-color:white;

    }   
    .itemhover:hover{
        cursor:pointer;
        color:#000; 
        background-color:#ccc
    }.itemhoverlabl:hover{
        cursor:pointer;
    }
    .itemhoverlabl{
        width:100%;
        display: block;
    }
</style>
<?php
//  session_start();
include("./includes/connection.php");
$Item_ID = 0;

//$consultation_id = (isset($_GET['consultation_id']) ? $_GET['consultation_id'] : '');

if (isset($_GET['Consultation_Type'])) {
   $Consultation_Type = $_GET['Consultation_Type'];
}

if (isset($Consultation_Type)) {
   
    if ($Consultation_Type == 'Surgery') {
        //$Consultation_Type = 'Theater';
        $Consultation_Type = 'Surgery';
    }
    if ($Consultation_Type == 'Treatment') {
        $Consultation_Type = 'Pharmacy';
    }

    if (strtolower($Consultation_Type) == 'procedure') {
        $Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
	    (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
	    (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
	    (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
	    (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery')
	    OR(d.Department_Location='Procedure'))";

        $Consultation_Condition2 = "((Consultation_Type='Dialysis') OR
	    (Consultation_Type='Physiotherapy') OR (Consultation_Type='Optical')OR
	    (Consultation_Type='Dressing')OR(Consultation_Type='Maternity')OR
	    (Consultation_Type='Cecap')OR(Consultation_Type='Dental')OR(Consultation_Type='Ear') OR
	    (Consultation_Type='Hiv') OR(Consultation_Type='Eye') OR(Consultation_Type='Maternity') OR
	    (Consultation_Type='Rch') OR(Consultation_Type='Theater') OR
	    (Consultation_Type='Family Planning') OR
	    (Consultation_Type='Procedure'))";
    } else {
        $Consultation_Condition = "d.Department_Location = '$Consultation_Type'";
        $Consultation_Condition2 = "Consultation_Type='$Consultation_Type'";
    }
}

?>

<?php
//check if provisional diagnosis
//Selecting Submitted Tests,Procedures, Drugs

$test_name = '';//

if (isset($_GET['test_name'])) {
    $test_name = $_GET['test_name'];
} else {
    $test_name = '';
}

if (isset($_GET['consultation_id'])) {
    $_GET['consultation_ID'] = $_GET['consultation_id'];
}


?>
<table width='100%' id='thead'>
    <tr id='thead'>
        <td style='width: 3%'><b>Click</b></td>
        <td><b>Item</b></td>
    </tr>
    <?php
    $Item_Category_ID = 'All';
    $Item_Subcategory_ID = 'All';
    if (isset($_GET['Item_Category_ID'])) {
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }if (isset($_GET['Item_Category_ID'])) {
        $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
    }

    $i = 1;

    $qr = "SELECT * FROM tbl_items where $Consultation_Condition2 ";
    //die($qr);
    if ($Item_Subcategory_ID == 'All' || $Item_Subcategory_ID == 0) {
        if ($Item_Category_ID == 'All') {
            $category_condition = "";
        } else {
            $category_condition = " AND Item_Subcategory_ID IN (
			    SELECT DISTINCT ics77.Item_Subcategory_ID FROM tbl_item_subcategory ics77,tbl_item_category ic77
			    WHERE ic77.Item_Category_ID = $Item_Category_ID AND ic77.Item_Category_ID = ics77.Item_Category_ID )";
        }
    } else {
        $category_condition = " AND Item_Subcategory_ID = $Item_Subcategory_ID";
    }
    
    $search_name='';
    if(!empty($test_name)){
        $search_name =" AND Product_Name LIKE '%$test_name%' ";
    }
    
    $qr.=$category_condition;
    $qr.=" AND Status='Available' AND Can_Be_Sold='yes' AND Ct_Scan_Item='no' AND Ward_Round_Item='no' $search_name ORDER BY Product_Name   LIMIT 20";
    $result = mysqli_query($conn,$qr);
    if (mysqli_num_rows($result) > 0) {
        while ($row = @mysqli_fetch_assoc($result)) {
           
            ?>

            <tr>
                 <td>
                     <input type='checkbox' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="append_item('<?php echo $row['Item_ID'] ?>');">
                   
                </td>
                <td class="itemhover" >
                    <label id="item_<?php echo $row['Item_ID']?>"class="itemhoverlabl" for="<?php echo $row['Item_ID']; ?>">
                        <?php echo $row['Product_Name']; ?>
                    </label>
                </td>
            </tr>
           <?php
            $i++;
        }
    } else {
        echo '<tr>
										  <td colspan="2" style="text-align:center;width:100%;font-size:20px;color:red;font-weight:bold;">
											  No Item Found
										  </td>
									</tr>';
    }
    ?>
</table>
