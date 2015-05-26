<?php

   namespace Alo;

   use PHPMailer;

   if(!defined('GEN_START')) {
      http_response_code(404);
      die();
   }

   require_once DIR_SYS . 'external' . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
   require_once DIR_SYS . 'external' . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . 'PHPMailerAutoload.php';

   \Alo::loadConfig('email');

   /**
    * Mail wrapper for the external PHPMailer library
    *
    * @author Art <a.molcanovas@gmail.com>
    * @link   https://github.com/PHPMailer/PHPMailer
    * @todo   Implement changes from the PHPMailer wrapper at work
    */
   class Email extends PHPMailer {

      /**
       * Instantiates the class
       *
       * @author Art <a.molcanovas@gmail.com>
       *
       * @param boolean $exceptions Should we throw external exceptions?
       */
      function __construct($exceptions = false) {
         parent::__construct($exceptions);

         if(ALO_EMAIL_ERR_LANG != 'en') {
            $this->setLanguage(ALO_EMAIL_ERR_LANG);
         }

         $this->isSMTP(ALO_EMAIL_USE_SMTP);
         $this->Host       = ALO_EMAIL_HOSTS;
         $this->SMTPAuth   = ALO_EMAIL_AUTH;
         $this->Username   = ALO_EMAIL_USERNAME;
         $this->Password   = ALO_EMAIL_PASSWORD;
         $this->SMTPSecure = ALO_EMAIL_SECURE;
         $this->Port       = ALO_EMAIL_PORT;
         $this->From       = ALO_EMAIL_FROM_DEFAULT_ADDR;
         $this->FromName   = ALO_EMAIL_FROM_DEFAULT_NAME;
         $this->Subject    = ALO_EMAIL_SUBJECT_DEFAULT;
         $this->isHTML(ALO_EMAIL_HTML_ENABLED);
      }

   }