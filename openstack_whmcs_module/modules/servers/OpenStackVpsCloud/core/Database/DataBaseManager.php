<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Database;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\MySqlBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\Database\Builders\ModuleMySqlBuilder;
/**
 * Class DatabaseManager
 *
 * Manages database operations for the module framework.
 * Provides methods for database schema manipulation and table management.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Database
 */
class DatabaseManager
{
    /**
     * Drop all module-related tables from the database.
     *
     * @return void
     */
    public function dropAllModuleTables()
    {
        $builder = new ModuleMySqlBuilder(Manager::connection());
        $builder->dropAllTables();
    }
}