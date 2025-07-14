<?php

// Get the first image from categories directory
$categoriesDir = '../storage/app/public/categories/';
$brandsDir = '../storage/app/public/brands/';

// Function to get first image from directory
function getFirstImage($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && is_file($dir . $file)) {
                return $file;
            }
        }
    }
    return null;
}

// Get sample images
$categoryImage = getFirstImage($categoriesDir);
$brandImage = getFirstImage($brandsDir);

// Display debug information
echo '<h1>Image Debug Information</h1>';

echo '<h2>Filesystem Configuration</h2>';
echo '<pre>';
echo 'Storage Link Exists: ' . (file_exists('../public/storage') ? 'Yes' : 'No') . "\n";
echo 'Categories Directory Exists: ' . (is_dir($categoriesDir) ? 'Yes' : 'No') . "\n";
echo 'Brands Directory Exists: ' . (is_dir($brandsDir) ? 'Yes' : 'No') . "\n";
echo '</pre>';

echo '<h2>Sample Images</h2>';

echo '<h3>Category Image</h3>';
if ($categoryImage) {
    echo '<p>Found image: ' . htmlspecialchars($categoryImage) . '</p>';
    echo '<p>Full path: ' . htmlspecialchars($categoriesDir . $categoryImage) . '</p>';
    echo '<p>File exists: ' . (file_exists($categoriesDir . $categoryImage) ? 'Yes' : 'No') . '</p>';
    echo '<p>File size: ' . (file_exists($categoriesDir . $categoryImage) ? filesize($categoriesDir . $categoryImage) . ' bytes' : 'N/A') . '</p>';
    echo '<p>Public URL: /storage/categories/' . htmlspecialchars($categoryImage) . '</p>';
    echo '<img src="/storage/categories/' . htmlspecialchars($categoryImage) . '" style="max-width: 300px;" alt="Category Image">';
} else {
    echo '<p>No category images found</p>';
}

echo '<h3>Brand Image</h3>';
if ($brandImage) {
    echo '<p>Found image: ' . htmlspecialchars($brandImage) . '</p>';
    echo '<p>Full path: ' . htmlspecialchars($brandsDir . $brandImage) . '</p>';
    echo '<p>File exists: ' . (file_exists($brandsDir . $brandImage) ? 'Yes' : 'No') . '</p>';
    echo '<p>File size: ' . (file_exists($brandsDir . $brandImage) ? filesize($brandsDir . $brandImage) . ' bytes' : 'N/A') . '</p>';
    echo '<p>Public URL: /storage/brands/' . htmlspecialchars($brandImage) . '</p>';
    echo '<img src="/storage/brands/' . htmlspecialchars($brandImage) . '" style="max-width: 300px;" alt="Brand Image">';
} else {
    echo '<p>No brand images found</p>';
}

// Check permissions
echo '<h2>Directory Permissions</h2>';
echo '<pre>';
echo 'Public Directory: ' . substr(sprintf('%o', fileperms('../public')), -4) . "\n";
echo 'Storage Directory: ' . substr(sprintf('%o', fileperms('../storage')), -4) . "\n";
echo 'Public Storage Link: ' . (file_exists('../public/storage') ? substr(sprintf('%o', fileperms('../public/storage')), -4) : 'N/A') . "\n";
echo 'App/Public Directory: ' . (is_dir('../storage/app/public') ? substr(sprintf('%o', fileperms('../storage/app/public')), -4) : 'N/A') . "\n";
echo 'Categories Directory: ' . (is_dir($categoriesDir) ? substr(sprintf('%o', fileperms($categoriesDir)), -4) : 'N/A') . "\n";
echo 'Brands Directory: ' . (is_dir($brandsDir) ? substr(sprintf('%o', fileperms($brandsDir)), -4) : 'N/A') . "\n";
echo '</pre>';

// Check if the symbolic link is working correctly
echo '<h2>Symbolic Link Test</h2>';
echo '<pre>';
if (is_link('../public/storage')) {
    echo 'Storage is a symbolic link: Yes' . "\n";
    echo 'Link target: ' . readlink('../public/storage') . "\n";
    echo 'Target exists: ' . (file_exists(readlink('../public/storage')) ? 'Yes' : 'No') . "\n";
} else {
    echo 'Storage is a symbolic link: No' . "\n";
}
echo '</pre>';

// Check URL generation
echo '<h2>URL Generation</h2>';
echo '<p>Base URL: ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '</p>';
echo '<p>Test Image URL: ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/storage/categories/' . ($categoryImage ?: 'no-image') . '</p>';