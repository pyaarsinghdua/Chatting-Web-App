<?php

    $id = $_SESSION['id'];
    $sql = "select * from users where id = :id";
    $data = $DB->read($sql,['id'=>$id]);

    $mydata = "Invalid Data from the server";

    if(is_array($data)){
        $data = $data[0];

        //image
        if(file_exists($data->image))
        {
            $check = $data->image;
        }else
        {
            if($data->gender == "Male")
            {
                $check = "ui/images/user_male.png";
            }else
            {
                $check = "ui/images/user_female.png";
            }
        }

        //gender
        $gender_female = "";
        $gender_male = "";
        if($data->gender == "Male")
        {
            $gender_male = "checked";
        }else{
            $gender_female = "checked";
        }

        $mydata = 
        '
        <style type="text/css">

        @keyframes appear{
            0%{opacity:0; transform: translateY(50px) rotate(10deg)}
            100%{opacity:1; transform: translateY(0px) rotate(0deg)}
        }
        

        form {
            text-align: left;
            margin: auto;
            /* background-color: gray; */
            padding: 10px;
            width: 100%;
            max-width: 400px;
        }

        input[type=text],
        input[type=password],
        input[type=submit] {
            padding: 10px;
            margin: 10px;
            width: 200px;
            border-radius: 5px;
            border: solid thin green;
        }

        input[type=submit] {
            padding: 10px;
            margin: 10px;
            width: 220px;
            cursor: pointer;
        }

        input[type=radio] {
            transform: scale(1.2);
            cursor: pointer;
            z-index: -1;
        }

        #change_image_button{
            background-color: #b8b876;
            display:inline-block; 
            padding:1em; 
            border-radius: 5px;
            cursor: pointer;
        }

        .dragging {
            border: dashed 2px #aaa;
        }

        
    </style>

            <div id="error" style="text-align: center; padding: 0.5em; color: yellow; font-size: 17px; display: none;">
                Error
            </div>
            <div style="display:flex; animation: appear 1s ease">

            <div>
                <img ondragover="handle_drag_picture(event)" ondrop="handle_drag_picture(event)" ondragleave="handle_drag_picture(event)" src="'.$check.'" style="width:200px; height:200px; margin-top:10px; margin-left: 10px; margin-right: 10px;" />
                <div style="color: blue">Drag image to upload</div>
                <label for="change_image" id="change_image_button">
                    Change Image
                </label>
                    <input type="file" onchange="upload_profile_image(this.files)" style="display:none;" id="change_image"> <br>
            
            </div>
            
            
            <form id="signup_form">
                <input id="username_id" type="text" name="username" placeholder="Username" value="'.$data->username.'"> <br>
                <input id="email_id" type="text" name="email" placeholder="Email" value="'.$data->email.'"> <br>
                <div style="padding: 10px;">
                    <br>Gender: <br>
                    <input type="radio" id="male_id" value="Male" name="gender" '.$gender_male.'>Male <br>
                    <input type="radio" id="female_id" value="Female" name="gender" '.$gender_female.'>Female <br>
                </div>
                <input type="password" id="password_id" name="password" placeholder="Password" value="'.$data->password.'"> <br>
                <input type="password" id="password1_id" name="password2" placeholder="Retype Password" value="'.$data->password.'"> <br>
                <input type="submit" onclick="collect_data(event); return false;" value="Save" id="save_settings_button"> <br>
            </form>
            </div>
        ';

        
        // $result = $result[0];
        // $result->logged_in = true;
        $info->message = $mydata;
        $info->data_type = "settings";
        echo json_encode($info);
        
    }else{
            $info->message = "No settings found";
            $info->data_type = "error";
            echo json_encode($info);

    }

?>
