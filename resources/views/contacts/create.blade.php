@include('contacts.form', [
    'title'  => 'Add Contact',
    'action' => 'Add',
    'route'  => route('contacts.store'),

    'contact' => new App\Models\Contact,
])
