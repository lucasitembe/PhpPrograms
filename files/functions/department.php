<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Sub_Department_Name($Sub_Department_ID) {
        global $conn;
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no_of_rows = mysqli_num_rows($select);
        if($no_of_rows){
            while($data = mysqli_fetch_array($select)){
                $Sub_Department_Name = $data['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
        }

        return $Sub_Department_Name;
    }

    function Get_Sub_Department($Sub_Department_ID) {
        global $conn;
        $Sub_Department = array();
        $Sub_Department_Result = Get_From("tbl_sub_department", array("Sub_Department_ID", "=", $Sub_Department_ID), array(), 1);
        $hasError = $Sub_Department_Result["error"];
        if (!$hasError) {
            $Sub_Department = $Sub_Department_Result["data"][0];
        } else {
            echo $Sub_Department_Result["errorMsg"];
        }

        return $Sub_Department;
    }

    function Get_Requesting_Department_From_Requisition($Requisition_ID) {
        global $conn;
        $Sub_Department = array();
        $Sub_Department_Result = Query_DB("SELECT sd.Sub_Department_Name, sd.Sub_Department_ID
                                            FROM tbl_sub_department sd, tbl_requisition req
                                            WHERE sd.Sub_Department_ID = req.Store_Need
                                            AND req.Requisition_ID = '{$Requisition_ID}'");
        $hasError = $Sub_Department_Result["error"];
        if (!$hasError) {
            $Sub_Department = $Sub_Department_Result["data"][0];
        } else {
            echo $Sub_Department_Result["errorMsg"];
        }

        return $Sub_Department;
    }

    function Get_Issuing_Department_From_Requisition($Requisition_ID) {
        global $conn;
        $Sub_Department = array();
        $Sub_Department_Result = Query_DB("SELECT sd.Sub_Department_Name, sd.Sub_Department_ID
                                                FROM tbl_sub_department sd, tbl_requisition req
                                                WHERE sd.Sub_Department_ID = req.Store_Issue
                                                AND req.Requisition_ID = '{$Requisition_ID}'");
        $hasError = $Sub_Department_Result["error"];
        if (!$hasError) {
            $Sub_Department = $Sub_Department_Result["data"][0];
        } else {
            echo $Sub_Department_Result["errorMsg"];
        }

        return $Sub_Department;
    }

    function Get_Sub_Department_All() {
        global $conn;
        $Sub_Department_List = array();

        $Sub_Department_Result = Get_From("tbl_sub_department", array(), array(), 0);
        $hasError = $Sub_Department_Result["error"];
        if (!$hasError) {
            $Sub_Department_List = array_merge($Sub_Department_List, $Sub_Department_Result["data"]);
        } else {
            echo $Sub_Department_Result["errorMsg"];
        }

        return $Sub_Department_List;
    }

    function Get_Sub_Department_By_Department_ID($Department_ID) {
        global $conn;
        $Sub_Department_List = array();

        $Sub_Department_Result = Get_From("tbl_sub_department", array("Department_ID", "=", $Department_ID), array(), 0);
        $hasError = $Sub_Department_Result["error"];
        if (!$hasError) {
            $Sub_Department_List = array_merge($Sub_Department_List, $Sub_Department_Result["data"]);
        } else {
            echo $Sub_Department_Result["errorMsg"];
        }

        return $Sub_Department_List;
    }

    function Get_Sub_Department_By_Department_Nature($Department_Nature) {
        global $conn;
        $Sub_Department_List = array();
        
        $Department_Result = Get_From("tbl_department", array("Department_Location", "=", $Department_Nature,$filter_sub_d), array(), 0);
        $hasError = $Department_Result["error"];
        if (!$hasError) {
            $Department_List = $Department_Result["data"];
            foreach($Department_List as $Department) {
                $Sub_Department_List = array_merge($Sub_Department_List, Get_Sub_Department_By_Department_ID($Department['Department_ID']));
            }
        } else {
            echo $Department_Result["errorMsg"];
        }

        return $Sub_Department_List;
    }

    function Get_Sub_Department_By_List_Of_Department_Nature($Department_Nature_List) {
        global $conn;
        $Sub_Department_List = array();

        $Department_Nature_Statement = ""; $i=0;
        foreach($Department_Nature_List as $Department_Nature) {
            if ($i == 0) {
                $Department_Nature_Statement .= "WHERE Department_Location IN (";
            }

            if ($i == count($Department_Nature_List) - 1) {
                $Department_Nature_Statement .= "'{$Department_Nature['nature']}')";
            } else {
                $Department_Nature_Statement .= "'{$Department_Nature['nature']}',";
            }
            $i++;
        }

        $Department_Result = Query_DB("SELECT * FROM tbl_department
                                       {$Department_Nature_Statement}");
        $hasError = $Department_Result["error"];
        if (!$hasError) {
            $Department_List = $Department_Result["data"];
            foreach($Department_List as $Department) {
                $Sub_Department_List = array_merge($Sub_Department_List, Get_Sub_Department_By_Department_ID($Department['Department_ID']));
            }
        } else {
            echo $Department_Result["errorMsg"];
        }

        return $Sub_Department_List;
    }

    function Get_Stock_Balance_Sub_Departments($Sub_Department_ID = 0) {
        global $conn;
        $Stock_Balance_Sub_Department_List = array();

        $Sub_Department_ID_Statement = "";
        if ($Sub_Department_ID > 0) {
            $Sub_Department_ID_Statement = "AND sd.Sub_Department_ID = '{$Sub_Department_ID}'";
        }

        $Stock_Balance_Sub_Department_List_Result = Query_DB("SELECT sd.Sub_Department_ID, sd.Sub_Department_Name
                                                                FROM tbl_stock_balance_sub_departments sb, tbl_sub_department sd
                                                                WHERE sd.Sub_Department_ID = sb.Sub_Department_ID
                                                                {$Sub_Department_ID_Statement}
                                                                ORDER BY Sub_Department_Name");

        $hasError = $Stock_Balance_Sub_Department_List_Result["error"];
        if (!$hasError) {
            $Stock_Balance_Sub_Department_List = array_merge($Stock_Balance_Sub_Department_List, $Stock_Balance_Sub_Department_List_Result["data"]);
        } else {
            echo $Stock_Balance_Sub_Department_List_Result["errorMsg"];
        }

        return $Stock_Balance_Sub_Department_List;
    }


    function Get_Stock_Ledge_Sub_Departments($Employee_ID = 0) {
        global $conn;
        $Stock_Balance_Sub_Department_List = array();

        $Employee_ID_Statement = "";
        if ($Employee_ID > 0) {
            $Employee_ID_Statement = "AND es.Employee_ID = '{$Employee_ID}'";
        }
        $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
        $filter_sub_d="";
        if($can_login_to_high_privileges_department=='yes'){
            $filter_sub_d="AND sd.privileges='high'";
        }
        if($can_login_to_high_privileges_department!='yes'){
            $filter_sub_d="and privileges='normal'";
        }
        $Stock_Balance_Sub_Department_List_Result = Query_DB("SELECT sd.Sub_Department_ID, sd.Sub_Department_Name
                                                            FROM tbl_employee_sub_department es, tbl_sub_department sd, tbl_department d
                                                            WHERE sd.Sub_Department_ID = es.Sub_Department_ID AND
                                                            sd.Department_ID = d.Department_ID $filter_sub_d AND
                                                            d.Department_Location in('Pharmacy','Storage And Supply')
                                                            {$Employee_ID_Statement}
                                                            ORDER BY Sub_Department_Name") or die(mysqli_error($conn));

        $hasError = $Stock_Balance_Sub_Department_List_Result["error"];
        if (!$hasError) {
            $Stock_Balance_Sub_Department_List = array_merge($Stock_Balance_Sub_Department_List, $Stock_Balance_Sub_Department_List_Result["data"]);
        } else {
            echo $Stock_Balance_Sub_Department_List_Result["errorMsg"];
        }

        return $Stock_Balance_Sub_Department_List;
    }

    function Get_Storage_And_Pharmacy_Sub_Departments() {
        global $conn;
        $Stock_Balance_Sub_Department_List = array();

        $Stock_Balance_Sub_Department_List_Result = Query_DB("SELECT sd.Sub_Department_ID, sd.Sub_Department_Name
                                                            FROM tbl_sub_department sd
                                                            ORDER BY Sub_Department_Name") or die(mysqli_error($conn));

        $hasError = $Stock_Balance_Sub_Department_List_Result["error"];
        if (!$hasError) {
            $Stock_Balance_Sub_Department_List = array_merge($Stock_Balance_Sub_Department_List, $Stock_Balance_Sub_Department_List_Result["data"]);
        } else {
            echo $Stock_Balance_Sub_Department_List_Result["errorMsg"];
        }

        return $Stock_Balance_Sub_Department_List;
    }

    function Get_My_Store_List($Employee_ID = 0) {
        global $conn;
        $Stock_Balance_Sub_Department_List = array();

        $Employee_ID_Statement = "";
        if ($Employee_ID > 0) {
            $Employee_ID_Statement = "AND es.Employee_ID = '{$Employee_ID}'";
        }

        $Stock_Balance_Sub_Department_List_Result = Query_DB("SELECT sd.Sub_Department_ID, sd.Sub_Department_Name
                                                            FROM tbl_employee_sub_department es, tbl_sub_department sd, tbl_department d
                                                            WHERE sd.Sub_Department_ID = es.Sub_Department_ID AND
                                                            sd.Department_ID = d.Department_ID AND
                                                            d.Department_Location in('Storage And Supply')
                                                            {$Employee_ID_Statement}
                                                            ORDER BY Sub_Department_Name") or die(mysqli_error($conn));

        $hasError = $Stock_Balance_Sub_Department_List_Result["error"];
        if (!$hasError) {
            $Stock_Balance_Sub_Department_List = array_merge($Stock_Balance_Sub_Department_List, $Stock_Balance_Sub_Department_List_Result["data"]);
        } else {
            echo $Stock_Balance_Sub_Department_List_Result["errorMsg"];
        }

        return $Stock_Balance_Sub_Department_List;
    }

?>