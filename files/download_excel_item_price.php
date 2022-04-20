<?php
/*******EDIT LINES 3-8*******/
//$DB_Server = "localhost"; //MySQL Server    
//$DB_Username = "root"; //MySQL Username     
//$DB_Password = "gpitg2014";             //MySQL Password     
//$DB_DBName = "final_one";         //MySQL Database Name  
//MySQL Table Name   
include("./includes/connection.php");
$DB_TBLName = "tbl_item_price"; 
        //File Name
/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
//create MySQL connection 
if(isset($_POST['dowload_excel_btn'])){
    
    $sponsor_id=$_POST['sponsor_id_txt'];
    $Item_Category_ID_3=$_POST['Item_Category_ID_3'];
     $Consultation_Type3 = $_POST['Consultation_Type3'];
     $filter="";
     if($Consultation_Type3!="All"){
        $filter=" AND Consultation_Type='$Consultation_Type3'";   
     }
    if($Item_Category_ID_3=="All"){
        $sql = "Select Product_Code,i.Item_ID,Product_Name,Unit_Of_Measure,Item_Category_Name,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID INNER JOIN tbl_item_category ic ON tis.Item_Category_ID=ic.Item_Category_ID INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID WHERE i.Status='Available' AND ip.Sponsor_ID='$sponsor_id' $filter";
    }else{
        $sql = "Select Product_Code,i.Item_ID,Product_Name,Unit_Of_Measure,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID INNER JOIN tbl_item_category ic ON tis.Item_Category_ID=ic.Item_Category_ID INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID WHERE ic.Item_Category_ID='$Item_Category_ID_3' AND i.Status='Available' AND ip.Sponsor_ID='$sponsor_id' $filter";
    }
    
$result = @mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error($conn). "<br>" . mysqli_errno($conn));  

$getSponsor2 = "SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsor_id'";
		    $sponsor_result2 = mysqli_query($conn,$getSponsor2) or die(mysqli_error($conn));
                     $row32 = mysqli_fetch_assoc($sponsor_result2);
                      $sponsorname=$row32['Guarantor_Name'];
if($Item_Category_ID_3!="All"||empty($Item_Category_ID_3)){
 $sql_select_category_result=mysqli_query($conn,"SELECT Item_Category_Name FROM tbl_item_category WHERE Item_Category_ID='$Item_Category_ID_3'") or die(mysqli_error($conn)); 
  if(mysqli_num_rows($sql_select_category_result)>0){
      $category_name=mysqli_fetch_assoc($sql_select_category_result)['Item_Category_Name'];
  } 
}else{
    $category_name="All_category";
}


if(empty($sponsor_id)||$sponsor_id=="All")$sponsorname="GENERAL_PRICE";

$filename = $sponsorname."_".$category_name."_PriceList"; 
$filename = preg_replace('/\s+/', '', $filename);

$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields

    for ($i = 0; $i < mysqli_num_fields($result); $i++) {
        // echo mysqli_field_name($result,$i) . "\t";

        while ($property = mysqli_fetch_field($result)) {
            echo $property->name."\t";
        }
    }

    

echo "Price";
print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysqli_fetch_row($result))
    {
       $Item_ID=$row[1];
       $sql_select_item_price="SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID='$sponsor_id' AND Item_ID='$Item_ID'";
       $sql_select_item_price_result=mysqli_query($conn,$sql_select_item_price) or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_item_price_result)>0){
           $price_row=mysqli_fetch_assoc($sql_select_item_price_result);
           $Items_Price=$price_row['Items_Price'];
       }else{
           $Items_Price="0";
       }
       $cc=mysqli_num_rows($sql_select_item_price_result);
        $schema_insert = "";
        for($j=0; $j<mysqli_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert .=$Items_Price.$sep;
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
}
?>
