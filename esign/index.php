<!DOCTYPE html>
<html>
<head>
    <title>eSign | login</title>
    <style type="text/css">
    body {
        background: #f5f5f5;
    }
        #login-div {
            margin: 0 auto;
            margin-top: 15%;
            padding: 20px;
            padding-top: 18px;
            width: 350px;
            height: 150px;
            border: 1px solid #c0c0c0;
            border-radius: 5px;
            box-shadow: 0 0 15px  0  blue;
        }
        .form-control {
            padding:10px;
            border: 1px solid #c9c9c9;
            border-radius: 5px;
            width: 90%;
        }
        .art-btn {
            padding: 5px;  
            width: 80px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div id="login-div">
    <table width="100%">
    <thead>
        <tr>
            <th colspan="2" align="center" style="color:blue;font-size: 1.2em;">eSIGN Login</th>
        </tr>
    </thead>
        <tbody>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" class="form-control"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" class="form-control"></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><a href="signature.php"><input type="submit" class="art-btn" name="login" value="Login"></a></td>
            </tr>
        </tbody>
    </table>
    <p style="text-align:center;padding:20px;font-weight:bold;color:blue;">eSIGN &copy 2017</p>
</div>
</body>
</html>