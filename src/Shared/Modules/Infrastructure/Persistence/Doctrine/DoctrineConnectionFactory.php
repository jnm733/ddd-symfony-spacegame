<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

final class DoctrineConnectionFactory {

    private static array $connections;

    public static function createConnection(string $contextName, array $params): Connection {
        $connection = DoctrineConnectionFactory::getConnection($contextName);
        if (!$connection) {
            $connection = DoctrineConnectionFactory::createAndConnect($params);
            DoctrineConnectionFactory::registerConnection($contextName, $connection);
        }

        return $connection;
    }

    private static function getConnection(string $contextName): ?Connection {
        return (isset(DoctrineConnectionFactory::$connections[$contextName])) ? DoctrineConnectionFactory::$connections[$contextName] : null;
    }

    private static function registerConnection(string $contextName, Connection $connection): void {
        DoctrineConnectionFactory::$connections[$contextName] = $connection;
    }

    private static function createAndConnect(array $params): Connection {
        return DriverManager::getConnection($params);
    }
}