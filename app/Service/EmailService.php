<?php


namespace Blog\Service;


use Blog\Entity\Contact;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    private PHPMailer $phpMailer;

    public function __construct()
    {
        $phpMailer = new PHPMailer(true);
        $phpMailer->SMTPDebug = 0;
        $phpMailer->isSMTP();
        $phpMailer->isHTML();
        $phpMailer->Host       = 'smtp.mailtrap.io';
        $phpMailer->SMTPAuth   = true;                                   //Enable SMTP authentication
        $phpMailer->Username   = '84a7fd0b1e99dd';                     //SMTP username
        $phpMailer->Password   = '843a9dd5383b44';                               //SMTP password
        $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $phpMailer->Port       = 465;
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
