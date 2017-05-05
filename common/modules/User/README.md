# Yii2-user 
[![Build Status](https://img.shields.io/travis/ptech/yii2-user/master.svg?style=flat-square)] 
[![Version](https://img.shields.io/badge/Version-0.0.0.0-green.svg)]
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Most of web applications provide a way for users to register, log in or reset
their forgotten passwords. Rather than re-implementing this on each application,
you can use Yii2-user which is a flexible user management module for Yii2 that
handles common tasks such as registration, authentication and password retrieval.
The latest version includes following features:

* Registration with an optional confirmation per mail
* Registration via social networks
* Password recovery
* Account and profile management
* Console commands
* User management interface

> **NOTE:** Module is in initial development. Anything may change at any time.

## Documentation

[Installation instructions](docs/getting-started.md) | [Definitive guide to Yii2-user](docs/README.md)

## Contributing to this project

Anyone and everyone is welcome to contribute. Please take a moment to
review the [guidelines for contributing](CONTRIBUTING.md).

## License

Yii2-user is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.




### Update database schema

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@common/modules/User/migrations
$ php yii migrate/down --migrationPath=@common/modules/User/migrations
$ php yii migrate/create --migrationPath=@common/modules/User/migrations
```