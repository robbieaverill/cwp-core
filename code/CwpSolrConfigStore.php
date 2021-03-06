<?php

/**
 * Class SolrConfigStore_CWP
 *
 * Uploads configuration to Solr via the PHP proxy CWP uses to filter requests
 */
class CwpSolrConfigStore implements SolrConfigStore {
       function __construct($config) {
               $options = Solr::solr_options();

               $this->url = implode('', array(
                       'http://',
                       isset($config['auth']) ? $config['auth'].'@' : '',
                       $options['host'] . ':' . $options['port'],
                       $config['path']
               ));
               $this->remote = $config['remotepath'];
       }

       function uploadFile($index, $file) {
               $this->uploadString($index, basename($file), file_get_contents($file));
       }

       function uploadString($index, $filename, $string) {
               $targetDir = "{$this->url}/config/$index";

               file_get_contents($targetDir.'/'.$filename, false, stream_context_create(array('http' => array(
                       'method' => 'POST',
                       'header' => 'Content-type: application/octet-stream',
                       'content' => (string)$string
               ))));
       }

       function instanceDir($index) {
               return $this->remote ? "{$this->remote}/$index" : $index;
       }
}
