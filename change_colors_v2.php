<?php
$files = [
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\sass\\_variables.scss',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\public\\visitor\\assets\\css\\style.css',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\public\\css\\styles.css',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\paiement\\recu.blade.php',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\paiement\\recu-v1.blade.php',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\paiement\\facture.html',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\layouts\\admin.blade.php',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\layouts\\faso.blade.php',
    'c:\\Users\\DID\\Pictures\\fs\\faso-wifi\\resources\\views\\layouts\\app.blade.php'
];

$replacements = [
    '#0d6efd' => '#1D4ED8', // Bootstrap Primary
    '#07298f' => '#1D4ED8', // Receipt Dark Blue
    '#2465dd' => '#2563EB', // Receipt Light Blue (matching Tailwind Blue-600)
    'background-color: blue;' => 'background-color: #1D4ED8;',
    '#f4623a' => '#1D4ED8', // Old flashy orange/red sometimes used as primary
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        foreach ($replacements as $old => $new) {
            $content = str_ireplace($old, $new, $content);
        }
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
