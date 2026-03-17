<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Repositories;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;

class BackupsRepository
{
    protected $backupsModel;

    public function __construct()
    {
        $this->backupsModel = new Backups();
    }

    public function getIDsAndPinnedBySource(string $sourceID)
    {
        $data = [];
        foreach($this->backupsModel->where('sourceID', '=', $sourceID)->get() as $key => $row)
        {
            $data[$key]['sourceID'] = $row->sourceID;
            $data[$key]['backupID'] = $row->backupID;
            $data[$key]['pinned']   = (bool) $row->pinned;
        }
        return $data;
    }

    public function changeProtectionStatus(array $backupsIDs)
    {
        $this->backupsModel->whereIn('backupID', $backupsIDs)->update(['pinned' => DB::raw('NOT `pinned`')]);
    }

    public function massDelete(array $backupsIDs)
    {
        $this->backupsModel->whereIn('backupID', $backupsIDs)->delete();
    }
}