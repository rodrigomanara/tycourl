# Ticourl API

This document describes only the HTTP REST API exposed by the Ticourl project (public entry: `app/public/restApi.php`). Endpoints are typically under the `/api` prefix (e.g. `/api/authenticate`).

## Authentication

- Token-based (JWT) authentication is used for protected endpoints.
- Obtain a token via `POST /api/authenticate` with credentials.
- Include the token in requests using the `Authorization: Bearer <token>` header.
- Signing keys are located in `src/keys/` (replace private key for production).

## Common headers

- Content-Type: application/json
- Accept: application/json
- Authorization: Bearer <token> (for protected routes)

## Endpoints (summary)

1. POST /api/authenticate
   - Purpose: Authenticate a user and return a JWT access token.
   - Body (example):
     ```json
     {
       "username": "user@example.com",
       "password": "secret"
     }
     ```
   - Success (200):
     ```json
     {
       "token": "<jwt-token>",
       "expires_in": 3600
     }
     ```

2. POST /api/hash
   - Purpose: Create a shortened URL (requires auth).
   - Body (example):
     ```json
     {
       "url": "https://example.com/long/path",
       "title": "Optional title",
       "private": false
     }
     ```
   - Success (201):
     ```json
     {
       "id": "abc123",
       "short_url": "https://yourhost/abc123",
       "qr": "data:image/png;base64,..."
     }
     ```

3. GET /api/hash
   - Purpose: List/Query created short URLs (requires auth).
   - Query params: paging, filters (implementation-specific).
   - Success (200): Array of hash objects with id, original url, visits, created_at.

4. GET /api/hash/{id}
   - Purpose: Get details and analytics for a specific short URL (requires auth or public based on config).
   - Success (200): hash object (id, url, visits, metadata).

5. PUT /api/hash/{id}
   - Purpose: Update metadata or target URL (requires auth and ownership)
   - Body: fields to update (title, url, private, etc.)
   - Success (200): updated hash object

6. DELETE /api/hash/{id}
   - Purpose: Delete a short URL (requires auth and ownership)
   - Success (204): no content

7. GET /api/analytics
   - Purpose: Retrieve analytics for URLs (requires auth)
   - Query params: hash id or date ranges
   - Success (200): analytics object (visits by date, referrers, countries, etc.)

8. GET /api/ping
   - Purpose: Health check / ping endpoint
   - Success (200): { "status": "ok" }

9. /api/users
   - Typical operations: POST to create user, GET list, GET/{id}, PUT/{id}, DELETE/{id}
   - Access restricted to admin or owner depending on route.

## Error responses

Standard JSON error response (examples):

- Validation / bad request (400):
  ```json
  {
    "error": "Validation failed",
    "details": { "url": "Invalid URL format" }
  }
  ```

- Authentication error (401):
  ```json
  {
    "error": "Invalid credentials or token"
  }
  ```

- Not found (404):
  ```json
  {
    "error": "Resource not found"
  }
  ```

- Forbidden (403):
  ```json
  {
    "error": "Not authorized to perform this action"
  }
  ```

- Server error (500):
  ```json
  {
    "error": "Internal server error"
  }
  ```

Error structure and codes may vary slightly by endpoint; consult `app/public/swagger.json` or `app/swagger.php` when available.

## API specification / Swagger

- Swagger/OpenAPI spec (if present): `app/public/swagger.json` or `app/swagger.php`.
- Use that file for exact request/response schemas and available parameters.

## Notes & tips

- Replace JWT keys in `src/keys/` for production and keep the private key secure.
- Some endpoints require ownership or admin role; see role handling under `src/Type/RoleType.php` and authorization logic in `src/Library/Api/Middleware.php`.
- The exact query parameters and pagination behavior are implementation details; check the controller code in `src/RestApi/` for specifics.

---

For a full, route-by-route reference, I can scan `src/RestApi/` and generate a complete list with parameter and response schemas — tell me if you want that next.
