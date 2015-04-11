<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Email.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Email
{
	// PHPMailer class
	private $Mail;

	// E-mail subject
	private $subject;

	// HTML content of the e-mail
	private $body;

	/**
	 * --------------------------------------------------------------------
	 * CLASS CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct($config)
	{
		require("thirdparty/PHPMailer/PHPMailerAutoload.php");

		$this->Mail = new PHPMailer();

		// Check if e-mail information is empty
		if($config['general_email_smtp'] == "" ||
		   $config['general_email_authentication'] == "" ||
		   $config['general_email_username'] == "" ||
		   $config['general_email_password'] == ""
		) {
			Html::Error("<b>Mailer Error:</b> You're unable to send e-mails. Check your SMTP settings in "
				. "<a href='admin/' target='_blank'>Administration Control Panel</a>."
			);
		}

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

	/**
	 * --------------------------------------------------------------------
	 * SET UP MESSAGE AND RECIPIENT
	 * $to ACCEPTS BOTH STRING AND ARRAY ($email => $name)
	 * --------------------------------------------------------------------
	 */
	public function Send($to, $subject, $body, $redirect = "index.php")
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
			exit;
		}
		else {
			// Error
			if(!method_exists("Html", "Error")) {
				require("Html.php");
			}
			Html::Error("<b>Mailer Error:</b> " . $this->Mail->ErrorInfo);
		}
	}
}
