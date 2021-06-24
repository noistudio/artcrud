<?php

namespace Artcrud\fields\checkbox;


class CheckboxField
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

        return array('default'=>'false');
    }

    public function getFieldConfig(){
        return $this->field_config;
    }

    public function getTitleField(){
        return "Checkbox";
    }

    public function getMigrationCodeUp(){

        $code='$table->boolean("'.$this->name.'")->default(false)';

        if(isset($this->config['default']) and is_string($this->config['default']) ){
            if($this->config['default']=="true" or $this->config['default']=="1"){
                $code='$table->boolean("'.$this->name.'")->default(true)';
            }
        }




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

    public function getDataStore(){
        $path_to_create = __DIR__ . "/controller/store.txt";
        $result=file_get_contents($path_to_create);


        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);
        $result=str_replace("[name]",$this->config['name'],$result);




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
