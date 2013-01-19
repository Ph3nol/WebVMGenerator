# WebVMGenerator Project

## Requirements

* PHP 5.3+

## Implement a Puppet Element

**Create a dedicated class**, based on `Sly\Bundle\VMBundle\Generator\PuppetElement\ExampleElement`.

It extends `Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement`
and implements `Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface`.

This class will contains conditions to be applied, manifest lines and Git submodules to add.

**Define a service**, based on your new Puppet element class, tagged `sly_vm.puppet_element`.

Here is an example:

``` xml
<service id="sly_vm.puppet_element.example" class="You\Bundle\YourProjectBundle\Generator\PuppetElement\ExampleElement">
    <tag name="sly_vm.puppet_element" />
</service>
```
