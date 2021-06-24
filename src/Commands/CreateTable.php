<?php

namespace Artcrud\Commands;

use Artcrud\TableModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artcrud:table {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file_path');
        $config = Storage::disk("local")->get($file);
        $config = json_decode($config, true);

        TableModel::makeMigration($config);
       TableModel::makeModel($config);
        TableModel::makeController($config);
       TableModel::makeRoute($config);
        TableModel::makeViews($config);


        return 0;
    }
}
