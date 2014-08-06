<?php

function access_to_string($access_level){
    switch ($access_level) {
        case 10:
            return 'viewer';
        case 20:
            return 'editor';
        case 30:
            return 'creator';
        case 40:
            return 'admin';
        case 50:
            return 'super user';
    }
    return 'unknown';
}