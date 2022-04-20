<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = 0;
    }
    
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
    
    $total = 0;
    echo '<center><table width =100%>';
    echo "<tr id='thead'><td><b>Check in type</b></td>";
        echo '<td><b>Location</b></td>
                <td><b>Item description</b></td>
                    <td style="text-align:right; width: 10%;"><b>Price</b></td>
                        <td style="text-align:right; width: 10%;"><b>Discount</b></td>
                            <td style="text-align:right; width: 10%;"><b>Quantity</b></td>
                                <td style="text-align:right; width: 10%;"><b>Sub total</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity
		from tbl_items t, tbl_adhoc_items_list_cache alc
		    where alc.Item_ID = t.Item_ID and
			alc.Employee_ID = '$Employee_ID' and
				Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
    }   echo "</tr>";
        echo "<tr><td colspan=7 style='text-align: right;'> Total : ".number_format($total)."</td></tr>";
?></table></center>