<style>
    #selections{
        font-size: 15px;
    }
    #table-custom td{
        padding: 8px;
    }
    thead{
        background-color: #eee;
    }
</style>

<?php include("./includes/connection.php"); ?>

<div id="selections">
    <span>SELECT WARD</span>
    <select name="ward_selection" class="ward_selection" id="" style="width: 100%;padding:2px">
        <option value="">Select ward</option>
        <?php
            $Query = mysqli_query($conn,"select * from tbl_hospital_ward") or die(mysqli_error($conn));
            while ($row = mysqli_fetch_assoc($Query)) {
                echo '<option value="' . $row['Hospital_Ward_ID'] . '">' . $row['Hospital_Ward_Name'].'~~>'.$row['ward_nature']. '</option>';
            }
        ?>
        <option value="">Ward Name</option>
    </select>
</div>

<br>

<div id="table-custom">
    <table style="width: 100%;">
        <thead>
            <tr>
                <td width="8%" style="text-align: center;">S/N</td>
                <td width="30%">Name</td>
                <td width="25%">Room Type</td>
                <td width="32%">Action</td>
            </tr>
        </thead>

        <tbody id="pull"></tbody>
    </table>
</div>

<script>
    $(document).ready(() => {
        $("select.ward_selection").change(function() {
            var selected_id = $(this).children("option:selected").val();
            var selected_dose_name = $(this).children("option:selected").text();
            var get_wards = "wards";

            $.post(
                'rooms_pull.php',{
                    selected_id:selected_id,
                    get_wards:get_wards
                },(response) => {
                    $('#pull').html(response);
                }
            );
        });
    })

    function edit_room(room_id) { 
        var room_name = document.getElementById(room_id).value;
        var room_edit_requets = "edit";
        $.post(
            'rooms_pull.php',{
                room_id: room_id,
                room_name:room_name,
                room_edit_requets:room_edit_requets
            },(response) => {
                alert(response);
            }
        );
    }
</script>

<script>
    function disableRoom(room_id){
        var disable_room = "disable_room";
        $.post(
            'rooms_pull.php',{
                room_id: room_id,
                disable_room:disable_room
            },(response) => {
                alert(response);
            }
        );
    }
</script>