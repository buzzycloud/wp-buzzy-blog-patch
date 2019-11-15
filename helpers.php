<?php
function notify_telegram($text) {

    if(!$text){
        return;
    }
    
    $tgChannel = "https://api.telegram.org/bot". getenv('BUZZY_TG_TOKEN') ."/sendMessage";
    
    $postdata = http_build_query(
      array(
          'chat_id' => getenv('BUZZY_TG_CHAT_ID'),
          'text' =>   $text,
          'parse_mode'=> 'html'
      )
    );
    
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    
    $context  = stream_context_create($opts);
    
    $result = file_get_contents($tgChannel, false, $context);
    return $result;
  }

function notify_new_comment(int $id, WP_Comment $comment){
    $text = 'New Comment From '. getenv('BUZZY_APP_NAME') . ': ' .PHP_EOL . $comment->comment_content;
    notify_telegram($text);
}


  // Listen for publishing of a new post
function notify_new_post($new_status, $old_status, $post) {

    if ($post->post_type !== 'post'){
      return;
    }
    $text = '';
  
    if('publish' === $new_status && $post->post_date === $post->post_modified) {
      // publish new post
      $text = 'New post published  in '. getenv('BUZZY_APP_NAME') . ': ' . PHP_EOL . $post->post_title;
    }else if ('publish' === $new_status && $post->post_date !== $post->post_modified){
      // update new post
      $text = 'Post updated in ' . getenv('BUZZY_APP_NAME') . ': ' . PHP_EOL . $post->post_title;
    }
  
    notify_telegram($text);
  
  }