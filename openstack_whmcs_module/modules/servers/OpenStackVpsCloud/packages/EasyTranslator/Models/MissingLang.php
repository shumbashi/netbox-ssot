<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class MissingLang extends ExtendedEloquentModel
{
    public $timestamps = true;
    protected $fillable = ['language', 'lang', 'source'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'MissingLang';

    public function scopeByKey($query, $key)
    {
        return $query->where('lang', $key);
    }
}