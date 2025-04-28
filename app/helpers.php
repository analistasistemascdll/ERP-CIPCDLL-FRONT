<?php  
if (!function_exists('api_url')) {
    function api_url($path = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/' . ltrim($path, '/');
    }
}
?>