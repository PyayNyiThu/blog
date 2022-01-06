<?php

require_once 'inc/header.php';

if (isset($_GET['delete'])) {
    $slug = $_GET['slug'];
    $post = Post::delete($slug);
    Helper::redirect("index.php");
}

if (isset($_GET['category'])) {
    $slug = $_GET['category'];
    $post = Post::articleByCategory($slug);
} else if (isset($_GET['language'])) {
    $slug = $_GET['language'];
    $post = Post::articleByLanguage($slug);
} else if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $post = Post::search($search);
} else if (isset($_GET['your_post'])) {
    $slug = $_GET['your_post'];
    $post = Post::yourPost($slug);
} else {
    $post = Post::all();
}

?>
<div class="card card-dark mb-3">
    <div class="card-body">
        <a href="<?php echo $post['prev_url']; ?>" class="btn btn-danger">Prev Posts</a>
        <a href="<?php echo $post['next_url']; ?>" class="btn btn-danger float-right">Next Posts</a>
    </div>
</div>
<div class="card card-dark">
    <div class="card-body">
        <div class="row">
            <!-- Loop this -->
            <?php
            foreach ($post['data'] as $article) {
            ?>
                <div class="col-md-4 p-1">
                    <div class="card">
                        <img class="card-img-top" src="<?php echo $article->image; ?>" alt="Card image cap" width="220px" height="200px">
                        <div class="card-body">
                            <h5 class="text-dark"><?php echo $article->title; ?></h5>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?php
                                    $user_id = User::auth() ? User::auth()->id : 0;
                                    ?>
                                    <i class="fas fa-heart text-warning like" data-user_id="<?php echo $user_id; ?>" data-article_id="<?php echo $article->id; ?>" id="like"></i>
                                    <small class="text-muted" id="like_count_<?php echo $article->id ?>"><?php echo $article->like_count ?></small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="far fa-comment text-dark"></i>
                                    <small class="text-muted"><?php echo $article->comment_count; ?></small>
                                </div>
                                <div class="col-md-6 text-center">
                                    <a href="<?php echo "detail.php?slug=" . $article->slug; ?>" class="btn btn-success p-1"><span class="fa fa-eye"></span></a>
                                    <?php
                                    if ($article->user_id == $user_id) {
                                    ?>
                                        <a href="<?php echo "edit_article.php?slug=" . $article->slug; ?>" class="btn btn-primary p-1"><span class="fa fa-edit"></span></a>
                                        <a href="<?php echo "index.php?slug=" . $article->slug; ?>&delete=true" class="btn btn-danger p-1" onclick="return confirm('Are you sure to delete!');"><span class="fa fa-trash"></span></a>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
    require_once 'inc/footer.php';
    ?>

    <script>
        $(document).on("click", "#like", function(e) {
            e.preventDefault();
            var user_id = $(this).data('user_id');
            var article_id = $(this).data('article_id');

            if (user_id == 0) {
                location.href = "login.php";
            } else {
                axios.get(`api.php?like&user_id=${user_id}&article_id=${article_id}`)
                    .then(function(res) {
                        if (res.data == 'already_like') {
                            toastr.warning('Already Liked!');
                        }
                        if (Number.isInteger(res.data)) {
                            $('#like_count_' + article_id).empty().append(res.data);
                            toastr.success('Liked Success!');
                        }
                    });
            }
        })
    </script>