<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class DynamicTranslation extends ExtendedEloquentModel
{
    public $timestamps = true;
    protected $fillable = ['lang', 'regex'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'DynamicTranslation';

    public function scopeByKey($query, $key)
    {
        return $query->where('lang', $key);
    }
}