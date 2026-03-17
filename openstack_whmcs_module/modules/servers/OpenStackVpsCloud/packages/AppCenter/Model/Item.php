<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;

class Item extends ExtendedEloquentModel
{
    public $timestamps = false;

    protected $fillable = ['type', 'name', 'description', 'image', 'source', 'status'];
    protected $primaryKey = 'id';

    protected $casts = [];

    protected $table = 'AppItems';

    /**
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('status', AppStatus::STATUS_ACTIVE);
    }

    public function itemConfig()
    {
        return $this->hasMany(ItemConfig::class, 'item_id');
    }

    public function itemsGroups()
    {
        return $this->hasMany(ItemsGroups::class, 'item_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, (new ItemsGroups())->getTable(), 'item_id', 'group_id');
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            $item->itemConfig->each->delete();
            $item->itemsGroups->each->delete();
        });
    }
}
