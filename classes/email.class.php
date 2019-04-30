<?php

class Email {

    public $subject, $content, $attachments, $recipient, $email_type, $from, $cc, $bcc, $reply, $charset, $header;
    public $debug = false;

    public function __construct() {
        $this->_cleanup();
    }

    /** Add Content, and optional attachments */
    public function content($content, $attachments = false) {
        self::attachment($attachments);
        $this->content = $content;
        return $this;
    }

    /** Add Content, and optional attachments */
    public function attachment($attachment) {
        if (is_array($attachment) && isset($attachment['name']) && isset($attachment['file']) && is_file($attachment['file'])) {
            array_push($this->attachments, $attachment);
        } else if (is_array($attachment)) {
            foreach ($attachment as $attachment_part) {
                self::attachment($attachment_part);
            }
        } else if (is_string($attachment) && is_file($attachment)) {
            array_push($this->attachments, $attachment);
        }
        return $this;
    }

    /** Shortcut for Email::recipient */
    public function to($recipient) {
        return self::recipient($recipient);
    }

    /** Add a recipient */
    public function recipient($recipient) {
        if (is_array($recipient)) {
            foreach ($recipient as $recipient_part) {
                self::recipient($recipient_part);
            }
        } else if (is_string($recipient) && Validate::is_email($recipient)) {
            array_push($this->recipient, $recipient);
        }
        return $this;
    }

    /** Set a type eg. html or text */
    public function type($type) {
        if ($type == 'text' || $type == 'txt') {
            $type = 'text/plain';
        }
        if ($type == 'html') {
            $type = 'text/html';
        }
        if (strstr($type, '/')) {
            $this->email_type = $type;
        }
        return $this;
    }

    public function debug() {
        $this->debug = true;
        return $this;
    }

    /** Send Email */
    public function send() {
        $amount_of_recipients = count($this->recipient);
        if ($this->debug) {
            debug('Sending E-Mail to:');
            debug($this->recipient);
        }
        try {
            $PHPMailer = new PHPMailer();
            self::header($PHPMailer);
            //
            if (is_array($this->attachments) && !empty($this->attachments)) {
                foreach ($this->attachments as $index => $attachment) {
                    if (is_array($attachment)) {
                        $PHPMailer->addAttachment($attachment['file'], $attachment['name']);
                    } else if (is_string($attachment)) {
                        $PHPMailer->addAttachment($attachment, File::_name($attachment_filepath));
                    }
                }
            }
            //
            if (strstr($this->email_type, 'html')) {
                $PHPMailer->isHTML(true);
            }
            $PHPMailer->Subject = $this->subject;
            $PHPMailer->Body = $this->content;
            $PHPMailer->AltBody = strip_tags($this->content);
            //
            //
            if ($amount_of_recipients > 1) {
                if ($amount_of_recipients > 100) {
                    $delay = round(10000 / $amount_of_recipients);
                } else if ($amount_of_recipients > 30) {
                    $delay = round(3000 / $amount_of_recipients);
                } else if ($amount_of_recipients > 10) {
                    $delay = round(1000 / $amount_of_recipients);
                } else {
                    $delay = 75;
                }
                foreach ($this->recipient as $index => $to) {
                    if ($index > 0) {
                        usleep($delay);
                    }
                    $PHPMailer->addAddress($to);
                    $PHPMailer->send();
                }
            } else if ($amount_of_recipients == 1) {
                $PHPMailer->addAddress(reset($this->recipient));
                $PHPMailer->send();
            }
        } catch (Exception $e) {
            if ($this->debug) {
                debug($e);
            }
        }

        self::_cleanup();
    }

    #Helpers:

    /** Clean up all internal variables */
    public function _cleanup() {
        $this->subject = '(no subject)';
        $this->content = '';
        $this->attachments = array();
        $this->recipient = array();
        $this->email_type = 'text/plain';
        $this->cc = array();
        $this->bcc = array();
        $this->reply = false;
        $this->charset = 'UTF-8';
        $this->from = false;
    }

    /** Generate the mail-header */
    private function header($PHPMailer) {
        if ($this->reply && Validate::is_email($this->reply)) {
            $PHPMailer->addReplyTo($this->reply);
        }
        if ($this->from && Validate::is_email($this->from)) {
            $PHPMailer->setFrom($this->from);
        }
        if ($this->cc) {
            foreach ($this->cc as $cc) {
                if (Validate::is_email($cc)) {
                    $PHPMailer->addCC($cc);
                }
            }
        }
        if ($this->bcc) {
            foreach ($this->bcc as $bcc) {
                if (Validate::is_email($bcc)) {
                    $PHPMailer->addBCC($bcc);
                }
            }
        }
    }

}
