<?php
//class used for displaying flash bang messages
class FlashBang{
    //uses $_SESSION to pass data, mainly error and success statements between pages
    static function addFlashBang($color,$header,$message){
        $_SESSION['proposal_flashbang_color'] = $color;
        $_SESSION['proposal_flashbang_header'] = $header;
        $_SESSION['proposal_flashbang_message'] = $message;
        $_SESSION['proposal_flashbang_exists'] = 1;
    }
    
    static function getFlashBang(){
        //Reads if there is a flash bang
        if(@$_SESSION['proposal_flashbang_exists'] === 1){
            //HTML code for a flashbang
            $alertClass;
            switch($_SESSION['proposal_flashbang_color']){
                case "Red":
                    $alertClass = "alert-error";
                    break;
                case "Blue":
                    $alertClass = "alert-info";
                    break;
                case "Green":
                    $alertClass = "alert-success";
                    break;
                default:
                    $alertClass = "alert-info";
                    break;
            }
            $codeBlock = '<div class="container alert '.$alertClass.'"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>'.$_SESSION['proposal_flashbang_header'].'</h4>'.$_SESSION['proposal_flashbang_message'].'</div>';
            $_SESSION['proposal_flashbang_exists'] = 0;
            $_SESSION['proposal_flashbang_color'] = '';
            $_SESSION['proposal_flashbang_header'] = '';
            $_SESSION['proposal_flashbang_message'] = '';
            return $codeBlock;
            
        }else{
            return '';
        }
    }
}
?>
