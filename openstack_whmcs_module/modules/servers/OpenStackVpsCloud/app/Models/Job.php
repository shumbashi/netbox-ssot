<?php
namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job as CoreJob;
use WHMCS\Database\Capsule as DB;

class Job extends CoreJob
{
    const TYPE_HOSTING = 'Hosting';
    const TABLE_NAME = 'OpenStackVpsCloud_Job';

    /**
     * @param null $id
     * @return Job
     */
    public static function factory($id = null)
    {
        if ($id !== null)
        {
            $job = Job::where('id', $id)->first();

            return $job;
        }

        return new self();
    }

    /**
     * @param $query
     * @param string $jobName
     * @return mixed
     */
    public function scopeByJob($query, string $jobName)
    {
        return $query->where(self::TABLE_NAME.'.job', 'LIKE', '%'. $jobName .'%');
    }

    /**
     * @param $query
     * @param int $jobID
     * @return mixed
     */
    public function scopeByJobID($query, int $jobID)
    {
        return $query->where(self::TABLE_NAME.'.id', '=', $jobID);
    }

    /**
     * @param $query
     * @param int $type
     * @return mixed
     */
    public function scopeByRelType($query, string $type)
    {
        return $query->where(self::TABLE_NAME.'.rel_type', '=', $type);
    }

    /**
     * @param $query
     * @param int $hostingID
     * @return mixed
     */
    public function scopeByServiceID($query, int $hostingID)
    {
        return $query->where(self::TABLE_NAME.'.rel_id', '=', $hostingID);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where(self::TABLE_NAME.'.status', '=', $status);
    }



}