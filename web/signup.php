<?php
//db Connection file
include('functions.php');
//code for signup
if(isset($_POST['signup'])){
    $message = '';
    $data = array("name"=>$_POST['name'],"username"=>$_POST['username'],"email"=>$_POST['emailid'],"password"=> $_POST['inputpass']);
    $response = callAPI('POST', 'register.php', $data);
    $message = $response['data']->message;
    if($response['httpcode'] == 200 && $response['data']->status == 200){
        echo "<script>alert('You have successfully registered');</script>";
    } else {
        echo "<script>alert('".$message."');</script>";
    }
    // echo "<script>window.location.href='signup.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UMS</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
<script type="text/javascript">
function checkpass()
{
if(document.signup.inputpass.value!=document.signup.repeatpass.value)
{
alert('New Password and Repeat Password field does not match');
document.signup.repeatpass.focus();
return false;
}
return true;
}   

</script>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h5 style="color:blue"><?= isset($message) ? $message : ''; ?></h5>
                                <h1 class="h4 text-gray-900 mb-4">Register an User!</h1>
                            </div>
                            <form class="user" name="signup" method="post" onsubmit="return checkpass();">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="name" placeholder="First Name" name="name" required="true">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="username" required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="emailid" placeholder="Email Address" name="emailid" required="true">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="inputpass" name="inputpass" placeholder="Password" required="true">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="repeatpass" placeholder="Repeat Password" name="repeatpass" required="true">
                                    </div>
                                </div>
                                <button type="submit" name="signup" class="btn btn-primary btn-user btn-block">
                                    Register User
                                </button>
                           
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="index.php">Already Registered? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>