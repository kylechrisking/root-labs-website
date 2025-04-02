<?php
require_once 'functions.php';

$users = [
    'kking' => 'R00TL@B$',
    'JSamford' => 'R00TL@B$'
];

echo "Generated password hashes for .env file:\n\n";
echo "ADMIN_USERS=";

$hashes = [];
foreach ($users as $username => $password) {
    $hash = createPasswordHash($password);
    $hashes[] = $username . ':' . $hash;
}

echo implode(' ', $hashes);
echo "\n\nCopy this line to your .env file.\n";
echo "Then DELETE THIS FILE for security!\n"; 