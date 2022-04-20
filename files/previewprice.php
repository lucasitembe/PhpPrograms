<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = '';
    }

    if(isset($_GET['Search_Product'])){
        $Search_Product = $_GET['Search_Product'];
    }else{
        $Search_Product = '';
    }

    $data = '';
    $data .='<table align="center" width="100%">
                <tr>
                    <td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td>
                </tr>
                <tr>
                    <td style="text-align:center"><b>ITEMS PRICE LIST</b></td>
                </tr>
            </table>
            <hr style="width:100%"/>';


    //GET CATEGORIES
    if($Search_Product != null && $Search_Product != ''){
        $filter = "i.Product_Name like '%$Search_Product%' and ";
    }else{
        $filter = '';
    }





    $data .=  '<table width=100%>
                    <tr>
                        <td width="3%"><b>SN</b></td>
                        <td width=20%><b>CATEGORY</b></td>
                        <td width="7%"><b>P CODE</b></td>
                        <td><b>PRODUCT NAME</b></td>';

            $select = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name from tbl_sponsor sp, tbl_price_list_selected_sponsors ss where
                                    ss.Sponsor_ID = sp.Sponsor_ID order by sp.Sponsor_ID") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            $Sub_Department = array('','','');
            if($no > 0){
                $Counter = 0;
                while ($dt = mysqli_fetch_array($select)) {
                    $Sub_Department[$Counter] = $dt['Sponsor_ID'];
                    $data .= "<td width='10%' style='text-align: right;'><b>".$dt['Guarantor_Name']."&nbsp;&nbsp;&nbsp;</b></td>";
                    $Counter++;
                }
            }

    
            $nmz = 0;
            $selected_sponsors = mysqli_query($conn,"select sp.Guarantor_Name, sp.Sponsor_ID from tbl_sponsor sp, tbl_price_list_selected_sponsors ss where
                                                ss.Sponsor_ID = sp.Sponsor_ID order by sp.Sponsor_ID") or die(mysqli_error($conn));
            $S_Num  = mysqli_num_rows($selected_sponsors);
            if($Item_Category_ID == 0){
                $items = mysqli_query($conn,"select Product_Name, Product_Code, Item_ID, Item_Category_Name from tbl_items i, tbl_item_category ic, tbl_item_subcategory isu where
                                    i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                                    isu.Item_category_ID = ic.Item_Category_ID and
                                    $filter
                                    i.Can_Be_Sold = 'yes' order by Item_Category_Name") or die(mysqli_error($conn));
            }else{
                $items = mysqli_query($conn,"select Product_Name, Product_Code, Item_ID, Item_Category_Name from tbl_items i, tbl_item_category ic, tbl_item_subcategory isu where
                                    i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                                    isu.Item_category_ID = ic.Item_Category_ID and
                                    ic.Item_Category_ID = '$Item_Category_ID' and
                                    $filter
                                    i.Can_Be_Sold = 'yes' order by Item_Category_Name") or die(mysqli_error($conn));
            }

            $nm = mysqli_num_rows($items);
            if($nm > 0){
                while ($dtz = mysqli_fetch_array($items)) {
                    $Item_ID = $dtz['Item_ID'];
                    if($S_Num > 0){
                        $data .= "<tr>
                                    <td>".++$nmz."</td>
                                    <td>".$dtz['Item_Category_Name']."</td>
                                    <td>".$dtz['Product_Code']."</td>
                                    <td>".$dtz['Product_Name']."</td>";

                        //generate suppotive sql
                        for($i = 0; $i <= $Counter - 1; $i++){
                            $sql = mysqli_query($conn,"select Items_Price from tbl_item_price where Sponsor_ID = '$Sub_Department[$i]' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $noz = mysqli_num_rows($sql);
                            if($noz > 0){
                                while ($td = mysqli_fetch_array($sql)) {
                                    $data .= "<td style='text-align: right;'>".number_format($td['Items_Price'])."&nbsp;&nbsp;&nbsp;</td>";
                                }
                            }else{
                                $data .= "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
                            }
                        }                            
                        $data .= "</tr>";
                    }
                }
            }
    $data .= '</table>';

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    
    $mpdf->ignore_invalid_utf8=true;
    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($data,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>