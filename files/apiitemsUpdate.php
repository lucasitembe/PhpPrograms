<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

?>
<a href='itemsconfiguration.php' class='art-button-green'>
        BACK
    </a>
<br/><br/>
<fieldset style="background-color: white;width:100%;height: 400px;overflow-x: hidden;overflow-y: scroll " >
    <legend>Item Synchronization</legend>
    <center>
        <select name='Sponsor_ID' id='Sponsor_ID' required='required' style="width:50%">
            <option selected='selected'></option>
            <?php
            $select_Insurances = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor where Guarantor_name <> 'CASH' AND auto_item_update_api='1'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select_Insurances);
            if ($no > 0) {
                while ($row = mysqli_fetch_array($select_Insurances)) {
                    ?>
                    <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
                    <?php
                }
            }
            ?>
        </select>

        <input type='button' name='Print_Filter' id='synchronize_filter_btn' class='art-button-green' value='SYNCHRONIZE' onclick="Synchronize()">
        <input type='button' name='Print_Filter' id='synchronize_filter_btn' class='art-button-green' value='FORCE UPDATE PRICE' onclick="Synchronize_force()">
        <input type='button' name='Print_Filter' id='synchronize_filter_btn' class='art-button-green' value='GET EXCLUDEDE ITEMS' onclick="Synchronize_excluded()">
    </center>
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
    <div style="text-align: center;" id="statusMessage"></div>
   <!-- <button onclick="run_test()">TESTING YA MAANA</button>
    <div id="display_fedbck">
        fdhjdjdhjdhdjhjdjhdjhjh
        <?php //require "nhif3/index.php";?>
    </div>-->
</fieldset>
<script>
    function run_test(){
         
        $.ajax({
           type: "GET",
           url: 'nhif3/index.php',
           dataType: 'text',
           data: {getprice:"getprice"},
           success:function(data){
               console.log(data);
            // alert("yyyt")
               $("#display_fedbck").html(data);
           },
           error:function(data){
                    alert(data);
                }
       });
    }
    function Synchronize() {
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Sponsor_ID != null && Sponsor_ID != '') {
            var datastring = 'Sponsor_ID=' + Sponsor_ID;

            $.ajax({
                type: 'GET',
                url: 'apiitmesUpdate_frame.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                    $("#synchronize_filter_btn").prop('disabled', true);
                },
                success: function (data) {
                    $('#statusMessage').html('<h2>' + data + '</h2>');
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
                    $("#synchronize_filter_btn").prop('disabled', false);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#progressStatus').hide();
                }
            });
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }

            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
    function Synchronize_force() {
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Sponsor_ID != null && Sponsor_ID != '') {
            var datastring = 'Sponsor_ID=' + Sponsor_ID;

            $.ajax({
                type: 'GET',
                url: 'item_price_update_force.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                    $("#synchronize_filter_btn").prop('disabled', true);
                },
                success: function (data) {
                    $('#statusMessage').html('<h2>' + data + '</h2>');
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
                    $("#synchronize_filter_btn").prop('disabled', false);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#progressStatus').hide();
                }
            });
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }

            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
    
    function Synchronize_excluded() {
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Sponsor_ID != null && Sponsor_ID != '') {
            var datastring = 'getpriceexcluded=' + Sponsor_ID;

            $.ajax({
                type: 'GET',
                url: 'getexcludeditems.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                    $("#synchronize_filter_btn").prop('disabled', true);
                },
                success: function (data) {
                    $('#statusMessage').html('<h2>' + data + '</h2>');
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
                    $("#synchronize_filter_btn").prop('disabled', false);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#progressStatus').hide();
                }
            });
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }

            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>
