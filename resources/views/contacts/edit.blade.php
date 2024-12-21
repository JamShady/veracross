@include('contacts.form', [
    'title'  => 'Edit Contact',
    'action' => 'Update',
    'route'  => route('contacts.update', ['contact' => $contact]),
    'method' => 'put',
])
