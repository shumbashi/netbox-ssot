<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

/**
 * Class ExtendedEloquentModel
 *
 * Wrapper for EloquentModel with custom table prefixing and composite key support.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Models
 */
class ExtendedEloquentModel extends EloquentModel
{
    /**
     * ExtendedEloquentModel constructor.
     *
     * Prefixes the table name with the module's database prefix.
     *
     * @param array $attributes Model attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = ModuleConstants::getPrefixDataBase() . $this->table;

        parent::__construct($attributes);
    }

    /**
     * Set the keys for a save update query.
     * Supports composite primary keys.
     *
     * @param Builder $query The Eloquent query builder
     * @return Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys))
        {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName)
        {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     * Supports composite primary keys.
     *
     * @param mixed|null $keyName The key name
     * @return mixed The key value
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName))
        {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName]))
        {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    /**
     * Serialize a date for array/JSON serialization.
     *
     * @param \DateTimeInterface $date The date instance
     * @return string The formatted date string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
