<?php

use Nette\ComponentModel\IComponent;
use Nette\DI\Container;
use Nette\DI\Helpers;
use Nette\Reflection\Method;


/**
 * Trait AutowiredComponent
 *
 * @author  geniv
 */
trait AutowiredComponent
{
    /** @var Container */
    private $presenterContext;


    /**
     * Get presenter context.
     *
     * @return Container
     */
    private function getPresenterContext()
    {
        if ($this->presenterContext === null) {
            $this->presenterContext = $this->getPresenter()->context;
        }
        return $this->presenterContext;
    }


    /**
     * Create component.
     *
     * @param $name
     * @return IComponent
     * @throws UnexpectedValueException
     */
    protected function createComponent($name)
    {
        $ucFirstComponentName = ucfirst($name);
        $componentName = 'createComponent' . $ucFirstComponentName;

        // check name component and method exist in context
        if ($ucFirstComponentName !== $name && method_exists($this, $componentName)) {
            $method = new Method($this, $componentName);

            if ($method->getName() !== $componentName) {
                return;
            }
            $parameters = $method->getParameters();

            // separate first parameter component $name
            $args = [];
            if (($first = reset($parameters)) && !$first->className) {
                $args[] = $name;
            }

            $args = Helpers::autowireArguments($method, $args, $this->getPresenterContext());
            $component = call_user_func_array([$this, $componentName], $args);
            if (!$component instanceof IComponent && !isset($this->components[$name])) {
                throw new UnexpectedValueException('Method ' . $method . ' did not return or create the desired component.');
            }
            return $component;
        }
    }
}
