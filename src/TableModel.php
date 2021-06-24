<?php


namespace Artcrud;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class TableModel
{


    static function makeMigration($config)
    {
        print("starting make migration\n");
        Artisan::call('make:migration', [
            'name' => 'crud_' . $config['name_table']
        ]);

        $path_to_migrations = base_path("database/migrations");
        $files = scandir($path_to_migrations);
        $file_migrate = null;
        foreach ($files as $key => $file) {
            $next_key = $key + 1;
            if (!isset($files[$next_key])) {
                $file_migrate = $files[$key];
            }
        }
        $path_to_migration = base_path("database/migrations/" . $file_migrate);
        $content_migration = file_get_contents($path_to_migration);
        $occurrence = strpos($content_migration, "//");
        $content_migration = substr_replace($content_migration, "[upcode]", strpos($content_migration, "//"), strlen("//"));
        $occurrence = strpos($content_migration, "//");
        $content_migration = substr_replace($content_migration, "[downcode]", strpos($content_migration, "//"), strlen("//"));
        //
        $template_migration_up = config('artcrud.template_migration_up');
        $template_migration_up = str_replace("%name_table%", $config['name_table'], $template_migration_up);
        $template_migration_down = config('artcrud.template_migration_down');
        $template_migration_down = str_replace("%name_table%", $config['name_table'], $template_migration_down);
        $code_up = "";
        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            $tmp_code=$field->getMigrationCodeUp();
            if(!is_null($tmp_code)) {
                $code_up .= $tmp_code . ";";
            }
        }



