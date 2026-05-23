<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function getMailEnv(string $key, string $default = ''): string
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        return trim($value, " \t\n\r\0\x0B'\"");
    }

    protected function sendMail(string $to, string $subject, string $body, bool $isHtml = true): array
    {
        $host = $this->getMailEnv('MAIL.SMTPHOST');
        $username = $this->getMailEnv('MAIL.SMTPUSER');
        $password = $this->getMailEnv('MAIL.SMTPPASS');
        $port = $this->getMailEnv('MAIL.SMTPPORT');
        $debug = $this->getMailEnv('MAIL.SMTPDebug', '0');
        $timeout = $this->getMailEnv('MAIL.SMTPTIMEOUT', '60');
        $secure = $this->getMailEnv('MAIL.SMTPSecure');

        $debugOutput = '';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure ?: PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $port;
        $mail->Timeout = (int) $timeout;
        $mail->SMTPDebug = (int) $debug;
        $mail->Debugoutput = function ($str, $level) use (&$debugOutput) {
            $debugOutput .= trim($str) . "\n";
        };

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($username, 'Playon Events');
        $mail->addReplyTo($username, 'Playon Events');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $isHtml ? $body : nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));
        $mail->AltBody = $isHtml ? strip_tags($body) : $body;

        $mail->send();

        return [
            'success' => true,
            'debug' => trim($debugOutput),
            'error' => '',
        ];
    }
}
