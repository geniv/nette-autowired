Autowired
=========

Installation
------------

```sh
$ composer require geniv/nette-autowired
```
or
```json
"geniv/nette-autowired": ">=1.0.0"
```

require:
```json
"php": ">=5.6.0",
"nette/nette": ">=2.4.0"
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
protected function createComponentDatagrid($name, IDatagridFactory $factory)
{
    return $factory->create();
}
```
OR
```php
protected function createComponentSomeone(Someone $someone)
{
    return $someone;
}
```
