<?php
// update_sidebar.php - Move Logout button directly below Settings in all admin pages
$dir = __DIR__ . '/admin';
$files = glob($dir . '/*.php');

$target = '<div class="sidebar-footer">';
$updated_count = 0;

foreach ($files as $file) {
    if (basename($file) === 'login.php' || basename($file) === 'db_connect.php') continue;

    $content = file_get_contents($file);
    if (strpos($content, $target) !== false) {
        // Pattern matches:
        // </ul>\s*<div class="sidebar-footer">\s*<a href="login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>\s*</div>\s*</aside>
        $pattern = '/\s*<\/ul>\s*<div class="sidebar-footer">\s*<a href="login\.php"><i class="bi bi-box-arrow-left"><\/i>\s*Logout<\/a>\s*<\/div>\s*<\/aside>/i';
        $replacement = "\n            <li><a href=\"login.php\" class=\"logout-link\"><i class=\"bi bi-box-arrow-left\"></i> Logout</a></li>\n        </ul>\n    </aside>";

        $new_content = preg_replace($pattern, $replacement, $content);
        if ($new_content && $new_content !== $content) {
            file_put_contents($file, $new_content);
            echo "Updated: " . basename($file) . "\n";
            $updated_count++;
        }
    }
}

echo "Total updated: $updated_count\n";
