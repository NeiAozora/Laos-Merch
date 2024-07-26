<?php

class ProfileController extends Controller
{
    public function profileSettings(){
        $editMode = false;
        if (isset($_GET["edit"])){
            if (strtolower($_GET["edit"]) == "true"){
                $editMode = true;
            }
        }
        $userData = AuthHelpers::getLoggedInUserData();
        if (is_null($userData)){
            jsRedirect("/login");
            // throw new ValueError('Userdata of the logged in person are null');
        }
        $this->view("profile/biodata/index" , ["isEditMode"=> $editMode, 'userData' => $userData]);
    }    
}