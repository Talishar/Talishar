<?php
/**
 * SendGrid Email Service with SSL Certificate Fix
 * 
 * This wrapper handles SSL certificate verification issues that can occur
 * in Docker/Windows environments when communicating with SendGrid's API.
 */

class TalisharSendGrid {
    private $apiKey;
    private $fromEmail;
    private $fromName;
    private $sendgrid;
    
    public function __construct($apiKey, $fromEmail = "noreply@talishar.net", $fromName = "Talishar") {
        if (empty($apiKey)) {
            throw new Exception("SendGrid API key not configured");
        }
        
        if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
            throw new Exception("SendGrid library not installed");
        }
        
        require __DIR__ . '/../vendor/autoload.php';
        
        $this->apiKey = $apiKey;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->sendgrid = new \SendGrid($apiKey);
    }
    
    /**
     * Send password reset email
     * 
     * @param string $recipientEmail Email address to send to
     * @param string $resetUrl Full reset URL with token
     * @return bool True if email accepted, false otherwise
     */
    public function sendPasswordReset($recipientEmail, $resetUrl) {
        try {
            $mail = new \SendGrid\Mail\Mail();
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->setSubject("Talishar Password Reset Link");
            $mail->addTo($recipientEmail);
            $mail->addContent(
                "text/html",
                $this->buildPasswordResetHtml($resetUrl)
            );
            
            // Send with SSL verification disabled (for environments with certificate issues)
            return $this->sendWithSSLFix($mail);
            
        } catch (Exception $e) {
            error_log('SendGrid Password Reset Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email with SSL certificate verification bypass
     * This is needed for Docker/Windows environments that have SSL cert issues
     * 
     * @param \SendGrid\Mail\Mail $mail Email object
     * @return bool True if accepted (status 202), false otherwise
     */
    private function sendWithSSLFix($mail) {
        try {
            // Try normal send first
            $response = $this->sendgrid->send($mail);
            $statusCode = $response->statusCode();
            
            if ($statusCode === 202) {
                error_log("SendGrid: Email accepted (Status: 202)");
                return true;
            } elseif (in_array($statusCode, [400, 401, 403])) {
                // Client error - might be SSL issue
                error_log("SendGrid Warning: Status $statusCode - " . $response->body());
                return $this->sendWithCurlOverride($mail);
            } else {
                error_log("SendGrid: Unexpected status $statusCode");
                return false;
            }
            
        } catch (Exception $e) {
            // Try with curl SSL bypass
            error_log("SendGrid: Caught exception - " . $e->getMessage());
            return $this->sendWithCurlOverride($mail);
        }
    }
    
    /**
     * Alternative send method using direct curl with SSL verification disabled
     * Used as fallback when standard method fails
     * 
     * @param \SendGrid\Mail\Mail $mail Email object
     * @return bool True if successful
     */
    private function sendWithCurlOverride($mail) {
        try {
            error_log("SendGrid: Attempting with cURL SSL override...");
            
            $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
            
            // Convert mail object to JSON using SendGrid's method
            $mailJson = json_encode($mail->getJsonBody());
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $mailJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            // SSL verification disabled for troublesome environments
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ));
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($httpCode === 202) {
                error_log("SendGrid cURL Override: Email accepted (Status: 202)");
                return true;
            } elseif ($curlError) {
                error_log("SendGrid cURL Error: $curlError (HTTP $httpCode)");
                return false;
            } else {
                error_log("SendGrid cURL Response: HTTP $httpCode - $response");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("SendGrid cURL Override Exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build HTML content for password reset email
     */
    private function buildPasswordResetHtml($resetUrl) {
        return "
        <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <div style='max-width: 600px; margin: 0 auto;'>
                    <h2>Password Reset Request</h2>
                    <p>We received a password reset request for your Talishar account.</p>
                    <p>If you did not make this request, you can safely ignore this email.</p>
                    <p style='margin: 30px 0;'>
                        <a href='" . htmlspecialchars($resetUrl) . "' 
                           style='background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;'>
                            Reset Password
                        </a>
                    </p>
                    <p>Or copy this link: <code>" . htmlspecialchars($resetUrl) . "</code></p>
                    <p style='color: #666; font-size: 12px; margin-top: 30px;'>
                        This link will expire in 30 minutes.<br/>
                        If you continue to have problems, please contact us on Discord.
                    </p>
                </div>
            </body>
        </html>";
    }
}
?>
