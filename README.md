# AutocompleteFormControl

## Options
	multiple : false // viac položiek [ešte neporiešené]
	minLength : 3 // minimálny počet zadaných znakov [ešte sa nepredáva do js]
	limit : null // počet vrátených výsledkov
	offset : null // offset vrátených výsledkov
	delimeter : ',' // oddeľovač pri viac položkách
	fieldValue : 'id' // čo nastaví ako ID
	fieldLabel : 'title' // čo nastaví ako label
	columns : ['title'] // názvy stlpcov v ktorých vyhľadáva (je možné použiť aj cudzí kľúč napr. langs.title)
	select : '*' // ake hodnoty vráti


## Example
    $form->addAutocomplete('value', _('Article'), '/api/v1/article-search', [
		'columns' => ['langs.title'],
		'select' => 'a.id, langs.title'
	]);