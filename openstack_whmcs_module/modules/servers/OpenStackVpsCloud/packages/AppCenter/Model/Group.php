<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class Group extends ExtendedEloquentModel
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];
    protected $primaryKey = 'id';

    protected $table = 'AppGroups';

    public function itemsGroups()
    {
        return $this->hasMany(ItemsGroups::class, 'group_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, (new ItemsGroups())->getTable(), 'group_id', 'item_id');
    }

    protected static function booted()
    {
        static::deleting(function ($group) {
            $group->itemsGroups()->delete();
        });
    }
}
