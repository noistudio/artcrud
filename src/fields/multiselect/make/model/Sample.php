<?php

namespace [name_space];

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class [NewTableTitle] extends Model
{
    use HasFactory;

    protected $table = '[NewTable]';


    public function [table_source]_row()
    {
        return $this->hasOne([data_source_model]::class,"[data_source_id]","[data_local_key]");

    }

    public function [table]_row()
    {
        return $this->hasOne([table_source]::class,"id","[data_source2_id]");

    }
}
