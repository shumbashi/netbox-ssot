<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class Logs extends ExtendedEloquentModel
{
    protected $casts = ['data' => 'array'];
    protected $dates = ['date'];
    protected $fillable = ['id', 'type', 'date', 'message', 'data'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'Logs';
}
