<?php

namespace MsPedidosApp\adapters\db\external;

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

class MongoRepository
{
    /**
     * @var Manager
     */
    private Manager $connection;

    /**
     * @var String
     */
    private String $namespace;

    /**
     * @var String
     */
    private String $db;

    /**
     * @var string
     */
    private string $lastInsertId;

    /**
     * @var string
     */
    private string $pKey;

    /**
     * @param MongoConnection $connection
     */
    public function __construct(MongoConnection $connection)
    {
        $this->connection = $connection->manager;
    }

    /**
     * @return ObjectId
     */
    public function getId()
    {
        return new ObjectId();
    }

    /**
     * @param string $collection
     * @return void
     */
    public function setNamespace(string $collection):void
    {
        $this->namespace = "{$this->getDb()}.{$collection}";
    }

    /**
     * @return String
     */
    public function getDb(): string
    {
        return $this->db;
    }

    /**
     * @param String $db
     */
    public function setDb(string $db): void
    {
        $this->db = $db;
    }


    /**
     * @param string $pKey
     */
    public function setPKey(string $pKey): void
    {
        $this->pKey = $pKey;
    }

    /**
     * @return string
     */
    public function getLastInsertId(): string
    {
        return $this->lastInsertId;
    }

    /**
     * @param string $lastInsertId
     */
    public function setLastInsertId(string $lastInsertId): void
    {
        $this->lastInsertId = $lastInsertId;
    }

    /**
     * @param array $data
     * @return int|null
     * @throws Exception
     */
    public function create(array $data): ?int
    {
        $bulk = new BulkWrite();
        $id = $bulk->insert($data);
        $write = $this->connection->executeBulkWrite($this->namespace, $bulk);

        $query = new Query(['_id' => new ObjectId($id)]);
        $inserted = $this->connection->executeQuery($this->namespace, $query)->toArray()[0];
        $this->setLastInsertId($inserted->_id->__toString());

        return $write->getInsertedCount();
    }

    /**
     * @param string $id
     * @param array $data
     * @param $pKey
     * @param array $expToUpdate
     * @return int|null
     */
    public function update(string $id, array $data): ?int
    {//print_r($this->getQuery($id));die;
        unset($data['oid']);
        $bulk = new BulkWrite();
        $bulk->update(['_id' => new ObjectId($id)], $data);

        $write = $this->connection->executeBulkWrite($this->namespace, $bulk);

        return $write->getModifiedCount();
    }

    /**
     * @param string $id
     * @return int|null
     */
    public function delete(string $id): ?int
    {
        $bulk = new BulkWrite();
        $bulk->delete(['_id' => new ObjectId($id)]);
        $write = $this->connection->executeBulkWrite($this->namespace, $bulk);

        return $write->getDeletedCount();
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function show(string $id): array
    {
        $doc = (array)$this->connection->executeQuery($this->namespace, $this->getQuery($id))->toArray();
        if(empty($doc)){
            return [];
        }
        return (array)$doc[0];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function list(): array
    {
        $query = new Query([]);

        return $this->connection->executeQuery($this->namespace, $query)->toArray();
    }

    /**
     * @param array $filter
     * @return array
     * @throws Exception
     */
    public function query(array $filter): array
    {
        $query = new Query($filter);

        return $this->connection->executeQuery($this->namespace, $query)->toArray();
    }

    private function getQuery($id)
    {
        return $this->pKey == 'oid'?new Query(['_id' => ['$eq' => new ObjectId($id)]]):new Query([$this->pKey => ['$eq' => $id]]);
    }
}