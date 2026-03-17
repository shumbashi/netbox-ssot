<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use WHMCS\Database\Capsule as DB;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\KeyPairModel;

/**
 * Class Keypairs
 * @package ModulesGarden\OpenStackVpsCloud\App\Models
 */
class Keypairs extends EloquentModel
{
    const KEY_PUBLIC = 'public';
    const KEY_PRIVATE = 'private';

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'OpenStackVpsCloud_Keypairs';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['id', 'hid', 'key', 'publicKey', 'date', 'name'];

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
     * Create OpenStackVpsCloud_Keypairs table
     */
    public function createTableIfNotExist()
    {
        if (!DB::schema()->hasTable($this->table))
        {
            DB::schema()->create($this->table, function ($table)
            {
                $table->increments('id');
                $table->integer('hid');
                $table->binary('key');
                $table->binary('publicKey');
                $table->date('date');
                $table->string('name');
            });
        }
    }

    /**
     * @param int $hostingID
     * @param KeyPairModel $sshKey
     */
    public function createOrUpdate(int $hostingID, KeyPairModel $sshKey)
    {
        $this->updateOrCreate(
            [
                'hid' => $hostingID,
            ],
            [
                'key' => encrypt($sshKey->getPrivate()),
                'publicKey' => encrypt($sshKey->getPublic()),
                'date' => date('Y-m-d'),
                'name' => $sshKey->getName(),
            ]
        );
    }

    /**
     * @param $query
     * @param int $hostingId
     * @return mixed
     */
    public function scopeByHostingId($query, int $hostingId)
    {
        return $query->where($this->table . '.hid', '=', $hostingId);
    }

}