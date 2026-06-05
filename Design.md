# Campus Events Information and RSVP Website

## Major Callouts

### DO NOT USE GPT TO WRITE YOUR CODE NOR COMMENTS
- Please do not use GPT/ Claude/ AI etc to write your code nor comments. This would definitely get the project flagged. Feel free to use it for research and design, but do not use it to write your code.

### DEV OPS PROCESS
- commits to main are forbidden
- all commits should go to dev
- all committed code should be tested and well commented before being committed to dev
- code going into main must be accompanied with a PR (pull request) or MR (merge request) and a summary of the MR
- code going into main must be reviewed and approved by at least one group member
- main branch has the pipelines that build and publish the site, therefore, please carefully test your code before opening a PR

### ALL GROUP MEMBERS ARE EXPECTED TO PUT THEIR BEST FOOT FORWARD. NO HALF-EFFORTS


## System Architecture and Project Scope

## 1. Project Overview

The project is a **Campus Events Information and RSVP Website** designed to solve the problem of scattered campus event information. Students often miss events because announcements are spread across posters, WhatsApp groups, classroom announcements, social media, and word of mouth.

This website will provide one central platform where students can:

* View upcoming campus events
* Search or filter events by category
* View full event details
* Submit RSVP or attendance information
* Submit event listing requests if they are organizers

The system will also include a simple admin dashboard where the group can view RSVP submissions and event request submissions.

---

# 2. Final System Architecture

## Recommended Architecture

```text
Users / Students / Organizers
        |
        v
Public Website
HTML + CSS + JavaScript + Jekyll
Hosted on GitHub Pages
        |
        | API calls / form submissions
        v
Server-Side API
Node.js + Express.js
Hosted as Firebase Cloud Functions
        |
        v
Firebase Firestore Database
Events, RSVPs, Event Requests
        ^
        |
React Admin Dashboard
Hosted on Firebase Hosting or GitHub Pages
```

---

# 3. Architecture Layers

| Layer                   | Technology                         | Purpose                                             |
| ----------------------- | ---------------------------------- | --------------------------------------------------- |
| Public Website          | HTML, CSS, JavaScript, Jekyll      | Displays event information to students              |
| Static Hosting          | GitHub Pages                       | Hosts the main public website                       |
| Server-Side API         | Node.js + Express.js               | Handles form submissions and database operations    |
| Backend Hosting         | Firebase Cloud Functions           | Hosts the Express.js API                            |
| Database                | Firebase Firestore                 | Stores events, RSVP submissions, and event requests |
| Admin Dashboard         | React.js                           | Allows the group/admin to view submissions          |
| Version Control / CI-CD | GitHub                             | Stores code and supports deployment workflow        |
| Usability Testing       | Student testers and feedback forms | Supports the usability evaluation section           |

---

# 4. Main Project Modules

## 4.1 Home Page

### Purpose

The home page introduces the purpose of the website and explains the problem being solved.

### Content

* Website name
* Short project description
* Explanation of the campus event information problem
* Buttons linking to:

  * View Events
  * Submit an Event
  * Contact Us
* Highlight section for upcoming events

### Assessment Value

This page helps show the significance of the problem and introduces the website solution clearly.

---

## 4.2 Events Listing Page

### Purpose

The events listing page displays upcoming campus events in one central location.

### Features

* Event cards
* Event title
* Event date
* Event time
* Venue
* Category
* Organizer
* “View Details” button
* Search or filter by category

### Suggested Event Categories

* Academic
* Sports
* Clubs
* Career
* Social
* Workshop
* Religious/Cultural
* Other

### Data Source

Events can be loaded from Firebase Firestore through the API.

As a fallback, a local JSON file may be used:

```text
/data/events.json
```

---

## 4.3 Individual Event Details Page

### Purpose

The event details page allows students to view full information about a specific event.

### Displayed Information

* Event title
* Full description
* Date
* Time
* Venue
* Organizer
* Category
* Event image or poster, if available
* RSVP form or RSVP button
* Contact information, if applicable

### Suggested URL Structure

```text
event.html?id=event001
```

This allows one dynamic event details page to display different events based on the event ID.

---

## 4.4 RSVP / Attendance Form

### Purpose

The RSVP form allows students to register interest in attending an event.

### Suggested Fields

* Full name
* Student ID
* Email address
* Programme or department
* Event ID
* Attendance status
* Optional comment or question

