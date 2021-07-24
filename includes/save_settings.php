<?php

$info = (Object)[];

    $data = false;
    $data['id'] = $_SESSION['id'];
    // $data['date'] = date("Y-m-d H:i:s");

    //validate username
    $data['username'] = $DATA_OBJ->username;
    if(empty($DATA_OBJ->username))
    {
        $Error .= " Please Enter a valid username.";
    }else
    {
        if(strlen($DATA_OBJ->username) < 3)
        {
            $Error .= " username must be atleast 3 character.";
        }
        if(!preg_match("/^[a-z A-Z]*$/", $DATA_OBJ->username) )
        {
            $Error .= " Username should contain only alphabets.";
        }
    }

    if(empty($DATA_OBJ->gender))
    {
        $Error .= " Please select gender.";
    }else
    {
        $data['gender'] = $DATA_OBJ->gender;
    }

    //validate email
    $data['email'] = $DATA_OBJ->email;
    if(empty($DATA_OBJ->email))
    {
        $Error .= " Please Enter a valid email-id.";
    }else
    {
        if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $DATA_OBJ->email) )
        {
            $Error .= " Please Enter a valid email-id.";
        }
    }

    //Validate Password
    $data['password'] = $DATA_OBJ->password;
    $password = $DATA_OBJ->password1;
    if(empty($DATA_OBJ->password))
    {
        $Error .= " Please Enter a valid password.";
    }else
    {
        if($DATA_OBJ->password != $password)
        {
            $Error .= " Password doesn't match.";
        }
        if(strlen($DATA_OBJ->password) < 8)
        {
            $Error .= " Password must be atleast 8 letters.";
        }
    }

    

    //For checking genuine data
    if($Error == "")
    {
        $query = "UPDATE users SET username=:username, email=:email, gender=:gender, password=:password WHERE id=:id";
        // $result = false;
        $result = $DB->write($query,$data);
        if($result)
        {
            $info->message = "Your Profile updated";
            $info->data_type = "save_settings";
            echo json_encode($info);
        }else
        {
            $info->message = "Sorry! Profile not updated";
            $info->data_type = "save_settings";
            echo json_encode($info);
            
        }
    }else
    {
        $info->message = $Error;
        $info->data_type = "save_settings";
        echo json_encode($info);
    }