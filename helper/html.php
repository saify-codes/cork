<?php

class Html{
    public static function rating($count = 1){
        for ($i=0; $i < $count; $i++) { 
         echo "<box-icon name='star' type='solid' color='#ffff00' ></box-icon>";   
        }
    }
    
    public static function switch($id , $checked = ''){
        echo "<div class='switch'>
                <input type='checkbox' name='appproved' value='$id' hidden='hidden' id='switch-$id' $checked>
                <label for='switch-$id'></label>
            </div>";
    }
}

?>