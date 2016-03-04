AkeneoInlineEditBundle
=============================

This Bundle provides ability to edit product attributes from Product Grid.


## Installation

### Step 1: Download bundle using composer

Add AkeneoInlineEditBundle by running the command:

``` bash
$ php composer.phar require default-value/akeneo-inline-edit-bundle "@dev"
```

Composer will install the bundle to your project's `vendor/default-value` directory.


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new DefaultValue\Bundle\AkeneoInlineEditBundle\DefaultValueAkeneoInlineEditBundle(),
    );
}
```

### Step 3: Add routing configuration

Add to `app/config/routing.yml` following routing configuration:

```yml
default_value_akeneo_inline_edit:
    resource: "@DefaultValueAkeneoInlineEditBundle/Resources/config/routing.yml"
```



## Configuration

Set following properties in `datagrid` configuration:

1. Set `rowAction` to false (to disable default row Action):

```yml
actions:
    edit:
        type:      navigate
        label:
        icon:
        link:      edit_link
        rowAction: false
```

2. Add to `properties` configuration following options:

```yml
update_attribute_value:
    type: url
    route: default_value_inline_edit_update_attribute
    params:
        - id
        - dataLocale
        - scopeCode
```