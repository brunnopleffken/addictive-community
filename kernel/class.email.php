<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.email.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	class Email
	{
		// PHPMailer class
		private $Mail;

		// E-mail subject
		private $subject;

		// HTML content of the e-mail
		private $body;

		// ---------------------------------------------------
		// Build class
		// ---------------------------------------------------

		public function __construct($config)
		{
			require("thirdparty/PHPMailer/PHPMailerAutoload.php");

			$this->Mail = new PHPMailer();

			// Set SMTP configuration
			
			$this->Mail->isSMTP();
			$this->Mail->Host       = $config['general_email_smtp'];
			$this->Mail->SMTPAuth   = $config['general_email_authentication'];
			$this->Mail->Username   = $config['general_email_username'];
			$this->Mail->Password   = $config['general_email_password'];
			$this->Mail->SMTPSecure = $config['general_email_auth_method'];
			$this->Mail->Port       = $config['general_email_port'];

			// Set sender information

			$this->Mail->From     = $config['general_email_from'];
			$this->Mail->FromName = $config['general_email_from_name'];

			// Define content type

			$this->Mail->isHTML(true);
			$this->Mail->Charset = "UTF-8";
		}

		// ---------------------------------------------------
		// Define message and recipient
		// $to accepts both string and array ($email => $name)
		// ---------------------------------------------------

		public function Send($to, $subject, $body, $redirect = "")
		{
			if(is_array($to)) {
				foreach($to as $address => $username) {
					$this->Mail->addAddress($address, $username);
				}
			}
			else {
				$this->Mail->addAddress($to);
			}

			$this->Mail->Subject = $subject;
			$this->Mail->Body    = $body;

			if($this->Mail->send()) {
				// Done!
				header("Location: " . $redirect);
			}
			else {
				// Error
				if(!method_exists(Html, "Error")) {
					require("class.html.php");
				}
				Html::Error("<b>Mailer Error:</b> " . $mail->ErrorInfo);
			}
		}

	}

?>