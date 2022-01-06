<?php

require_once "inc/header.php";

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    $article = DB::table('articles')->where('slug', $slug)->getOne();

    if (!$article) {
        Helper::redirect("404.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $article = Post::update($_POST);
        // print_r($article);
        // die();
        // Helper::redirect("edit_article.php?slug=$slug");
        Helper::redirect('index.php');
    }
} else {
    Helper::redirect("404.php");
}

?>

<div class="card card-dark">
    <div class="card-header">
        <h3>Edit Article</h3>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="slug" value="<?php echo $article->slug; ?>">
            <?php
            if (isset($article) && $article == 'success') {
            ?>
                <div class="alert alert-success">Article Update Success!</div>
            <?php
            }
            ?>
            <div class="form-group">
                <label for="" class="text-white">Enter Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter Title" value="<?php echo $article->title; ?>">
            </div>
            <div class="form-group">
                <label for="" class="text-white">Choose Category</label>
                <select name="category_id" id="" class="form-control">
                    <?php
                    $categories = DB::table("category")->get();
                    foreach ($categories as $category) {
                        $selected = $category->id == $article->category_id ? "selected" : "";
                    ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo $category->name; ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
            <div class="form-check form-check-inline">
                <?php
                $languages = DB::table("languages")->get();
                $language_id = DB::table("article_language")->where('article_id', $article->id)->get();
                $data = [];

                foreach ($languages as $key => $language) {
                    $checked = "";
                    if (!empty($language_id)) {
                        foreach ($language_id as $l) {
                            // $data[] = $l->language_id;
                            if ($l->language_id == $language->id) {
                                $checked = "checked";
                            }
                        }
                    }

                ?>
                    <span class="mr-2">
                        <input class="form-check-input" id="inlineCheckbox<?php echo $key; ?>" type="checkbox" name="language_id[]" value="<?php echo $language->id; ?>" <?php echo $checked; ?>>
                        <label class="form-check-label" for="inlineCheckbox<?php echo $key; ?>"><?php echo $language->name; ?></label>
                    </span>
                <?php
                }
                ?>

            </div>
            <br><br>
            <div class="form-group">
                <label for="">Choose Image</label>
                <input type="file" class="form-control" name="image">
                <img src="<?php echo $article->image; ?>" style="width:200px;border: radius 20px;" class="mt-3">
                <hr>
            </div>
            <div class="form-group">
                <label for="" class="text-white">Enter Articles Description</label>
                <textarea name="description" class="form-control" id="" cols="30" rows="10"><?php echo $article->description; ?></textarea>
            </div>
            <input type="submit" value="Update" class="btn  btn-outline-warning">
        </form>
    </div>
</div>

<?php

require_once "inc/footer.php";

?>