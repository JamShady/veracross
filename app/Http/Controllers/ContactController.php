<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Contact as FormRequest;

class ContactController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $contacts =  Contact::paginate(5);

        return view('contacts.index', compact('contacts'));
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('contacts.create');
    }

    /**
     * @throws \Throwable
     */
    public function store(FormRequest $request): Response|RedirectResponse
    {
        $contact = new Contact();
        $contact->fill($request->safe()->except(['number']));
        $contact->save();
        foreach ($request->number as $number) {
            PhoneNumber::create(['number' => $number, 'contact_id' => $contact->id]);
        }

        return redirect()->route('contacts.show', compact('contact'));
    }

    public function show(Contact $contact): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(FormRequest $request, Contact $contact): Response|RedirectResponse
    {
        $contact->fill($request->safe()->except(['number']));

        foreach ($contact->phoneNumbers as $phoneNumber) {
            if (! in_array($phoneNumber->number, $request->number)) {
                $phoneNumber->delete();
            }
        }
        foreach ($request->number as $number) {
            $alreadyAssigned = $contact->phoneNumbers->firstWhere('number', $number);
            if (
                empty($alreadyAssigned)
                && ! empty($number)
            ) {
                PhoneNumber::create(['number' => $number, 'contact_id' => $contact->id]);
            }
        }
        $contact->save();

        return redirect()->route('contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact): Response|RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}
