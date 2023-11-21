<?php 

    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php";
    require "./bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //print_r($_POST);

    class Email {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function msg_valida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }

        public function email_incompleto() {
            $email_correto = "/\.com(\.br)?$/i"; //colocar o \.br dentro dos () faz com que ele se torne opcional.
            if(!preg_match($email_correto, $this->para)) { //preg_match é a forma correta de validar regex em php
                return false;
            }

            return true;
        }
    }

    $email = new Email();

    $email->__set("para", $_POST["para"]);
    $email->__set("assunto", $_POST["assunto"]);
    $email->__set("mensagem", $_POST["mensagem"]);

    //print_r($email);

    if(!$email->msg_valida() && $email->email_incompleto()) {
        echo "Falha ao enviar email";
        die();
    }

    $mail = new PHPMailer(true);
	try {
			//Server settings
			$mail->SMTPDebug = 2;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'seu email';                     //SMTP username
			$mail->Password   = 'sua senha';                               //SMTP password
			$mail->SMTPSecure = "tls";         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->setFrom('renangblima@gmail.com', 'Renan Gabriel Remetente');
			$mail->addAddress('renangblima@gmail.com');     //Add a recipient
			// $mail->addAddress('ellen@example.com');               //Name is optional
			//$mail->addReplyTo('info@example.com', 'Information'); //caso quem recebeu o email queira responder. Pelo que entendi posso fazer com que a resposta chegue a um terceiro usuário
			// $mail->addCC('cc@example.com'); /adiciona destinatários de cópias
			// $mail->addBCC('bcc@example.com'); /adiciona cópia oculta

			//Attachments
			// $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $email->assunto;
			$mail->Body    = $email->mensagem;
			$mail->AltBody = $email->mensagem;

			$mail->send();
			echo 'Message has been sent';
	} catch (Exception $e) {
			echo "Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.";
			echo 'Detalhes do erro: ' . $mail->ErrorInfo;
	}

?>