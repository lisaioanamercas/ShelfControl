<?php

/**
 * ImageOptimizer - Clasa care face imaginile mai frumoase si mai mici
 * Autor: Programatorul care se satura de imagini de 50MB
 * Versiune: 1.0 - "Fac imaginile sa nu mai ocupe tot hardul"
 */

class ImageOptimizer {
    
    // Constantele noastre - ca sa nu le uitam pe drum
    const MAX_WIDTH = 1920;      // Latime maxima - nu avem monitor de NASA
    const MAX_HEIGHT = 1080;     // Inaltime maxima - nici pe verticala nu e cinema
    const JPEG_QUALITY = 85;     // Calitate JPEG - balance intre frumos si mic
    const PNG_COMPRESSION = 6;   // Compresie PNG - 0 = mare, 9 = mic dar lent
    const WEBP_QUALITY = 80;     // Calitate WebP - formatul viitorului
    
    // Tipurile de imagine pe care le stim sa le optimizam
    private $supportedTypes = [
        'image/jpeg' => 'jpg',
        'image/jpg' => 'jpg', 
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];
    
    // Folderul unde tinem imaginile optimizate
    private $outputDir;
    
    /**
     * Constructor - initializam optimizatorul nostru minunat
     * @param string $outputDir - unde salvam imaginile optimizate
     */
    public function __construct($outputDir = 'optimized_images/') {
        $this->outputDir = rtrim($outputDir, '/') . '/';
        
        // Creez folderul daca nu exista - ca un programator prevazator
        if (!file_exists($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
            echo "<!-- Folderul '{$this->outputDir}' a fost creat automat -->\n";
        }
        
        // Verific daca GD este instalat - fara el nu putem face nimic
        if (!extension_loaded('gd')) {
            throw new Exception("Extensia GD nu e instalata! Nu pot optimiza fara ea!");
        }
    }
    
    /**
     * Functia principala - optimizeaza o imagine ca un boss
     * @param string $inputPath - calea catre imaginea originala
     * @param string $outputPath - unde salvez imaginea optimizata (optional)
     * @param array $options - optiuni extra pentru cei pretentiosi
     * @return string|false - calea catre imaginea optimizata sau false daca a esuat
     */
    public function optimizeImage($inputPath, $outputPath = null, $options = []) {
        
        // Verific daca fisierul exista - nu pot optimiza aer
        if (!file_exists($inputPath)) {
            echo "<!-- Eroare: Fisierul '$inputPath' nu exista! Poate a fugit? -->\n";
            return false;
        }
        
        // Aflu ce tip de imagine e - sa stiu cum sa o tratez
        $imageInfo = getimagesize($inputPath);
        if ($imageInfo === false) {
            echo "<!-- Eroare: '$inputPath' nu pare sa fie o imagine valida -->\n";
            return false;
        }
        
        $mimeType = $imageInfo['mime'];
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        
        // Verific daca stiu sa lucrez cu acest tip de imagine
        if (!isset($this->supportedTypes[$mimeType])) {
            echo "<!-- Eroare: Tipul '$mimeType' nu e suportat. Imi pare rau! -->\n";
            return false;
        }
        
        // Generez numele pentru fisierul optimizat daca nu e specificat
        if ($outputPath === null) {
            $pathInfo = pathinfo($inputPath);
            $outputPath = $this->outputDir . $pathInfo['filename'] . '_optimized.' . $pathInfo['extension'];
        }
        
        // Calculez noile dimensiuni - sa nu fie mai mare decat limita
        $newDimensions = $this->calculateNewDimensions($originalWidth, $originalHeight, $options);
        
        echo "<!-- Optimizez imaginea '$inputPath' de la {$originalWidth}x{$originalHeight} la {$newDimensions['width']}x{$newDimensions['height']} -->\n";
        
        // Creez imaginea sursa din fisier
        $sourceImage = $this->createImageFromFile($inputPath, $mimeType);
        if ($sourceImage === false) {
            echo "<!-- Eroare: Nu pot crea imaginea sursa din '$inputPath' -->\n";
            return false;
        }
        
        // Creez o imagine noua cu dimensiunile calculate
        $optimizedImage = imagecreatetruecolor($newDimensions['width'], $newDimensions['height']);
        
        // Pentru PNG si GIF, pastrez transparenta - ca nu sunt barbat
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            $this->preserveTransparency($optimizedImage, $sourceImage, $mimeType);
        }
        
        // Redimensionez imaginea cu antialiasing - sa arate frumos
        imagecopyresampled(
            $optimizedImage, $sourceImage,
            0, 0, 0, 0,
            $newDimensions['width'], $newDimensions['height'],
            $originalWidth, $originalHeight
        );
        
        // Salvez imaginea optimizata
        $saved = $this->saveOptimizedImage($optimizedImage, $outputPath, $mimeType, $options);
        
        // Eliberez memoria - sa nu raman fara RAM
        imagedestroy($sourceImage);
        imagedestroy($optimizedImage);
        
        if ($saved) {
            // Calculez cat spatiu am economisit
            $originalSize = filesize($inputPath);
            $optimizedSize = filesize($outputPath);
            $savedBytes = $originalSize - $optimizedSize;
            $savedPercent = round(($savedBytes / $originalSize) * 100, 1);
            
            echo "<!-- Succes! Imaginea optimizata salvata la '$outputPath' -->\n";
            echo "<!-- Spatiu economisit: " . $this->formatBytes($savedBytes) . " ({$savedPercent}%) -->\n";
            
            return $outputPath;
        }
        
        return false;
    }
    
