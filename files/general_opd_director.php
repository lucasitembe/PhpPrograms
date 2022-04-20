<?php 
    if(isset($_POST['Region_ID'])){
        $Region_Id = $_POST['Region_ID'];
        $Region_Name = $_POST['Region_Name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        echo "

            <table class='table table-borderd'>
                <tbody>
                    <tr><button style='border-radius:0px' onclick='open_selected_region_details_iframe(\"$Region_Name\",3)'>ADMISSIONS</button></tr>

                    <tr><button style='border-radius:0px' onclick='open_selected_region_details_iframe(\"$Region_Name\",1)'>NEW REGISTRATION</button></tr>
                    
                    <tr><button style='border-radius:0px' onclick='open_selected_region_details_iframe(\"$Region_Name\",2)'>RE-ATTENDANCES</button></tr>
                </tbody>
            </table>

        ";
    }
?>