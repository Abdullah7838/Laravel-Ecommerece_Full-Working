<?php

// Display header
echo "<h1>Image Cache Clearing Utility</h1>";

// Function to delete directory contents
function deleteDirectoryContents($dir) {
    if (!is_dir($dir)) {
        echo "<p>Directory does not exist: {$dir}</p>";
        return false;
    }
    
    $files = array_diff(scandir($dir), array('.', '..'));
    
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        
        if (is_dir($path)) {
            deleteDirectoryContents($path);
            rmdir($path);
        } else {
            unlink($path);
        }
    }
    
    echo "<p>Cleared contents of: {$dir}</p>";
    return true;
}

// Clear Laravel cache
echo "<h2>Clearing Laravel Cache</h2>";

// Clear view cache
$viewCachePath = __DIR__ . '/../storage/framework/views';
echo "<p>Clearing view cache...</p>";
deleteDirectoryContents($viewCachePath);

// Clear application cache
$cachePath = __DIR__ . '/../storage/framework/cache/data';
echo "<p>Clearing application cache...</p>";
if (is_dir($cachePath)) {
    deleteDirectoryContents($cachePath);
} else {
    echo "<p>Cache directory not found: {$cachePath}</p>";
}

// Recreate storage link
echo "<h2>Checking Storage Link</h2>";
$publicStoragePath = __DIR__ . '/storage';
$storageAppPublicPath = __DIR__ . '/../storage/app/public';

// Check if storage link exists
if (is_link($publicStoragePath)) {
    echo "<p>Storage link exists. Removing old link...</p>";
    unlink($publicStoragePath);
} elseif (is_dir($publicStoragePath)) {
    echo "<p>Storage directory exists but is not a link. Removing directory...</p>";
    deleteDirectoryContents($publicStoragePath);
    rmdir($publicStoragePath);
} elseif (file_exists($publicStoragePath)) {
    echo "<p>Storage path exists but is a file. Removing file...</p>";
    unlink($publicStoragePath);
}

// Create symbolic link
if (symlink($storageAppPublicPath, $publicStoragePath)) {
    echo "<p>Successfully created storage link from {$storageAppPublicPath} to {$publicStoragePath}</p>";
} else {
    echo "<p>Failed to create storage link. Error: " . error_get_last()['message'] . "</p>";
}

// Check if categories and brands directories exist
echo "<h2>Checking Image Directories</h2>";
$categoriesDir = $storageAppPublicPath . '/categories';
$brandsDir = $storageAppPublicPath . '/brands';

// Check categories directory
if (!is_dir($categoriesDir)) {
    echo "<p>Creating categories directory...</p>";
    if (mkdir($categoriesDir, 0755, true)) {
        echo "<p>Successfully created categories directory</p>";
    } else {
        echo "<p>Failed to create categories directory. Error: " . error_get_last()['message'] . "</p>";
    }
} else {
    echo "<p>Categories directory exists</p>";
}

// Check brands directory
if (!is_dir($brandsDir)) {
    echo "<p>Creating brands directory...</p>";
    if (mkdir($brandsDir, 0755, true)) {
        echo "<p>Successfully created brands directory</p>";
    } else {
        echo "<p>Failed to create brands directory. Error: " . error_get_last()['message'] . "</p>";
    }
} else {
    echo "<p>Brands directory exists</p>";
}

// Display success message
echo "<h2>Cache Clearing Complete</h2>";
echo "<p>The cache has been cleared and storage links have been recreated. Please try uploading and viewing images again.</p>";
echo "<p><a href='/admin'>Return to Admin Panel</a></p>";