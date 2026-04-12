# SkillSwap

Welcome to the SkillSwap project repository! 

## Documentation

Here are the related documentation files and diagrams for the system:

### Architecture & Flow (Images)

- **System Architecture**:
  ![System Architecture](./docs/skillswap_system_architecture.svg)

- **Swap Flow**:
  ![Swap Flow](./docs/skillswap_swap_flow.svg)

### Database Schema

```mermaid
erDiagram
  USERS ||--o{ USER_SKILLS : "has"
  SKILLS ||--o{ USER_SKILLS : "tagged by"
  USERS ||--o{ SWAP_REQUESTS : "sends"
  USERS ||--o{ SWAP_REQUESTS : "receives"
  SWAP_REQUESTS ||--o| THREADS : "opens"
  THREADS ||--o{ MESSAGES : "contains"
  USERS ||--o{ RATINGS : "gives"
  SWAP_REQUESTS ||--o{ RATINGS : "triggers"

  USERS {
    int id PK
    string name
    string email
    string password_hash
    string bio
    string location
    float avg_rating
    timestamp created_at
  }

  SKILLS {
    int id PK
    string name
    string category
  }

  USER_SKILLS {
    int id PK
    int user_id FK
    int skill_id FK
    enum type
  }

  SWAP_REQUESTS {
    int id PK
    int sender_id FK
    int receiver_id FK
    int offered_skill_id FK
    int wanted_skill_id FK
    enum status
    timestamp created_at
  }

  THREADS {
    int id PK
    int swap_id FK
    timestamp created_at
  }

  MESSAGES {
    int id PK
    int thread_id FK
    int sender_id FK
    text body
    timestamp sent_at
  }

  RATINGS {
    int id PK
    int swap_id FK
    int rater_id FK
    int ratee_id FK
    tinyint stars
    text comment
    timestamp created_at
  }
```

### Folder Structure

```text
skillswap/
├── config/
│   ├── db.php (DB connection)
│   └── constants.php (base URL, site name)
├── includes/ (shared across all pages)
│   ├── header.php
│   ├── footer.php
│   ├── auth_check.php (session guard)
│   └── functions.php (sanitize, flash msgs)
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── profile/
│   ├── view.php (?user_id=X)
│   ├── edit.php (offer + request skills)
│   └── update_skills.php (POST handler)
├── match/ (core feature)
│   ├── index.php (runs match engine, lists results)
│   └── match_engine.php (bidirectional SQL)
├── swaps/
│   ├── request.php (send swap request)
│   ├── respond.php (accept / decline)
│   ├── my_swaps.php (dashboard of all swaps)
│   └── complete.php (mark swap done)
├── messages/
│   ├── thread.php (?swap_id=X — private thread view)
│   └── send.php (POST — insert new message)
├── ratings/
│   ├── rate.php (submit stars + comment)
│   └── update_avg.php (recalculate user avg_rating)
├── admin/
│   ├── index.php (dashboard overview)
│   ├── users.php
│   ├── skills.php (manage skill categories)
│   └── reports.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   ├── main.js
│   │   └── match_filter.js (live JS filter on match page)
│   └── img/ (avatars, placeholders)
├── database/
│   └── skillswap.sql (CREATE TABLE scripts)
└── index.php (landing / explore page)
```
