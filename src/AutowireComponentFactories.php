<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

use Nette\ComponentModel\IComponent;
use Nette\DI\Container;
use Nette\DI\Helpers;
use Nette\Reflection\Method;
use Nette\UnexpectedValueException;


/**
 * trait AutowireComponentFactories
 * @package Autowired
 *
 * @author matej21 <matej21@matej21.cz>
 * @author Filip Procházka <filip@prochazka.su>
 * @author Radek Fryšták <geniv.radek@gmail.com>
 */
trait AutowireComponentFactories
{

    /**
     * @var Container
     */
    private $autowireComponentFactoriesLocator;


    /**
     * @return Container
     */
    protected function getComponentFactoriesLocator()
    {
        if ($this->autowireComponentFactoriesLocator === NULL) {
            $this->autowireComponentFactoriesLocator = $this->getPresenter()->context;
        }
        return $this->autowireComponentFactoriesLocator;
    }


    /**
     * @param $name
     * @return IComponent
     * @throws UnexpectedValueException
     */
    protected function createComponent($name)
    {
        $sl = $this->getComponentFactoriesLocator();

        $ucName = ucfirst($name);
        $method = 'createComponent' . $ucName;
        if ($ucName !== $name && method_exists($this, $method)) {
            $methodReflection = new Method($this, $method);
            if ($methodReflection->getName() !== $method) {
                return;
            }
            $parameters = $methodReflection->getParameters();

            $args = [];
            if (($first = reset($parameters)) && !$first->className) {
                $args[] = $name;
            }

            $args = Helpers::autowireArguments($methodReflection, $args, $sl);
            $component = call_user_func_array([$this, $method], $args);
            if (!$component instanceof IComponent && !isset($this->components[$name])) {
                throw new UnexpectedValueException("Method $methodReflection did not return or create the desired component.");
            }
            return $component;
        }
    }
}
