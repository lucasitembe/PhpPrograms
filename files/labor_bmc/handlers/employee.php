<?php



function get_employee_info($conn)
{
    $Doctor = "Doctor";

    $Nurse = "nurse";

    $data = array();

    $d = array();

    $select_employee_details = "SELECT Employee_ID, Employee_Name 
                                FROM tbl_employee 
                                WHERE Employee_Type=? or Employee_Type=?";

    $stmt = mysqli_prepare($conn, $select_employee_details);

    mysqli_stmt_bind_param($stmt, "ss", $Doctor, $Nurse);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $employee_id, $employee_name);

    while (mysqli_stmt_fetch($stmt)) 
    {
        $d['employee_id'] = $employee_id;

        $d['employee_name'] = $employee_name;

        array_push($data, $d);

    }

    return $data;
}
