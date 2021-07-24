<?php

require_once("classes/autoload.php");
$DB = new Database();

$DATA_RAW = file_get_contents("php://input");
$DATA_OBJ = json_decode($DATA_RAW);

session_start();    //for declaring global variable session
$info = (Object)[];
//check if logged in
if(!isset($_SESSION['id']))
{
    if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type != "signup")
    {
        $info->logged_in = false;
        echo json_encode($info);
        die;
    }
}


//funtion for getting chats blocks
function message_left($for_message,$for_image)
{
    if($for_message->files != ""){
        return "
        <div id='message_left'>
            <div></div>
                <img src='$for_image->image'> <a href='$for_message->files' download > Download File </a>
                <br><span style='font-size:8px; color:yellow;'>".date("jS M Y H:i:s a",strtotime($for_message->date))."</span>
        </div>
        ";
    }
    return "
    <div id='message_left'>
        <div></div>
            <img src='$for_image->image'> $for_message->message
            <br><span style='font-size:8px; color:yellow;'>".date("jS M Y H:i:s a",strtotime($for_message->date))."</span>
    </div>
    ";
}

function message_right($for_message,$for_image)
{
    $status = "sent";
    if($for_message->seen == 1){
        $status = "seen";
    }else if($for_message->received == 1){
        $status = "delivered";
    }

    if($for_message->files != ""){
        return "
        <div id='message_right'>
            <div></div>
                <img src='$for_image->image'> <a href='$for_message->files' download > Download File </a>
                <br><span style='font-size:8px; color:yellow;'>".date("jS M Y H:i:s a",strtotime($for_message->date))."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;".$status."</span>
        </div>
        ";
    }
    return "
    <div id='message_right'>
        <div></div>
            <img src='$for_image->image'> $for_message->message
            <br><span style='font-size:8px; color:yellow;'>".date("jS M Y H:i:s a",strtotime($for_message->date))."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;".$status."</span>
    </div>
    ";
}

$Error = "";
//Process the data
if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "signup")
{
    //signup
    include("includes/signup.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "user_info")
{
    //user info
    include("includes/user_info.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "login")
{
    //login
    include("includes/login.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "logout")
{
    //logout
    include("includes/logout.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "contacts")
{
    //contacts
    include("includes/contacts.php");

}else if(isset($DATA_OBJ->data_type) && ($DATA_OBJ->data_type == "chats" || $DATA_OBJ->data_type == "chats_refresh") )
{
    //chat
    include("includes/chats.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "settings")
{
    //settings
    include("includes/settings.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "save_settings")
{
    //update account
    include("includes/save_settings.php");

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "send_message")
{
    //chatting send message
    include("includes/send_message.php");
    

}else if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "first_login")
{
    //received to 1
    $temp = $_SESSION['id'];
    $DB->write("update messages set received = 1 where receiver = $temp");    

}


