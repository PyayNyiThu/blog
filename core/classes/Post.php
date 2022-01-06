<?php

class Post
{
    public static function all()
    {
        $data = DB::table('articles')->orderBy('id', 'DESC')->paginate(3);
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->comment_count = DB::table('article_comments')->where('article_id', $value->id)->count();
            $data['data'][$key]->like_count = DB::table('article_likes')->where('article_id', $value->id)->count();
        }
        return $data;
    }

    public static function detail($slug)
    {
        $data = DB::table('articles')->where('slug', $slug)->getOne();

        // try to get language
        $data->languages = DB::raw("SELECT languages.id, languages.slug, languages.name FROM article_language
        LEFT JOIN languages
        ON article_language.language_id = languages.id 
        WHERE article_id = {$data->id}")->get();

        // try to get comments
        $data->comments = DB::table('article_comments')->where('article_id', $data->id)->orderBy('id', 'DESC')->get();

        // try to get category
        $data->category = DB::table('category')->where('id', $data->category_id)->getOne();

        $data->comment_count = DB::table('article_comments')->where('article_id', $data->id)->count();
        $data->like_count = DB::table('article_likes')->where('article_id', $data->id)->count();

        return $data;
    }

    public static function articleByCategory($slug)
    {
        $category_id = DB::table('category')->where('slug', $slug)->getOne()->id;
        $data = DB::table('articles')->where('category_id', $category_id)->orderBy('id', 'DESC')->paginate(3, "category=$slug");
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->comment_count = DB::table('article_comments')->where('article_id', $value->id)->count();
            $data['data'][$key]->like_count = DB::table('article_likes')->where('article_id', $value->id)->count();
        }
        return $data;
    }

    public static function articleByLanguage($slug)
    {
        $language_id = DB::table('languages')->where('slug', $slug)->getOne()->id;

        $data = DB::raw("SELECT * FROM article_language
        LEFT JOIN articles
        ON article_language.article_id=articles.id
        WHERE article_language.language_id={$language_id}")
            ->orderBy('articles.id', 'DESC')->paginate(3, "language=$slug");
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->comment_count = DB::table('article_comments')->where('article_id', $value->id)->count();
            $data['data'][$key]->like_count = DB::table('article_likes')->where('article_id', $value->id)->count();
        }
        return $data;
    }

    public static function create($request)
    {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $path = "assets/article/$image_name";
        $tmp_name = $image['tmp_name'];

        if (move_uploaded_file($tmp_name, $path)) {
            $article = DB::create('articles', [
                'user_id' => User::auth()->id,
                'category_id' => $request['category_id'],
                'slug' => Helper::slug($request['title']),
                'title' => $request['title'],
                'image' => $path,
                'description' => $request['description'],
            ]);

            if ($article) {
                foreach ($request['language_id'] as $language_id) {
                    DB::create('article_language', [
                        'article_id' => $article->id,
                        'language_id' => $language_id,
                    ]);
                }
                return "success";
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function search($search)
    {
        $data = DB::table('articles')->where('title', 'like', "%$search%")->orderBy('id', 'DESC')->paginate(3, "search=$search");
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->comment_count = DB::table('article_comments')->where('article_id', $value->id)->count();
            $data['data'][$key]->like_count = DB::table('article_likes')->where('article_id', $value->id)->count();
        }
        return $data;
    }

    public static function yourPost($slug)
    {
        $user_id = DB::table('users')->where('slug', $slug)->getOne()->id;
        $data = DB::table('articles')->where('user_id', $user_id)->orderBy('id', 'DESC')->paginate(3, "your_post=$slug");
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->comment_count = DB::table('article_comments')->where('article_id', $value->id)->count();
            $data['data'][$key]->like_count = DB::table('article_likes')->where('article_id', $value->id)->count();
        }
        return $data;
    }

    public static function delete($slug)
    {
        $id = DB::table('articles')->where('slug', $slug)->getOne()->id;
        DB::delete('article_comments', 'article_id', $id);
        DB::delete('article_likes', 'article_id', $id);
        DB::delete('article_language', 'article_id', $id);

        $data = DB::delete('articles', $id);

        return $data;
    }

    public static function update($request)
    {
        $article = DB::table('articles')->where('slug', $request['slug'])->getOne();

        if (($_FILES['image']['tmp_name']) != "") {
            $image = $_FILES['image'];
            $image_name = $image['name'];
            $path = "assets/article/$image_name";
            $tmp_name = $image['tmp_name'];
            move_uploaded_file($tmp_name, $path);
        } else {
            $path = $article->image;
        }

        $article_language = DB::update('articles', [
            'user_id' => $article->user_id,
            'category_id' => $request['category_id'],
            'slug' => Helper::slug($request['title']),
            'title' => $request['title'],
            'image' => $path,
            'description' => $request['description'],
        ], $article->id);

        if ($article_language) {
            if (count($request['language_id']) >= 2) {
                DB::delete('article_language', 'article_id', $article_language->id);
                foreach ($request['language_id'] as $language_id) {
                    DB::create('article_language', [
                        'article_id' => $article_language->id,
                        'language_id' => $language_id,
                    ]);
                }
                return "success";
            } else if (count($request['language_id']) == 1) {
                $language = DB::table('article_language')->where('article_id', $article_language->id)->get();
                if (count($language) == 1) {
                    foreach ($request['language_id'] as $language_id) {
                        DB::update('article_language', [
                            'article_id' => $article_language->id,
                            'language_id' => $language_id,
                        ], 'article_id', $article_language->id);
                    }
                    return "success";
                } else if (count($language) == 0) {
                    if (count($request['language_id']) == 0) {
                        DB::delete('article_language', 'article_id', $article_language->id);
                        return "success";
                    } else if (count($request['language_id']) > 0) {
                        DB::delete('article_language', 'article_id', $article_language->id);
                        foreach ($request['language_id'] as $language_id) {
                            DB::create('article_language', [
                                'article_id' => $article_language->id,
                                'language_id' => $language_id,
                            ]);
                        }
                        return "success";
                    }
                } else {
                    DB::delete('article_language', 'article_id', $article_language->id);
                    foreach ($request['language_id'] as $language_id) {
                        DB::create('article_language', [
                            'article_id' => $article_language->id,
                            'language_id' => $language_id,
                        ]);
                    }
                    return "success";
                }
            } else if (empty($request['language_id'])) {
                DB::delete('article_language', 'article_id', $article_language->id);
                return "success";
            }
        } else {
            return false;
        }
    }
}
