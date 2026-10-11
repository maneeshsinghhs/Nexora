<?php
// Disposable test installation only. Captures mail locally without delivering it.
add_filter('pre_wp_mail', function ($return, $attributes) {
    file_put_contents('/tmp/nexora-mail.json', wp_json_encode($attributes));
    return true;
}, 10, 2);
