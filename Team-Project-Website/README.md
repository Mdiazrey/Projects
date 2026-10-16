# Student Tutoring Help Site

A course project created by Mauricio Diaz Reyes, Samuel Berebitsky, and Madelynn Hedges for Indiana University's I308 Information Representation course (Fall 2025).

The site connects students requesting help with tutors. It stores student requests, tutor availability and rates, tutor/student matches, and payment status reports.

## Technology and project map

- PHP 8 with the `mysqli` extension provides the server-rendered pages and form handlers.
- MySQL 8 stores the seven project tables: `student`, `tutor`, `tutor_req`, `course`, `request`, `overseer`, and `tutor_match`.
- `index.html`, `student-form.html`, `tutor-form.html`, `overseer-login.html`, and `faq.html` are the static entry pages.
- `handle_students.php`, `handle_tutors.php`, `handle_matches.php`, `payment-form.php`, `payment-report.php`, and `overseer-dashboard.php` read and update MySQL.
- `db.php` owns all database configuration and initializes an empty database.
- `project8.sql` is the schema and portfolio demo data. `project9.sql` preserves the original course query exercise.
- `Dockerfile` is the production web image. `compose.yaml` runs the complete site locally.

The sample people, email addresses, and phone numbers in `project8.sql` are demonstration data, not real user accounts.

## Run locally with Docker

Docker Desktop is the only prerequisite.

```bash
docker compose up --build
```

Open <http://localhost:8080>. The local-only overseer password is `local-admin-only`. MySQL data persists in the `tutoring-data` Docker volume.

To stop the app:

```bash
docker compose down
```

## Deploy publicly on Railway

Railway is a convenient fit because it can build the included Dockerfile and provision a MySQL service in the same private network.

1. Publish this folder to a GitHub repository. Do **not** publish an `.env` file.
2. In a new Railway project, choose **New → Database → MySQL**.
3. Add another service from the GitHub repository. Railway detects `Dockerfile` automatically.
4. In the web service's **Variables** tab, add references to the MySQL service for these five variables: `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, and `MYSQLDATABASE`. Use Railway's variable-reference picker so credentials stay synchronized.
5. Add `ADMIN_PASSWORD` to the web service with a long, unique password. Optionally add `DB_AUTO_INIT=true`; initialization is already enabled by default.
6. Deploy the web service. On its first database connection, `db.php` creates the schema and loads the demo rows from `project8.sql`.
7. In the web service's **Settings → Networking**, generate a public domain. The container listens on Railway's supplied `PORT` automatically.

Keep the MySQL service private; only the PHP web service needs a public domain. Subsequent GitHub pushes trigger new builds without replacing existing database data.

## Environment variables

| Variable | Purpose |
| --- | --- |
| `MYSQLHOST` / `DB_HOST` | MySQL hostname |
| `MYSQLPORT` / `DB_PORT` | MySQL port (defaults to `3306`) |
| `MYSQLUSER` / `DB_USER` | MySQL user |
| `MYSQLPASSWORD` / `DB_PASSWORD` | MySQL password |
| `MYSQLDATABASE` / `DB_NAME` | Database name |
| `DB_AUTO_INIT` | Set to `false` to disable first-run schema/demo-data setup |
| `ADMIN_PASSWORD` | Password for the overseer dashboard |

See `.env.example` for generic non-Railway names. Secrets belong in the hosting provider's variables, never in Git.

## Before making the repository public

The university database password was committed in the original repository history. Deleting the file in the latest commit does not erase older commits. For a public portfolio copy, either create a new repository with a fresh history from the current files, or use a history-cleaning tool before pushing. The retired university credential should also be treated as compromised and never reused.

Because this was a group project, keep the contributor attribution and confirm that all teammates are comfortable with the public portfolio copy.

## License

Reuse and modification are permitted for educational purposes. Commercial use requires permission; see [LICENSE](LICENSE).

