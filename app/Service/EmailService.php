<?php


namespace Blog\Service;



use Blog\Entity\Contact;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    const SMTP_HOST_DEFAULT= 'smtp.mailtrap.io';
    const SMTP_USERNAME_DEFAULT= '84a7fd0b1e99dd';
    const SMTP_PASSWORD_DEFAULT= '843a9dd5383b44';
    const SMTP_PORT_DEFAULT= '465';

    private PHPMailer $phpMailer;

    public function __construct()
    {
        $phpMailer = new PHPMailer(true);
        $phpMailer->SMTPDebug  = 0;
        $phpMailer->isSMTP();
        $phpMailer->isHTML();
        $phpMailer->Host       = $_ENV['SMTP_HOST'] ?? self::SMTP_HOST_DEFAULT;
        $phpMailer->SMTPAuth   = true;                                                 //Enable SMTP authentication
        $phpMailer->Username   = $_ENV['SMTP_USERNAME'] ?? self::SMTP_USERNAME_DEFAULT;//SMTP username
        $phpMailer->Password   = $_ENV['SMTP_PASSWORD'] ?? self::SMTP_PASSWORD_DEFAULT;//SMTP password
        $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                       //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $phpMailer->Port       = $_ENV['SMTP_PORT'] ?? self::SMTP_PORT_DEFAULT;
        $this->phpMailer = $phpMailer;
    }

    public function sendEmail(Contact $contact)
    {
        $phpMailer = $this->phpMailer;
        $phpMailer->addAddress($contact->getEmail());
        $phpMailer->Subject = $contact->getSubject();
        $phpMailer->Body = $contact->getMessage();
        try {
            $phpMailer->send();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

}
