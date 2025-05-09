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

        $template = file_get_contents($templatePath);

        foreach ($data as $key => $value) {
            $template = str_replace('{$' . $key . '}', $value, $template);
        }

        echo $template;
    }
}