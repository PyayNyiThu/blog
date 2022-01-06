<?php

// require_once "core/autoload.php";
require_once "inc/header.php";

if(User::auth()) {
    Helper::redirect("index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $user = $user->register($_POST);

    if ($user == "success") {
        Helper::redirect("index.php");
    }
}

// require_once "inc/header.php";

?>

<div class="card card-dark">
    <div class="card-header bg-warning">
        <h3>Register</h3>
    </div>
    <div class="card-body">
        <form action="" method="post">

            <?php
            if (isset($user) && is_array($user)) {
                foreach ($user as $error) {
            ?>

                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>

            <?php
                }
            }
            ?>
            <div class="form-group">
                <label for="" class="text-white">Enter Username</label>
                <input type="name" class="form-control" placeholder="enter username" name="name">
            </div>

            <div class="form-group">
                <label for="" class="text-white">Enter Email</label>
                <input type="email" class="form-control" placeholder="enter email" name="email">
            </div>

            <div class="form-group">
                <label for="" class="text-white">Enter Password</label>
                <input type="password" class="form-control" placeholder="enter password" name="password">
            </div>
            <input type="submit" value="Register" class="btn  btn-outline-warning">
        </form>
    </div>
</div>

<?php

require_once "inc/footer.php";

?>