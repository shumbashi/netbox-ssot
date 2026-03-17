<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Upgrade as CoreUpgrade;

class Upgrade extends CoreUpgrade
{
    const STATUS_PENDING = 'Pending';
    const STATUS_COMPLETED = 'Completed';
    const TYPE_CONFIGOPTIONS = 'configoptions';

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSelectAll($query)
    {
        return $query->select('tblupgrades.*');
    }

    /**
     * @param int $hostingID
     * @return mixed
     */
    public function isPending(int $hostingID)
    {
        return $this->where('relid', '=', $hostingID)
            ->where('status', '=', self::STATUS_PENDING)
            ->where('type', '=', self::TYPE_CONFIGOPTIONS)
            ->where('date', '=', date('Y-m-d', strtotime("now")))
            ->count();
    }

    /**
     * @param int $hostingID
     * @return mixed
     */
    public function isPayed(int $hostingID)
    {
        return $this->where('relid', '=', $hostingID)
            ->where('status', '=', self::STATUS_COMPLETED)
            ->where('type', '=', self::TYPE_CONFIGOPTIONS)
            ->where('date', '=', date('Y-m-d', strtotime("now")))
            ->count();
    }

    /**
     * @param int $optionID
     * @param int $serviceID
     * @return mixed
     */
    public function hasNewValue(int $optionID, int $serviceID)
    {
        return $this->selectAll()
            ->where('relid', '=', $serviceID)
            ->where('originalvalue', 'like', $optionID.'=>%')
            ->count();
    }
}