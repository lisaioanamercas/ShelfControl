<?php

namespace App\Models;

use Exception;

class ImageOptimizer
{
    private $cacheDir;
    private $maxWidth = 300;
    private $maxHeight = 450;
    private $quality = 85;

    public function __construct()
    {
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/ShelfControl/cache/images/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function optimizeBookCover($imageUrl, $bookId, $priority = 'normal')
    {
        if (empty($imageUrl)) {
            return '/ShelfControl/assets/img/default-book.png';
        }

        // Generate cache filename
        $extension = $this->getImageExtension($imageUrl);
        $cacheFile = $this->cacheDir . "book_{$bookId}.webp";
        $fallbackFile = $this->cacheDir . "book_{$bookId}.{$extension}";

        // Check if optimized version exists and is recent
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
            return $this->getCachedImageUrl($cacheFile);
        }

        // Download and optimize image
        return $this->processImage($imageUrl, $cacheFile, $fallbackFile, $priority);
    }

    private function processImage($imageUrl, $webpPath, $fallbackPath, $priority)
    {
        try {
            // Download image with timeout
            $timeout = ($priority === 'high') ? 10 : 5;
            $context = stream_context_create([
                'http' => [
                    'timeout' => $timeout,
                    'user_agent' => 'ShelfControl/1.0'
                ]
            ]);

            $imageData = file_get_contents($imageUrl, false, $context);
            if (!$imageData) {
                return '/ShelfControl/assets/img/default-book.png';
            }

            // Create image resource
            $image = imagecreatefromstring($imageData);
            if (!$image) {
                return '/ShelfControl/assets/img/default-book.png';
            }

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate new dimensions maintaining aspect ratio
            $ratio = min($this->maxWidth / $originalWidth, $this->maxHeight / $originalHeight);
            $newWidth = intval($originalWidth * $ratio);
            $newHeight = intval($originalHeight * $ratio);

            // Create optimized image
            $optimized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($optimized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Save WebP version (better compression)
            if (function_exists('imagewebp')) {
                imagewebp($optimized, $webpPath, $this->quality);
            }

            // Save fallback version
            $extension = $this->getImageExtension($imageUrl);
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($optimized, $fallbackPath, $this->quality);
                    break;
                case 'png':
                    imagepng($optimized, $fallbackPath, 8);
                    break;
                default:
                    imagejpeg($optimized, $fallbackPath, $this->quality);
            }

            // Clean up memory
            imagedestroy($image);
            imagedestroy($optimized);

            // Return WebP if available, fallback otherwise
            if (file_exists($webpPath)) {
                return $this->getCachedImageUrl($webpPath);
            }
            return $this->getCachedImageUrl($fallbackPath);

        } catch (Exception $e) {
            error_log("Image optimization failed: " . $e->getMessage());
            return '/ShelfControl/assets/img/default-book.png';
        }
    }

    private function getImageExtension($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';
    }

    private function getCachedImageUrl($filePath)
    {
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
    }

    public function preloadCriticalImages($bookIds)
    {
        // Return preload links for critical images
        $preloadLinks = [];
        foreach (array_slice($bookIds, 0, 6) as $bookId) {
            $webpPath = "/ShelfControl/cache/images/book_{$bookId}.webp";
            $jpegPath = "/ShelfControl/cache/images/book_{$bookId}.jpg";
            
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $webpPath)) {
                $preloadLinks[] = "<link rel='preload' as='image' href='{$webpPath}' type='image/webp'>";
            } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . $jpegPath)) {
                $preloadLinks[] = "<link rel='preload' as='image' href='{$jpegPath}'>";
            }
        }
        return implode("\n", $preloadLinks);
    }
}