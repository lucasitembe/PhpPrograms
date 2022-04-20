<form action='#' method='post' name='login' id='login' name="myForm" onsubmit="return validateForm();">
    <input type='checkbox' name='checkbox1' id='checkbox1' value='yes'>
    <input type='checkbox' name='checkbox2' id='checkbox2' value='yes'>
    <input type='checkbox' name='checkbox3' id='checkbox3' value='yes'>
        <input type='submit' value='save'>
</form>

<?php
$checkbox1 = 'no';
$checkbox2 = 'no';
$checkbox3 = 'no';
include "./includes/connection.php"; 
if(isset($_POST['checkbox1'])) {$checkbox1 = 'yes'; }
if(isset($_POST['checkbox2'])) {$checkbox2 = 'yes'; }
if(isset($_POST['checkbox3'])) {$checkbox3 = 'yes'; }
    $save = "insert into tblcheckbox(checkbox1,checkbox2,checkbox3) values('$checkbox1','$checkbox2','$checkbox3')";
    if(!mysqli_query($conn,$save)){
        die(mysqli_error($conn));
    }
?>
<p>Click the button to display a confirm box.</p>

<button onclick="myFunction()">Try it</button>

<p id="demo"></p>

<script type="text/javascript">
function myFunction()
{
var x;
var r=alert("Press a button!");
if (r==ok)
  {
  x="You pressed OK!";
  }
else
  {
  x="You pressed Cancel!";
  }
document.getElementById("demo").innerHTML=x;
}
</script>



<img src ="planets.gif" width="145" height="126" alt="Planets" usemap ="#planetmap" />

<map name="planetmap">
  <area shape="rect" coords="0,0,82,126" href="sun.htm" alt="Sun" />
  <area shape="circle" coords="90,58,3" href="mercur.htm" alt="Mercury" />
  <area shape="circle" coords="124,58,8" href="venus.htm" alt="Venus" />
</map>





<script type="text/javascript">
    function ageCount() {
        var date1 = new Date();
        var  dob= document.getElementById("dob").value;
        var date2=new Date(dob);
        var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/; //Regex to validate date format (dd/mm/yyyy)
        if (pattern.test(dob)) {
            var y1 = date1.getFullYear(); //getting current year
            var y2 = date2.getFullYear(); //getting dob year
            var age = y1 - y2;           //calculating age 
            document.write("Age : " + age);
            return true;
        } else {
            alert("Invalid date format. Please Input in (dd/mm/yyyy) format!");
            return false;
        }

    }
</script>
</head>
<body>
Date of Birth(dd/mm/yyyy):
<input type="text" name="dob" id="dob" />
<input type="submit" value="Age" onclick="ageCount();">
    

    
    
    
    
<?php $_age = floor( (strtotime(date('Y-m-d')) - strtotime('1985-07-17')) / 31556926);  echo "Teh Age is".$_age; ?>


<?php
    $date1 = new DateTime("2014-01-01");
    $date2 = new DateTime("2014-07-31");
    $diff = $date1 -> diff($date2);
    echo "Difference ".$diff->y."years ".$diff->m."months ".$diff->d."days ";

?>