<?php

namespace Artcrud\fields\string;


class StringField
{
    private $name;
    private $config=array();
    private $field_config=array();
    private $table_config=array();

    public function __construct($array=array(),$table_config=array())
    {
        if(isset($array['name'])){
            $this->name=$array['name'];
        }
        if(isset($array) and is_array($array)){
            $this->config=$array;
        }
        $this->table_config=$table_config;
    }

    private function setFieldConfig($name,$type){
        $this->field_config[$name]=$type;

    }

    public function getDefaultValues(){

        return array('length'=>200);
    }

    public function getFieldConfig(){
        return $this->field_config;
    }

    public function getTitleField(){
        return "Строка";
    }

    public function getMigrationCodeUp(){

        $code='$table->string("'.$this->name.'")';
        if(isset($this->config['length'])){
            $code='$table->string("'.$this->name.'","'.$this->config['length'].'")';
        }
        if(isset($this->config['default']) and is_string($this->config['default']) and strlen($this->config['default'])>=1){
           $code.='->default("'.$this->config['default'].'")';
        }
        if(isset($this->config['nullable'])){
            $code.='->nullable()';
        }
        if(isset($this->config['unique'])){
            $code.='->unique()';
        }


        return $code;
    }
    public function isFillable(){
        return true;
    }

    public function getMigrationForNewTable(){
        return false;
    }



    public function getRequestValidate(){
        $length=$this->config['length'];
        $result=" '".$this->config['name']."' => ['required','max:".$length."',";
        return $result;

    }

    public function getTemplateCreate(){
        $path_to_create=__DIR__."/views/create.txt";
        $result=file_get_contents($path_to_create);
        $result=str_replace("[name]",$this->name,$result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        return $result;
    }

    public function getTemplateEdit(){
        $path_to_create=__DIR__."/views/edit.txt";
        $result=file_get_contents($path_to_create);
        $result=str_replace("[name]",$this->name,$result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        return $result;
    }

    public function getTemplateIndex(){
        $path_to_create=__DIR__."/views/index.txt";
        $result=file_get_contents($path_to_create);
        $result=str_replace("[name]",$this->name,$result);
        return $result;
    }
    public function getTemplateShow(){
        $path_to_create=__DIR__."/views/show.txt";
        $result=file_get_contents($path_to_create);
        $result=str_replace("[name]",$this->name,$result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        return $result;
    }

    public function getDataCreate(){
        return null;
    }
    public function getDataEdit(){
        return null;
    }
    public function getMethodForModel(){
        return null;
    }



}
