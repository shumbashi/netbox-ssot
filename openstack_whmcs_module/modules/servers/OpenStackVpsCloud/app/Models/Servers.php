<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\FlavorCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\ImageCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\NetworkCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\SecurityGroupsCacheModel;
use WHMCS\Database\Capsule as DB;

class Servers extends EloquentModel
{
    const TABLE_NAME = 'OpenStackVpsCloud_Servers';

    const SERVER_ID = 'serverID';
    const SERVICE   = 'service';
    const NODE_ID   = 'nodeID';
    const ENDPOINT  = 'endpoint';

    const INTERFACE = 'interface';

    const TENANT_ID     = 'tenantId';
    const API_VERSION   = 'apiVersion';
    const DOMAIN_NAME   = 'domainName';
    const CERTIFICATE   = 'certificate';
    const PROJECT_NAME  = 'projectName';
    const PATH          = 'path';

    const COMPUTE       = 'compute';
    const IDENTITY      = 'identity';
    const IMAGE         = 'image';
    const NETWORK       = 'network';
    const GNOCCHI       = 'gnocchi';
    const VOLUME        = 'volume';
    const VOLUME_V3     = 'volumev3';
    const REGION        = 'region';
    const METRIC        = 'metric';

    const AVAILABLE_IMAGES      = 'availableImages';
    const AVAILABLE_FLAVORS     = 'availableFlavors';
    const AVAILABLE_NETWORKS    = 'availableNetworks';
    const AVAILABLE_SECURITY_GROUPS = 'availableSecurityGroups';
    const AVAILABLE_ENDPOINTS   = 'endpoints';

    /**
     * Table name
     *
     * @var string
     */
    protected $table = self::TABLE_NAME;

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['id', 'serverID', 'service', 'nodeID', 'endpoint'];

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Create OpenStackVPS_Servers table
     */
    public function createTableIfNotExist()
    {
        if (!DB::schema()->hasTable($this->table))
        {
            DB::schema()->create($this->table, function ($table)
            {
                $table->increments('id');
                $table->integer('serverID');
                $table->string('service');
                $table->string('nodeID');
                $table->text('endpoint');
            });
        }
    }

    /**
     * Create record or update if exist
     *
     * @param int $serverID
     * @param string $service
     * @param string|null $nodeID
     * @param string|null $endpoint
     */
    public function createOrUpdate(int $serverID, string $service, string $nodeID = null, string $endpoint = null)
    {
        $this->updateOrCreate(
            [
                self::SERVER_ID => $serverID,
                self::SERVICE   => $service,
            ],
            [
                self::NODE_ID   => $nodeID ? : '',
                self::ENDPOINT  => trim($endpoint) ? : '',
            ]
        );

    }

    /**
     * @param int $serverId
     * @param string $service
     */
    public function deleteRecord(int $serverId, string $service)
    {
        $this->byServerID($serverId)
            ->byService($service)
            ->delete();
    }

    /**
     * @param int $serverId
     * @param string $service
     * @return array|string|null
     * @throws \Exception
     */
    public function getEndpoint(int $serverId, string $service)
    {
        if (!DB::schema()->hasTable($this->table))
        {
            return null;
        }

        $setting =  $this->byServerID($serverId)
            ->byService($service)
            ->first();

        if (in_array($service, [self::AVAILABLE_IMAGES, self::AVAILABLE_NETWORKS, self::AVAILABLE_FLAVORS, self::AVAILABLE_ENDPOINTS, self::AVAILABLE_SECURITY_GROUPS]))
        {

            try {
                $resourcesArray = json_decode($setting->endpoint, true);
            }
            catch (\Throwable $t)
            {
                //TODO: log, and remove
                var_dump($t);
                die();
            }

            return $setting->endpoint ?
                $resourcesArray ? $this->parseCacheResourcesModels($service, $resourcesArray) : unserialize($setting->endpoint) :
                [];
        }

        return $setting->endpoint;
    }

    /**
     * @param string $service
     * @param array $resourcesArray
     * @return ImageCacheModel[]|FlavorCacheModel[]|NetworkCacheModel[]
     * @throws \Exception
     */
    private function parseCacheResourcesModels(string $service, array $resourcesArray): array
    {
        $resourcesModels = [];

        foreach ($resourcesArray as $resourceProps)
        {
            $resourcesModels[] = $this->getResourceModel($service, $resourceProps);
        }

        return $resourcesModels;
    }

    /**
     * @param string $service
     * @param array $resourceProps
     * @return ImageCacheModel|FlavorCacheModel|NetworkCacheModel
     * @throws \Exception
     */
    private function getResourceModel(string $service, array $resourceProps)
    {
        switch ($service)
        {
            case self::AVAILABLE_IMAGES:
                $resourceModel = new ImageCacheModel();
                break;
            case self::AVAILABLE_FLAVORS:
                $resourceModel = new FlavorCacheModel();
                break;
            case self::AVAILABLE_NETWORKS:
                $resourceModel = new NetworkCacheModel();
                break;
            case self::AVAILABLE_SECURITY_GROUPS:
                $resourceModel = new SecurityGroupsCacheModel();
                break;
            default:
                throw new \Exception('Invalid service: ' . $service);
        }

        $resourceModel->fill($resourceProps);

        return $resourceModel;
    }

    /**
     * @param $query
     * @param int $serverId
     * @return mixed
     */
    public function scopeByServerID($query, int $serverId)
    {
        return $query->where($this->table . '.serverID', '=', $serverId);
    }

    /**
     * @param $query
     * @param string $service
     * @return mixed
     */
    public function scopeByService($query, string $service)
    {
        return $query->where($this->table . '.service', '=', $service);
    }

    /**
     * @param $query
     * @param string $nodeID
     * @return mixed
     */
    public function scopeByNodeID($query, string $nodeID)
    {
        return $query->where($this->table . '.nodeID', '=', $nodeID);
    }

    /**
     * Change column type from varchar to text
     * so far, when creating a new table (from schema.sql), the type varchar was set
     */
    public function extendColumnLengthIfTooSmall()
    {
        $column = DB::table('information_schema.columns')->select('*')->where(['table_name' => $this->table, 'COLUMN_NAME' => 'endpoint'])->first();

        if ($column && $column->COLUMN_TYPE !== 'text')
        {
            DB::statement("ALTER TABLE OpenStackVpsCloud_Servers MODIFY endpoint text");
        }

    }

}
