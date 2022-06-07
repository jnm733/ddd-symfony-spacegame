<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class DoctrineRepository {

    private Connection $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    protected abstract function tableName(): string;

    protected function connection(): Connection {
        return $this->connection;
    }

    protected function queryBuilder(): QueryBuilder {
        return $this->connection->createQueryBuilder();
    }

    protected function insert(array $data, string $tableName=null): void {
        $queryBuilder = $this->queryBuilder();
        $queryBuilder->insert(($tableName) ?: $this->tableName());

        $values = [];
        foreach ($data as $field => $value)
            $values[$field] = ":$field";

        $queryBuilder->values($values);

        foreach ($data as $field => $value)
            $queryBuilder->setParameter($field, $value);

        $queryBuilder->executeQuery();
    }

    protected function update(array $data, string $tableName=null): void {
        $queryBuilder = $this->queryBuilder();

        $queryBuilder->update(($tableName) ?: $this->tableName())
            ->where('id = :id')->setParameter('id', $data['id']);

        foreach ($data as $field => $value)
            $queryBuilder->set($field, ":$field")->setParameter($field, $value);

        $queryBuilder->executeQuery();
    }
}