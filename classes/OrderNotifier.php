<?php
class OrderNotifier {

    private $date;
    const FROM_EMAIL = 'order@3ddesigncanada.com';
    //const TO_EMAIL   = 'info@3ddesigncanada.com';
    const TO_EMAIL   = 'cyan.you.are@gmail.com';
    const FROM_NAME  = 'Wallpaper';
    
    function __construct()
    {
        $this->date = date("Y-m-d H:i:s");
    }
    
    public static function sanitizer($str){
        return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
    }

    private function buildHeaders()
    {
        $name  = self::FROM_NAME;
        $email = self::FROM_EMAIL;
        $headers  = <<<HEAD
                  MIME-Version: 1.0
                  Content-type: text/html; charset=iso-8859-1
                  From: $name <$email>
                  Reply-To: $email
                  Date: $this->date
                  HEAD;
        return $headers;
    }

    private function buildBody($fields)
    {
        $html  = '<h2>You recived new order.</h2>';
        $table = '<table>';
        foreach ($fields as $key => $field) {
            if($key === 'Thumbnail') {
                $field = urldecode($field);
                $field = str_replace(' ', '+', $field);
                $field = '<img src="' . $field . '"></img>';
            }
            if(!empty($field))
                $table .= <<<BODY
                    <tr>
                     <td><strong>$key</strong></td>
                     <td><i>$field</i></td>
                    </tr>
                    BODY;
            }
        $table .= '</table>';
        $html  .= $table;
        return $html;
    }

    public function sendEmail($fields)
    {
        if (!empty($fields)) {
            $fields  = $this->sanitizer($fields);
            $headers = $this->buildHeaders();
            $body    = $this->buildBody($fields);
            $subject = "New order recived!";
            $res     = mail (self::TO_EMAIL, $subject, $body, $headers);
        } else {
            $res = false;
        }
        return $res;
    }
    
}