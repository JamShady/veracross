@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h1>Contacts</h1>
            <div class="flex flex-col md:flex-row md:justify-end gap-4 w-full">
                <form action="{{ route('contacts.index') }}" method="GET" class="flex gap-2 md:w-3/5">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search by name or company..."
                        pattern="^[a-zA-Z0-9]{3,}$"
                        title="Only letters and numbers allowed, minimum 3 characters"
                        class="flex-1"
                        required
                    >
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if($search)
                        <a href="{{ route('contacts.index') }}" class="btn btn-info">Clear</a>
                    @endif
                </form>

                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add Contact</a>
            </div>
        </div>

        @if($contacts->isEmpty())
            <div class="text-center py-8 text-gray-600">
                @if($search)
                    No contacts found matching "{{ $search }}"
                @else
                    No contacts found
                @endif
            </div>
        @endif

        @foreach($contacts as $contact)
            <div class="row">
                <div class="card w-100">
                    <div class="card-body">
                        <a href="{{ route('contacts.show', ['contact' => $contact]) }}">
                            <h5 class="card-title">{{ $contact->full_name }}</h5>
                        </a>
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ $contact->company_name }} &ndash; {{ $contact->position }}
                        </h6>
                        <p class="card-text">
                            @if(isset($contact->email))
                                {{ $contact->email }}<br />
                            @endif

                            @foreach($contact->phoneNumbers as $phoneNumber)
                                {{ $phoneNumber->number }}<br />
                            @endforeach
                        </p>
                    </div>
                    <div class="border-t p-4 flex justify-between items-center">
                        <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="btn btn-info">Edit</a>
                        <form method="POST" action="{{ route('contacts.destroy', ['contact' => $contact]) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <div>
            {{ $contacts->links() }}
        </div>
    </div>
@endsection
