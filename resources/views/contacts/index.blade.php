@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3">
        <div class="flex flex-row justify-between items-center">
            <h1>Contacts</h1>
            <div>
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add Contact</a>
            </div>
        </div>
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
