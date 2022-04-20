<?php
include("./includes/connection.php");
$Registration_ID = $_POST["Registration_ID"];
$Round_Date = $_POST["Round_Date"];
$filter = $_POST["filter"];

$sql = "SELECT 
        i.Product_Name,
        ep.Employee_Name,
        ilc.Status,
        ilc.Doctor_Comment
        FROM
        tbl_item_list_cache ilc,
        tbl_payment_cache pc, 
        tbl_items i,
        tbl_employee ep
        WHERE
        pc.Payment_Cache_ID = ilc.Payment_Cache_ID
        AND pc.Registration_ID = '$Registration_ID '
        AND DATE(ilc.Service_Date_And_Time) = '$Round_Date'
        AND ilc.Item_ID = i.Item_ID
        AND ilc.Consultant_ID = ep.Employee_ID";

if(!empty($filter) && $filter == "Pharmacy"){
    $sql2 = $sql ." and ilc.Check_In_Type = 'Pharmacy'";
    $query = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    $index = 0;
    while($rows = mysqli_fetch_assoc($query)){
        $Product_Name = $rows['Product_Name'];
        $Employee_Name = $rows['Employee_Name'];
        $Doctor_Comment = $rows['Doctor_Comment'];
        $Status = '';

        if($rows['Status'] == "dispensed"){
            $Status = 'Dispensed';
        }else{
            $Status = 'Not Dispensed';
        }

        echo "<tr>";
        echo "<td>".++$index."</td>";
        echo "<td><b>$Product_Name</b></td>";
        echo "<td><b>$Doctor_Comment</b></td>";
        echo "<td>$Employee_Name</td>";
        echo "<td>$Status</td>";
        echo "</tr>";

    }
}

if(!empty($filter) && $filter == "Laboratory"){
    $sql2 = $sql ." and ilc.Check_In_Type = 'Laboratory'";
    $query = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    $index = 0;
    while($rows = mysqli_fetch_assoc($query)){
        $Product_Name = $rows['Product_Name'];
        $Employee_Name = $rows['Employee_Name'];
        $Doctor_Comment = $rows['Doctor_Comment'];
        $Status = '';

        if($rows['Status'] == "active"){
            $Status = 'Not Paid or Not Approved';
        }else{
            $Status = 'Paid or Approved';
        }

        echo "<tr>";
        echo "<td>".++$index."</td>";
        echo "<td><b>$Product_Name</b></td>";
        echo "<td><b>$Doctor_Comment</b></td>";
        echo "<td>$Employee_Name</td>";
        echo "<td>$Status</td>";
        echo "</tr>";

    }
}

if(!empty($filter) && $filter == "Radiology"){
    $sql2 = $sql ." and ilc.Check_In_Type = 'Radiology'";
    $query = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    $index = 0;
    while($rows = mysqli_fetch_assoc($query)){
        $Product_Name = $rows['Product_Name'];
        $Employee_Name = $rows['Employee_Name'];
        $Doctor_Comment = $rows['Doctor_Comment'];
        $Status = '';

        if($rows['Status'] == "active"){
            $Status = 'Not Paid or Not Approved';
        }else{
            $Status = 'Paid or Approved';
        }

        echo "<tr>";
        echo "<td>".++$index."</td>";
        echo "<td><b>$Product_Name</b></td>";
        echo "<td><b>$Doctor_Comment</b></td>";
        echo "<td>$Employee_Name</td>";
        echo "<td>$Status</td>";
        echo "</tr>";

    }
}

if(!empty($filter) && $filter == "doctorplan"){
    $sql = "SELECT 
                Employee_Name,
                Findings
            FROM
                tbl_ward_round,
                tbl_employee
            WHERE
                tbl_ward_round.Employee_ID = tbl_employee.Employee_ID AND
                DATE(Ward_Round_Date_And_Time) = '$Round_Date'
                    AND Registration_ID = '$Registration_ID'
            ORDER BY Round_ID DESC";
    $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $index = 0;
    while($rows = mysqli_fetch_assoc($query)){
        $Employee_Name = $rows['Employee_Name'];
        $Findings = $rows['Findings'];

        echo "<tr>";
        echo "<td>".++$index."</td>";
        echo "<td><b>$Employee_Name</b></td>";
        echo "<td>$Findings</td>";
        echo "</tr>";

    }
}

if(!empty($filter) && $filter == "nurseplan"){
    $sql = "SELECT 
                Employee_Name,
                Notes
            FROM
                tbl_nurse_notes,
                tbl_employee
            WHERE
                tbl_nurse_notes.Employee_ID = tbl_employee.Employee_ID and
                DATE(Notes_Date_Time) = '$Round_Date'
                AND Registration_ID = '$Registration_ID'
            ORDER BY Notes_ID DESC";
    $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $index = 0;
    while($rows = mysqli_fetch_assoc($query)){
        $Employee_Name = $rows['Employee_Name'];
        $Findings = $rows['Notes'];

        echo "<tr>";
        echo "<td>".++$index."</td>";
        echo "<td><b>$Employee_Name</b></td>";
        echo "<td>$Findings</td>";
        echo "</tr>";

    }
}

mysqli_close($conn);