### Validation Rules

Client-side validation should check that:

* Full name is not empty
* Email is not empty
* Email format is valid
* Event ID exists
* Required fields are completed before submission

Server-side validation should also check the submitted data before it is saved to the database.

### RSVP Submission Flow

```text
Student fills RSVP form
        |
JavaScript validates input
        |
POST request sent to Express API
        |
Express validates data again
        |
Data is saved to Firebase Firestore
        |
Student sees confirmation message
```

---

## 4.5 Event Submission Request Form

### Purpose

This form allows organizers to request that their event be listed on the website.

### Suggested Fields

* Organizer name
* Organizer email
* Event title
* Event category
* Proposed event date
* Proposed event time
* Venue
* Event description
* Expected audience
* Optional event poster link

### Request Status Values

Each event request should have a status field:

```text
pending
approved
rejected
```

For the first version of the project, event requests can be reviewed manually by the group from the admin dashboard.

---

## 4.6 Admin Dashboard

### Purpose

The admin dashboard allows the group to view and manage submitted data.

React.js should be described as the **admin front-end dashboard**, not the backend.

### Admin Features

* View all RSVP submissions
* Filter RSVP submissions by event
* View RSVP count per event
* View event submission requests
* Mark event requests as reviewed, approved, or rejected
* Optionally add approved event requests to the event listing

### Technology

* React.js
* Firebase SDK or API calls to the Express.js backend

---

# 5. Server-Side Design

The project should clearly include a server-side component.

## Recommended Backend Technology

```text
Node.js + Express.js + Firebase Cloud Functions
```

## API Endpoints

| Method | Endpoint                        | Purpose                      |
| ------ | ------------------------------- | ---------------------------- |
| GET    | `/api/events`                   | Fetch approved events        |
| GET    | `/api/events/:id`               | Fetch details for one event  |
| POST   | `/api/rsvps`                    | Submit RSVP                  |
| POST   | `/api/event-requests`           | Submit event listing request |
| GET    | `/api/admin/rsvps`              | Admin views RSVP submissions |
| GET    | `/api/admin/event-requests`     | Admin views event requests   |
| PATCH  | `/api/admin/event-requests/:id` | Admin updates request status |

## Server-Side Responsibilities

The backend should:

* Receive form data
* Validate submitted data
* Save RSVP records to Firestore
* Save event request records to Firestore
* Return success or error messages
* Protect admin-related routes where possible
* Keep database logic separate from the public website code

---

# 6. Database Design

The system will use Firebase Firestore with three main collections:

1. `events`
2. `rsvps`
3. `eventRequests`

---

## 6.1 `events` Collection

```js
{
  id: "event001",
  title: "Career Development Workshop",
  category: "Workshop",
  description: "A workshop to help students prepare resumes and interviews.",
  date: "2026-05-20",
  time: "14:00",
  venue: "Lecture Theatre 1",
  organizer: "Computer Science Club",
  contactEmail: "csclub@example.com",
  imageUrl: "images/career-workshop.jpg",
  status: "approved",
  createdAt: timestamp
}
```

---

## 6.2 `rsvps` Collection

```js
{
  id: "rsvp001",
  eventId: "event001",
  fullName: "John Doe",
  studentId: "1050000",
  email: "john@example.com",
  programme: "Computer Science",
  attendanceStatus: "attending",
  comment: "Looking forward to it.",
  submittedAt: timestamp
}
```

---

## 6.3 `eventRequests` Collection

```js
{
  id: "request001",
  organizerName: "Jane Smith",
  organizerEmail: "jane@example.com",
  eventTitle: "Club Recruitment Drive",
  category: "Clubs",
  proposedDate: "2026-05-25",
  proposedTime: "10:00",
  venue: "Campus Courtyard",
  description: "An event for students to learn about clubs.",
  expectedAudience: "All students",
  status: "pending",
  submittedAt: timestamp,
  reviewedAt: null
}
```

---

# 7. Website Page Structure

## Public Website Structure

```text
/
|-- index.html
|-- events.html
|-- event.html
|-- submit-event.html
|-- about.html
|-- contact.html
|-- admin/
    |-- index.html or React app
|-- assets/
    |-- css/
    |-- js/
    |-- images/
|-- data/
    |-- events.json optional fallback
```

---

## Public Navigation

```text
Home | Events | Submit Event | About | Contact
```

