<?php
if (!function_exists('canView')) {
    function canView($permission) {
        return auth()->user()->can($permission);
    }
}
