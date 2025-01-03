@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>{{ $title }}</h1>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Attention needed</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ $route }}">
            @csrf
            @if(($method ?? null) === 'put')
                @method('put')
            @endif
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">First Name
                        <input type="text" name="first_name" value="{{ old('first_name', $contact->first_name) }}" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Last Name
                        <input type="text" name="last_name" value="{{ old('last_name', $contact->last_name) }}" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Date of Birth
                        <input type="date" name="DOB" value="{{ old('DOB', $contact->DOB) }}"/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Company
                        <input type="text" name="company_name" value="{{ old('company_name', $contact->company_name) }}" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Position
                        <input type="text" name="position" value="{{ old('position', $contact->position) }}" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Email
                        <input type="email" name="email" value="{{ old('email', $contact->email) }}"/>
                    </label>
                </div>
            </div>
            @foreach(array_filter(old('number', $contact->phoneNumbers->pluck('number')->toArray())) as $index => $phoneNumber)
                <div class="row">
                    <div class="col-auto">
                        <label class="form-label">Phone Number #{{ $index + 1 }}
                            <input
                                type="tel"
                                name="number[]"
                                pattern="^\+?\d+$"
                                title="Please enter only numbers with an optional + at the start"
                                value="{{ $phoneNumber }}"
                            />
                        </label>
                    </div>
                </div>
            @endforeach
            {{-- Blank field outside the loop to allow new number. Refactor later to add multiple at a time. --}}
            <div class="row">
                <div class="col-auto">
                    <label class="form-label">Phone Number
                        <input
                            type="tel"
                            name="number[]"
                            pattern="^\+?\d+$"
                            title="Please enter only numbers with an optional + at the start"
                        />
                    </label>
                </div>
            </div>
           <div class="row">
               <div class="col-auto">
                   <button type="submit" class="btn btn-primary">{{ $action }}</button>
               </div>
           </div>
        </form>
    </div>
@endsection
