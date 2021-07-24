<?php

    $myid = $_SESSION['id'];
    $sql = "select * from users where id != '$myid'";
    $myusers = $DB->read($sql,[]);

    $mydata = 
    '
    <style>
        @keyframes appear{
            0%{opacity:0; transform: translateY(50px)}
            100%{opacity:1; transform: translateY(0px)}
        }

        #contact{
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0,.55,.99,.28);
            //www.cubic-bezier.com for how to action accelerates
        }

        #contact:hover{
            transform: scale(1.1);
        }
    </style>

    <div style="text-align: center; animation: appear 1s ease">';

    if(is_array($myusers))
    {
        foreach ($myusers as $row) {
            
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

            $mydata .="        
            <div id='contact' userid='$row->id' onclick='start_chat(event)'>
            <!-- userid is any arbitrary atribute for getting the info in the function --!>
                <img src='$check'> <br> $row->username
            </div>";
        }
    }
        
    $mydata .='
    </div>';

    // $result = $result[0];
    // $result->logged_in = true;
    $info->message = $mydata;
    $info->data_type = "contacts";
    echo json_encode($info);

    die;

    $info->message = "No Contacts found";
    $info->data_type = "error";
    echo json_encode($info);

?>
