<style>
    .links{
        margin-bottom: 30px !important;
    }
</style>

<div>
    <!-- link content -->
    1. <a href="#" onclick="pharmacyHelp(1)" class='links' href=""  style="font-family: sans-serif;font-size:large">How to dispense cash patient</a>
    <br/><br>
    2. <a href="#" onclick="pharmacyHelp(2)" class='links' href=""  style="font-family: sans-serif;font-size:large">How to dispense credit patient</a>
    <br><br>
    3. <a href="#" onclick="pharmacyHelp(3)" class='links' href=""  style="font-family: sans-serif;font-size:large">How to dispense item(s) sent to another department</a>
    <br><br>
    4. <a href="#" onclick="pharmacyHelp(4)" class='links' href="" style="font-family: sans-serif;font-size:large">How to Reports</a>
    <br><br>
</div>

<script>
    function pharmacyHelp(param) {  
        window.open('pharmacy/pharmacyHelp.php?helpId='+param);
    }
</script>