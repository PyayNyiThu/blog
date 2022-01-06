<?php

require_once "inc/header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article = Post::create($_POST);
}

?>

<div class="card card-dark">
    <div class="card-header">
        <h3>Create New Article</h3>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($article) && $article == 'success') {
            ?>
                <div class="alert alert-success">Article Created Success!</div>
            <?php
            }
            ?>
            <div class="form-group">
                <label for="" class="text-white">Enter Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter Title">
            </div>
            <div class="form-group">
                <label for="" class="text-white">Choose Category</label>
                <select name="category_id" id="" class="form-control">
                    <?php
                    $categories = DB::table("category")->get();
                    foreach ($categories as $category) {
                    ?>
                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
            <div class="form-check form-check-inline">
                <?php
                $languages = DB::table("languages")->get();
                foreach ($languages as $language) {
                ?>
                    <span class="mr-2">
                        <input class="form-check-input" type="checkbox" name="language_id[]" value="<?php echo $language->id; ?>">
                        <label class="form-check-label" for="inlineCheckbox1"><?php echo $language->name; ?></label>
                    </span>
                <?php
                }
                ?>

            </div>
            <br><br>
            <div class="form-group">
                <label for="">Choose Image</label>
                <input type="file" class="form-control" name="image">
            </div>
            <div class="form-group">
                <label for="" class="text-white">Enter Articles Description</label>
                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
            </div>
            <input type="submit" value="Create" class="btn  btn-outline-warning">
        </form>
    </div>
</div>

<?php

require_once "inc/footer.php";

?>