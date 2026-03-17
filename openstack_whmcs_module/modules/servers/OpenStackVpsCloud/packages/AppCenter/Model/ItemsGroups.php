<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class ItemsGroups extends Pivot
{
    public $timestamps = false;

    protected $fillable = ['item_id', 'group_id'];
    protected $primaryKey = 'id';

    protected $table = 'AppItemsGroups';

    public function __construct(array $attributes = [])
    {
        $this->table = ModuleConstants::getPrefixDataBase() . $this->table;

        parent::__construct($attributes);
    }

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
    public function scopeOfGroupId($query, $setting)
    {
        return $query->where('group_id', $setting);
    }
}
