<?php


namespace App;


use App\Exceptions\RedCapApiServiceException;
use Illuminate\Support\Collection;
use IU\PHPCap\PhpCapException;

class RedCapProject extends \IU\PHPCap\RedCapProject
{
    /**
     * @var array
     */
    protected $recordsCache = [];

    /**
     * @param string $recordId
     * @return Collection
     * @throws RedCapApiServiceException
     */
    public function getRecord(string $recordId): Collection
    {
        if (isset($this->recordsCache[$recordId])) {
            return $this->recordsCache[$recordId];
        }

        try {
            $records = $this->exportRecords('php', 'flat', [$recordId]);
        } catch (PhpCapException $e) {
            throw new RedCapApiServiceException("Failed to export record : '{$recordId}'", 0, $e);
        }

        if (count($records) === 0) {
            throw new RedCapApiServiceException("Record with record id : '{$recordId}' not found", 404);
        }

        return $this->recordsCache[$recordId] = $records;
    }

    /**
     * @param string $filterLogic
     * @return Collection
     * @throws RedCapApiServiceException
     */
    public function filterRecords(string $filterLogic): Collection
    {
        try {
            return new Collection($this->exportRecords('php', 'flat', null, null, null, null, $filterLogic));
        } catch (PhpCapException $e) {
            throw new RedCapApiServiceException("Failed to export records with logic : '{$filterLogic}'", 0, $e);
        }
    }

}
