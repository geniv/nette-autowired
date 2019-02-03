Autowired for component
=======================

This trait allows self class use in parameters for `createComponent*`

inspired by: https://github.com/Kdyby/Autowired

Installation
------------

```sh
$ composer require geniv/nette-autowired
```
or
```json
"geniv/nette-autowired": ">=1.0"
```

require:
```json
"php": ">=7.0",
"nette/component-model": ">=2.3",
"nette/di": ">=2.4",
"nette/reflection": ">=2.4"
```

Include in application
----------------------
base presenters:
```php
class BasePresenter extends Presenter
{
    use AutowiredComponent;
```

usage:
```php
protected function createComponentDatagrid(string $name, IDatagridFactory $factory): DatagridFactory
{
    return $factory->create();
}
```
or
```php
protected function createComponentSomeone(Someone $someone): Someone
{
    return $someone;
}
```
