<?php
$files = [
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\public\\visitor\\assets\\css\\style.css',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\public\\css\\styles.css',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\sass\\_variables.scss',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\layouts\\admin.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Replace Flashy Cyan-Blue with Pro Blue
        $content = str_ireplace('#2196F3', '#1D4ED8', $content);
        // Replace Flashy Success Green with Pro Green
        $content = str_ireplace('#198754', '#15803D', $content);
        
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    } else {
        echo "Missing " . basename($file) . "\n";
    }
}
