<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\ChangingPackage;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\CreationVm;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RebuildingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolume;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolumeProcess;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;

class JobManager
{
    const REBUILD_JOBS = [
        RebuildingVolume::class,
        ChangingPackage::class,
        RestoringVolumeProcess::class,
        RestoringVolume::class
    ];

    const BUILD_JOBS = [
        CreationVm::class,
    ];

    const SCHEDULED_BACKUPS = 'ScheduledBackups';

    public static function getStatus(int $jobId)
    {
        $job = Job::byJobID($jobId)->first();

        return $job->status;
    }

    public function isActiveTask(int $serviceID, string $taskName)
    {
        $result = Job::byServiceID($serviceID)
            ->byJob($taskName)
            ->whereNotIn('status', [Status::FINISHED, Status::FAILED, Status::CANCELLED])
            ->first();

        return $result ? true : false;
    }

    public function deleteTask(int $serviceID, string $taskName)
    {
        Job::byServiceID($serviceID)
            ->byJob($taskName)
            ->delete();
    }

    public static function isBuilding(int $serviceId)
    {
        $query = Job::byServiceID($serviceId)
            ->whereNotIn('status', [Status::FINISHED, Status::FAILED, Status::CANCELLED])
            ->whereIn('job', self::BUILD_JOBS);

        return $query->exists();
    }

    public static function isRebuiling(int $serviceId)
    {
        $query = Job::byServiceID($serviceId)
            ->whereNotIn('status', [Status::FINISHED, Status::FAILED, Status::CANCELLED])
            ->whereIn('job', self::REBUILD_JOBS);

        return $query->exists();
    }

    public static function areCirticalBeingPerformed(int $serviceId): bool
    {
        if (self::isBuilding($serviceId)) {
            return true;
        }

        if (self::isRebuiling($serviceId)) {
            return true;
        }

        return false;
    }
}