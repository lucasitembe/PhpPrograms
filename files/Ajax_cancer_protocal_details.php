<?php 
require_once('includes/connection.php');
session_start();
if(isset($_POST['protocal_type_details'])){
    $Patient_protocal_details_ID =$_POST['Patient_protocal_details_ID'];
    $Registration_ID =$_POST['Registration_ID'];   
        $name_cancer=$_POST['disease_name'];
   
  
               include('chemotherapy_patient_protocal_inclusion.php');
        ?>
    <table class="table"> 
        
        <tr>
            <td>
                <select name="" id="Protocal_status" class="form-control text-center">
                    <option value="">~~~select Status~~~</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancel</option>
                    <option value="OnProgress">Continue</option>
                </select>
            </td>
            <td>
                <textarea name="" id="Reason"  rows="2" class="form-control" placeholder="Enter reason for changing status"></textarea>
            </td>
            <td>
                <button class="btn btn-primary xm" name='btn_update_status'  onclick="change_protocal_status( <?php echo $Patient_protocal_details_ID;?>)">UPDATE STATUS </button>
            </td>
            
        </tr>
    </table>
    <?php
}