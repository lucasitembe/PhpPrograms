<?php 
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> -->

<link type="text/css" href="asset/css/jquery.signature.css" rel="stylesheet"> 
<script type="text/javascript" src="asset/js/jquery.signature.js"></script>
    <style>
        .kbw-signature { width: 400px; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
</head>
<body>
<center>
<br/><br/><br/>
<section style="width:75%; ">

<div class="container">
    <form method="POST" action="upload.php">

       <h1>Witness Signature </h1>

            <!-- <label class="" for="">Signature:</label> -->
            <br/>
            <div id="sig" ></div>
            <br/>
            <button id="clear">Clear Signature</button>
            <textarea id="signature64" name="signed" style="display: none"></textarea>
            <input type="text" name="registrationID" value="<?php echo $Registration_ID; ?>" >
        </div>
        <br/>
        <button class="btn btn-success">Submit</button>
    </form>
</div>
</section>
</center>


  

<script type="text/javascript">

    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});

    $('#clear').click(function(e) {

        e.preventDefault();

        sig.signature('clear');

        $("#signature64").val('');

    });

</script>

  

</body>

</html>

