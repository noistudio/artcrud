<?php

namespace Artcrud\fields\slug;


class SlugField
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
        $this->setFieldConfig("slug_field", "string");
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
        return "slug-url";
    }

    public function getMigrationCodeUp(){

        $code='$table->string("'.$this->name.'",300)->nullable()';

//        if(isset($this->config['default']) and is_string($this->config['default']) and strlen($this->config['default'])>=1){
//            $code.='->default("'.$this->config['default'].'")';
//        }
//        if(isset($this->config['nullable'])){
//            $code.='->nullable()';
//        }
//        if(isset($this->config['unique'])){
//            $code.='->unique()';
//        }


        return $code;
    }
    public function isFillable(){
        return false;
    }

    public function getMigrationForNewTable(){
        return false;
    }



    public function getRequestValidate(){
       return null;
    }

    public function getTemplateCreate(){

        return "";
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

    public function getDataCreate()
    {
       return null;
    }

    public function getDataEdit(){
        return null;
    }
    public function getMethodForModel(){
        return null;
    }

    public function getDataStore(){
        $path_to_create = __DIR__ . "/controller/store.txt";
        $result=file_get_contents($path_to_create);


        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);
        $result=str_replace("[name]",$this->config['name'],$result);
        $result=str_replace("[field.slug_field]",$this->config['slug_field'],$result);



        return $result;

    }

    public function getDataUpdate(){
        $path_to_create = __DIR__ . "/controller/update.txt";
        $result=file_get_contents($path_to_create);
        $result = str_replace("[name]",$this->config['name'],$result);

        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);



        return $result;

    }



}
