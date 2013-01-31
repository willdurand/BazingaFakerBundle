# FakerBundle #

[![Build Status](https://secure.travis-ci.org/willdurand/BazingaFakerBundle.png)](http://travis-ci.org/willdurand/BazingaFakerBundle)

This bundle integrates [Faker](https://github.com/fzaninotto/Faker), a PHP library that generates fake data for you.
It provides a command to load random data for your model objects as simple as possible.


## Installation ##

Install this bundle as usual:

> git submodule add git://github.com/willdurand/BazingaFakerBundle.git vendor/bundles/Bazinga/Bundle/FakerBundle

Add the [Faker](https://github.com/fzaninotto/Faker) library:

> git submodule add git://github.com/fzaninotto/Faker.git vendor/faker

Register the namespace in `app/autoload.php` (Symfony 2):

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Bazinga'          => __DIR__.'/../vendor/bundles',
        'Faker'            => __DIR__.'/../vendor/faker/src',
    ));

Register the namespace in `app/autoload.php` (Symfony 2.1):

    // app/autoload.php
    $loader->add('Bazinga', __DIR__.'/../vendor/bundles');
    $loader->add('Faker', __DIR__.'/../vendor/faker/src');

Register the bundle in `app/AppKernel.php`:

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
        );
    }

## Reference Configuration ##

In order to use the `BazingaFakerBundle`, you have to configure it.

First of all if you use Doctrine or Mandango instead of Propel you must define it so that the bundle can reconfigure itself:

``` yaml
# app/config/config*.yml

bazinga_faker:
    orm: doctrine
```

``` yaml
# app/config/config*.yml

bazinga_faker:
    orm: mandango
```

Afterwards you just need to configure which entities you want to populate and in which quantity (default: 5).

``` yaml
# app/config/config*.yml

bazinga_faker:
    entities:
        Acme\LibraryBundle\Model\Author:
            number: 5
        Acme\LibraryBundle\Model\Book:
            number: 5
            custom_formatters:
                Isbn:   { method: lexify, parameters: [ '?????????????' ] }
```

You can add your own formatter for each column of each entity:

``` yaml
bazinga_faker:
    entities:
        Acme\LibraryBundle\Model\Book:
            custom_formatters:
                Isbn:   { method: randomElement, parameters: [ [ 'aaaaaaaaaa', 'bbbbbbbb', 'cccccccc' ] ] }
```

You can use all formatters provided by Faker, with or without arguments:

``` yaml
bazinga_faker:
    entities:
        Acme\LibraryBundle\Model\Book:
            custom_formatters:
                Isbn:   { method: word }
```

You can also set `null` to a column value in order to get the default value generated by a behavior (e.g. Propel behaviors):

``` yaml
bazinga_faker:
    entities:
        Acme\LibraryBundle\Model\Book:
            custom_formatters:
                Slug:   { method: null }
```

There are a few more optional settings available for more advanced customization of faker:

``` yaml
bazinga_faker:
    seed:       1234
    locale:     en_GB
    populator:  Your\Own\Populator
    entity:     Your\Own\EntityPopulator
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
