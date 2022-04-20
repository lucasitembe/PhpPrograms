<?php
 include("./includes/connection.php");

 if(isset($_POST['depertment_search_value'])){
    $Depertment_Name_Search = str_replace(" ", "%", $_POST['depertment_search_value']);
}else{
    $Depertment_Name_Search = '';
}

$html = '
        <div id="depertments_list">
                        <table class="table">';

                        $sql_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled' AND finance_department_name like '%$Depertment_Name_Search%'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_result)>0){
                                        while($category_rows=mysqli_fetch_assoc($sql_result)){
                                            $Depertment_ID=$category_rows['finance_department_id'];
                                                $Depertment_Name=$category_rows['finance_department_name'];
                                                $html .=  "<tr>
                                                            <td>
                                                                <label style='font-weight:normal'>
                                                                    <input type='checkbox'class='Depertment_ID' name='Depertment_ID' value='$Depertment_ID'>$Depertment_Name
                                                                </label>
                                                            </td>
                                                            
                                                    </tr>";
                                        }
                                    }else{
                                        $html .=  "<tr>
                                                    <td>
                                                        <label style='color:red;'>
                                                            SORRY, NO RESULT FOUND!
                                                        </label>
                                                    </td>
                                                    
                                            </tr>";
                                    }
                       $html .=  '</table>
                    </div>';

                    echo $html;
?>