---

## Admin Navigation

```text
Dashboard | RSVP Responses | Event Requests | Event Statistics
```

---

# 8. User Roles

| User Type            | What They Can Do                                                     |
| -------------------- | -------------------------------------------------------------------- |
| Student              | Browse events, search/filter events, view event details, submit RSVP |
| Organizer            | Submit event listing request                                         |
| Admin / Group Member | View RSVPs, view event requests, approve/reject requests             |
| Lecturer / Evaluator | Test the website and review project files                            |

---

# 9. Security and Privacy Considerations

The system should include basic privacy and security considerations.

## Minimum Privacy Considerations (not really necessary express can handle these easily just puttting it here for visibility)

* Do not collect unnecessary sensitive information.
* Do not publicly display RSVP submissions.
* Email addresses should only be used for RSVP or event contact purposes.
* Admin pages should not expose private RSVP records to regular users.
* Firebase security rules should restrict direct database access where possible.
* Server-side validation should be performed before data is saved.

---

# 10. Usability Evaluation Plan (Just some UAT testing)

The project will include a usability evaluation with a small group of students.

## Suggested Number of Testers

```text
5 to 7 students
```

## Tasks for Testers (Fake data)

Each tester should be asked to complete the following tasks:

1. Find an upcoming event.
2. View the details of that event.
3. Submit an RSVP.
4. Search or filter events by category.
5. Submit a new event request.
6. Find the about/contact page.

---

## Areas to Measure

| Area                   | Example Measure                                       |
| ---------------------- | ----------------------------------------------------- |
| Ease of navigation     | Could users find the events page quickly?             |
| Clarity of information | Did users understand the event details?               |
| Form usability         | Could users submit RSVP without confusion?            |
| Visual design          | Was the layout readable and organized?                |
| Usefulness             | Would students use the website to find campus events? |
| Errors                 | Where did users get stuck?                            |

---

## Sample Feedback Questions (Done within the group)

Use a 1–5 rating scale for the following statements:

1. The website was easy to navigate.
2. Event information was clear.
3. The RSVP form was easy to complete.
4. The event submission form was understandable.
5. The visual design was clean and readable.
6. I would use this website to find campus events.

Open-ended question:

```text
What improvement would make this website more useful?
```

---


# 12. Project Folder Structure (Something like this but once the jekyll site it generated it would look different from this)

The submitted folder should be organized clearly.

```text
CSE2201Project/
|
|-- public-site/
|   |-- index.html
|   |-- events.html
|   |-- event.html
|   |-- submit-event.html
|   |-- about.html
|   |-- contact.html
|   |-- assets/
|       |-- css/
|       |-- js/
|       |-- images/
|
|-- backend/
|   |-- functions/
|       |-- index.js
|       |-- package.json
|       |-- routes/
|       |-- controllers/
|
|-- admin-dashboard/
|   |-- src/
|   |-- public/
|   |-- package.json
|
|-- documentation/
|   |-- project-writeup.docx or pdf
|   |-- usability-evaluation.docx or pdf
|   |-- screenshots/
|
|-- README.md
```

---

# 13. Feature Priority

## 13.1 Must-Have Features

These features are required for the project to feel complete:

* Home page
* Events listing page
* Event details page
* RSVP form
* Event request form
* Firebase database storage
* Express.js server-side API
* Basic admin view
* Usability evaluation
* Project write-up

---

## 13.2 Should-Have Features

These features will make the project stronger:

* Search/filter by category
* RSVP count per event
* Admin status update for event requests
* Responsive design for mobile
* Basic form validation messages

---

# 14. PHP stuff cause sir taught PHP ( Some-one can pick this up)
Just have the contact page post to a php form.

# 15. Final Locked Project Scope

The system will be a campus events information and RSVP website that allows students to browse upcoming campus events, view event details, search or filter by category, and submit RSVP information. Organizers will be able to submit event listing requests.

RSVP submissions and event requests will be stored in Firebase Firestore through a server-side Express.js API hosted on Firebase Cloud Functions. A simple React admin dashboard will allow the group to view RSVP submissions and event requests.

The website will be evaluated through usability testing with a small group of students. Feedback will be collected on ease of navigation, clarity of information, form usability, visual design, and overall usefulness.

This project scope is suitable for CSE 2201 because it includes client-side development, a server-side component, database storage, website design, implementation, and usability evaluation.
