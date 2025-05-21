<?php
namespace App\Views;

class BaseView
{
    public function renderTemplate($templateName, $data)
    {
        $templatePath = __DIR__ . "/template/{$templateName}.tpl";

        if (!file_exists($templatePath)) {
            die("Template file not found: " . $templatePath);
        }
        
        // Read the template file
        $content = file_get_contents($templatePath);
        
        // Replace all placeholders with values
        foreach ($data as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $content = str_replace('{$' . $key . '}', $value, $content);
            }
        }
        
        // Extract data array to local variables that can be used in the template
        extract($data);
        
        // Process any PHP code that might be in the template
        ob_start();
        eval('?>' . $content);
        $processedContent = ob_get_clean();
        
        echo $processedContent;
    }
}