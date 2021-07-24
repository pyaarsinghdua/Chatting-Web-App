<?php

    if(!isset($DATA_OBJ->find->id)){
        $mydata = "Please select contact to chat with.";

        $info->user = $mydata;
        $info->messages = "";
        $info->data_type = "chat";
        echo json_encode($info);
    }else{
        $arr['id'] = $DATA_OBJ->find->id;
        $sql = "select * from users where id = :id limit 1";
        $result = $DB->read($sql,$arr);
    
        if(is_array($result)){
            //user found
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

            $refresh = false;
            if($DATA_OBJ->data_type == "chats_refresh"){
                $refresh = true;
            }
            
            if(!$refresh){
            $mydata ="   
                Now Chatting with: <br>
                <div id='active_contact' userid='$row->id' onclick='start_chat(event)'>
                <img src='$row->image'> $row->username
                </div>";
            }else{
                $mydata = "";
            }

            //read messages from dataBase
            $arr2['sender'] = $_SESSION['id'];
            $arr2['receiver'] = $DATA_OBJ->find->id;
            $sql = "SELECT * FROM messages WHERE :sender IN (sender,receiver) AND :receiver IN (sender,receiver) ORDER BY date";
            $result_messages = $DB->read($sql,$arr2);
            $message = "";
            if(is_array($result_messages)){
                if(!$refresh){
                    $message = "
                    <div id='message_holder' style='height: 400px; overflow-y:auto;'>";
                }
                foreach ($result_messages as $message1) {
                    # code...
                    if($message1->receiver == $arr2['sender'] && ($message1->seen == 0) ){
                        $DB->write("update messages set received = 1, seen = 1 where id = $message1->id limit 1");
                    }
                    if($message1->sender == $_SESSION['id'])
                    {
                        $message .= message_right($message1,$row1);
                    }else{
                        $message .= message_left($message1,$row);
                    }
                }
            }
            if(!$refresh){
            $message .="
                </div>
                <div style='display:flex; width:100%; margin:2px;'>
                    <label for='message_file' style='width:40px; '>
                        <img src='ui/icons/attach_file.png' style='opacity:0.8; width:40px; cursor:pointer;'>
                    </label>
                        <input id='message_file' onchange='send_file(this.files)' type='file' style='display:none;' />
                        <input id='message_text' onkeyup='enter_pressed(event)' style='flex:6; margin:3px; border:none; box-shadow: 0px 0px 10px #aaa;' type='text' placeholder='Type your message here!' autofocus/>
                        <input id='send_button' style='flex:1; margin:3px; border:none; box-shadow: 0px 0px 10px #aaa; cursor:pointer;' type='submit' value='send'  onclick='send_message(event)' />
                    
                </div>
                ";
            }
    
    
            $info->user = $mydata;
            $info->messages = $message;
            if(!$refresh){
                $info->data_type = "chats";
            }else{
                $info->data_type = "chats_refresh";
            }
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
