<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

class ModuleCache extends ExtendedEloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'ModuleCache';

    protected $primaryKey = 'name';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = ['name'];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['name', 'value', 'valid_until'];

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = false;

    public $timestamps = false;
}
