<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use WHMCS\Database\Capsule as DB;

class JobLog extends \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog
{

    const TABLE_NAME = 'OpenStackVpsCloud_JobLog';

    /**
     * Create OpenStackVpsCloud_Job table
     */
    public function createTableIfNotExist()
    {
        if (!DB::schema()->hasTable(self::TABLE_NAME))
        {
            DB::schema()->create(self::TABLE_NAME, function ($table)
            {
                $table->increments('id');
                $table->integer('job_id')->unsigned();
                $table->string('type');
                $table->string('message');
                $table->text('additional')->nullable();
                $table->timestamps();
                $table->index('job_id');
                $table->index('type');
            });
        }
    }

    /**
     * @param $query
     * @param int $jobId
     * @return mixed
     */
    public static function scopeByJobId($query, int $jobId)
    {
        return $query->where('OpenStackVpsCloud_JobLog.job_id', '=', $jobId);
    }

}