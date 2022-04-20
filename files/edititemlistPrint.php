<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Include_Non_Solid_Items']) && $_SESSION['Include_Non_Solid_Items'] == 'yes'){
      $filter = "";
    }else{
      $filter = " and i.Can_Be_Sold = 'yes'";
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

    $age = $Today - $original_Date;
}
?>
<link rel="stylesheet" type="text/css" href="bootstrap.css">
    <?php
include("./includes/connection.php");
$temp = 1;
if (isset($_GET['Product_Name'])) {
    $Product_Name = $_GET['Product_Name'];
} else {
    $Product_Name = '';
}

$data = '';
$postedData = '';
$separator = '';
$isGeneral = 0;
$sponsoName='';
?>

<?php

if (isset($_GET['action']) && $_GET['action'] == 'selectItems') {
       $item = $_GET['Item'];
        $sponsor = $_GET['Sponsor'];
        $postedData = "action=" . $_GET['action'] . "&Sponsor=$sponsor&Item=$item";
        if ($item == "All") {
            $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                         where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                             isc.Item_category_ID = ic.Item_category_ID $filter AND i.Status='Available'";
        } else {
            $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                    where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                          isc.Item_category_ID = ic.Item_category_ID AND ic.Item_category_ID='".$item."' $filter AND i.Status='Available'";
        }
        
         $separator = 'TenganishaData&<a href="edititemlistPrint.php?' . $postedData . '" target="_blank" class="art-button-green" >Print Preview</b>';
   
} elseif (isset($_GET['action']) && $_GET['action'] == 'getItems') {
    
    $category_ID = $_GET['Item'];
    $Sponsor_ID = $_GET['Sponsor'];
    $search_word = $_GET['search_word'];

    $postedData = "action=" . $_GET['action'] . "&Sponsor_ID=$Sponsor_ID&category_ID=$category_ID&search_word=$search_word";


    if ($category_ID == "All") {
        $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                         where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                             isc.Item_category_ID = ic.Item_category_ID $filter AND Product_Name LIKE '%$search_word%' AND i.Status='Available'";
    } else {
        $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                    where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                          isc.Item_category_ID = ic.Item_category_ID AND ic.Item_category_ID='" . $category_ID . "' $filter AND i.Status='Available' AND Product_Name LIKE '%$search_word%'";
    }
     
    $separator = 'TenganishaData&<a href="edititemlistPrint.php?' . $postedData . '" target="_blank" class="art-button-green" >Print Preview</b>';
} else {
    $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
					where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
					    isc.Item_category_ID = ic.Item_category_ID $filter AND i.Status='Available'";

    $isGeneral = 1;
  
    $separator = 'TenganishaData&<a href="edititemlistPrint.php" target="_blank" class="art-button-green" >Print Preview</b>';

}

