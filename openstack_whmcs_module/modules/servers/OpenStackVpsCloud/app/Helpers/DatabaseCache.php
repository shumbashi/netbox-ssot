<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Models\ModuleCache;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;

use Illuminate\Database\Capsule\Manager as DB;
/**
 * Helper for caching data in database
 * stores json in the $modelClass
 */

class DatabaseCache
{
    /**
     * @var string
     */
    protected $modelClass = ModuleCache::class;


    protected $data = null;

    /**
     * @var int
     * timestamp of the last update
     */
    protected $validUntil = null;

    /**
     * @var int
     * valid period for stored data, after this it will be autoupdated by callback
     * in secounds like a timestamp
     */
    protected $validPeriod = 300;

    protected $dataKey = null;

    protected $model = null;

    /**
     * @var callable
     * function returning data for the key
     */
    protected $callback = null;

    protected $assocJsonDecode = false;

    public function __construct($key, $callback, $timeout = 300, $assoc = false, $forceReload = false)
    {
        $this->model           =  new $this->modelClass;
        $this->dataKey         = $key;
        $this->validPeriod     = (int)$timeout;
        $this->callback        = $callback;
        $this->assocJsonDecode = $assoc;

        $this->initLoadProcess($forceReload);
        $this->deleteOldData();
    }

    /**
     * wrapper for loading data process
     * @param bool $forceReload
     */
    protected function initLoadProcess($forceReload = false)
    {
        if ($forceReload)
        {
            $this->updateRemoteData();

            return;
        }

        $this->loadDataFromDb();

        if (!$this->isDataValid())
        {
            $this->updateRemoteData();
        }
    }

    protected function deleteOldData()
    {
        $this->model->where('valid_until', '<', time())->delete();
    }

    /**
     * loads remote data and updates to local storage
     */
    protected function updateRemoteData()
    {
        try {
            $data = $this->loadRemoteData();
        }
        catch (\Exception $exception) {
            Logger::error(LoggerMessages::EXCEPTION, [
                'message' => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString()
            ]);

            throw $exception;
        }

        $this->validUntil = time() + $this->validPeriod;
        $this->updateDbCache($data, $this->validUntil);

        $this->data = $data;

    }

    /**
     * updates data in database
     */
    protected function updateDbCache($data, $validUntil)
    {
        $dbData = $this->model->where('name', $this->dataKey)->first();
        if ($dbData)
        {
            $dbData->update([
                'value'       => json_encode($data),
                'valid_until' => $validUntil
            ]);
        }
        else
        {
            $table = $this->model->getTable();
            $encodedData = json_encode($data);
            $command = "REPLACE INTO `{$table}` VALUES (?, ?, ?)";
            DB::statement($command, [$this->dataKey, $encodedData, $validUntil]);
        }
    }

    /**
     * using callback function to load data from custom source
     */
    protected function loadRemoteData()
    {
        if (!is_callable($this->callback))
        {
            throw new \Exception('Provided callback is not callable', ['callback' => $this->callback]);
        }

        $data = call_user_func_array($this->callback, []);
        return $data;
    }

    /**
     * returns loaded data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * static wrapper for creating instance and retriving data
     */
    public static function loadData($key, $callback, $timeout = 300, $assoc = false, $forceReload = false)
    {
        $loader = new DatabaseCache($key, $callback, $timeout, $assoc, $forceReload);

        return $loader->getData();
    }

    /**
     * loads data stored in DB
     */
    protected function loadDataFromDb()
    {
        $dbData = $this->model->where('name', $this->dataKey)->first();

        if (!$dbData)
        {
            return false;
        }

        $this->data = json_decode($dbData->value, $this->assocJsonDecode);
        $this->validUntil = $dbData->valid_until;
    }

    /**
     * Check if data is still befeore renewal time
     */
    protected function isDataValid()
    {
        if (!$this->data || !$this->validUntil)
        {
            return false;
        }

        if (time() > $this->validUntil)
        {
            return false;
        }

        return true;
    }
}
