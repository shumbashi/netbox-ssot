<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class ItemConfig extends ExtendedEloquentModel
{
    protected $table = 'AppItemConfig';

    protected $fillable = ['item_id', 'setting', 'value', 'source', 'description', 'protected', 'field', 'options', 'validator', 'visible'];
    protected $primaryKey = 'id';

    protected $casts = [
        'value' => 'json',
        'options' => 'array',
        'validator' => 'array'
    ];

    public $timestamps = false;

    /**
     * @param $query
     * @param $itemId
     * @return mixed
     */
    public function scopeOfItemId($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * @param $query
     * @param $setting
     * @return mixed
     */
    public function scopeOfSetting($query, $setting)
    {
        return $query->where('setting', $setting);
    }

    public function appItem()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function setValidatorAttribute($value)
    {
        $this->attributes['validator'] = empty($value) ? null : json_encode(is_array($value) ? $value : [$value]);

        return $this;
    }
}
