<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{

    public function test_can_view_contacts_list(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contacts.index'));

        $response->assertStatus(200)
                 ->assertViewIs('contacts.index')
                 ->assertViewHas('contacts')
                // assumes the contacts are listed as most recently updated first
                 ->assertSee($contact->first_name)
                 ->assertSee($contact->last_name);
    }

    public function test_can_search_contacts(): void
    {
        foreach(['matchingContact', 'nonMatchingContact'] as $var) {
            $$var = [
                'first_name' => fake()->firstName(),
                'last_name'  => fake()->lastName(),
                'company_name' => fake()->company(),
            ];
        }

        Contact::factory()->create($matchingContact);
        Contact::factory()->create($nonMatchingContact);

        foreach ($matchingContact as $key => $value) {
            $parts = array_filter(preg_split('/[^a-zA-Z0-9]/', $value), fn($part) => strlen(trim($part)) > 3);
            $search = $parts[0];

            $this->get(route('contacts.index', compact('search')))
                 ->assertStatus(200)
                 ->assertViewHas('contacts')
                 ->assertSee($matchingContact[$key])
                 ->assertDontSee($nonMatchingContact[$key]);
        }
    }

    public function test_can_create_contact(): void
    {
        $contactData = Contact::factory()->raw();
        $contactData['number'] = [
            fake()->e164PhoneNumber
        ];

        $response = $this->post(route('contacts.store'), $contactData);

        $contact = Contact::latest(
            // Laravel defaults to using created_at,
            // which doesn't guarantee we'll get the actual latest value if multiple records were inserted simultaneously
            (new Contact)->getKeyName()
        )->first();
        $response->assertRedirect(route('contacts.show', $contact));

        $this->assertDatabaseHas('contacts', [
            'first_name' => $contactData['first_name'],
            'last_name' => $contactData['last_name'],
            'company_name' => $contactData['company_name'],
            'email' => $contactData['email'],
        ]);
    }

    public function test_can_update_contact(): void
    {
        $contact = Contact::factory()->create();

        $updates = Contact::factory()->raw();
        $updates['number'] = [
            fake()->e164PhoneNumber
        ];

        $response = $this->put(route('contacts.update', $contact), $updates);

        $response->assertRedirect(route('contacts.show', $contact));

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => $updates['first_name'],
            'last_name' => $updates['last_name'],
            'company_name' => $updates['company_name'],
            'email' => $updates['email'],
        ]);
    }

    public function test_can_delete_contact(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contacts.destroy', $contact));

        $response->assertRedirect(route('contacts.index'));

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id
        ]);
    }

    public function test_search_validation(): void
    {
        // Test invalid characters
        $response = $this->get(route('contacts.index', ['search' => 'test@#$%']));

        $response->assertSessionHasErrors('search');

        // Test valid search
        $response = $this->get(route('contacts.index', ['search' => 'John123']));

        $response->assertSessionHasNoErrors();
    }

    public function test_contact_validation_rules(): void
    {
        // Test required fields
        $response = $this->post(route('contacts.store'), []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'company_name', 'position']);

        // Test email format
        $response = $this->post(route('contacts.store'), [
            'email' => 'invalid-email'
        ]);

        $response->assertSessionHasErrors('email');

        // Test phone number format
        $response = $this->post(route('contacts.store'), [
            'number' => ['invalid-number']
        ]);

        $response->assertSessionHasErrors('number.0');
    }



    protected function _test_invalid_form_response($response, $route, $data) {
        $response->assertRedirect($route)
                 ->assertSessionHasErrors(['email'])
                 ->assertSessionHasInput('first_name', $data['first_name'])
                 ->assertSessionHasInput('last_name', $data['last_name'])
                 ->assertSessionHasInput('company_name', $data['company_name'])
                 ->assertSessionHasInput('email', $data['email']);

        if (isset($data['number'])) {
            $response->assertSessionHasInput('number', $data['number']);
        }

        // Follow the redirect and check the form
        $page = $this->get($route)
             ->assertSee($data['first_name'])
             ->assertSee($data['last_name'])
             ->assertSee($data['company_name'])
             ->assertSee($data['email']);

        if (isset($data['number'])) {
            $page->assertSee($data['number'][0]);
        }
    }


    public function test_create_form_repopulates_on_validation_error(): void
    {
        $invalidData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_name' => 'Test Company',
            'email' => 'invalid-email', // Invalid email to trigger validation error
            'number' => ['1234567890']
        ];

        $response = $this->from(route('contacts.create'))
                         ->post(route('contacts.store'), $invalidData);

        $this->_test_invalid_form_response($response, route('contacts.create'), $invalidData);
    }

    public function test_update_form_repopulates_on_validation_error(): void
    {
        $contact = Contact::factory()->create();

        $invalidData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'company_name' => 'Updated Company',
            'email' => 'invalid-email', // Invalid email to trigger validation error
            'number' => ['9876543210']
        ];

        $response = $this->from(route('contacts.edit', $contact))
                         ->put(route('contacts.update', $contact), $invalidData);

        $this->_test_invalid_form_response($response, route('contacts.edit', $contact), $invalidData);
    }


    public function test_cannot_create_contact_with_duplicate_email(): void
    {
        // Create an existing contact
        $existingContact = Contact::factory()->create();

        // Attempt to create a new contact with the same email
        $contactData = Contact::factory()->raw([
            'email' => $existingContact->email
        ]);

        $response = $this->from(route('contacts.create'))
                         ->post(route('contacts.store'), $contactData);

        $this->_test_invalid_form_response($response, route('contacts.create'), $contactData);
    }

    public function test_cannot_update_contact_with_duplicate_email(): void
    {
        // Create two contacts
        $existingContact = Contact::factory()->create();
        $contactToUpdate = Contact::factory()->create();

        // Attempt to update second contact with first contact's email
        $updateData = Contact::factory()->raw([
            'email' => $existingContact->email
        ]);

        $response = $this->from(route('contacts.edit', $contactToUpdate))
                         ->put(route('contacts.update', $contactToUpdate), $updateData);

        $this->_test_invalid_form_response($response, route('contacts.edit', $contactToUpdate), $updateData);
    }

}
