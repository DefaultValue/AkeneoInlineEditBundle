AkeneoInlineEditBundle
=============================



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