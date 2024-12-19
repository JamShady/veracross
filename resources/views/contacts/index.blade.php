@extends('layouts.app')

@section('content')
    <div class="flex">
        <div class="mb-3">
            <div class="col-10">
                <h1 class="text-3xl font-bold">Contacts</h1>
            </div>
            <div class="col-2">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add Contact</a>
            </div>
        </div>
        @foreach($contacts as $contact)
            <div class="row pb-3">
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
                                {{ $contact->email }}<br>
                            @endif

                            @foreach($contact->phoneNumbers as $phoneNumber)
                                {{ $phoneNumber->number }}
                            @endforeach
                        </p>
                    </div>
                    <div class="border-top  p-4">
                        <span class="float-start">
                            <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="btn btn-info">Edit</a>
                        </span>
                        <span class="float-end">
                            <form method="POST" action="{{ route('contacts.destroy', ['contact' => $contact]) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Delete</a>
                            </form>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection
