<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Database;

use Illuminate\Database\Capsule\Manager;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader;

/**
 * Class FileLoader
 *
 * Automates database query execution from SQL files.
 * Provides functionality to read SQL files and execute queries sequentially.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Database
 */
class FileLoader
{
    /**
     * Perform raw queries from a SQL file.
     *
     * @param string $file Path to the SQL file
     * @return bool True if any query failed, false if all succeeded
     */
    public function performQueryFromFile(string $file)
    {
        return $this->checkIsAllSuccess(array_map([$this, 'execute'], $this->getQueries($file)));
    }

    /**
     * Check if all queries were successful.
     *
     * @param array $array Array of query execution results
     * @return bool True if any query failed, false if all succeeded
     */
    protected function checkIsAllSuccess(array $array = [])
    {
        return in_array(false, $array, true);
    }

    /**
     * Parse SQL queries from a file.
     *
     * @param string $file Path to the SQL file
     * @return array Array of SQL query strings
     */
    protected function getQueries($file)
    {
        return array_filter(explode(';', Reader::read($file)->get()), function($element) {
            $tElement = trim($element);
            if ($element === '' || $tElement === '')
            {
                return false;
            }

            return true;
        });
    }

    /**
     * Execute a single SQL query.
     *
     * @param string $query SQL query string to execute
     * @return bool|null True on success, null on empty query, false on failure
     */
    protected function execute($query)
    {
        $pdo = Manager::connection()->getPdo();
        if (empty($query) === false)
        {
            $statement = $pdo->prepare($query);
            $statement->execute();
        }
    }
}
