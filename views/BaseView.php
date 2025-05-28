<?php
namespace App\Views;
use App\Controllers\BaseController;

class BaseView
{
    public function renderTemplate($templateName, $data, $includeHeaderFooter = true)
    {
        //aici vreau sa injectez informatiile din jwt ca sa le pun in header la navbar la sectiunea de profil
        // Skip stats loading for pages that don't need the header/footer
        $skipHeaderFooter = !$includeHeaderFooter || in_array($templateName, ['login', 'register', 'landing']);
        
        // Only fetch user data and stats if we're including the header
        if (!$skipHeaderFooter && !isset($data['user']) && isset($_COOKIE['jwt'])) {
            require_once __DIR__ . '/../controllers/baseController.php';
            $jwt = new \App\Controllers\BaseController();
            $decoded = $jwt->validateJWT($_COOKIE['jwt']);
            
            if ($decoded && isset($decoded->data)) {
                // Basic user info from JWT
                $userEmail = $decoded->data->email;
                
                require_once __DIR__ . '/../models/dbConnection.php';
                $conn = getConnection();

                // Now that we have a connection, create the UserModel
                $userModel = new \App\Models\UserModel($conn);
                $userId = $userModel->getUserIdByEmail($userEmail);
                
                $data['user'] = [
                    'username' => $decoded->data->username ?? '',
                    'email' => $userEmail ?? '',
                    'role' => $decoded->data->role ?? ''
                ];
                
                if ($userId) {
                    // Get user stats
                    $data['userStats'] = $userModel->getUserStats($userId);
                    
                    // If you want creation date too
                    $userData = $userModel->getUserByEmail($userEmail);
                    if ($userData && isset($userData['CREATED_AT'])) {
                        $data['user']['created_at'] = $userData['CREATED_AT'];
                    }
                }
            }
        }

        $templatePath = __DIR__ . "/template/{$templateName}.tpl";

        if (!file_exists($templatePath)) {
            die("Template file not found: " . $templatePath);
        }
        
        // $skipHeaderFooter = !$includeHeaderFooter || in_array($templateName, ['login', 'register', 'landing']);
        
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