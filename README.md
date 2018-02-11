# :whale: :dvd: dfsc
Docker "Full State" Composer

## Feature Overview
- generate `.gitlab-ci.yml` build script (not reversible)
- generate `docker-compose.yml` provision script (not reversible)
- persist volume data (by removing volumes _or_ creating a data volume container)
- misc extras: wait4services, composer scripts

### Persistence

#### `ignored`
Nothing is done with regards to volumes. Data will be lost unless you map volumes.

#### `remove-volumes`
Volumes are removed from the image (by exporting and importing back), therefore data is kept within the image.

#### `data-container`
A container is created and persisted automatically to hold data of all volumes (either on a container-level or a global-level).

### Waiting
A key aspect of building services is that you need to wait for the right time to start performing actions.
This is why a `wait` script is included on a global or container level.
Installing `uuf6429/wait4services` makes it easy to avoid writing scripts from scratch (for usage, see [Composer Scripts](#composer-scripts)).

### Composer Scripts
If you install additional Composer dependencies that export a binary (eg; `behat`, `phpunit`, `wait4services` etc),
you can easily call these binaries inside the script by prefixing them with an `@`. Careful though, for the syntax to be correct, the line has to be quoted.
For example:
```yaml
containers:
  MyContainer:
    image: MyImage
    wait: "@wait4services check http://$CONTAINER_HOST:80"
    script: "@behat"
```

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

## :construction: Todo List

- [ ] Finish readme
- [ ] Finish schema & example schema
  - [ ] Add `export: false` (default=true) to containers in schema
- [ ] Finish init command
- [ ] Finish build command
- [x] Finish verify command
- [ ] Finish generate command
- [ ] Support variable substitution in config (with basic bash parameter substitution)
- [ ] Consider extensibility scenarios, eg, generating other types of scripts, issues:
  - system should not be coupled with docker, to support other containerization systems
    - not sure if they would work with this software, or even if they do have the problem we're solving
  - generators should be modules, available only if the target system is in use (eg; don't generate docker-compose if docker is not used)
  - the usual problem with extending over composer+autoload, how does it discover extensions?

- [ ] Create test for final parsed config, in particular to assert parameter substitution
- [ ] Create test for each command
- [ ] Create end-to-end application test
