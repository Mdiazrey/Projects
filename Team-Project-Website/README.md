# Student Tutoring Help Site

> **Disclaimer:** These projects were worked on and completed in Indiana University's academic ecosystem. AI-assisted tools were used to help prepare sanitized portfolio versions that can be displayed without compromising IU data, credentials, or services. This repository presents previous academic work to demonstrate experience with different technologies, design methods, and resources. It is not connected to or endorsed by Indiana University. All work is presented as academic portfolio material, and group contributors remain credited.

A course project created by Mauricio Diaz Reyes, Samuel Berebitsky, and Madelynn Hedges for Indiana University's I308 Information Representation course (Fall 2025).

The application connects students requesting help with tutors. It stores student requests, tutor availability and rates, tutor/student matches, and payment-status reports.

## Local demonstration only

The original application used an Indiana University-hosted MySQL database that is no longer available. This portfolio version does **not** connect to IU systems. It runs locally with its own MySQL container and automatically loads the included demonstration schema and sample records.

The complete application can be explored on a local computer, but no public deployment is currently maintained. Do not enter real personal, academic, or payment information.

## Technology and project map

- PHP 8 with the `mysqli` extension provides the server-rendered pages and form handlers.
- MySQL 8 stores the seven project tables: `student`, `tutor`, `tutor_req`, `course`, `request`, `overseer`, and `tutor_match`.
- `index.html`, `student-form.html`, `tutor-form.html`, `overseer-login.html`, and `faq.html` are the primary entry pages.
- `handle_students.php`, `handle_tutors.php`, `handle_matches.php`, `payment-form.php`, `payment-report.php`, and `overseer-dashboard.php` read and update MySQL.
- `db.php` manages database configuration and initializes an empty database.
- `project8.sql` contains the schema and portfolio demonstration data. `project9.sql` preserves the original course query exercise.
- `Dockerfile` defines the PHP/Apache web container. `compose.yaml` starts both the website and its local MySQL database.

The names, email addresses, phone numbers, and records in `project8.sql` are fictional demonstration data rather than real user accounts.

## Run the complete website locally

### Prerequisites

- [Git](https://git-scm.com/downloads)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/), running before the commands below are entered

### 1. Clone the portfolio repository

```bash
git clone https://github.com/Mdiazrey/Projects.git
cd Projects/Team-Project-Website
```

If the repository is already downloaded, open a terminal directly inside the `Team-Project-Website` folder.

### 2. Build and start the application

```bash
docker compose up --build
```

The first launch can take a few minutes while Docker downloads and builds the PHP and MySQL images. Leave this terminal open while using the website.

### 3. Open the website

Visit [http://localhost:8080](http://localhost:8080) in a browser.

On the first database-backed request, the application creates the tables and loads the demonstration rows from `project8.sql`. Submitted student requests, tutors, and matches remain in the local Docker volume between restarts.

### 4. Explore the overseer dashboard

Open the **Overseer Login** page from the website. The local demonstration password is:

```text
local-admin-only
```

This password is intentionally limited to the local demonstration configuration and must never be reused for another account or public deployment.

### 5. Stop the application

Press `Ctrl+C` in the terminal running Docker, then run:

```bash
docker compose down
```

This stops the containers without deleting the locally stored demonstration database.

## Reset the demonstration database

To erase all locally submitted data and return to the original sample dataset, stop the application and remove its Docker volume:

```bash
docker compose down --volumes
docker compose up --build
```

**Warning:** `--volumes` permanently deletes the database created by this local Docker project. It does not affect any IU system or remote database.

## Troubleshooting

### Port 8080 is already in use

Stop the other application using port 8080, or change the first number in this line of `compose.yaml`:

```yaml
ports:
  - "8080:80"
```

For example, use `8081:80`, restart Docker Compose, and open `http://localhost:8081`.

### The website cannot reach the database

Check the container status and logs:

```bash
docker compose ps
docker compose logs database
docker compose logs web
```

Wait until the database reports that it is healthy. If this is the first launch, Docker may still be downloading or initializing MySQL.

### Start again after restarting the computer

From the `Team-Project-Website` folder, run:

```bash
docker compose up
```

Use `--build` again only when the source code or Docker configuration has changed.

## Configuration reference

The included `compose.yaml` supplies the local demonstration configuration automatically. The application also supports the following environment variables for advanced setups:

| Variable | Purpose |
| --- | --- |
| `MYSQLHOST` / `DB_HOST` | MySQL hostname |
| `MYSQLPORT` / `DB_PORT` | MySQL port; defaults to `3306` |
| `MYSQLUSER` / `DB_USER` | MySQL user |
| `MYSQLPASSWORD` / `DB_PASSWORD` | MySQL password |
| `MYSQLDATABASE` / `DB_NAME` | Database name |
| `DB_AUTO_INIT` | Set to `false` to disable first-run schema and demo-data setup |
| `ADMIN_PASSWORD` | Password for the overseer dashboard |

See `.env.example` for the generic variable names. Never commit real credentials or an `.env` file.

## Academic and security notes

- This is a portfolio copy of collaborative academic work, not a production service.
- The repository uses a fresh public history and does not contain the retired university database password.
- No application component connects to Indiana University infrastructure.
- The included records are demonstration data and should not be replaced with real personal information.
- Contributor attribution should remain intact.

## License

Reuse and modification are permitted for educational purposes. Commercial use requires permission; see [LICENSE](LICENSE).
