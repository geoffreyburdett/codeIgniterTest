<?php

function set_edit_value($form_element, $orignal_data){
    if($value = set_value($form_element)){
        return $value;
    } elseif ($value = @$orignal_data->$form_element){
        return $value;
    } else{
        return false;
    }
}