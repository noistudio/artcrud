<?php

namespace Artcrud\core;

class Template
{
    private $file;
    private $content;
    function __construct($path_to_file){
        $this->file=$path_to_file;
        $this->content=file_get_contents($this->file);
    }

    function replace($search,$to_replace){
      $this->content=str_replace($search,$to_replace,$this->content);

    }

}
