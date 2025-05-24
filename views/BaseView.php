<?php
namespace App\Views;

class BaseView
{
    public function renderTemplate($templateName, $data, $includeHeaderFooter = true)
    {
        $templatePath = __DIR__ . "/template/{$templateName}.tpl";

        if (!file_exists($templatePath)) {
            die("Template file not found: " . $templatePath);
        }
        
        $skipHeaderFooter = !$includeHeaderFooter || in_array($templateName, ['login', 'register', 'landing']);
        
        if ($skipHeaderFooter) {
            $content = file_get_contents($templatePath);
            
            foreach ($data as $key => $value) {
                if (is_string($value) || is_numeric($value)) {
                    $content = str_replace('{$' . $key . '}', $value, $content);
                }
            }
            
        
            extract($data);
            
            ob_start();
            eval('?>' . $content);
            $processedContent = ob_get_clean();
        } else {

            $headerPath = __DIR__ . "/template/header.tpl";
            $footerPath = __DIR__ . "/template/footer.tpl";
            
            $headerContent = file_exists($headerPath) ? file_get_contents($headerPath) : '';
            $content = file_get_contents($templatePath);
            $footerContent = file_exists($footerPath) ? file_get_contents($footerPath) : '';

            // Combine contents
            $fullContent = $headerContent . $content . $footerContent;

            // Replace all placeholders with values
            foreach ($data as $key => $value) {
                if (is_string($value) || is_numeric($value)) {
                    $fullContent = str_replace('{$' . $key . '}', $value, $fullContent);
                }
            }
            
            
            extract($data);
            
           
            ob_start();
            eval('?>' . $fullContent);
            $processedContent = ob_get_clean();
        }
        
        echo $processedContent;
    }
}