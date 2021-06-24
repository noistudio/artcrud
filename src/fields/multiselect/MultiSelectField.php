<?php


namespace Artcrud\fields\multiselect;


class MultiSelectField
{
    private $name;
    private $config = array();
    private $field_config = array();
    private $table_config=array();

    public function __construct($array = array(),$table_config=array())
    {
        if (isset($array['name'])) {
            $this->name = $array['name'];
        }
        if (isset($array) and is_array($array)) {
            $this->config = $array;
        }
        $this->table_config=$table_config;
        $this->setFieldConfig("data_table", "string");
        $this->setFieldConfig("data_title", "string");
        $this->setFieldConfig("data_primary_key", "string");
        if (isset($this->config['data_table'])) {
            $this->config['data_model'] = "\\" . config("artcrud.template_model.new_name_space") . "\\" . ucfirst($this->config['data_table']);
            $this->config["source_model"]="\\" . config("artcrud.template_model.new_name_space") . "\\" . ucfirst($this->table_config['name_table']);
        }
    }

    public function getTitleField()
    {
        return "МультиСписок";
    }

    private function setFieldConfig($name, $type)
    {
        $this->field_config[$name] = $type;

    }

    public function createModel(){
        $model=file_get_contents(__DIR__."/make/model/Sample.php");
        $new_name_space = config("artcrud.template_model.new_name_space");
        $model=str_replace("[name_space]",$new_name_space,$model);
        $new_table_title_model=ucfirst($this->table_config['name_table']."".$this->config['data_table']);
        $model=str_replace("[NewTableTitle]",$new_table_title_model,$model);
        $model=str_replace("[NewTable]",$this->table_config['name_table']."_".$this->config['data_table'],$model);
        $model=str_replace("[table_source]",$this->config['data_table'],$model);
        $model=str_replace("[data_source_model]",$this->config['data_model'],$model);
        $model=str_replace("[data_source_id]",$this->config['data_primary_key'],$model);
        $model=str_replace("[data_local_key]","id_".$this->config['data_table'],$model);
        $model=str_replace("[table]",$this->table_config['name_table'],$model);
        $model=str_replace("[table_source]",$this->config['source_model'],$model);
        $model=str_replace("[data_source2_id]","id_".$this->table_config['name_table'],$model);

        $path_to_store = config("artcrud.template_model.path_to_store") . "/" . $new_table_title_model.".php";
        file_put_contents($path_to_store, $model);




    }

    public function getMigrationForNewTable(){
        $up_code_migration=file_get_contents(__DIR__."/make/migration/up.txt");
        $down_code_migration=file_get_contents(__DIR__."/make/migration/down.txt");
        $up_code_migration=str_replace("[data_table]",$this->config['data_table'],$up_code_migration);
        $up_code_migration=str_replace("[name_table_little]",$this->table_config['name_table'],$up_code_migration);
        $up_code_migration=str_replace("%name_table_new%",$this->table_config['name_table']."_".$this->config['data_table'],$up_code_migration);
        $down_code_migration=str_replace("%name_table_new%",$this->table_config['name_table']."_".$this->config['data_table'],$down_code_migration);


       return array('up'=>$up_code_migration,'down'=>$down_code_migration);


    }

    public function getFieldConfig()
    {
        return $this->field_config;
    }

    public function getMigrationCodeUp()
    {

        return false;
    }

    public function isFillable()
    {
        return false;
    }

    public function getRequestValidate()
    {

        return null;

    }

    public function getTemplateCreate()
    {
        $path_to_create = __DIR__ . "/views/create.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[name]", $this->name, $result);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        $result = str_replace("[data_title]", $this->config['data_title'], $result);
        $result = str_replace("[data_primary_key]", $this->config['data_primary_key'], $result);


        return $result;
    }

    public function getTemplateEdit()
    {
        $path_to_create = __DIR__ . "/views/edit.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[name]", $this->name, $result);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        $result = str_replace("[data_title]", $this->config['data_title'], $result);
        $result = str_replace("[data_primary_key]", $this->config['data_primary_key'], $result);
        return $result;
    }

    public function getTemplateIndex()
    {
        $path_to_create = __DIR__ . "/views/index.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[name]", $this->name, $result);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result = str_replace("[data_title]", $this->config['data_title'], $result);
        $result = str_replace("[data_primary_key]", $this->config['data_primary_key'], $result);
        return $result;
    }

    public function getTemplateShow()
    {
        $path_to_create = __DIR__ . "/views/show.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[name]", $this->name, $result);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result=  str_replace("[field.title]",$this->config['title'],$result);
        $result = str_replace("[data_title]", $this->config['data_title'], $result);
        $result = str_replace("[data_primary_key]", $this->config['data_primary_key'], $result);

        return $result;
    }

    public function getDataCreate()
    {
        $path_to_create = __DIR__ . "/controller/create.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result = str_replace("[data_model]", $this->config['data_model'], $result);

        return $result;
    }

    public function getDataStore(){
        $path_to_create = __DIR__ . "/controller/store.txt";
        $result=file_get_contents($path_to_create);
        $result = str_replace("[name]",$this->config['name'],$result);
        $new_model = "\\".config("artcrud.template_model.new_name_space")."\\".ucfirst($this->table_config['name_table']."".$this->config['data_table']);
        $result = str_replace("[data_model2]",$new_model,$result);
        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);
        $result = str_replace("[data_model]", $this->config['data_model'], $result);
        $result=str_replace("[data_table]",$this->config['data_table'],$result);

        return $result;

    }

    public function getDataUpdate(){
        $path_to_create = __DIR__ . "/controller/update.txt";
        $result=file_get_contents($path_to_create);
        $result = str_replace("[name]",$this->config['name'],$result);
        $new_model = "\\".config("artcrud.template_model.new_name_space")."\\".ucfirst($this->table_config['name_table']."".$this->config['data_table']);
        $result = str_replace("[data_model2]",$new_model,$result);
        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);
        $result = str_replace("[data_model]", $this->config['data_model'], $result);
        $result=str_replace("[data_table]",$this->config['data_table'],$result);


        return $result;

    }
    public function getDataEdit()
    {
        $path_to_create = __DIR__ . "/controller/edit.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result = str_replace("[data_model]", $this->config['data_model'], $result);
        $result = str_replace("[name]",$this->config['name'],$result);
        $new_model = "\\".config("artcrud.template_model.new_name_space")."\\".ucfirst($this->table_config['name_table']."".$this->config['data_table']);
         $result = str_replace("[data_model2]",$new_model,$result);
         $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);



        return $result;
    }

    public function getMethodForModel()
    {
        $path_to_create = __DIR__ . "/model/method.txt";
        $result = file_get_contents($path_to_create);
        $result = str_replace("[name]", $this->config['name'], $result);
        $result = str_replace("[data_table]", $this->config['data_table'], $result);
        $result = str_replace("[data_model]", $this->config['data_model'], $result);
        $result = str_replace("[data_primary_key]", $this->config['data_primary_key'], $result);
        $new_table_title_model=ucfirst($this->table_config['name_table']."".$this->config['data_table']);
        $result=  str_replace("[data_model2]",$new_table_title_model,$result);
        $result=str_replace("[name_table_little]",$this->table_config['name_table'],$result);


        return $result;
    }


}

