# AutocompleteFormControl

## Options
| parameter | example | description |
| --------- | ------- | ----------- |
| limit | 10 | počet vrátených výsledkov |
| offset | 20 | offset vrátených výsledkov |
| fieldValue | id | čo nastaví ako ID |
| fieldLabel | title | čo nastaví ako label |
| columns | [title] | názvy stlpcov v ktorých vyhľadáva (je možné použiť aj cudzí kľúč napr. langs.title) |
| select | * | ake hodnoty vráti |


## Example
```
$parent->addAutocomplete('article', _('Article'))
        ->setAttribute('placeholder', _('Begin typing...'))
        ->setSource('/api/v1/parameter-value-search?id=' . $parameterRelationEntity->getId())
        ->setColumns(['langs.title'])
        ->setSelect('a.id, langs.title')
        ->setFieldValue('id')
        ->setFieldLabel('title')
        ->setFieldSearch('title');
```