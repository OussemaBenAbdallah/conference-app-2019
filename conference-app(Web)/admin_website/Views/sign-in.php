<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>

    <title>Sign In</title>

</head>

<body class="container py-3">
<article class="row">
    <section class="mx-auto col-sm-10">
        <div class="card my-2">
            <!-- form header -->
            <div class="card-header">
                <h4 class="mb-0">Add or edit a conference</h4>
            </div>
            <!-- form body -->
            <div class="card-body">
                <form class="form" action="../API/login.php" method="get">
                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">login </label>
                        <input class="form-control col-lg-8" type="email" name="email_admin" id="email_admin" placeholder="Email"
                        value="<?php echo !empty($_GET['email_admin'])?htmlspecialchars($_GET['email_admin']):'';?>">
                    </div>
                    <div class="form-group row mx-1">
                        <label for="pwd" class="col-lg-4 col-form-label form-control-label">Password</label>
                        <input class="form-control col-lg-8" type="password" name="pwd" id="pwd">
                    </div>
                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">conference </label>
                        <input class="form-control col-lg-8" type="text" name="short_name_conf" id="short_name_conf"
                               placeholder="ICTA">
                    </div>
                    <div class="row mt-5 ">
                        <div class="col-sm-10 ml-auto mr-n5">
                            <input type="reset" class="btn btn-secondary btn-lg w-25 mx-4 " value="Cancel">
                            <input type="submit" class="btn btn-primary btn-lg w-25 mx-4" value="Sign in">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /form user info -->
    </section>
</article>
</body>

</html>