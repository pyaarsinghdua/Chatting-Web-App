<?php

$info = (Object)[];

    $data = false;
    $data['userid'] = $DB->generate_id(20);
    $data['date'] = date("Y-m-d H:i:s");

    //validate username
    $data['username'] = $DATA_OBJ->username;
    if(empty($DATA_OBJ->username))
    {
        $Error .= "<br>Please Enter a valid username.";
    }else
    {
        if(strlen($DATA_OBJ->username) < 3)
        {
            $Error .= "<br> username must be atleast 3 character.";
        }
        if(!preg_match("/^[a-z A-Z]*$/", $DATA_OBJ->username) )
        {
            $Error .= "<br> Username should contain only alphabets.";
        }
    }

    if(empty($DATA_OBJ->gender))
    {
        $Error .= "<br>Please select gender.";
    }else
    {
        $data['gender'] = $DATA_OBJ->gender;
        if($data['gender'] == "Male"){
            $data['image'] = "ui/images/user_male.png";
        }else{
            $data['image'] = "ui/images/user_female.png";
        }
    }

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

    //Validate Password
    $data['password'] = $DATA_OBJ->password;
    $password = $DATA_OBJ->password1;
    if(empty($DATA_OBJ->password))
    {
        $Error .= "<br>Please Enter a valid password.";
    }else
    {
        if($DATA_OBJ->password != $password)
        {
            $Error .= "<br> Password doesn't match.";
        }
        if(strlen($DATA_OBJ->password) < 8)
        {
            $Error .= "<br> Password must be atleast 8 letters.";
        }
    }

    

    //For checking genuine data
    if($Error == "")
    {
        $query = "insert into users (userid,username,email,gender,password,date,image) values (:userid,:username,:email,:gender,:password,:date,:image)";
        // $result = false;
        $result = $DB->write($query,$data);
        if($result)
        {
            $info->message = "Your Profile created";
            $info->data_type = "info";
            echo json_encode($info);
        }else
        {
            $info->message = "Sorry! Profile not created";
            $info->data_type = "error";
            echo json_encode($info);
            
        }
    }else
    {
        $info->message = $Error;
        $info->data_type = "error";
        echo json_encode($info);
    }