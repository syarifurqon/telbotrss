<?php
$TOKEN="{ISI_DENGAN_TOKEN_BOT}";

function request_url($method)
{
        global $TOKEN;
        return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}

function getLink($url)
{
        require_once ('simplerss.php');
        
        $rss = new simplerss;
        $items = $rss->parse(array($url));
        $i = 1;
        $result = '';
        foreach($items as $feed)
        {
          $result .=  '<a href="'.$feed->link.'">'.$feed->title.'</a>

';
          if($i == 5) break;
          $i++;
        }

        return $result;
}

function send_reply($chatid, $msgid, $text)
{
    $data = array(
        'parse_mode' => 'HTML',
        'chat_id' => $chatid,
        'text'  => $text,
        'disable_web_page_preview' => true
        //'reply_to_message_id' => $msgid
    );
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents(request_url('sendMessage'), false, $context);
}

function create_response($text)
{
   if(strpos($text, '/about')!== FALSE)
   {
       $result = 
       'Silahkan Kontak Saya di @syarifurqon

Terima Kasih
        ';
   }
   else if(strpos(strtolower($text), '/new')!== FALSE){
       
        $result = '<b>Artikel Terbaru SyariFurqon.ID</b>

';
       $result .= getLink('http://syarifurqon.id/feed/');
   }
   else if(strpos(strtolower($text), '/help')!== FALSE){
       
        $result = '<b>Command List bot</b>

';
       $result .= '<b>/new</b> - Get Update Article Syarifurqon.id
<b>/about</b> - Get Info Developer
<b>/help</b> - Get Command List

';
   }
   else {
       
       $result = 'Senang berkenalan dengan anda';
   }

   return $result;
}


function process_message($message)
{
    $updateid = $message["update_id"];
    $message_data = $message["message"];
    if (isset($message_data["text"])) {
        $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];
        $text = $message_data["text"];
        $response = create_response($text);
        send_reply($chatid, $message_id, $response);
    } 
    return $updateid;
}

$entityBody = file_get_contents('php://input');
$message = json_decode($entityBody, true);
process_message($message);
#echo getLink();
?>