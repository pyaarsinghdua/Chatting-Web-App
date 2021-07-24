<?php

$info = (Object)[];

    $data = false;

    //validate email
    $data['id'] = $_SESSION['id'];
    
    

    //For checking genuine data
    if($Error == "")
    {
        $query = "select * from users where id = :id limit 1";
        // $result = false;
        $result = $DB->read($query,$data);
        if(is_array($result))
        {
            $result = $result[0];
            $result->logged_in = true;
            $result->data_type = "user_info";
            if(file_exists($result->image))
            {
                $check = $result->image;
            }else
            {
                if($result->gender == "Male")
                {
                    $check = "ui/images/user_male.png";
                }else
                {
                    $check = "ui/images/user_female.png";
                }
            }
            $result->image = $check;
            echo json_encode($result);
        }else
        {
            $info->message = "Wrong email";
            $info->data_type = "error";
            echo json_encode($info);
            
        }
    }else
    {
        $info->message = $Error;
        $info->data_type = "error";
        echo json_encode($info);
    }