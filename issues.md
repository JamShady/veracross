# Issues

- No user authentication/authorisation to access any part of the system, i.e. login, CRUD operations, etc
- No verification or safeguard for deleting a contact
- No input validation
  - No checks to ensure malicious data/requests are filtered out
  - No checks to ensure data is sensible (i.e. DOB isn't within the last couple of years, etc)
- No rate limiting/throttling

# Improvements

- Use class constants for field names (i.e. Contact::FIRST_NAME), rather than hard-coding, to avoid potential misspelling, etc
- Display the contact's DOB (or age) alongside the rest of their information
- Use soft-deletes so contact information isn't permanently purged immediately
- Make the layout responsive to make better use of space
- Generate as much front-end HTML via Vue et al, rather than sending a large HTML bundle to the browser
- Make the results table sortable
- Allow different pagination values (i.e. more than 5-per-page)
- Add different filters for fine-tuning searches
- Implement live/real-time client-side validation
- Highlight error'd fields specifically, along with error details
- Implement specific phone number formatting
- Deeper email validation (i.e. prevent fake email submissions)
- Add a 'Back/Cancel' button to the forms to return the user to the previous page

- The auto-adding of phone number fields should have fallbacks for non-JS environments, and perhaps even a direct button to add fields in case the javascript as written fails to work properly on some clients
