<?php

class User
{
    public static function auth()
    {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            return DB::table('users')->where('id', $user_id)->getOne();
        } else {
            return false;
        }
    }

    public function login($request)
    {
        $errors = [];
        $email = Helper::filter($request['email']);
        $password = $request['password'];

        $user = DB::table("users")->where('email', $email)->getOne();

        if ($user) {
            $db_password = $user->password;

            if (password_verify($password, $db_password)) {
                $_SESSION['user_id'] = $user->id;
                return "success";
            } else {
                $errors[] = "Wrong Password!";
            }
        } else {
            $errors[] = "Email Not Found!";
        }
        return $errors;
    }

    public function register($request)
    {
        $errors = [];

        if (isset($request)) {
            if (empty($request['name'])) {
                $errors[] = "Name Field is required!";
            }

            if (empty($request['email'])) {
                $errors[] = "Email Field is required!";
            }

            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid Email Format!";
            }

            $user = DB::table('users')->where('email', $request['email'])->getOne();
            if ($user) {
                $errors[] = "Email Already Exists!";
            }

            if (empty($request['password'])) {
                $errors[] = "Password Field is required!";
            }

            if (count($errors)) {
                return $errors;
            } else {
                $user = DB::create('users', [
                    'name' => Helper::filter($request['name']),
                    'slug' => Helper::slug($request['name']),
                    'email' => Helper::filter($request['email']),
                    'password' => password_hash($request['password'], PASSWORD_BCRYPT),
                ]);

                $_SESSION['user_id'] = $user->id;

                return "success";
            }
        }
    }

    public static function update($request) {
        $user = DB::table('users')->where('slug', $request['slug'])->getOne();

        if($request['password']) {
            $password = password_hash($request['password'], PASSWORD_BCRYPT);
        } else {
            $password = $user->password;
        }

        if (($_FILES['image']['tmp_name']) != "") {
            $image = $_FILES['image'];
            $image_name = $image['name'];
            $path = "assets/user/$image_name";
            $tmp_name = $image['tmp_name'];
            move_uploaded_file($tmp_name, $path);
        } else {
            $path = $user->image;
        }

        DB::update('users', [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $password,
            'image' => $path,
        ], $user->id);

        return "success";
    }
}
