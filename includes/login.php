<?php

$info = (Object)[];

    $data = false;

    //validate email
    $data['email'] = $DATA_OBJ->email;
    if(empty($DATA_OBJ->email))
    {
        $Error .= "<br>Please Enter a valid email-id.";
    }else
    {
        if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $DATA_OBJ->email) )
        {
            $Error .= "<br>Please Enter a valid email-id.";
        }
    }

    

    //For checking genuine data
    if($Error == "")
    {
        $query = "select * from users where email = :email limit 1";
        // $result = false;
        $result = $DB->read($query,$data);
        if(is_array($result))
        {
            $result = $result[0];
            if($result->password == $DATA_OBJ->password){
                $_SESSION['id'] = $result->id; 

                $info->message = "You're good to go.";
                $info->data_type = "info";
                echo json_encode($info);
            }else{
                $info->message = "Wrong password";
                $info->data_type = "error";
                echo json_encode($info);

            }
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