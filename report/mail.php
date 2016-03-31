<?php
require '../PHPMailer-master/PHPMailerAutoload.php';
class ReportMailer {
	private $mail;
	
	/**
	 * 此处只是设置了发送者、接收者、发送标题和内容的定义，完整案例如下：
	 * require 'PHPMailerAutoload.php';
	 *
	 * $mail = new PHPMailer;
	 *
	 * //$mail->SMTPDebug = 3; // Enable verbose debug output
	 *
	 * $mail->isSMTP(); // Set mailer to use SMTP
	 * $mail->Host = 'smtp1.example.com;smtp2.example.com'; // Specify main and backup SMTP servers
	 * $mail->SMTPAuth = true; // Enable SMTP authentication
	 * $mail->Username = 'user@example.com'; // SMTP username
	 * $mail->Password = 'secret'; // SMTP password
	 * $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
	 * $mail->Port = 587; // TCP port to connect to
	 *
	 * $mail->setFrom('from@example.com', 'Mailer');
	 * $mail->addAddress('joe@example.net', 'Joe User'); // Add a recipient
	 * $mail->addAddress('ellen@example.com'); // Name is optional
	 * $mail->addReplyTo('info@example.com', 'Information');
	 * $mail->addCC('cc@example.com');
	 * $mail->addBCC('bcc@example.com');
	 *
	 * $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
	 * $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
	 * $mail->isHTML(true); // Set email format to HTML
	 *
	 * $mail->Subject = 'Here is the subject';
	 * $mail->Body = 'This is the HTML message body <b>in bold!</b>';
	 * $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	 *
	 * if(!$mail->send()) {
	 * echo 'Message could not be sent.';
	 * echo 'Mailer Error: ' . $mail->ErrorInfo;
	 * } else {
	 * echo 'Message has been sent';
	 * }        	
	 */
	public function __construct($from, $to, $title, $body) {
		$this->mail = new PHPMailer ();
		$this->init ();
		$this->mail->CharSet = 'utf-8';
		$this->mail->setFrom ( $from );
		$this->mail->addAddress ( $to );
		$this->mail->isHTML ( true );
		$this->mail->Subject = $title;
		$this->mail->Body = $body;
		
		$this->send ();
	}
	private function init() {
		$this->mail->isSMTP (); // Set mailer to use SMTP
		$this->mail->Host = 'smtp.163.com'; // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true; // Enable SMTP authentication
		$this->mail->Username = 'ljqian@163.com'; // SMTP username
		$this->mail->Password = 'ljqian1989'; // SMTP password
		$this->mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 465; // TCP port to connect to
	}
	private function send() {
		if (! $this->mail->send ()) {
			return 'Message could not be sent.\n\rMailer Error: ' . $this->mail->ErrorInfo;
		} else {
			return 'Message has been sent';
		}
	}
}