if ((isset($_GET['Item']) == 'All' && $_GET['Item'] == 'All') || (isset($_GET['category_ID']) == 'All' && $_GET['category_ID'] == 'All') || $isGeneral == 1) {
    $catName = mysqli_query($conn,"SELECT Item_category_ID,Item_Category_Name FROM tbl_item_category") or die(mysqli_error($conn));
    
    if(isset($_GET['Sponsor']) == 'All' && $_GET['Sponsor'] == 'All'){
        $sponsoName='GENERAL';
    }else{
        $sponsoName= strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='".$_GET['Sponsor']."'"))['Guarantor_Name']);
   }
   
    if($isGeneral == 1){
        $sponsoName='GENERAL';
    }
      

    //die( $_GET['Sponsor']);
    
     $data.='<table align="center" width="100%">
                 <tr>
                 <td style="text-align:center">
                    <img src="./branchBanner/branchBanner.png">
                 </td>
                 </tr>
                 <tr>';
    if(isset($_SESSION['Include_Non_Solid_Items']) && $_SESSION['Include_Non_Solid_Items'] == 'yes'){
        $data .= '<td style="text-align:center"><b>ITEMS PRICE LIST (<i>Includes non solid items</i>)</b></td>';
    }else{
        $data .= '<td style="text-align:center"><b>ITEMS PRICE LIST</b></td>';
    }                         

     $data.= '  </tr>
                 <tr>
                 <td style="text-align:center"><b>Category Name</b>:&nbsp;All</span></td>
                 </tr>
                 <tr>
                 <td style="text-align:center"><b>Sponsor</b>:&nbsp;'.$sponsoName.'</td>
                 </tr>
               </table>
               <hr style="width:100%"/>';

    while ($row1 = mysqli_fetch_assoc($catName)) {
       //$data.='<br/><span style="text-align:left;font-size:20px">' . ucfirst(strtolower($row1['Item_Category_Name'])). '</span>';

    $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                         where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                             isc.Item_category_ID = ic.Item_category_ID $filter AND ic.Item_category_ID='" . $row1['Item_category_ID'] . "' AND i.Status='Available'";

        $data .= "<br/><center><table width ='100%' id='show_Sponsor'>
         <thead>
              <tr><td colspan='6'><span style='text-align:left;font-size:20px;margin-bottom:-100px;bottom:0;padding-bottom:-100px'>" . ucfirst(strtolower($row1['Item_Category_Name'])). "</span></td></tr>
              <tr><td colspan='6'><hr style='width:100%'/></td></tr>
              <tr><td style='text-align:center;width: 5%'><b>SN</b></td>
		<td><b>TYPE</b></td>
                <td><b>PRODUCT CODE</b></td>
		    <td style='text-align:right'><b>PRODUCT NAME</b></td>
                <td style='width:16%;text-align:right'><b>FAST TRACK PRICE</b></td>
		        <td style='width:16%;text-align:right'><b>ITEM PRICE</b></td>
				</tr><tr><td colspan='6'><hr style='width:100%'/></td></tr></thead><tbody>";
          
        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
            $data .= "<tr style='border:1px solid #2E2E2E'><td style='text-align: center' id='thead'>" . $temp . "</td>";
            //$data .= "<td>" . $row['Item_Category_Name'] . "</td>";
            $data .= "<td>" . $row['Consultation_Type'] . "</td>";
            $data .= "<td>" . $row['Product_Code'] . "</td>";
            $data .= "<td style='text-align:right'>" . $row['Product_Name'] . "</td>";
            if (isset($_GET['action'])  && $_GET['action'] == 'selectItems') {
                
                    //die('doeneen');
                    // $data .= $_GET['action'];
                    $item = $_GET['Item'];
                    $sponsor = $_GET['Sponsor'];


                    if ($sponsor == 'All') {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
                    } else {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
                    }
            } elseif (isset($_GET['action']) && $_GET['action'] == 'getItems') {
                    $item = $_GET['category_ID'];
                    $sponsor = $_GET['Sponsor_ID'];


                    if ($sponsor == 'All') {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
                    } else {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
                    }
            } else {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
            }

            $results = mysqli_fetch_assoc($getPrice);
             if(empty( $results['Items_Price'])){
                  $results['Items_Price']=0;
             }

            //Get fast track price
            $slct = mysqli_query($conn,"select Item_Price from tbl_Fast_Track_Price where Item_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($slct);
            if($nm > 0){
              $Pr = mysqli_fetch_assoc($slct);
              $Fast_Track_Price = $Pr['Item_Price'];
            }else{
              mysqli_query($conn,"insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('".$row['Item_ID']."','0')") or die(mysqli_error($conn));
              $Fast_Track_Price = 0;
            }
            $data .= "<td style='text-align:right'>" . number_format($Fast_Track_Price) . "</td>";
            $data .= "<td style='text-align:right'>" .number_format($results['Items_Price']) . "</td></tr>";
            //$data .= "<td><button onclick='updatePrice(".$row['Item_ID'].")' id='".$row['Item_ID']."'>Update</button></td>";
            $temp++;
        } 

        $data .= '</tbody></table>
            </center><hr style="width:100%"/>';
    }
} else {
     
            $catid=0;
            
            if(isset($_GET['Item'])) {
                $catid=$_GET['Item'];
                    
            }elseif(isset($_GET['category_ID'])) {
                $catid=$_GET['category_ID'];
                    
            } 
            
             $qr2 = "SELECT Item_Category_Name  FROM tbl_item_category WHERE Item_category_ID='$catid'";
            
             if(isset($_GET['Sponsor']) == 'All' && $_GET['Sponsor'] == 'All'){
                 $sponsoName='GENERAL';
             }else{
                 $sponsoName= strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='".$_GET['Sponsor']."'"))['Guarantor_Name']);
             }
            
        $select_Filtered_Patients2 = mysqli_query($conn,$qr2) or die(mysqli_error($conn));
        $Item_Category_Name= mysqli_fetch_assoc($select_Filtered_Patients2)['Item_Category_Name'];
        
         $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        $data.='<table align="center" width="100%">
                         <tr>
                         <td style="text-align:center">
                            <img src="./branchBanner/branchBanner.png">
                         </td>
                         </tr>
                         <tr>';
        if(isset($_SESSION['Include_Non_Solid_Items']) && $_SESSION['Include_Non_Solid_Items'] == 'yes'){
            $data .= '<td style="text-align:center"><b>ITEMS PRICE LIST (<i>Includes non solid items</i>)</b></td>';
        }else{
            $data .= '<td style="text-align:center"><b>ITEMS PRICE LIST</b></td>';
        }
        $data .= '</tr>
                     <tr>
                     <td style="text-align:center"><b>Category Name</b>:&nbsp;'.$Item_Category_Name.'</span></td>
                     </tr>
                     <tr>
                     <td style="text-align:center"><b>Sponsor</b>:&nbsp;'.$sponsoName.'</td>
                     </tr>
                     
                   </table>
                   <hr style="width:100%"/><br/><br/><br/>';
        
         $data .= "
            
             <center><table width ='100%' id='show_Sponsor'>
         <thead>
               <tr><td colspan='6'><hr style='width:100%'/></td></tr>
               <tr><td style='text-align:center;width: 5%'><b>SN</b></td>
		<td><b>TYPE</b></td>
                <td><b>PRODUCT CODE</b></td>
		    <td style='text-align:right'><b>PRODUCT NAME</b></td>
                <td style='width:10%;text-align:right'><b>FAST TRACK PRICE</b></td>
		        <td style='width:10%;text-align:right'><b>ITEM PRICE</b></td>
                        	</tr>
               <tr><td colspan='6'><hr style='width:100%'/></td></tr>                 
               </thead><tbody>";

        while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
            $data .= "<tr><td style='text-align: center' id='thead'>" . $temp . "</td>";
            //$data .= "<td>" . $row['Item_Category_Name'] . "</td>";
            $data .= "<td>" . $row['Consultation_Type'] . "</td>";
            $data .= "<td>" . $row['Product_Code'] . "</td>";
            $data .= "<td style='text-align:right'>" . $row['Product_Name'] . "</td>";
            if (isset($_GET['action'])  && $_GET['action'] == 'selectItems') {
                   //die('doeneen');
                    // $data .= $_GET['action'];
                    $item = $_GET['Item'];
                    $sponsor = $_GET['Sponsor'];


                    if ($sponsor == 'All') {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
                    } else {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
                    }
                
            } elseif (isset($_GET['action']) && $_GET['action'] == 'getItems') {
                    $item = $_GET['Item'];
                    $sponsor = $_GET['Sponsor'];


                    if ($sponsor == 'All') {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
                    } else {
                        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='".$row['Item_ID'] . "'");
                    }
            } else {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
            }

            $results = mysqli_fetch_assoc($getPrice);
             if(empty( $results['Items_Price'])){
                  $results['Items_Price']=0;
             }

            //Get fast track price
            $slct = mysqli_query($conn,"select Item_Price from tbl_Fast_Track_Price where Item_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($slct);
            if($nm > 0){
              $Pr = mysqli_fetch_assoc($slct);
              $Fast_Track_Price = $Pr['Item_Price'];
            }else{
              mysqli_query($conn,"insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('".$row['Item_ID']."','0')") or die(mysqli_error($conn));
              $Fast_Track_Price = 0;
            }
            $data .= "<td style='text-align:right'>" . number_format($Fast_Track_Price) . "</td>";
            $data .= "<td style='text-align:right'>" . number_format($results['Items_Price']) . "</td></tr>";
            //$data .= "<td><button onclick='updatePrice(".$row['Item_ID'].")' id='".$row['Item_ID']."'>Update</button></td>";
            $temp++;
        } 

        $data .= '</tbody></table>
            </center>';
}
  //echo $data;
include("./MPDF/mpdf.php");

    $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
    
    $mpdf->ignore_invalid_utf8=true;
    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($data,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>