<?php


namespace Artcrud\core;


use Illuminate\Support\Facades\Artisan;

class Migration
{
    private $file_migration=null;
    public function __construct($name_migration){
        Artisan::call('make:migration', [
            'name' => $name_migration
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
        $this->file_migration=$path_to_migration;
    }
    public function getFile(){
        return $this->file_migration;
    }

}
