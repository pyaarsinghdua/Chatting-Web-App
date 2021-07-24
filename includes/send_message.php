<?php

    if(!isset($DATA_OBJ->find->id)){
        $mydata = "Please select contact to chat with.";

        $info->user = $mydata;
        $info->messages = "Please select contact to chat with.";
        $info->data_type = "send_message";
        echo json_encode($info);
    }else{
        $arr['id'] = $_SESSION['id'];
        $sql = "select * from users where id = :id limit 1";
        $result = $DB->read($sql,$arr);

        
        if(is_array($result)){
            //user found
            $arr2['sender'] = $_SESSION['id'];
            $arr2['receiver'] = $DATA_OBJ->find->id;
            $arr2['message'] = $DATA_OBJ->find->message;
            $arr2['date'] = date("Y-m-d H:i:s");

            $sql = "INSERT INTO messages (sender,receiver,message,date) values (:sender, :receiver, :message, :date)";
            $check = $DB->write($sql,$arr2);
            if(! $check){
                $message = "Error at the database level";
                $info->messages = $message;
                $info->data_type = "send_message";
                echo json_encode($info);
                die;
            }else{
                // $info->user = $mydata;
                $info->messages = $DATA_OBJ->find->message;
                $info->data_type = "send_message";
                echo json_encode($info);
                die;
            }
            $row = $result[0];
            if(file_exists($row->image))
            {
                $check = $row->image;
            }else
            {
                if($row->gender == "Male")
                {
                    $check = "ui/images/user_male.png";
                }else
                {
                    $check = "ui/images/user_female.png";
                }
            }
            $row->image = $check;

            $current_user['id'] = $_SESSION['id'];
            $sql = "select * from users where id = :id limit 1";
            $result = $DB->read($sql,$current_user);

            $row1 = $result[0];
            if(file_exists($row1->image))
            {
                $check = $row1->image;
            }else
            {
                if($row1->gender == "Male")
                {
                    $check = "ui/images/user_male.png";
                }else
                {
                    $check = "ui/images/user_female.png";
                }
            }
            $row1->image = $check;

            
    
            $mydata ="   
            Now Chatting with: <br>
            <div id='active_contact' userid='$row->id' onclick='start_chat(event)'>
                <img src='$row->image'> $row->username
            </div>";

            $message = "
            <div id='message_holder' style='height: 90%; overflow-y:scroll;'>";
                
            $for_message['message'] = "Hello man";
            $message .= message_left($row,$for_message);
            $message .="
            </div>
            <div style='display:flex; width:100%; margin:2px;'>
                <label for='file' style='width:40px; '>
                    <img src='ui/icons/attach_file.png' style='opacity:0.8; width:40px; cursor:pointer;'>
                    <input id='message_file' type='file' style='display:none;' />
                </label>
                    <input id='message_text' style='flex:6; margin:3px; border:none; box-shadow: 0px 0px 10px #aaa;' type='text' placeholder='Type your message here!' />
                    <input style='flex:1; margin:3px; border:none; box-shadow: 0px 0px 10px #aaa; cursor:pointer;' type='submit' value='send'  onclick='send_message(event)' />
                
            </div>
            ";
    
    
            $info->user = $mydata;
            $info->messages = $message;
            $info->data_type = "send_message";
            echo json_encode($info);
        }else{
            //user not found
            $info->message = "That Contact not found";
            $info->data_type = "error";
            echo json_encode($info);
        }
        
    }

    // $result = $result[0];
    // $result->logged_in = true;


?>