        $template_migration_up = str_replace("//code//", $code_up, $template_migration_up);

        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            if(method_exists($field,"getMigrationForNewTable")) {
                $tmp_code = $field->getMigrationForNewTable();
                if (is_array($tmp_code)) {
                    $template_migration_up .= $tmp_code['up'];
                    $template_migration_down.=$tmp_code['down'];
                }
            }
        }
        $content_migration = str_replace("[upcode]", $template_migration_up, $content_migration);
        $content_migration = str_replace("[downcode]", $template_migration_down, $content_migration);


        $path_to_migrations = base_path("database/migrations");
        file_put_contents($path_to_migration, $content_migration);
        Artisan::call('migrate');
        print(" migration success creating \n");
        return true;


    }

    static function makeModel($config)
    {
        $path_to_model = config("artcrud.template_model.path_to_model");
        $new_name_space = config("artcrud.template_model.new_name_space");
        $path_to_store = config("artcrud.template_model.path_to_store") . "/" . ucfirst($config['name_table'] . ".php");
        $sample_model = file_get_contents($path_to_model);
        $sample_model = str_replace("[name_space]", $new_name_space, $sample_model);
        $sample_model = str_replace("[class]", ucfirst($config['name_table']), $sample_model);
        $sample_model = str_replace("[table]", $config['name_table'], $sample_model);
        $fillable_fields = array();
        $fillable_text = "";
        $additonal_methods="";
        $casts_field="";

        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            if ($field->isFillable()) {
                $fillable_fields[] = $field_row['name'];
                $fillable_text .= "'" . $field_row['name'] . "',";
            }
            $tmp_method=$field->getMethodForModel();
            if(!is_null($tmp_method)){
                $additonal_methods.=$tmp_method;
            }
            if(method_exists($field,"createModel")){
                $field->createModel();
            }
            if(method_exists($field,"getModelCastField")){
                $casts_field.=$field->getModelCastField();
            }


        }
        $additonal_methods.="//additional_methods//";



        $sample_model = str_replace("[fillable]", $fillable_text, $sample_model);
        $sample_model = str_replace("[casts_field]",$casts_field,$sample_model);
        $sample_model = str_replace("//additional_methods//",$additonal_methods,$sample_model);
        file_put_contents($path_to_store, $sample_model);
        print($path_to_store . " model is creating!\n");
        return true;
    }

    static function makeRoute($config)
    {
        $route_url_prefix = config("artcrud.template_route.route_url_prefix") . $config['name_table'];
        $route_name_prefix = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'];
        $route_function = file_get_contents(config("artcrud.template_route.template_path"));
        $route = file_get_contents(config("artcrud.template_route.route_store_path"));
        $namespace_controller = config("artcrud.template_controller.new_name_space") . "\\" . ucfirst($config['name_table']) . "Controller";
        $route_function = str_replace("[url]", $route_url_prefix, $route_function);
        $route_function = str_replace("[namespace_controller]", $namespace_controller, $route_function);
        $route_function = str_replace("[name]", $route_name_prefix, $route_function);
        $route_function = str_replace("[name_table_little]", $config['name_table'], $route_function);
        $route_function .= "


        //insert_dynamic_routes_heere//";
        $route = str_replace("//insert_dynamic_routes_heere//", $route_function, $route);

        file_put_contents(config("artcrud.template_route.route_store_path"), $route);
        print(config("artcrud.template_route.route_store_path") . " route file is updating!\n");

        return true;

    }

    static function makeController($config)
    {
        $path_to_controller = config("artcrud.template_controller.template_path");
        $current_namespace = config("artcrud.template_controller.new_name_space");
        $store_controller = config("artcrud.template_controller.store_controller") . "/" . ucfirst($config['name_table']) . "Controller.php";
        $namespace_model = config("artcrud.template_model.new_name_space") . "\\" . ucfirst($config['name_table']);

        $controller = file_get_contents($path_to_controller);
        $current_namespace_controller = substr($current_namespace, 1);

        $controller = str_replace("[current_name_space]", $current_namespace_controller, $controller);
        $controller = str_replace("[namespace_model]", $namespace_model, $controller);
        $controller = str_replace("[name_table]", ucfirst($config['name_table']), $controller);
        $controller = str_replace("[name_table_little]", $config['name_table'], $controller);

        $create_alias = config("artcrud.template_views.create.short_alias") . "." . $config['name_table'] . ".create";
        $edit_alias = config("artcrud.template_views.edit.short_alias") . "." . $config['name_table'] . ".edit";
        $index_alias = config("artcrud.template_views.index.short_alias") . "." . $config['name_table'] . ".index";
        $show_alias = config("artcrud.template_views.show.short_alias") . "." . $config['name_table'] . ".show";
        $controller = str_replace("[VIEW_INDEX]", $index_alias, $controller);
        $controller = str_replace("[VIEW_CREATE]", $create_alias, $controller);
        $controller = str_replace("[VIEW_EDIT]", $edit_alias, $controller);
        $controller = str_replace("[VIEW_SHOW]", $show_alias, $controller);
        $route_index = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".index";
        $route_edit = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".edit";
        $controller = str_replace("[ROUTE_INDEX]", $route_index, $controller);
        $controller = str_replace("[ROUTE_EDIT]",$route_edit,$controller);
        $request_validate_create = "";
        $request_validate_update ="";
        $data_create="";
        $data_edit="";
        $data_store="";
        $data_update="";


        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            $tmp_validate=$field->getRequestValidate();
            if(!is_null($tmp_validate)) {
                $field_validate_create=$field->getRequestValidate();
                $field_validate_update=$field_validate_create;
                if(isset($field_row['unique'])){
                    $field_validate_update.='Rule::unique("'.$config['name_table'].'")->ignore($'.$config['name_table'].'->id),';
                    $field_validate_create.='"unique:'.$namespace_model.','.$field_row['name'].'",';
                }
                $field_validate_create.="],";
                $field_validate_update.="],";
                $request_validate_create .= $field_validate_create;
                $request_validate_update.=$field_validate_update;
            }
            $tmp_create=$field->getDataCreate();
            $tmp_edit=$field->getDataEdit();
            if(!is_null($tmp_create)){
              $data_create.=$tmp_create;
            }
            if(!is_null($tmp_edit)){
                $data_edit.=$tmp_edit;
            }
            if(method_exists($field,"getDataStore")){
                $data_store.=$field->getDataStore();
            }
            if(method_exists($field,"getDataUpdate")){
                $data_update.=$field->getDataUpdate();
            }
        }


        $data_create.="//data_create//";
        $data_edit.="//data_edit//";



        $data_update=str_replace("[name_table_little]",$config['name_table'],$data_update);

        $data_store=str_replace("[name_table_little]",$config['name_table'],$data_store);
        $data_update=str_replace('$'.$config['name_table'].'->save();',"",$data_update);
        $data_update.='$'.$config['name_table'].'->save();';
        $data_store=str_replace('$'.$config['name_table'].'->save();',"",$data_store);
        $data_store.='$'.$config['name_table'].'->save();';



        $controller=str_replace("//data_create//",$data_create,$controller);
        $controller=str_replace("//data_edit//",$data_edit,$controller);
        $controller=str_replace("//update//",$data_update,$controller);
        $controller=str_replace("//store//",$data_store,$controller);
        $controller = str_replace("[REQUEST_VALIDATE_CREATE]", $request_validate_create, $controller);
        $controller = str_replace("[REQUEST_VALIDATE_UPDATE]", $request_validate_update, $controller);
        file_put_contents($store_controller, $controller);
        print($store_controller . " controller is creating!\n");
        return true;
    }

    static function makeViews($config)
    {
        $view_folder = config("artcrud.template_views.create.path_to_store") . "/" . $config['name_table'];

        $route_index = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".index";
        $route_name=config("artcrud.template_route.route_name_prefix").".".$config['name_table'];
        $route_create = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".create";
        $route_show = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".show";
        $route_edit = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".edit";
        $route_store = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".store";
        $route_update = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".update";
        $route_destroy = config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'] . ".destroy";


        $create_path = config("artcrud.template_views.create.path_to_view");
        $create_to_view = config("artcrud.template_views.create.path_to_store") . "/" . $config['name_table'] . "/create.blade.php";
        $create_alias = config("artcrud.template_views.create.short_alias") . "." . $config['name_table'] . ".create";
        $create = file_get_contents($create_path);
        $create = str_replace("[ROUTE_INDEX]", $route_index, $create);
        $create = str_replace("[ROUTE_STORE]", $route_store, $create);
        $create_fields_html = "";
        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            $create_fields_html .= $field->getTemplateCreate();

        }
        $create = str_replace("[CREATE_FIELDS]", $create_fields_html, $create);
        $create=str_replace("[TABLE.TITLE]",$config['title_table'],$create);


        $edit_path = config("artcrud.template_views.edit.path_to_view");
        $edit_to_view = config("artcrud.template_views.edit.path_to_store") . "/" . $config['name_table'] . "/edit.blade.php";
        $edit_alias = config("artcrud.template_views.edit.short_alias") . "." . $config['name_table'] . ".edit";

        $edit = file_get_contents($edit_path);
        $edit = str_replace("[ROUTE_INDEX]", $route_index, $edit);
        $edit = str_replace("[ROUTE_UPDATE]", $route_update, $edit);


        $edit_fields_html = "";
        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            $edit_fields_html .= $field->getTemplateEdit();

        }
        $edit = str_replace("[EDIT_FIELDS]", $edit_fields_html, $edit);

        $edit = str_replace("[name_table_little]", $config['name_table'], $edit);
        $edit=str_replace("[TABLE.TITLE]",$config['title_table'],$edit);
        $edit=str_replace("[ROUTE_PREFIX]",config("artcrud.template_route.route_name_prefix") . "." . $config['name_table'],$edit );


        $index_path = config("artcrud.template_views.index.path_to_view");
        $index_head=config("artcrud.template_views.index.head_title");
        $index_head_content=file_get_contents($index_head);
        $index_to_view = config("artcrud.template_views.index.path_to_store") . "/" . $config['name_table'] . "/index.blade.php";
        $index_alias = config("artcrud.template_views.index.short_alias") . "." . $config['name_table'] . ".edit";

        $index = file_get_contents($index_path);
        $index = str_replace("[ROUTE_CREATE]", $route_create, $index);
        $index = str_replace("[ROUTE_DESTROY]", $route_destroy, $index);
        $index = str_replace("[ROUTE_SHOW]", $route_show, $index);
        $index = str_replace("[ROUTE_EDIT]", $route_edit, $index);
        $index = str_replace("[ROUTE_NAME]",$route_name,$index);

        $index_fields_html = "";
        $index_fields_title="";
        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            if(isset($field_row['list'])) {
                $index_fields_html .= $field->getTemplateIndex();
                $tmp_fields_title=$index_head_content;
                $tmp_fields_title=str_replace("[field.title]",$field_row['title'],$tmp_fields_title);

                $index_fields_title.=$tmp_fields_title;
            }

        }
        $index = str_replace("[TABLE_NAME]",$config['name_table'],$index);
        $index=str_replace("[TABLE.TITLE]",$config['title_table'],$index);
        $index = str_replace("[TITLE_FIELDS]", $index_fields_title, $index);
        $index = str_replace("[FIELD_LIST]", $index_fields_html, $index);
        $index = str_replace("[name_table_little]", $config['name_table'], $index);



        $show_path = config("artcrud.template_views.show.path_to_view");
        $show_to_view = config("artcrud.template_views.show.path_to_store") . "/" . $config['name_table'] . "/show.blade.php";
        $show_alias = config("artcrud.template_views.show.short_alias") . "." . $config['name_table'] . ".show";
        $show = file_get_contents($show_path);
        $show = str_replace("[ROUTE_INDEX]", $route_index, $show);
        $show_fields_html = "";
        foreach ($config['fields'] as $field_row) {
            $field = new $field_row['class']($field_row,$config);
            $show_fields_html .= $field->getTemplateShow();

        }

        $show = str_replace("[FIELDS_SHOW]", $show_fields_html, $show);
        $show = str_replace("[name_table_little]", $config['name_table'], $show);
        $show=str_replace("[TABLE.TITLE]",$config['title_table'],$show);

        if (!file_exists($view_folder)) {
            mkdir($view_folder);
        }

        file_put_contents($create_to_view, $create);
        print($create_to_view . " success generate!\n");
        file_put_contents($edit_to_view, $edit);
        print($edit_to_view . " success generate!\n");
        file_put_contents($index_to_view, $index);
        print($index_to_view . " success generate!\n");
        file_put_contents($show_to_view, $show);
        print($show_to_view . " success generate!\n");

        return true;
    }


}
