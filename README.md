AkeneoInlineEditBundle
=============================




Add to datagrid `properties` configuration following options:

```yml
update_attribute_value:
    type: url
    route: default_value_inline_edit_update_attribute
    params:
        - id
        - dataLocale
        - scopeCode
```