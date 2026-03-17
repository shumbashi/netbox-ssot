<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Tasks extends EloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'OpenStackVPSTasks';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['UUID', 'hostingID', 'VMUUID', 'itemID', 'action', 'createDate', 'configs', 'attempt', 'lastAttemptDate', 'message', 'locked', 'finished'];

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

}