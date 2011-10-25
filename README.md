# FakerBundle #

This bundle integrates [Faker](https://github.com/fzaninotto/Faker), a PHP library that generates fake data for you.
It provides a command to load random data for your model objects as simple as possible.

**Note:** It only works out of the box with [Propel ORM](https://github.com/propelorm/Propel) at the moment
but you can configure your own `populator` so it can works with all database abstraction systems.


## Installation ##

Install this bundle as usual:

> git submodule add git://github.com/willdurand/BazingaFakerBundle.git vendor/bundles/Bazinga/Bundle/FakerBundle

Add the [Faker](https://github.com/fzaninotto/Faker) library:

> git submodule add git://github.com/fzaninotto/Faker.git vendor/faker

Register the namespace in `app/autoload.php`:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Bazinga'          => __DIR__.'/../vendor/bundles',
        'Faker'            => __DIR__.'/../vendor/faker/src',
    ));

Register the bundle in `app/AppKernel.php`:

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bazinga\Bundle\FakerBundle\FakerBundle(),
        );
    }

## Configuration ##

In order to use the `BazingaFakerBundle`, you have to configure it.
Actually, you just need to configure entities you want to populate and in which quantity.

``` yaml
# app/config/config*.yml

faker:
#    seed:      1234
#    populator: \Your\Own\Populator
    entities:
#        Acme\LibraryBundle\Model\Author: 10
#        Acme\LibraryBundle\Model\Book: 5
```


## Command ##

The bundle provides a new Symfony2 command: `faker:populate` which will populate all configured entities.

    php app/console faker:populate


## Usage ##

In real life, you'll have to populate your database with data. It's often a pain because it requires imagination
and time you probably don't have or you don't want to waste. Faker to the rescue!

You just have to configure your entities to populate, then run the `faker:populate` command to add new data without effort.
It's better than fixtures because you don't need to write anything.
If you drop your database, then re create it and load fresh data.


## Credits ##

* François Zaninotto (Creator of Faker)
* William Durand


## License ##

See `Resources/meta/LICENSE`.
