<?php declare(strict_types=1);

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
     * @internal
     * @return Container
     */
    private function getPresenterContext(): Container
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
     * @throws ReflectionException
     */
    protected function createComponent($name)
    {
        $ucFirstComponentName = ucfirst($name);
        $componentName = 'createComponent' . $ucFirstComponentName;

        // check name component and method exist in context
        if ($ucFirstComponentName !== $name && method_exists($this, $componentName)) {
            $method = new Method($this, $componentName);
            $parameters = $method->getParameters();

            // separate first parameter component $name
            $args = [];
            if (isset($parameters[0]) && !$parameters[0]->className) {
                $args[] = $name;
            }

            $args = Helpers::autowireArguments($method, $args, $this->getPresenterContext());
            $component = call_user_func_array([$this, $componentName], $args);
            if (!$component instanceof IComponent && !isset($this->components[$name])) {
                throw new UnexpectedValueException('Method ' . $method . ' did not return or create the desired component.');
            }
            return $component;
        }
        // override nette exception for fail component
        throw new UnexpectedValueException('Component {control ' . $name . '} did not find any method `' . $componentName . '`');
    }
}
