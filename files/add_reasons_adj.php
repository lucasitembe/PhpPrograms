<table style="width: 100%;">
    <thead>
        <tr style="background-color:#eee">
            <td style="padding: 10px;" colspan="2">
                <input type="text" placeholder="Enter Reason" id="name" style="padding: 8px;">
            </td>
            <td style="padding: 10px;">
                <select  id='nature' style="width: 100%;padding:8px">
                    <option>Select Nature</option>
                    <option value='adjustment_plus'>Adjustment Plus</option>
                    <option value='adjustment_minus'>Adjustment Minus</option>
                </select>
            </td>

            <td style="padding: 10px;">
                <a href="#" onclick="add_reason()" class='art-button-green'>ADD REASON</a>
            </td>
        </tr>
    </thead>

    <thead>
        <tr style="padding: 10px;background-color:#ddd">
            <td style="padding: 10px;" width='10%'><center><b>S/N</b></center></td>
            <td style="padding: 10px;" ><b>Reason</b></td>
            <td style="padding: 10px;" width='20%'><b>Nature</b></td>
            <td style="padding: 10px;" width='20%'><b>Action</b></td>
        </tr>
    </thead>

    <tbody id="reasons-display"></tbody>
</table>



<script>
    $(document).ready(() => {
        load_adjustment_reasons();
    })

    function load_adjustment_reasons(){
        $.ajax({
            type: "GET",
            url: "adjustment_reasons_core.php",
            data: {
                load_reasons:'load_reasons'
            },
            success: (response) => {
                $('#reasons-display').html(response);
            }
        });
    }

    function enable_disable(id) {  
        var enable_disable = document.getElementById(id).value;

        $.ajax({
            type: "POST",
            url: "adjustment_reasons_core.php",
            data: {
                id:id,
                enable_disable:enable_disable,
                availability:'availability'
            },
            success: (response) => {
                alert(response);
            }
        });
    }

    function add_reason() {  
        var name = document.getElementById('name').value;
        var nature = document.getElementById('nature').value;

        if(name == ""){
            document.getElementById('name').style.border = "2px solid red";
            exit();
        }else if(nature == ""){
            document.getElementById('nature').style.border = "2px solid red";
        }

        $.ajax({
            type: "POST",
            url: "adjustment_reasons_core.php",
            data: {
                name:name,
                nature:nature,
                add_reason:'add_reason'
            },
            success: (response) => {
                document.getElementById('name').value = "";
                document.getElementById('nature').value = "";
                load_adjustment_reasons();
            }
        });
    }
</script>