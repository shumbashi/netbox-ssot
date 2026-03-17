<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use WHMCS\Database\Capsule as DB;

class Settings extends EloquentModel
{
    const SERVICE_ID = 'serviceID';
    const SETTING = 'setting';
    const VALUE = 'value';


    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'OpenStackVpsCloud_Settings';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['id', 'serviceID', 'setting', 'value'];

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
     * Create OpenStackVPS_Settings table
     */
    public function createTableIfNotExist()
    {
        if (!DB::schema()->hasTable($this->table))
        {
            DB::schema()->create($this->table, function ($table)
            {
                $table->increments('id');
                $table->integer('serviceID');
                $table->string('setting');
                $table->string('value');
            });
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSelectAll($query)
    {
        return $query->select('OpenStackVpsCloud_Settings.*');
    }

    /**
     * @param $query
     * @param $serviceID
     * @return mixed
     */
    public function scopeByServiceID($query, int $serviceID)
    {
        return $query->where('OpenStackVpsCloud_Settings.serviceID', '=', $serviceID);
    }

    /**
     * @param $query
     * @param $setting
     * @return mixed
     */
    public function scopeBySetting($query, string  $setting)
    {
        return $query->where('OpenStackVpsCloud_Settings.setting', '=', $setting);
    }

    /**
     * @param int $serviceID
     * @param string $settingName
     * @param string $value
     */
    public function setSetting(int $serviceID, string $settingName, string $value)
    {
        $this->updateOrCreate(
            [
                self::SERVICE_ID => $serviceID,
                self::SETTING    => $settingName,
            ],
            [
                self::VALUE     => $value,
            ]
        );

    }

    /**
     * @param int $serviceID
     */
    public function deleteSettingsByServiceID(int $serviceID)
    {
        $this->byServiceID($serviceID)->delete();
    }
}