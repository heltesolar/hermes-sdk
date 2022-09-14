<?php

/**
 * This creates a fake object of a fake class in runtime.
 * 
 * Yes, this is ugly. But it was necessary to deal with cross-project jobs.
 * 
 * @param string $namespace
 * @param string $class_name
 * @param array $uses
 * @param array $public_parameters
 * @param array $private_parameters
 * @param array $protected_parameters
 */
if (!function_exists('fakeObject')) {
    function fakeObject(string $namespace,string $class_name,array $uses = [],array $public_parameters = [],array $private_parameters = [],array $protected_parameters = [])
    {
        $public_class_parameters = collect(array_keys($public_parameters))->map(function ($public_parameter) {
            return "public \${$public_parameter};";
        })->implode('');

        $private_class_parameters = collect(array_keys($private_parameters))->map(function ($private_parameter) {
            return "private \${$private_parameter};";
        })->implode('');

        $protected_class_parameters = collect(array_keys($protected_parameters))->map(function ($protected_parameter) {
            return "protected \${$protected_parameter};";
        })->implode('');

        $class_uses = collect($uses)->map(function ($use) {
            return "use $use;";
        })->implode('');

        $namespaced_class_name = "$namespace\\$class_name";

        if (!class_exists($namespaced_class_name)) {

            $class = "
                namespace $namespace;

                $class_uses

                class $class_name
                { 
                    $public_class_parameters 
                    $private_class_parameters 
                    $protected_class_parameters 
                    
                    public function setVariable (\$name, \$value){
                        \$this->\$name = \$value;
                    } 
                }";

            eval($class);
        }

        $object = new $namespaced_class_name;

        foreach ($public_parameters as $parameter_name => $parameter_value) {
            $object->setVariable($parameter_name, $parameter_value);
        }

        foreach ($private_parameters as $parameter_name => $parameter_value) {
            $object->setVariable($parameter_name, $parameter_value);
        }

        foreach ($protected_parameters as $parameter_name => $parameter_value) {
            $object->setVariable($parameter_name, $parameter_value);
        }

        return $object;
    }
}