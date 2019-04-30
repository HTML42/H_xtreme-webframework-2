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
        $header = self::header();
        $amount_of_recipients = count($this->recipient);
        if ($this->debug) {
            debug($header);
            debug($this->recipient);
        }
        try {
            $separator = md5(time());
            $eol = "\n";
            $message = $this->content;
            //
            $this->content = "--" . $separator . $eol;
            $this->content .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
            $this->content .= "Content-Transfer-Encoding: 8bit" . $eol;
            $this->content .= $message . $eol;
            //
            if (is_array($this->attachments) && !empty($this->attachments)) {
                foreach ($this->attachments as $index => $attachment) {
                    if (is_array($attachment)) {
                        $attachment_name = $attachment['name'];
                        $attachment_filepath = $attachment['file'];
                    } else if (is_string($attachment)) {
                        $attachment_filepath = $attachment;
                        $attachment_name = File::_name($attachment_filepath);
                    }
                    $content = file_get_contents($attachment_filepath);
                    $content = chunk_split(base64_encode($content));
                    //
                    if ($index > 0) {
                        $this->content .= $eol;
                    }
                    $this->content .= "--" . $separator . $eol;
                    $this->content .= "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\"" . $eol;
                    $this->content .= "Content-Transfer-Encoding: base64" . $eol;
                    $this->content .= "Content-Disposition: attachment" . $eol;
                    $this->content .= $content . $eol;
                    $this->content .= "--" . $separator . "--";
                    //
                    $header = preg_replace('|Content-Type:.+\n|isU', "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol, $header);
                }
            }
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
                    mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $this->content, $header);
                }
            } else if ($amount_of_recipients == 1) {
                mail(reset($this->recipient), '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $this->content, $header);
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

        $this->header = array(
            'MIME-Version' => '1.0',
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Transfer-Encoding' => '8bit'
        );
    }

    /** Generate the mail-header */
    private function header() {
        $header_code = 'Content-type:' . $this->email_type . ';charset=' . $this->charset . ';';
        foreach ($this->header as $header_key => $header_value) {
            $header_code .= "\n" . $header_key . ':' . $header_value . ';';
        }
        if ($this->reply && Validate::is_email($this->reply)) {
            $header_code .= "\n" . 'Reply-To:' . $this->reply . ';';
        }
        if ($this->from && Validate::is_email($this->from)) {
            $header_code .= "\n" . 'From:' . $this->from . ';';
        }
        if ($this->cc) {
            foreach ($this->cc as $cc) {
                if (Validate::is_email($cc)) {
                    $header_code .= "\n" . 'Cc:' . $cc . ';';
                }
            }
        }
        if ($this->bcc) {
            foreach ($this->bcc as $bcc) {
                if (Validate::is_email($cc)) {
                    $header_code .= "\n" . 'Bcc:' . $bcc . ';';
                }
            }
        }
        return $header_code;
    }

}
