<?php

class Database
{
    private $con;

    function __construct()
    {
        $this->con = $this->connect();
    }
    //to connect to database
    private function connect()
    {
        $string = "mysql:host=localhost;dbname=mychat_db";
        try
        {
            $connection = new PDO($string, DBUSER, DBPASS);
            return $connection;

        }catch(PDOException $e)
        {
            echo $e->getMessage();
            die;
        }

        return false;
    }

    //write to database
    public function write($query,$data_array = [])
    {
        $con = $this->connect();

        $statement = $con->prepare($query); //slect * from users

        // foreach ($data_array as $key => $value)
        // {
        //     $statement->bindparam(':'.$key,$value);
        // }
        $check = $statement->execute($data_array);

        // echo $check;

        if($check)
        {
            return true;
        }
        return false;
    }

    public function generate_id($max)
    {
        $rand = "";
        $rand_count = rand(4,$max);
        for ($i=0; $i < $rand_count; $i++) { 
            $r = rand(0,9);
            $rand .= $r;
        }

        return $rand;

    }

    //read from database
    public function read($query,$data_array = [])
    {
        $con = $this->connect();

        $statement = $con->prepare($query); //slect * from users

        // foreach ($data_array as $key => $value)
        // {
        //     $statement->bindparam(':'.$key,$value);
        // }
        $check = $statement->execute($data_array);

        //we need to resturn as a object
        if($check)
        {
            $result = $statement->fetchAll(PDO::FETCH_OBJ); //similar to PDO->FETCH_OBJ
            if(is_array($result) && count($result) > 0)
            {
                return $result;
            }
            return false;
        }
        return false;
    }
}