<?php
//namespace Libraries;
//use Resources;

class Simplerss {

    public function parse($urlrss=array())
    {
          // Set the feed URLs here
          $feeds = $urlrss;

          // Get all feed entries
          $entries = array();
          foreach ($feeds as $feed) {
              $xml = simplexml_load_file($feed);
              $entries = array_merge($entries, $xml->xpath('/rss//item'));
          }


          return $entries;
          
    }
}
