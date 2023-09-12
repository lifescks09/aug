<?php
//$val = getopt("t:m:s");
//var_export($val);
//sendBulkSMS($val['t'],$val['m'],$val['s']);
//sendBulkSMS($argv[0],$argv[1],$argv[2]);
//echo $argv[0];
//sendBulkSMS($textmessage,$mobilenum,$tmpid);
//sendBulkSMS('You have earned a certificate! Download it from your profile page on DIKSHA.','9849966811','1107169035688387826');
$shortopts  = "";
$shortopts .= "d:";
$shortopts .= "m:"; 
$longopts  = array(
    "text:"
);
$options = getopt($shortopts, $longopts);
var_export($options);
sendBulkSMS($options['text'],$options['m'],$options['d']);
function sendBulkSMS($textmessage,$mobilenum,$tmpid){
       
        $deptSecureKey = '67ad2244-58be-4762-be38-96d18ee2cf74';
        $username = 'mlasia-diksha';
        $password = 'dikshasp@Dic812sp';
        $senderid = 'IDKSHA';
        $enc_password = sha1(trim($password));
        $finalmessage = convert($textmessage);
        $key_one = hash('sha512', trim($username) . trim($senderid) . trim($finalmessage) . trim($deptSecureKey));
        $data = array(
            "username" => $username,
            "password" => $enc_password,
            "senderid" => $senderid,
            "content" => $finalmessage,
            "smsservicetype" => "unicodemsg",
            "bulkmobno" => $mobilenum,
            "key" => trim($key_one),
            "templateid" => $tmpid
        );

        $response = post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);
        // print_r($response); die;
        }

        function convert($body)
    {
        header('Content-Type: text/html; charset=UTF-8');
        $finalmessage = "";
        for ($i = 0; $i < mb_strlen($body, "UTF-8"); $i++) {
            $sss = mb_substr($body, $i, 1, "utf-8");
            $a = 0;
            $abcd = "&#" . ordutf8($sss, $a) . ";";
            $finalmessage .= $abcd;
        }

        return $finalmessage;
    }

      function post_to_url($url, $data)
    {
        $fields = '';
        $newUrl = $url;
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        curl_setopt($post, CURLOPT_SSLVERSION, 6);
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $newUrl);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post); //result from mobile seva server
        echo $result; die;

        if (curl_errno($post)) {
            $error_msg = curl_error($post);
        }

        curl_close($post);
        //return $result;
    }
	   function ordutf8($string, &$offset)
    {
        $code = ord(substr($string, $offset, 1));
        if ($code >= 128) { //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2; //110xxxxx
            else if ($code < 240) $bytesnumber = 3; //1110xxxx
            else if ($code < 248) $bytesnumber = 4; //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset++;
                $code2 = ord(substr($string, $offset, 1)) - 128; //10xxxxxx
                $codetemp = $codetemp * 64 + $code2;
            }
            $code = $codetemp;
        }
        return $code;
	  }
    ?>
