<?php

require_once("classes/autoload.php");
$DB = new Database();

session_start();    //for declaring global variable session
$info = (Object)[];
//check if logged in

/*
$_FILES variable

Array(
    [file] => Array(
        [name] => 123124.jpg
        [type] => image/jpg
        [tmp_name] => local location of file
        [error] => 0
        [size] => 223423
    )
)

$_POST variable

Array(
    [data_type] => change_profile_image
)

*/

$destination = "";
if(isset($_FILES['file']) && $_FILES['file']['name'] != "" && $_FILES['file']['error'] == 0){
    print_r($_FILES);
    if(true){
        
        //good to upload file
        $folder = "files/";
        if(!file_exists($folder)){
            mkdir($folder,0777,true);
        }

        $destination = $folder . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'],$destination);

    }else{
        echo "Only JPG or PNG format accepted";
    }

}

$data_type = "";
if(isset($_POST['data_type']) && $_POST['data_type'] == "send_file")
{
    $data_type = $_POST['data_type'];

    
    if($destination != ""){
        //save to DataBase
        $arr2['sender'] = $_SESSION['id'];
        $arr2['receiver'] = $_POST['current_chat_user'];
        $arr2['files'] = $destination;
        $arr2['date'] = date("Y-m-d H:i:s");
    
        $sql = "INSERT INTO messages (sender,receiver,files,date) values (:sender, :receiver, :files, :date)";
        $DB->write($sql,$arr2);

        $info->message = "file sent successfully";
        $info->data_type = "send_file";
        echo json_encode($info);
    }else {
        $info->message = "file not sent";
        $info->data_type = "send_file";
        echo json_encode($info);
    }
}




