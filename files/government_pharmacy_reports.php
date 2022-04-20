<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterSub = ' ';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
//display pharmacy report
if(isset($Filter_Category) && $Filter_Category=="yes"){
        $Start_Date=$fromDate;
    $End_Date=$toDate;
    if(isset($_POST['Sub_Department_ID'])){
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    echo "<table width='100%' style='background-color:white;'>";
    echo "<thead>";
    echo "<tr><th>Description Of Item</th><th>UoM</th><th>Stock Beginning Balance</th><th>Stock Received</th><th>Remaining Balance</th></tr>";
    echo "</thead>";
        $select="SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Pharmacy' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
        $resulsts=mysqli_query($conn,$select) or die(mysqli_error($conn));
        $temp=1;
        while($row=mysqli_fetch_array($resulsts)){
            $subcategory_id=$row['Item_Subcategory_ID'];
            $subcategory_name=$row['Item_Subcategory_Name'];
            
            echo "<tr><td colspan='5' style='background-color:gray;color:white;'>{$subcategory_name}</td></tr>";

            $Total_Final_Inward=0;
            $Total_Final_Outward=0;
            $Grand_Final_Balance=0;

            $item_results=mysqli_query($conn,"SELECT * FROM tbl_items WHERE Item_Subcategory_ID={$subcategory_id}");
            while ($row=mysqli_fetch_assoc($item_results)) {
                $Item_ID=$row['Item_ID'];
                $Product_Name=$row['Product_Name'];

                //get details
                $select_sub_dept=mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_sub_department sdept, tbl_department dept WHERE sdept.Department_ID=dept.Department_ID and dept.Department_Name='Main Store'");
                
                while($cat=mysqli_fetch_assoc($select_sub_dept)){
                $select = mysqli_query($conn,"SELECT * FROM tbl_stock_ledger_controler
                           WHERE Movement_Date BETWEEN '$Start_Date' AND '$End_Date'
                           AND Item_ID = '$Item_ID' AND Sub_Department_ID={$cat['Sub_Department_ID']}
                           ORDER BY Controler_ID") or die(mysqli_error($conn));

                $no = mysqli_num_rows($select);
                $Pre_Balance1=0;

    if($no > 0){
        $controler = 'yes';
        $Total_inward = 0;
        $Total_outward = 0;
        $temp = 0;
        $Running_Balance = 0;

        while ($data = mysqli_fetch_array($select)) {
            $Movement_Type = $data['Movement_Type'];
            $Internal_Destination = $data['Internal_Destination'];
            $External_Source = $data['External_Source'];
            $Pre_Balance = $data['Pre_Balance'];
            $Movement_Date = $data['Movement_Date'];
            $Movement_Date_Time = $data['Movement_Date_Time'];
            $Registration_ID = $data['Registration_ID'];

            if($controler == 'yes'){
                $Opening_Balance=$Pre_Balance;
            }
            if($Movement_Type == 'From External'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
 
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Without Purchase'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
 
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Open Balance'){
                $Total_inward = $data['Post_Balance'];
                $Total_outward = 0;
 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note'){

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Dispensed'){

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']); 
               
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'GRN Agains Issue Note'){
 
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Disposal'){
 
                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Outward'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward Outward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);
                 
                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
              
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note Manual'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            }  else if($Movement_Type == 'Stock Taking Under'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
               
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Stock Taking Over'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
               
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Received From Issue Note Manual'){
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
 
                $Grand_Balance = $data['Post_Balance'];
            }
        }
         /*
        echo "<tr><td colspan='7'><hr></td></tr>";
        echo "<tr><td colspan='2'>
                <td style='text-align: right;'>".$Item_ID."</td>
                <td style='text-align: right;'>".$Product_Name."</td>
                <td style='text-align: right;'>".$Total_inward."</td>
                <td style='text-align: right;'>".$Total_outward."</td>
                <td style='text-align: right;'>".$Grand_Balance."&nbsp;&nbsp;</td>
                </tr>";
        echo "<tr><td colspan='7'><hr></td></tr>";
        echo "</table>";

        */
            $Total_Final_Inward=$Total_inward;
            $Total_Final_Outward=$Total_outward;
            $Grand_Final_Balance=$Grand_Balance;

            $receivedItems=($Grand_Final_Balance+$Total_Final_Outward)-$Total_Final_Inward;
           /* $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$i,$row['Product_Name'])
                ->setCellValue('C'.$i,$row['Unit_Of_Measure'])
                ->setCellValue('D'.$i,$receivedItems)
                ->setCellValue('E'.$i,$Total_Final_Inward)
                ->setCellValue('F'.$i,$Grand_Final_Balance);
*/
            echo "<tr><td>".$row['Product_Name']."</td><td>".$row['Unit_Of_Measure']."</td><td>".$receivedItems."</td><td>".$Total_Final_Inward."</td><td>".$Grand_Final_Balance."</td></tr>";
           
        }else{
            //Get Item Balance
            $select = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $result = mysqli_fetch_assoc($select);
            $Item_Balance = $result['Item_Balance'];
            $Date = mysqli_fetch_assoc(mysqli_query($conn,"select now() as Today"));
            }}
    }
}
    echo "</table>";

}
?>