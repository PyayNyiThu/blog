<?php

// require_once "core/autoload.php";
require_once "inc/header.php";

// if(User::auth()) {
//     Helper::redirect("index.php");
// }

if (isset($_GET['user'])) {
    $slug = $_GET['user'];
    $user = DB::table('users')->where('slug', $slug)->getOne();

    if (!$user) {
        Helper::redirect("404.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $user = User::update($_POST);
        Helper::redirect("edit.php?user=$slug");
    }
} else {
    Helper::redirect("404.php");
}


require_once "inc/header.php";

?>

<div class="card card-dark">
    <div class="card-header bg-warning">
        <h3>Edit User Profile</h3>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <?php
            if (isset($user) && $user == 'success') {
            ?>
                <div class="alert alert-success">User Profile Update Success!</div>
            <?php
            }
            ?>
            <input type="hidden" name="slug" value="<?php echo $user->slug; ?>">
            <div class="form-group">
                <label for="" class="text-white">Enter Username</label>
                <input type="name" class="form-control" placeholder="enter username" name="name" value="<?php echo $user->name; ?>">
            </div>

            <div class="form-group">
                <label for="" class="text-white">Enter Email</label>
                <input type="email" class="form-control" placeholder="enter email" name="email" value="<?php echo $user->email; ?>">
            </div>

            <div class="form-group">
                <label for="" class="text-white">Enter Password</label>
                <input type="password" class="form-control" placeholder="enter password" name="password">
            </div>

            <div class="form-group">
                <label for="" class="text-white">Choose Image</label>
                <input type="file" class="form-control" name="image">
                <img src="<?php echo $user->image; ?>" style="width:200px;border: radius 20px;" class="mt-3">
            </div>
            <input type="submit" value="Update" class="btn  btn-outline-warning">
        </form>
    </div>
</div>

<?php

require_once "inc/footer.php";

?>