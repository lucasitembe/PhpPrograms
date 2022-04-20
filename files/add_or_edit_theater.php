<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

?>
<!-- <link href="./links/jquery-ui.css" rel="stylesheet"> -->
<script src="./links/jquery-1.10.2.js"></script>
<!-- <script src="./links/jquery-ui.js"></script> -->

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {

if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission") {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    } else {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    }
?>

<?php  }
} ?>



<br /><br />
<fieldset>
    <legend align=center><b>SET THEATER ROOMS</b></legend>
    <center>
        <table id="replace_table" width=60%>
            <tr>
                <td width=10% style='text-align: center; padding: 8px;'><b>Theater Room</b></td>
                <td width=45% style='text-align: center;' colspan="2"><input style='text-align: center;' id="room_name" name="room_name" type="text" placeholder="Enter Room Name"></td>
                <td width=10% style='text-align: center;'><span id='result'></span></td>

            </tr>
            <tr>
                <td width=10% style='text-align: center; padding: 8px;'><b>Location</b></td>
                <td width=45% style='text-align: center;' colspan="2">
                    <select style="width: 100%;" name="sub_dept" id="sub_dept">
                        <option value="0">~~~ Select Location~~~</option>
                        <?php
                        $select_sub_dept = mysqli_query($conn, "SELECT sb.Sub_Department_ID,sb.Sub_Department_Name FROM tbl_department d, tbl_sub_department sb WHERE d.Department_ID=sb.Department_ID AND d.Department_Location IN('Procedure','Surgery')");
                        while ($data = mysqli_fetch_array($select_sub_dept)) {
                        ?>
                            <option value="<?php echo $data['Sub_Department_ID']; ?>"><?php echo $data['Sub_Department_Name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td width=5% style='text-align: center;'><input type="submit" name="submit_room_name" onclick="set_room_name()" id="submit_room_name" value="SAVE" class="art-button-green"></td>
            </tr>
        </table>
    </center><br><br>
    <center>
        <fieldset style="overflow-y: scroll; height: 300px;width: 60%;">
            <h5><b>List of Theater Rooms</b></h5>
            <table width=100% id="update_list">
                <tr style="background-color: #CCC; color: black;">
                    <th width=5%>S/N</th>
                    <th width=25%>Theater Room Name</th>
                    <th width=10%>Room Status</th>
                    <th width=20%>Action</th>
                </tr>

                <?php
                $retrieve_query = "SELECT theater_room_id,theater_room_name,room_status FROM tbl_theater_rooms order by theater_room_name";
                $retrieve_query_run = mysqli_query($conn, $retrieve_query);
                $num_rowss = mysqli_num_rows($retrieve_query_run);

                if ($num_rowss > 0) {
                    $sn = 1;
                    while ($rows = mysqli_fetch_array($retrieve_query_run)) {
                ?>

                        <tr style="background-color: white;">
                            <td style="text-align: center;"><?= $sn ?></td>
                            <td style="text-align: center;"><?= $rows['theater_room_name'] ?></td>
                            <td style="text-align: center;"><?= $rows['room_status'] ?></td>
                            <td style="text-align: center;">
                                <input type="submit" name="submit_room_name3" onclick="edit_room_name(<?= $rows['theater_room_id'] ?>)" id="submit_room_name3" value="Edit" class="art-button-green">
                                <input type="submit" name="submit_room_name2" onclick="status_room_name(<?= $rows['theater_room_id'] ?>)" id="submit_room_name2" value="Enable / Disable" class="art-button-green">
                            </td>
                        </tr>

                    <?php
                        $sn++;
                    }
                } else {
                    ?>
                    <tr style="background-color: white;">
                        <td></td>
                        <td style="text-align: center;">
                            <h5 style="color: red;">No Theater Room Founds</h5>
                        </td>
                        <td></td>
                    </tr>

                <?php
                }
                ?>
            </table>
        </fieldset>
    </center>
</fieldset><br />

<script>
    function set_room_name() {
        var room_name = document.getElementById("room_name").value;
        var sub_dept_id = document.getElementById("sub_dept").value;

        if (room_name != "" && room_name != null) {
            if (sub_dept_id != "" && sub_dept_id != null && sub_dept_id != 0) {
                $.ajax({
                    url: './ajax_set_room_name.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        room_name: room_name,
                        sub_dept_id: sub_dept_id
                    },
                    success: function(response) {
                        // alert(response)

                        if (response == "Room name exist") {
                            document.getElementById("result").innerHTML = "<h5 style='color: red;'>Room Name Exist</h5>";
                        }
                        if (response == "succeded") {
                            document.getElementById("room_name").value = "";
                            document.getElementById("result").innerHTML = "<h5 style='color: green;'>Saved</h5>";
                            load_room_names();
                        }
                        if (response == "fail") {
                            document.getElementById("result").innerHTML = "<h5 style='color: red;'>Fail To Save</h5>";
                        }
                    }
                });
            } else {
                alert("Sub Department required");
            }
        } else {
            document.getElementById("room_name").style.border = "2px solid red";
        }

    }

    function edit_room_name(id) {
        var my_id = id;
        // var room_name = document.getElementById("room_name").value;

        $.ajax({
            url: './ajax_edit_room_name.php',
            method: 'POST',
            dataType: 'text',
            data: {
                edit_response: "edit",
                my_id: my_id
            },
            success: function(response) {
                $("#replace_table").html(response);
            }
        });

    }

    function load_room_names() {
        $.ajax({
            url: './ajax_load_room_names.php',
            method: 'POST',
            dataType: 'text',
            data: {
                room_name_request: "update"
            },
            success: function(response) {

                document.getElementById("update_list").innerHTML = response;

            }
        });
    }

    function status_room_name(id) {
        var my_id = id;

        $.ajax({
            url: './ajax_enable_or_disable_room.php',
            method: 'POST',
            dataType: 'text',
            data: {
                status_request: "status",
                my_id: my_id
            },
            success: function(response) {
                alert(response);

                load_room_names();

            }
        });

    }

    function update_room_detaits(id) {
        var my_id = id;
        var room_name = document.getElementById("room_name").value;
        var sub_dept_id = document.getElementById("sub_dept").value;

        $.ajax({
            url: './ajax_edit_room_name.php',
            method: 'POST',
            dataType: 'text',
            data: {
                update_request: "update",
                my_id: my_id,
                room_name: room_name,
                sub_dept_id: sub_dept_id
            },
            success: function(response) {
                if (response == "Updated Successfuly") {
                    document.getElementById("result2").innerHTML = "<h5 style='color: green;'>" + response + "</h5>";
                    load_room_names();

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    document.getElementById("result2").innerHTML = "<h5 style='color: red;'>" + response + "</h5>";
                }

                load_room_names();

            }
        });

    }
</script>
<script>
    $(document).ready(function() {
        $('select').select2();
    });
</script>
<?php
include("./includes/footer.php");
?>