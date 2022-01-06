<?php

require_once 'inc/header.php';

if (!isset($_GET['slug'])) {
    Helper::redirect('404.php');
} else {
    $slug = $_GET['slug'];

    $article = Post::detail($slug);

}

?>

<div class="card card-dark">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-dark">
                    <div class="card-body">
                        <div class="row">
                            <!-- icons -->
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <?php
                                        $user_id = User::auth() ? User::auth()->id : 0;
                                        $article_id = $article->id;
                                        ?>
                                        <i class="fas fa-heart text-warning like" user_id="<?php echo $user_id; ?>" article_id="<?php echo $article_id; ?>">
                                        </i>
                                        <small class="text-muted like_count"><?php echo $article->like_count; ?></small>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <i class="far fa-comment text-dark"></i>
                                        <small class="text-muted"><?php echo $article->comment_count; ?></small>
                                    </div>

                                </div>
                            </div>
                            <!-- Icons -->

                            <!-- Category -->
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="badge badge-primary"><?php echo $article->category->name; ?></a>

                                    </div>
                                </div>
                            </div>
                            <!-- Category -->


                            <!-- languages -->
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        foreach ($article->languages as $language) {
                                        ?>
                                            <a href="" class="badge badge-success"><?php echo $language->name; ?>
                                            </a>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <!-- languages -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="col-md-12">
            <h3><?php echo $article->title; ?></h3>
            <p>
                <?php echo $article->description; ?>
            </p>
        </div>

        <div class="card card-dark mb-3">
            <div class="card-body">
                <form method="post" action="" id="frmcmt">
                    <input type="text" id="comment" class="form-control" placeholder="Enter Comment"><br>
                    <input type="submit" value="Create" class="float-right btn btn-outline-warning">
                </form>
            </div>
        </div>

        <!-- Comments -->
        <div class="card card-dark">
            <div class="card-header">
                <h4>Comments</h4>
            </div>
            <div class="card-body">
                <!-- Loop Comment -->
                <div id="comment_list">

                    <?php
                    foreach ($article->comments as $comment) {
                    ?>
                        <div class="card-dark mt-1">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-1">
                                        <img src="<?php echo DB::table('users')->where('id', $comment->user_id)->getOne()->image; ?>" style="width:50px;border-radius:50%" alt="">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <?php echo DB::table('users')->where('id', $comment->user_id)->getOne()->name; ?>
                                    </div>
                                </div>
                                <hr>
                                <p><?php echo $comment->comment; ?></p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>
</div>

<?php

require_once 'inc/footer.php';

?>

<script>
    // comment
    var frmcmt = document.getElementById("frmcmt");

    frmcmt.addEventListener("submit", function(e) {
        e.preventDefault();
        var data = new FormData();
        data.append('comment', document.getElementById('comment').value);
        data.append('article_id', <?php echo $article->id; ?>);

        axios.post('api.php', data)
            .then(function(res) {
                document.getElementById('comment_list').innerHTML = res.data;
                document.getElementById('comment').value = "";
            });
    })


    // like
    var like = document.querySelector(".like");
    var like_count = document.querySelector(".like_count");

    like.addEventListener("click", function() {
        var user_id = like.getAttribute("user_id");
        var article_id = like.getAttribute("article_id");

        if (user_id == 0) {
            location.href = "login.php";
        } else {
            axios.get(`api.php?like&user_id=${user_id}&article_id=${article_id}`)
                .then(function(res) {
                    if (res.data == 'already_like') {
                        toastr.warning('Already Liked!');
                    }
                    if (Number.isInteger(res.data)) {
                        like_count.innerHTML = res.data;
                        toastr.success('Liked Success!');
                    }
                });
        }


    })
</script>