<?php
$target = dirname(__DIR__) . '/storage/app/public';
$link   = __DIR__ . '/storage';

if (file_exists($link) || is_link($link)) {
    echo '⚠️ Link already exists at: ' . $link;
} else {
    if (symlink($target, $link)) {
        echo '✅ Done! Symlink created: ' . $link . ' → ' . $target;
    } else {
        echo '❌ Failed to create symlink. Your host may not allow symlinks via PHP.';
    }
}
