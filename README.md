[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DefaultValue/AkeneoInlineEditBundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/DefaultValue/AkeneoInlineEditBundle/?branch=develop)

AkeneoInlineEditBundle
=============================

AkeneoInlineEditBundle provides ability to edit product attributes from Products Grid.

## Installation

### Step 1: Install bundle with composer

``` bash
$ php composer.phar require default-value/akeneo-inline-edit-bundle "@dev"
```

### Step 2: Enable the bundle

``` php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new DefaultValue\Bundle\AkeneoInlineEditBundle\DefaultValueAkeneoInlineEditBundle(),
    );
}
```
### Step 3: Add bundle routing to `app/config/routing.yml`

```yml
default_value_akeneo_inline_edit:
    resource: "@DefaultValueAkeneoInlineEditBundle/Resources/config/routing.yml"
```

## Configuration

### Datagrid

Set following properties in `datagrid` configuration:

1) Set `rowAction` to false (to disable default row Action):

```yml
actions:
    edit:
        type:      navigate
        label:     Label
        icon:      icon-class
        link:      edit_link
        rowAction: false
```

2) Add to `properties` configuration following options:

```yml
update_attribute_value:
    type: url
    route: default_value_inline_edit_update_attribute
    params:
        - id
        - dataLocale
        - scopeCode
```

3) Set column property `editable` to true:

```yml
columns:
    name:
        label:         Name
        type:          product_value_field
        selector:      product_value_base
        editable:      true
```

**Note**: Editable columns should have the same name as attribute code. E.g. product has attribute `name` and no attribute `title` which comes with default grid configuration.

### Acl

Update attribute value action has defined AclAncestor - `default_value_inline_edit_update_value`.
So, you able to configure what roles able to edit product on datagrid.
More details about ACL and permissions configuration you can find in [Akeneo documentation](http://docs.akeneo.com/latest/cookbook/acl/define-acl.html).


## Limitation

Bundle supports following types of attributes:
- Number
- Text Area
- Text
- Price (only for USD currency)
