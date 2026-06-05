---
layout: null
permalink: /
---
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>University of Guyana Events</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --background: #eaffe9;
      --surface: #ffffff;
      --surface-soft: #dff7e0;
      --primary: #022717;
      --primary-muted: #426651;
      --gold: #745b04;
      --border: #c1c8c1;
      --text: #0d1f11;
      --text-soft: #414843;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background: var(--background);
      color: var(--text);
      font-family: Inter, Arial, sans-serif;
    }

    header {
      border-bottom: 4px solid var(--gold);
      background: var(--primary);
      color: white;
    }

    .nav {
      width: min(1200px, calc(100% - 40px));
      min-height: 72px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }

    .brand {
      font-size: 24px;
      font-weight: 700;
    }

    .nav-links {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .nav a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      font-size: 14px;
      font-weight: 700;
    }

    .nav a.active {
      color: #ffe08d;
    }

    main {
      width: min(1200px, calc(100% - 40px));
      margin: 0 auto;
      padding: 48px 0 72px;
    }

    .page-title {
      max-width: 760px;
      margin-bottom: 32px;
    }

    h1 {
      margin: 0 0 12px;
      color: var(--primary);
      font-family: "Source Serif 4", Georgia, serif;
      font-size: clamp(34px, 5vw, 52px);
      line-height: 1.08;
    }

    .page-title p {
      margin: 0;
      color: var(--text-soft);
      font-size: 18px;
      line-height: 1.6;
    }

    .status {
      margin-bottom: 20px;
      padding: 14px 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--surface);
      color: var(--text-soft);
    }

    .event-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 20px;
    }

    .event-card {
      display: flex;
      min-height: 260px;
      flex-direction: column;
      justify-content: space-between;
      padding: 22px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--surface);
      box-shadow: 0 4px 12px rgba(26, 61, 43, 0.08);
    }

    .event-card h2 {
      margin: 14px 0 10px;
      color: var(--primary);
      font-family: "Source Serif 4", Georgia, serif;
      font-size: 24px;
      line-height: 1.25;
    }

    .badge {
      align-self: flex-start;
      padding: 5px 10px;
      border-radius: 999px;
      background: var(--surface-soft);
      color: var(--primary-muted);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .meta {
      margin: 0 0 18px;
      color: var(--text-soft);
      line-height: 1.5;
    }

    .button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 44px;
      padding: 0 16px;
      border-radius: 6px;
      background: var(--primary);
      color: white;
      font-weight: 700;
      text-decoration: none;
    }

    footer {
      border-top: 1px solid var(--border);
      background: #d1e9d2;
    }

    .footer-inner {
      width: min(1200px, calc(100% - 40px));
      margin: 0 auto;
      padding: 28px 0;
      color: var(--text-soft);
      font-size: 14px;
    }

    @media (max-width: 720px) {
      .nav {
        align-items: flex-start;
        flex-direction: column;
        padding: 18px 0;
      }
    }
  </style>
</head>
<body>
  <header>
    <nav class="nav">
      <div class="brand">University of Guyana Events</div>
      <div class="nav-links">
        <a class="active" href="{{ '/' | relative_url }}">Events</a>
        <a href="#">Submit Event</a>
        <a href="{{ '/about/' | relative_url }}">About</a>
      </div>
    </nav>
  </header>

  <main>
    <section class="page-title">
      <h1>Campus Events</h1>
      <p>Browse approved University of Guyana events and open a full event page for details.</p>
    </section>

    <div class="status" id="statusBox">Loading events...</div>
    <section class="event-grid" id="eventsList"></section>
  </main>

  <footer>
    <div class="footer-inner">&copy; 2026 University of Guyana. All rights reserved.</div>
  </footer>

  <script>
    const API_BASE_URL = "https://api-fagsagsasgasg.app/api/v1";

    function formatDate(dateValue) {
      if (!dateValue) {
        return "Date to be announced";
      }

      const date = new Date(`${dateValue}T00:00:00`);

      if (Number.isNaN(date.getTime())) {
        return dateValue;
      }

      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    }

    function setStatus(message) {
      document.getElementById("statusBox").textContent = message;
    }

    function hideStatus() {
      document.getElementById("statusBox").style.display = "none";
    }

    function renderEventCard(event) {
      const card = document.createElement("article");
      const content = document.createElement("div");
      const badge = document.createElement("span");
      const heading = document.createElement("h2");
      const meta = document.createElement("p");
      const link = document.createElement("a");
      const title = event.title || "Campus Event";
      const category = event.category || "General";
      const date = formatDate(event.date);
      const venue = event.venue || "Venue to be announced";

      card.className = "event-card";
      badge.className = "badge";
      badge.textContent = category;
      heading.textContent = title;
      meta.className = "meta";
      meta.textContent = `${date} | ${venue}`;
      link.className = "button";
      link.href = `{{ '/event_details.html' | relative_url }}?id=${encodeURIComponent(event.id)}`;
      link.textContent = "View details";

      content.appendChild(badge);
      content.appendChild(heading);
      content.appendChild(meta);
      card.appendChild(content);
      card.appendChild(link);

      return card;
    }

    async function loadEvents() {
      try {
        const response = await fetch(`${API_BASE_URL}/events`);
        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "Events could not be loaded.");
        }

        const eventsList = document.getElementById("eventsList");
        eventsList.innerHTML = "";

        result.data.forEach((event) => {
          eventsList.appendChild(renderEventCard(event));
        });

        if (result.data.length === 0) {
          setStatus("No approved events are available yet.");
        } else {
          hideStatus();
        }
      } catch (error) {
        console.error("Error loading events:", error);
        setStatus("Events could not be loaded. Please try again later.");
      }
    }

    loadEvents();
  </script>
</body>
</html>
