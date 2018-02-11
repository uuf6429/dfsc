# :whale: :dvd: dfsc
Docker "Full State" Composer

## :zap: Objective

DFSC lets you create Docker images whose state is modified by the host machine.
The state is not lost, however you won't be able to use volumes anymore and the state is kept within the image.

In principle, it works like so:
- services are started from your project configuration
- actions are applied to the services from the host system
- a snapshot of the container is taken and pushed to some registry

The longer story:
- A `docker-compose.yml` file is generated from project configuration and run
- The entrypoints of all the started containers are recorded for later use
- The containers are exported, stopped & removed and imported back (to remove volumes)
- Additional host tasks defined in project config are applied
- Snapshots of the containers are created (with the correct entrypoint set) and pushed to some registry

## :clipboard: Requirements

- PHP
- [Composer](https://getcomposer.org/)
- Docker (potentially DinD in CI)

## :sparkles: Usage

- ensure [requirements](#requirements) have been met
- set up a Composer project and install dfsc via composer: `composer require uuf6429/dfsc`
- initialise your dfsc project with `./vendor/bin/dfsc init`
- edit your dfs-compose.yml as applicable
- generate scripts to build your images from CI (eg; `.gitlab-ci.yml`) and to run images later (eg; `docker-compose.yml`)

## :construction: Todo List

- [ ] Finish readme
- [ ] Finish schema & example schema
- [ ] Finish init command
- [ ] Finish build command
