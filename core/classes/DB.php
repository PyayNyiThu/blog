<?php
class DB
{
    private static $data, $res, $sql, $count, $dbh = null;

    public function __construct()
    {
        self::$dbh = new PDO("mysql:host=localhost;dbname=mmc_tech_blog", "root", "");
        self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query($params = [])
    {
        self::$res = self::$dbh->prepare(self::$sql);
        self::$res->execute($params);
        return $this;
    }

    public function get()
    {
        $this->query();
        self::$data = self::$res->fetchAll(PDO::FETCH_OBJ);
        return self::$data;
    }

    public function getOne()
    {
        $this->query();
        self::$data = self::$res->fetch(PDO::FETCH_OBJ);
        return self::$data;
    }

    public function count()
    {
        $this->query();
        self::$count = self::$res->rowCount();
        return self::$count;
    }

    public static function table($table)
    {
        $sql = "SELECT * FROM $table";
        self::$sql = $sql;
        $db = new DB();
        // $db->query();
        return $db;
    }

    public function orderBy($col, $value)
    {
        self::$sql .= " ORDER BY $col $value";
        $this->query();
        return $this;
    }

    public function where($col, $operator, $value = "")
    {
        if (func_num_args() == 2) {
            self::$sql .= " WHERE $col='$operator'";
        } else {
            self::$sql .= " WHERE $col $operator '$value'";
        }
        return $this;
    }

    public function andWhere($col, $operator, $value = "")
    {
        if (func_num_args() == 2) {
            self::$sql .= " AND $col='$operator'";
        } else {
            self::$sql .= " AND $col $operator '$value'";
        }
        return $this;
    }

    public function orWhere($col, $operator, $value = "")
    {
        if (func_num_args() == 2) {
            self::$sql .= " OR $col='$operator'";
        } else {
            self::$sql .= " OR $col $operator '$value'";
        }
        return $this;
    }

    public static function create($table, $data)
    {
        $db = new DB();
        $str_col = implode(',', array_keys($data));
        $v = "";
        $x = 1;
        foreach ($data as $d) {
            $v .= "?";
            if ($x < count($data)) {
                $v .= ",";
                $x++;
            }
        }
        $sql = "INSERT INTO $table($str_col) VALUES($v)";
        self::$sql = $sql;
        $db->query(array_values($data));
        $id = self::$dbh->lastInsertId();
        return DB::table($table)->where('id', $id)->getOne();
    }

    public static function update($table, $data, $id, $name = "")
    {
        $db = new DB();
        $sql = "UPDATE $table SET ";
        $v = "";
        $x = 1;
        foreach ($data  as $k => $d) {
            $v .= "$k = ?";
            if ($x < count($data)) {
                $v .= ",";
                $x++;
            }
        }

        if(func_num_args() == 3) {
            $sql .= "$v WHERE id = $id";
        } else if(func_num_args() == 4) {
            $sql .= "$v WHERE $id = $name";
        }
        
        self::$sql = $sql;
        $db->query(array_values($data));
        return DB::table($table)->where('id', $id)->getOne();
    }

    // public static function delete($table, $id)
    // {
    //     $sql = "DELETE FROM $table WHERE id = $id";
    //     $db = new DB();
    //     self::$sql = $sql;
    //     $db->query();
    //     return true;
    // }

    public static function delete($table, $col_name, $id = "")
    {
        if(func_num_args() == 2) {
            $sql = "DELETE FROM $table WHERE id = $col_name";
        } else {
            $sql = "DELETE FROM $table WHERE $col_name = $id";
        }
        
        $db = new DB();
        self::$sql = $sql;
        $db->query();
        return true;
    }

    public static function raw($sql)
    {
        $db = new DB();
        self::$sql = $sql;
        return $db;
    }

    public function paginate($records_per_page, $append = '')
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }

        if (!isset($_GET['page'])) {
            $page = 1;
        }

        if (isset($_GET['page']) && $_GET['page'] < 1) {
            $page = 1;
        }

        $this->query();
        $count = self::$res->rowCount();

        $index = ($page - 1) * $records_per_page;
        self::$sql .= " limit $index, $records_per_page";
        $this->query();
        self::$data = self::$res->fetchAll(PDO::FETCH_OBJ);
        // $prev_no = $page - 1;
        // $next_no = $page +1;
        // $prev_page = "?page=".$prev_no;
        // $next_page = "?page=".$next_no;

        $prev_page = $page - 1;
        $next_page = $page + 1;

        // $data = [
        //     "data" => self::$data,
        //     "total" => $count,
        //     "prev_url" => $prev_page & $append,
        //     "next_url" => $next_page & $append,
        // ];

        $data = [
            "data" => self::$data,
            "total" => $count,
            "prev_url" => "?page=$prev_page&$append",
            "next_url" => "?page=$next_page&$append",
        ];
        return $data;
    }
}

// $db = new DB();
// $user = DB::table("product")->where('name', 'a')->orWhere('sale_price', 120)->get();
// echo "<pre>";
// var_dump($user);
// $user = DB::create('users', [
//     "slug" => "00129303i-dkdk-banana",
//     "name" => "Banana",
//     "email" => "banana@gmail.com",
// ]);

// print_r($user);

// $user = DB::update('users', [
//     "slug" => "00129303i-dkdk-banana",
//     "name" => "Banana",
//     "email" => "banana@gmail.com",
// ], 3);

// print_r($user);

// $user = DB::delete('users', 'email', 5);

// print_r($user);

// $user = DB::table('product')->paginate(3);
// echo "<pre>";
// print_r($user);

// $user = DB::raw("SELECT * FROM product")->paginate(3);
// echo "<pre>";
// print_r($user);