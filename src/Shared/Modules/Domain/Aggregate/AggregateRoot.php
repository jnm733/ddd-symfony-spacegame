<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\Aggregate;

abstract class AggregateRoot extends Entity
{
    private array $domainEvents = [];

    final public function pullDomainEvents(): array {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function addDomainEvent(DomainEvent $domainEvent): void {
        $this->domainEvents[] = $domainEvent;
    }

}
