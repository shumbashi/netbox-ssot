<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class Lang extends ExtendedEloquentModel
{
    public $timestamps = true;
    protected $fillable = ['id', 'language', 'lang', 'value', 'dynamic', 'created_at', 'updated_at'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'EasyTranslator';

    public function scopeLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeStatics($query)
    {
        return $query->where('dynamic', "!=", 1);
    }

    public function scopeDynamics($query)
    {
        return $query->where('dynamic', 1);
    }

    public function isDynamic():bool
    {
        return (bool)$this->dynamic;
    }
}