<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\Shared\Modules\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\Repositories\CanvasRepository;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;

final class DoctrineCanvasRepository  extends DoctrineRepository implements CanvasRepository {

    private CanvasMapper $mapper;

    public function __construct(Connection $connection, CanvasMapper $mapper) {
        parent::__construct($connection);
        $this->mapper = $mapper;
    }

    public function findCanvasByName(CanvasName $name): ?Canvas {
        return $this->findCanvas($name);
    }

    public function saveCanvas(Canvas $canvas): void {
        $canvasDBMap = $this->mapper::toPrimitives($canvas);
        $canvasDB = $this->findCanvas(null, $canvas->id());
        //Insert
        if (!$canvasDB) {
            $this->_saveCanvas($canvasDBMap);

            if ($canvasDBMap['spaceship'])
                $this->saveSpaceship($canvasDBMap['spaceship']);

            if ($canvasDBMap['obstacles'])
                foreach ($canvasDBMap['obstacles'] as $obstacle)
                    $this->saveObstacle($obstacle);
        }
        //Update
        else {
            $this->_saveCanvas($canvasDBMap, true);

            if ($canvasDBMap['spaceship'])
                $this->saveSpaceship($canvasDBMap['spaceship'], true);
            else
                $this->queryBuilder()->delete('spaceships')->where('id_canvas = :id_canvas')->setParameter('id_canvas', $canvasDBMap['id']);

            if ($canvasDBMap['obstacles']) {
                foreach ($canvasDBMap['obstacles'] as $obstacle) {
                    $this->saveObstacle($obstacle, $this->existsObstacle($obstacle['id']));
                }
            } else
                $this->queryBuilder()->delete('obstacles')->where('id_canvas = :id_canvas')->setParameter('id_canvas', $canvasDBMap['id']);
        }
    }

    protected function tableName(): string {
        return 'canvas';
    }

    private function _saveCanvas(array $canvas, bool $update=false): void {
        unset($canvas['spaceship']);
        unset($canvas['obstacles']);
        if ($update)
            $this->update($canvas);
        else
            $this->insert($canvas);
    }

    private function saveSpaceship(array $spaceship, bool $update=false): void {
        if ($update)
            $this->update($spaceship, 'spaceships');
        else
            $this->insert($spaceship, 'spaceships');
    }

    private function saveObstacle(array $obstacle, bool $update=false): void {
        if ($update)
            $this->update($obstacle, 'obstacles');
        else
            $this->insert($obstacle, 'obstacles');
    }

    private function findCanvas(CanvasName $name=null, UuidValueObject $id=null): ?Canvas {

        $queryBuilder = $this->queryBuilder()->select('*')
            ->from($this->tableName())
            ->setMaxResults(1);

        if ($name)
            $queryBuilder->where('name = :name')->setParameter('name', $name->value());

        if ($id)
            $queryBuilder->where('id = :id')->setParameter('id', $id->value());

        $canvas = $queryBuilder->fetchAssociative();

        if ($canvas) {
            $spaceship = $this->queryBuilder()->select('*')->from('spaceships')->where('id_canvas = :id_canvas')->setParameter('id_canvas', $canvas['id'])->setMaxResults(1)->fetchAssociative();
            $obstacles = $this->queryBuilder()->select('*')->from('obstacles')->where('id_canvas = :id_canvas')->setParameter('id_canvas', $canvas['id'])->fetchAllAssociative();

            $canvas['spaceship'] = ($spaceship) ?: null;
            $canvas['obstacles'] = (!empty($obstacles)) ? $obstacles : [];

            return $this->mapper::fromPrimitives($canvas);
        }
        return null;
    }

    private function existsObstacle(string $id): bool {

        $queryBuilder = $this->queryBuilder()->select('id')
            ->from('obstacles')
            ->where('id = :id')->setParameter('id', $id)
            ->setMaxResults(1);

        $obstacle = $queryBuilder->fetchAssociative();

        return (bool)$obstacle;
    }
}