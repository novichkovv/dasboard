<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 26.05.2015
 * Time: 10:10
 */
class tools_class
{
    /**
     * @param string $subject
     * @param string $message
     * @param string $to
     * @param string $from
     * @param string string $name
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */

    public static function mail($subject, $message, $to, $from = 'info@qcop.ru', $name = 'Client')
    {
        require_once LIBS_DIR.'phpmailer/class.phpmailer.php';
        $mail = new PHPMailer();
        $mail->SetFrom($from, 'qcop.ru');
        $mail->AddAddress($to, $name);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        return $mail->Send();
    }
}