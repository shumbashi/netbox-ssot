<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Backups extends EloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'OpenStackVpsCloud_Backups';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['id', 'sourceID', 'backupID', 'backupName', 'pinned'];

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