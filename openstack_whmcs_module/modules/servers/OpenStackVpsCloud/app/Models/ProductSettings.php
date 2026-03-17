<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use WHMCS\Database\Capsule as DB;

class ProductSettings extends EloquentModel
{
    const PID       = 'pid';
    const SETTING   = 'setting';
    const VALUE     = 'value';

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'OpenStackVpsCloud_ProductSettings';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['id', 'pid',  'setting', 'value'];

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

    protected $id;

    /**
     * Create OpenStackVPS_ProductSettings table
     */
    public function createTableIfNotExist()
    {
        if (!DB::schema()->hasTable($this->table))
        {
            DB::schema()->create($this->table, function ($table)
            {
                $table->increments('id');
                $table->integer('pid');
                $table->string('setting');
                $table->string('value');
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
    public function createOrUpdate(int $pid, string $setting, string $value = null)
    {
        $this->updateOrCreate(
            [
                self::PID       => $pid,
                self::SETTING   => $setting,
            ],
            [
                self::VALUE     => $value ? : ''
            ]
        );

    }

    /**
     * @param $query
     * @param int $productID
     * @return mixed
     */
    public function scopeByProductID($query, int $productID)
    {
        return $query->where($this->table . '.pid', '=', $productID);
    }

    /**
     * @param $query
     * @param string $setting
     * @return mixed
     */
    public function scopeBySetting($query, string $setting)
    {
        return $query->where($this->table . '.setting', '=', $setting);
    }

}