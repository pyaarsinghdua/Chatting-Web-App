<?php

require_once("classes/autoload.php");
$DB = new Database();

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
    if($_FILES['file']['type'] == "image/jpg" || $_FILES['file']['type'] == "image/png" || $_FILES['file']['type'] == "image/jpeg"){
        
        //googd to upload file
        $folder = "uploads/";
        if(!file_exists($folder)){
            mkdir($folder,0777,true);
        }

        $destination = $folder . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'],$destination);

        $info->message = "Image uploaded successfully";
        $info->data_type = "change_profile_image";
        echo json_encode($info);

    }else{
        echo "Only JPG or PNG format accepted";
    }

}

$data_type = "";
if(isset($_POST['data_type']) && $_POST['data_type'] == "change_profile_image")
{
    $data_type = $_POST['data_type'];

    if($destination != ""){

        //save to DataBase
        $data = false;
        $data['image'] = $destination;
        $data['id'] = $_SESSION['id'];
        $query = "UPDATE users SET image=:image WHERE id=:id";
        $DB->write($query,$data);
    }
}