    /**
     * Calculeaza dimensiunile noi pentru imagine - matematica de baza
     * @param int $originalWidth - latimea originala
     * @param int $originalHeight - inaltimea originala  
     * @param array $options - optiuni pentru redimensionare
     * @return array - noile dimensiuni calculate
     */
    private function calculateNewDimensions($originalWidth, $originalHeight, $options = []) {
        
        // Iau limitele din optiuni sau folosesc valorile default
        $maxWidth = $options['max_width'] ?? self::MAX_WIDTH;
        $maxHeight = $options['max_height'] ?? self::MAX_HEIGHT;
        
        // Daca imaginea e deja mica, o las in pace
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            return ['width' => $originalWidth, 'height' => $originalHeight];
        }
        
        // Calculez ratia de redimensionare - sa pastrez proportiile
        $widthRatio = $maxWidth / $originalWidth;
        $heightRatio = $maxHeight / $originalHeight;
        
        // Iau ratia cea mai mica - sa nu depasesc limitele
        $ratio = min($widthRatio, $heightRatio);
        
        return [
            'width' => round($originalWidth * $ratio),
            'height' => round($originalHeight * $ratio)
        ];
    }
    
    /**
     * Creez o imagine GD din fisier - functia magica
     * @param string $filePath - calea catre fisier
     * @param string $mimeType - tipul MIME al imaginii
     * @return resource|false - resursa imaginii sau false daca esueaza
     */
    private function createImageFromFile($filePath, $mimeType) {
        
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($filePath);
                
            case 'image/png':
                return imagecreatefrompng($filePath);
                
            case 'image/gif':
                return imagecreatefromgif($filePath);
                
            case 'image/webp':
                // WebP e mai special - verific daca e suportat
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($filePath);
                } else {
                    echo "<!-- WebP nu e suportat pe acest server -->\n";
                    return false;
                }
                
            default:
                echo "<!-- Nu stiu cum sa creez imagine din tipul '$mimeType' -->\n";
                return false;
        }
    }
    
    /**
     * Pastrez transparenta pentru PNG si GIF - ca nu sunt monster
     * @param resource $newImage - imaginea noua
     * @param resource $sourceImage - imaginea sursa
     * @param string $mimeType - tipul imaginii
     */
    // private function preserveTransparency($newImage, $sourceImage, $mimeType) {
        
    //     if ($mimeType === 'image/png') {
    //         // Pentru PNG, activez alpha blending si salvez transparenta
    //         imagealphablending($newImage, false);
    //         imagesavealpha($newImage, true);
            
    //         // Creez un pixel transparent
    //         $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
    //         imagefill($newImage, 0, 0, $transparent);
            
    //     } elseif ($mimeType === 'image/gif') {
    //         // Pentru GIF, copiez culoarea transparenta
    //         $transparentIndex = imagecolortransparent($sourceImage);
            
    //         if ($transparentIndex >= 0) {
    //             $transparentColor = imagecolorsforindex($sourceImage, $transparentIndex);
    //             $transparentNew = imagecolorallocate(
    //                 $newImage,
    //                 $transparentColor['red'],
    //                 $transparentColor['green'],
    //                 $transparentColor['blue']
    //             );
    //             imagefill($newImage, 0, 0, $transparentNew);
    //             imagecolortransparent($newImage, $transparentNew);
    //         }
    //     }
    // }
    
    // /**
    //  * Salvez imaginea optimizata pe disk - momentul adevarului
    //  * @param resource $image - imaginea de salvat
    //  * @param string $outputPath - unde sa o salvez
    //  * @param string $mimeType - tipul imaginii
    //  * @param array $options - optiuni de salvare
    //  * @return bool - true daca salvarea a reusit
    //  */
    // private function saveOptimizedImage($image, $outputPath, $mimeType, $options = []) {
        
    //     // Creez directorul daca nu exista
    //     $outputDir = dirname($outputPath);
    //     if (!file_exists($outputDir)) {
    //         mkdir($outputDir, 0755, true);
    //     }
        
    //     switch ($mimeType) {
    //         case 'image/jpeg':
    //         case 'image/jpg':
    //             // Pentru JPEG, setez calitatea
    //             $quality = $options['jpeg_quality'] ?? self::JPEG_QUALITY;
    //             return imagejpeg($image, $outputPath, $quality);
                
    //         case 'image/png':
    //             // Pentru PNG, setez compresia (0-9)
    //             $compression = $options['png_compression'] ?? self::PNG_COMPRESSION;
    //             return imagepng($image, $outputPath, $compression);
                
    //         case 'image/gif':
    //             // GIF nu are optiuni de calitate
    //             return imagegif($image, $outputPath);
                
    //         case 'image/webp':
    //             // WebP cu calitate setabila
    //             if (function_exists('imagewebp')) {
    //                 $quality = $options['webp_quality'] ?? self::WEBP_QUALITY;
    //                 return imagewebp($image, $outputPath, $quality);
    //             }
    //             return false;
                
    //         default:
    //             echo "<!-- Nu stiu cum sa salvez tipul '$mimeType' -->\n";
    //             return false;
    //     }
    // }
    
    /**
     * Optimizeaza un intreg director de imagini - pentru cei cu multe poze
     * @param string $inputDir - directorul cu imagini
     * @param string $outputDir - unde salvez imaginile optimizate
     * @param array $options - optiuni de optimizare
     * @return array - lista cu rezultatele optimizarii
     */
    public function optimizeDirectory($inputDir, $outputDir = null, $options = []) {
        
        $inputDir = rtrim($inputDir, '/') . '/';
        $outputDir = $outputDir ?? $this->outputDir;
        
        if (!is_dir($inputDir)) {
            echo "<!-- Eroare: Directorul '$inputDir' nu exista -->\n";
            return [];
        }
        
        // Scanez directorul pentru imagini
        $files = glob($inputDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        $results = [];
        
        echo "<!-- Gasit " . count($files) . " fisiere de optimizat -->\n";
        
        foreach ($files as $file) {
            $fileName = basename($file);
            $outputPath = $outputDir . $fileName;
            
            echo "<!-- Procesez: $fileName -->\n";
            
            $result = $this->optimizeImage($file, $outputPath, $options);
            $results[$fileName] = $result !== false;
        }
        
        $successful = array_sum($results);
        echo "<!-- Optimizate cu succes: $successful din " . count($files) . " imagini -->\n";
        
        return $results;
    }
    
    /**
     * Converteste o imagine in alt format - transformatorul de imagini
     * @param string $inputPath - imaginea de convertit
     * @param string $outputPath - unde salvez conversia
     * @param string $targetFormat - formatul dorit (jpg, png, webp, gif)
     * @param array $options - optiuni de conversie
     * @return string|false - calea catre imaginea convertita
     */
    public function convertFormat($inputPath, $outputPath, $targetFormat, $options = []) {
        
        if (!file_exists($inputPath)) {
            echo "<!-- Eroare: Fisierul '$inputPath' nu exista -->\n";
            return false;
        }
        
        // Determin tipul MIME din formatul dorit
        $targetMimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg', 
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        
        if (!isset($targetMimeTypes[$targetFormat])) {
            echo "<!-- Eroare: Formatul '$targetFormat' nu e suportat -->\n";
            return false;
        }
        
        $targetMime = $targetMimeTypes[$targetFormat];
        
        // Incarc imaginea originala
        $imageInfo = getimagesize($inputPath);
        $sourceImage = $this->createImageFromFile($inputPath, $imageInfo['mime']);
        
        if ($sourceImage === false) {
            echo "<!-- Nu pot incarca imaginea sursa -->\n";
            return false;
        }
        
        // Salvez in noul format
        $saved = $this->saveOptimizedImage($sourceImage, $outputPath, $targetMime, $options);
        
        imagedestroy($sourceImage);
        
        if ($saved) {
            echo "<!-- Conversie reusita: '$inputPath' -> '$outputPath' ($targetFormat) -->\n";
            return $outputPath;
        }
        
        return false;
    }
    
    /**
     * Genereaza thumbnail-uri - poze mici si dragute
     * @param string $inputPath - imaginea originala
     * @param int $thumbWidth - latimea thumbnail-ului
     * @param int $thumbHeight - inaltimea thumbnail-ului
     * @param bool $crop - daca sa tai imaginea sau sa o redimensionez
     * @return string|false - calea catre thumbnail
     */
    public function createThumbnail($inputPath, $thumbWidth = 150, $thumbHeight = 150, $crop = false) {
        
        if (!file_exists($inputPath)) {
            echo "<!-- Fisierul '$inputPath' nu exista pentru thumbnail -->\n";
            return false;
        }
        
        $imageInfo = getimagesize($inputPath);
        $sourceImage = $this->createImageFromFile($inputPath, $imageInfo['mime']);
        
        if ($sourceImage === false) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        
        // Creez imaginea thumbnail
        $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
        
        // Pastrez transparenta daca e nevoie
        if ($imageInfo['mime'] === 'image/png') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
            imagefill($thumbnail, 0, 0, $transparent);
        }
        
        if ($crop) {
            // Mod crop - tai din mijloc ca un barbier priceput
            $sourceRatio = $sourceWidth / $sourceHeight;
            $thumbRatio = $thumbWidth / $thumbHeight;
            
            if ($sourceRatio > $thumbRatio) {
                // Imaginea e mai lata - tai din laturi
                $newSourceWidth = $sourceHeight * $thumbRatio;
                $srcX = ($sourceWidth - $newSourceWidth) / 2;
                $srcY = 0;
                $srcW = $newSourceWidth;
                $srcH = $sourceHeight;
            } else {
                // Imaginea e mai inalta - tai de sus si de jos
                $newSourceHeight = $sourceWidth / $thumbRatio;
                $srcX = 0;
                $srcY = ($sourceHeight - $newSourceHeight) / 2;
                $srcW = $sourceWidth;
                $srcH = $newSourceHeight;
            }
            
            imagecopyresampled($thumbnail, $sourceImage, 0, 0, $srcX, $srcY, 
                             $thumbWidth, $thumbHeight, $srcW, $srcH);
        } else {
            // Mod resize - redimensionez pastrand proportiile
            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0,
                             $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);
        }
        
        // Generez numele pentru thumbnail
        $pathInfo = pathinfo($inputPath);
        $thumbPath = $this->outputDir . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        // Salvez thumbnail-ul
        $saved = $this->saveOptimizedImage($thumbnail, $thumbPath, $imageInfo['mime']);
        
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
        
        if ($saved) {
            echo "<!-- Thumbnail creat: '$thumbPath' ({$thumbWidth}x{$thumbHeight}) -->\n";
            return $thumbPath;
        }
        
        return false;
    }
    
    /**
     * Formateaza dimensiunea in bytes intr-un format citibil
     * @param int $bytes - numarul de bytes
     * @return string - dimensiunea formatata (ex: "1.5 MB")
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }
        
        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
    
    /**
     * Obtine informatii despre o imagine - pentru cei curiosi
     * @param string $imagePath - calea catre imagine
     * @return array|false - informatii despre imagine
     */
    public function getImageInfo($imagePath) {
        
        if (!file_exists($imagePath)) {
            return false;
        }
        
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo === false) {
            return false;
        }
        
        $fileSize = filesize($imagePath);
        
        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime_type' => $imageInfo['mime'],
            'file_size' => $fileSize,
            'file_size_formatted' => $this->formatBytes($fileSize),
            'aspect_ratio' => round($imageInfo[0] / $imageInfo[1], 2)
        ];
    }
    
    /**
     * Functie de debug - afiseaza toate informatiile utile
     * @param string $imagePath - imaginea de analizat
     */
    public function debugImage($imagePath) {
        echo "<!-- === DEBUG IMAGINE: $imagePath === -->\n";
        
        $info = $this->getImageInfo($imagePath);
        if ($info === false) {
            echo "<!-- Imaginea nu poate fi analizata -->\n";
            return;
        }
        
        echo "<!-- Dimensiuni: {$info['width']}x{$info['height']} px -->\n";
        echo "<!-- Tip MIME: {$info['mime_type']} -->\n";
        echo "<!-- Dimensiune fisier: {$info['file_size_formatted']} -->\n";
        echo "<!-- Raport aspect: {$info['aspect_ratio']} -->\n";
        echo "<!-- === SFARSIT DEBUG === -->\n";
    }
}

// Exemplu de utilizare - decomentati pentru a testa
/*
try {
    // Creez optimizatorul
    $optimizer = new ImageOptimizer('assets/images/optimized/');
    
    // Optimizez o imagine
    $result = $optimizer->optimizeImage('assets/images/ShelfControl(2).png');
    
    if ($result) {
        echo "Imaginea a fost optimizata cu succes!";
    } else {
        echo "Ceva nu a mers bine cu optimizarea...";
    }
    
    // Creez si un thumbnail
    $thumbnail = $optimizer->createThumbnail('assets/images/ShelfControl(2).png', 200, 200, true);
    
    // Informatii despre imagine
    $optimizer->debugImage('assets/images/ShelfControl(2).png');
    
} catch (Exception $e) {
    echo "Eroare: " . $e->getMessage();
}
*/

?>
