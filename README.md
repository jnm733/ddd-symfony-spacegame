# Example project using DDD and Hexagonal Architecture with Symfony

# Introduction.

This is an example application of using DDD, Testing and Hexagonal Architecture with Symfony.

The application implemented with Symfony models the behavior of a spaceship in space for use in a retro videogame.

# Description.

This endpoint create and canvas with name, size and number of obstacles. The initial position of the spaceship is always "0,0":

```text
GET http://localhost:8080/create-canvas?
 name=first_canvas&
 width=5&height=5&
 num_obstacles=5
```

With the next endpoint the spaceship moves. Passing one end, the ship appears on the opposite side

```text
|0,0|0,1|0,2|0,3|0,4|
|1,0|1,1|1,2|1,3| X |
|2,0|2,1|2,2|2,3|2,4|
|3,0|3,1|3,2|3,3|3,4|
|4,0|4,1|4,2|4,3|4,4|
```

## Installation
````shell
$ docker-compose up -d --build
$ docker exec symfony-ddd-hexagonal-spacegame-fpm composer install
````

## API Endpoints
````text
Create new canvas:
GET http://localhost:8080/create-canvas?name=first_canvas&width=5&height=5&num_obstacles=5

Movements:
GET http://localhost:8080/move/first_canvas/top
GET http://localhost:8080/move/first_canvas/right
GET http://localhost:8080/move/first_canvas/bottom
GET http://localhost:8080/move/first_canvas/left
````

````text
Launch Test Suite:
````
````shell
$ php composer test
````

# To do

- Improved integration with Doctrine, using its models and mappings to move from the persistence model to the domain model.

- Inclusion of integration and acceptance tests.

- Improved configuration management with unique configuration files per Bounded Context