<?php


namespace Artcrud;

use Illuminate\Support\Facades\Storage;
class FieldModel
{

    static function getFields()
    {
        $list_fields = config("artcrud.fields");



        $fields = array();
        if (isset($list_fields) and is_array($list_fields) and count($list_fields) > 0) {

            foreach ($list_fields as $class_field) {

                $field = new $class_field();

                $fields[$class_field] = $field;
            }
        }

        return $fields;
    }

    static function get($field_class)
    {
        $list_fields = config("artcrud.fields");
        if (in_array($field_class, $list_fields)) {
            $field = new $field_class();
            return $field;
        }

        return null;

    }

    static function generateConfigFile()
    {
        $post = request()->post();
        $result_array = array();
        $result_array['name_table'] = $post['name_table'];
        $result_array['title_table'] = $post['title_table'];
        if(isset($post['start_migrate'])){
            $result_array['start_migrate']=1;
        }
        $result_array['fields'] = array();
        if (isset($post['fields']) and is_array($post['fields']) and count($post['fields']) > 0) {
            foreach ($post['fields'] as $field) {
                if (isset($field['name']) and isset($field['class'])) {
                    if (FieldModel::get($field['class'])) {
                        $result_array['fields'][] = $field;
                    }
                }
            }
        }
        Storage::disk('local')->put('artcrud.txt', json_encode($result_array));

     //  $result=Storage::disk("local")->get("example.txt");

       // var_dump($result_array);

    }
}
