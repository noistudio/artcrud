<?php

namespace Artcrud\controllers;

use Artcrud\FieldModel;
use Artcrud\fields\string\NumberField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CrudController extends Controller
{
    public function create()
    {


        $fields = FieldModel::getFields();

        $data['fields'] = $fields;
        return view("artcrud::create_table", $data);
    }

    public function get_field(){
     $post=request()->post();
     if(isset($post['field'])) {
         $field = FieldModel::get($post['field']);
         $data['field']=$field;
         $data['config']=$field->getFieldConfig();
         if(method_exists($field,"getDefaultValues")){
         $data['defaults_value']=$field->getDefaultValues();
         }
         return view("artcrud::get_field",$data);

     }
    }

    public function docreate(){

        $result=FieldModel::generateConfigFile();
        if(config("artcrud.debug")==true){
            return "plz run php artisan artcrud:table artcrud.txt";
        }else {
            Artisan::call('artcrud:table',array("artcrud.txt"));
            return back()->with("success", "Таблица успешно создана!");
        }
    }
}
