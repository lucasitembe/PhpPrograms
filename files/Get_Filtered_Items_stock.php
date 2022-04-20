<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Start_Date'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = '';
    }
    
    if(isset($_GET['End_Date'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = '';
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    $search='';
    if(isset($_GET['Search_Value'])){
        $search=" AND Product_Name LIKE '%".$_GET['Search_Value']."%'";
    }
        echo '<table width=100%>'
                . '<tr>
                    <td width="10%"><b>SN</b></td>
                    <td><b>ITEM NAME</b></td>
                    <td width="12%"><b>BALANCE</b></td>
                  </tr>';
            
                $temp = 1; $total_items = 0;
                if(isset($_SESSION['Pharmacy'])){
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                $result = mysqli_query($conn,"SELECT * FROM `tbl_items` WHERE `Item_Type` ='pharmacy' $search ") or die(mysqli_error($conn));
                   $num = mysqli_num_rows($result); //echo $num;
                 if($num>0){				   
                   while( $itm=mysqli_fetch_array($result)){
					$Item_ID=$itm['Item_ID'];
					$Product_Name=$itm['Product_Name'];
					
                    $sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' AND Sub_Department_ID = '$Sub_Department_ID'
                                                        ") or die(mysqli_error($conn));
                    $num_balance = mysqli_num_rows($sql_balance);
                    if($num_balance > 0){
                        while($sd = mysqli_fetch_array($sql_balance)){
                            $Item_Balance = $sd['Item_Balance'];
                        }
                    }else{
                        $Item_Balance = 0;
                    }
                    echo "<tr><td style='text-align: left;' width=6%>$temp</td>";
                    echo "<td style='text-align: left;' width=53%>$Product_Name</td>";
                    echo "<td style='text-align: right;'>".number_format($Item_Balance)."</a></td></tr>";
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                }
				
                //$total_items = 0;
           
			}else{
			   echo '<tr><td style="text-align:center;color:red;font-weight:bold;font-size:18px;" colspan="3">No item found</td></tr>';
			}
        echo '</table>';

?>