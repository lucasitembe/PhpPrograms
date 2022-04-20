<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Password'])){
        $Password = md5($_GET['Password']);
    }else{
        $Password = '';
    }
    
    if(isset($_GET['Username'])){
        $Username = $_GET['Username'];
    }else{
        $Username = '';
    }
    if(isset($_GET['document_number'])){
        $document_number = $_GET['document_number'];
    }else{
        $document_number = '';
    }
    if(isset($_GET['document_type'])){
        $document_type = $_GET['document_type'];
    }else{
        $document_type = '';
    }
    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = $_GET['Supplier_ID'];
    }else{
        $Supplier_ID = '0';
    }
    if($Password != '' && $Password != null && $Username != '' && $Username != null){
        $sql_authenticate = mysqli_query($conn,"SELECT e.Employee_ID from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                                            where b.branch_id = be.branch_id and
                                                e.employee_id = be.employee_id and
                                                    e.employee_id = p.employee_id and
                                                        p.Given_Username = '$Username' and
                                                            p.Given_Password  = '$Password' 
                                                                ") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_authenticate);
        if($num > 0){
            while($row = mysqli_fetch_array($sql_authenticate)){
//                $_SESSION['Open_Balance_Supervisor_ID'] = $row['Employee_ID'];
                $Employee_ID= $row['Employee_ID'];
            } 
             
            ////check if user has enough privalage to approve this document
//            $Employee_ID=$_SESSION['Open_Balance_Supervisor_ID'];
            $sql_check_for_approval_privilages_result=mysqli_query($conn,"SELECT document_type FROM tbl_employee_assigned_approval_level eaal,tbl_document_approval_level dal WHERE eaal.document_approval_level_id=dal.document_approval_level_id AND dal.document_type='$document_type' AND assgned_Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_for_approval_privilages_result)>0){
                ///count approval level...gkc
                $sql_select_approval_level_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_level WHERE document_type='$document_type'") or die(mysqli_error($conn));
                $number_of_approval_level=mysqli_num_rows($sql_select_approval_level_result);
                
                //check if this employee already approve this document
                $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='$document_type' AND document_number='$document_number' AND approve_employee_id='$Employee_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_check_if_all_approve_result)>0){
                        //check if all required approval level already approve the document 
                         $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='$document_type' AND document_number='$document_number' GROUP BY approve_employee_id") or die(mysqli_error($conn));

                         $number_of_people_already_approve_this_document=mysqli_num_rows($sql_check_if_all_approve_result);
                         if($number_of_approval_level<=$number_of_people_already_approve_this_document){
                             $feedback="all_approve_success";
                         }else{
                             $remain_to_approve=$number_of_approval_level-$number_of_people_already_approve_this_document;
                             $feedback="Approved successfully \n\n $remain_to_approve approver still needed to complete the process";
                         }
                }else{
                   //insert approval detail to approval control table
                    //$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT dalt.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id  AND dac.document_type='$document_type' AND assgned_Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
                    $document_approval_level_title="no";
                    $document_approval_level_title_result= mysqli_query($conn,"SELECT dalt.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id  AND dal.document_type='$document_type' AND assgned_Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
                    while($document_approval_level_title_row=mysqli_fetch_assoc($document_approval_level_title_result)){
                        $document_approval_level_title=$document_approval_level_title_row['document_approval_level_title'];
                    }
                    $sql_insert_approval_result=mysqli_query($conn,"INSERT INTO tbl_document_approval_control(document_number,document_type,approve_employee_id,document_approval_level_title,Supplier_ID) VALUES('$document_number','$document_type','$Employee_ID','$document_approval_level_title','$Supplier_ID')")  or die(mysqli_error($conn));
                     if($sql_insert_approval_result){
                         //check if all required approval level already approve the document 
                         $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='$document_type' AND document_number='$document_number' GROUP BY approve_employee_id") or die(mysqli_error($conn));

                         $number_of_people_already_approve_this_document=mysqli_num_rows($sql_check_if_all_approve_result);
                         if($number_of_approval_level<=$number_of_people_already_approve_this_document){
                             $feedback="all_approve_success";
                         }else{
                             $remain_to_approve=$number_of_approval_level-$number_of_people_already_approve_this_document;
                             $feedback="Approved successfully \n\n $remain_to_approve approver still needed to complete the process";
                         }
                     }else{
                         $feedback="fail_to_approve";
                     }  
                }
               
                
                //$feedback = 'yes';
            }else{
               $feedback="invalid_privileges"; 
            }
            
        }else{
            $feedback = 'invalid_privileges';
            unset($_SESSION['Open_Balance_Supervisor_ID']);
        }        
    }
    
    echo $feedback; 
    
?>