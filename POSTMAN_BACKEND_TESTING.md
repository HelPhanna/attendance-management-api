# Postman Backend Testing Guide

This guide is for testing the Attendance Management backend with Postman.

It is based on the current Laravel API routes in `routes/api.php` and the request validation found in the controller/action layer.

## 1. Before You Start

Make sure the backend can run locally.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Default local API base URL:

```text
http://127.0.0.1:8000/api
```

If you use another port, update the Postman environment.

## 2. Create a Postman Environment

Create these variables in Postman:

| Variable | Example Value | Notes |
|---|---|---|
| `base_url` | `http://127.0.0.1:8000/api` | Main API base URL |
| `token` | empty at first | Filled after login |
| `user_id` | empty | Saved from auth/user responses |
| `student_id` | empty | Saved after student creation |
| `teacher_id` | empty | Saved after teacher lookup |
| `role_id` | empty | Saved after role creation/list |
| `permission_id` | empty | Saved after permission creation/list |
| `grade_level_id` | empty | Saved after grade level creation |
| `class_id` | empty | Saved after class creation |
| `academic_year_id` | empty | Saved after academic year creation |
| `term_id` | empty | Saved after term creation |
| `class_session_id` | empty | Saved after class session creation |
| `enrollment_id` | empty | Saved after enrollment creation |
| `blacklist_id` | empty | Saved after blacklist creation |

## 3. Common Headers

For most JSON requests:

| Key | Value |
|---|---|
| `Accept` | `application/json` |
| `Content-Type` | `application/json` |

For protected routes, also add:

| Key | Value |
|---|---|
| `Authorization` | `Bearer {{token}}` |

For `user-profile` image upload, use `form-data` instead of raw JSON.

## 4. Recommended Testing Order

Use this order so IDs exist before you test dependent endpoints:

1. `POST /auth/register`
2. `POST /auth/login`
3. `POST /user-profile/create`
4. `GET /roles` and `GET /permissions` or create them first
5. `POST /grade-levels/create`
6. `POST /classes/create`
7. `POST /academic-year`
8. `POST /term`
9. `GET /teachers`
10. `POST /students/create`
11. `POST /enrollments/create`
12. `POST /class-teachers/create`
13. `POST /class-session`
14. `POST /attendance-records`
15. `POST /report-export/pdf` or `POST /report-export/xlsx`

## 5. Save Token Automatically After Login

In the **Tests** tab of the login request, add:

```javascript
const json = pm.response.json();

if (json.token) {
  pm.environment.set("token", json.token);
}

if (json.user && json.user.id) {
  pm.environment.set("user_id", json.user.id);
}
```

## 6. Public Auth Endpoints

### 6.1 Register

**POST** `{{base_url}}/auth/register`

```json
{
  "name": "Student One",
  "email": "student1@example.com",
  "password": "password",
  "password_confirmation": "password",
  "status": "active"
}
```

Notes:

- `password` must be at least 6 characters.
- New users automatically get the default `student` role.
- If the `student` role does not exist in the database, registration may fail.

### 6.2 Login

**POST** `{{base_url}}/auth/login`

```json
{
  "email": "student1@example.com",
  "password": "password"
}
```

Expected success response includes:

- `message`
- `token`
- `user`

### 6.3 Forgot Password

**POST** `{{base_url}}/auth/forgot-password`

```json
{
  "email": "student1@example.com"
}
```

## 7. Protected Auth Endpoints

These need `Authorization: Bearer {{token}}`.

### 7.1 List Users

**GET** `{{base_url}}/auth/index`

### 7.2 Show User

**GET** `{{base_url}}/auth/show/{{user_id}}`

### 7.3 Update Logged-In User

**PUT** `{{base_url}}/auth/update`

```json
{
  "name": "Student One Updated",
  "email": "student1_updated@example.com",
  "password": "newpassword",
  "password_confirmation": "newpassword",
  "status": "active"
}
```

All fields are optional except when you choose to send them.

### 7.4 Logout

**POST** `{{base_url}}/auth/logout`

## 8. User Profile

All routes below are protected.

### 8.1 Create Profile

**POST** `{{base_url}}/user-profile/create`

Body type: `form-data`

| Key | Type | Example |
|---|---|---|
| `first_name` | Text | `Student` |
| `last_name` | Text | `One` |
| `phone` | Text | `0123456789` |
| `gender` | Text | `male` |
| `date_of_birth` | Text | `25/12/2004` |
| `address` | Text | `Bangkok` |
| `image` | File | optional jpg/jpeg/png |

Important:

- `date_of_birth` is parsed in `d/m/Y` format.
- `image` max size is 2 MB.

### 8.2 Show Profile

**GET** `{{base_url}}/user-profile/show`

### 8.3 Update Profile

**PUT** `{{base_url}}/user-profile/update`

Use the same `form-data` fields as create.

### 8.4 Delete Profile

**DELETE** `{{base_url}}/user-profile/delete`

## 9. Permissions

### 9.1 List Permissions

**GET** `{{base_url}}/permissions`

### 9.2 Create Permission

**POST** `{{base_url}}/permissions`

```json
{
  "name": "Manage Students",
  "key": "manage_students",
  "status": "active",
  "description": "Can create, update, and delete students"
}
```

### 9.3 Show Permission

**GET** `{{base_url}}/permissions/{{permission_id}}`

### 9.4 Update Permission

**PUT** `{{base_url}}/permissions/{{permission_id}}`

```json
{
  "name": "Manage Students",
  "key": "manage_students",
  "status": "active",
  "description": "Updated description"
}
```

### 9.5 Delete Permission

**DELETE** `{{base_url}}/permissions/{{permission_id}}`

## 10. Roles

### 10.1 List Roles

**GET** `{{base_url}}/roles`

### 10.2 Create Role

**POST** `{{base_url}}/roles/create`

```json
{
  "name": "Teacher",
  "key": "teacher",
  "status": "active",
  "description": "Teacher role"
}
```

### 10.3 Show Role

**GET** `{{base_url}}/roles/{{role_id}}`

### 10.4 Update Role

**PUT** `{{base_url}}/roles/update/{{role_id}}`

```json
{
  "name": "Teacher",
  "key": "teacher",
  "status": "active",
  "description": "Updated teacher role"
}
```

### 10.5 Delete Role

**DELETE** `{{base_url}}/roles/{{role_id}}`

## 11. User Roles

### 11.1 List User Roles

**GET** `{{base_url}}/user-roles`

### 11.2 Assign Roles to User

**POST** `{{base_url}}/user-roles/create`

```json
{
  "user_id": {{user_id}},
  "role_id": [{{role_id}}]
}
```

### 11.3 Update User Roles

**POST** `{{base_url}}/user-roles/update`

```json
{
  "user_id": {{user_id}},
  "role_id": [{{role_id}}]
}
```

### 11.4 Delete User Roles

**POST** `{{base_url}}/user-roles/delete`

```json
{
  "user_id": {{user_id}},
  "role_id": [{{role_id}}]
}
```

### 11.5 Show User Roles

**GET** `{{base_url}}/user-roles/{{user_id}}`

## 12. Role Permissions

### 12.1 List Role Permissions

**GET** `{{base_url}}/rolespermissions`

### 12.2 Assign Permissions to a Role

**POST** `{{base_url}}/rolespermissions`

```json
{
  "role_id": {{role_id}},
  "permission_id": [{{permission_id}}]
}
```

### 12.3 Replace Role Permissions

**PUT** `{{base_url}}/rolespermissions`

```json
{
  "role_id": {{role_id}},
  "permission_id": [{{permission_id}}]
}
```

### 12.4 Remove Permissions from a Role

**DELETE** `{{base_url}}/rolespermissions`

```json
{
  "role_id": {{role_id}},
  "permission_id": [{{permission_id}}]
}
```

## 13. Grade Levels

### 13.1 List Grade Levels

**GET** `{{base_url}}/grade-levels`

### 13.2 Create Grade Level

**POST** `{{base_url}}/grade-levels/create`

```json
{
  "code": "G1",
  "name": "Grade 1",
  "order_no": 1,
  "is_active": true
}
```

### 13.3 Show Grade Level

**GET** `{{base_url}}/grade-levels/{{grade_level_id}}`

### 13.4 Update Grade Level

**PUT** `{{base_url}}/grade-levels/update/{{grade_level_id}}`

```json
{
  "code": "G1",
  "name": "Grade 1 Updated",
  "order_no": 1,
  "is_active": true
}
```

### 13.5 Delete Grade Level

**DELETE** `{{base_url}}/grade-levels/{{grade_level_id}}`

## 14. Grade Level Subjects

### 14.1 List Grade Level Subjects

**GET** `{{base_url}}/grade-level-subjects`

### 14.2 Assign Subjects to Grade Level

**POST** `{{base_url}}/grade-level-subjects/create`

```json
{
  "grade_level_id": {{grade_level_id}},
  "subject_id": [1, 2]
}
```

### 14.3 Show One Grade Level Subject Row

**GET** `{{base_url}}/grade-level-subjects/1`

### 14.4 Replace Subject Mapping for a Grade Level

**PUT** `{{base_url}}/grade-level-subjects/update/{{grade_level_id}}`

```json
{
  "grade_level_id": {{grade_level_id}},
  "subject_id": [1, 2, 3]
}
```

Note:

- The route has `/{id}`, but the action validates `grade_level_id` from the body.
- Send the body shown above.

### 14.5 Remove All Subjects from a Grade Level

**DELETE** `{{base_url}}/grade-level-subjects/{{grade_level_id}}`

## 15. Classes

### 15.1 List Classes

**GET** `{{base_url}}/classes`

### 15.2 Create Class

**POST** `{{base_url}}/classes/create`

```json
{
  "name": "Class A",
  "grade_level_id": {{grade_level_id}},
  "start_date": "2026-05-01",
  "end_date": "2026-12-31",
  "room_number": "A-101"
}
```

### 15.3 Show Class

**GET** `{{base_url}}/classes/{{class_id}}`

### 15.4 Update Class

**PUT** `{{base_url}}/classes/update/{{class_id}}`

```json
{
  "name": "Class A Updated",
  "grade_level_id": {{grade_level_id}},
  "start_date": "2026-05-01",
  "end_date": "2026-12-31",
  "room_number": "A-102"
}
```

### 15.5 Delete Class

**DELETE** `{{base_url}}/classes/{{class_id}}`

## 16. Academic Year

### 16.1 List Academic Years

**GET** `{{base_url}}/academic-year`

### 16.2 Create Academic Year

**POST** `{{base_url}}/academic-year`

```json
{
  "name": "2026-2027",
  "start_date": "2026-05-01",
  "end_date": "2027-03-31"
}
```

### 16.3 Show Academic Year

**GET** `{{base_url}}/academic-year/{{academic_year_id}}`

### 16.4 Update Academic Year

**PUT** `{{base_url}}/academic-year/{{academic_year_id}}`

```json
{
  "name": "2026-2027",
  "start_date": "2026-05-01",
  "end_date": "2027-03-31"
}
```

### 16.5 Delete Academic Year

**DELETE** `{{base_url}}/academic-year/{{academic_year_id}}`

Extra routes:

- `DELETE /academic-year/all`
- `DELETE /academic-year`

Use these carefully because they are bulk-delete routes.

## 17. Term

### 17.1 List Terms

**GET** `{{base_url}}/term`

### 17.2 Create Term

**POST** `{{base_url}}/term`

```json
{
  "name": "Term 1",
  "academic_year_id": {{academic_year_id}},
  "start_date": "2026-05-01",
  "end_date": "2026-09-30"
}
```

### 17.3 Show Term

**GET** `{{base_url}}/term/{{term_id}}`

### 17.4 Update Term

**PUT** `{{base_url}}/term/{{term_id}}`

```json
{
  "name": "Term 1",
  "academic_year_id": {{academic_year_id}},
  "start_date": "2026-05-01",
  "end_date": "2026-09-30"
}
```

### 17.5 Delete Term

**DELETE** `{{base_url}}/term/{{term_id}}`

Extra routes:

- `DELETE /term/all`
- `DELETE /term`

## 18. Students

### 18.1 List Students

**GET** `{{base_url}}/students`

Optional query:

```text
{{base_url}}/students?status=active
```

### 18.2 Create Student

**POST** `{{base_url}}/students/create`

```json
{
  "user_id": {{user_id}},
  "student_code": "STD0001",
  "status": "active"
}
```

### 18.3 Show Student

**GET** `{{base_url}}/students/{{student_id}}`

### 18.4 Update Student

**PUT** `{{base_url}}/students/update/{{student_id}}`

```json
{
  "student_code": "STD0001",
  "status": "active"
}
```

### 18.5 Delete Student

**DELETE** `{{base_url}}/students/{{student_id}}`

## 19. Teachers

These routes are under `role:admin` middleware.

### 19.1 List Teachers

**GET** `{{base_url}}/teachers`

Optional query params:

- `status=active`
- `teacher_code=TCH`

Example:

```text
{{base_url}}/teachers?status=active&teacher_code=TCH
```

### 19.2 Show Teacher

**GET** `{{base_url}}/teachers/{{teacher_id}}`

### 19.3 Update Teacher

**PUT** `{{base_url}}/teachers/update/{{teacher_id}}`

```json
{
  "status": "active",
  "teacher_code": "TCH0001"
}
```

## 20. Enrollments

### 20.1 List Enrollments

**GET** `{{base_url}}/enrollments`

### 20.2 Create Enrollment

**POST** `{{base_url}}/enrollments/create`

```json
{
  "class_id": {{class_id}},
  "student_id": {{student_id}},
  "enrolled_on": "2026-05-02"
}
```

Important:

- Student must be `active`.
- Student cannot be enrolled twice in the same class.

### 20.3 Show Enrollment

**GET** `{{base_url}}/enrollments/{{enrollment_id}}`

### 20.4 Update Enrollment

**PUT** `{{base_url}}/enrollments/update/{{enrollment_id}}`

```json
{
  "enrolled_on": "2026-05-03"
}
```

### 20.5 Delete Enrollment

**DELETE** `{{base_url}}/enrollments/{{enrollment_id}}`

### 20.6 List Students in a Class

**GET** `{{base_url}}/enrollments/classes/{{class_id}}/students`

## 21. Class Teachers

### 21.1 List Class Teachers

**GET** `{{base_url}}/class-teachers`

### 21.2 Assign Teacher to Class

**POST** `{{base_url}}/class-teachers/create`

```json
{
  "class_id": {{class_id}},
  "teacher_id": {{teacher_id}},
  "assigned_at": "2026-05-02"
}
```

### 21.3 Show Class Teacher

**GET** `{{base_url}}/class-teachers/1`

### 21.4 Update Class Teacher

**PUT** `{{base_url}}/class-teachers/update/1`

```json
{
  "class_id": {{class_id}},
  "teacher_id": {{teacher_id}},
  "assigned_at": "2026-05-03"
}
```

### 21.5 Delete Class Teacher

**DELETE** `{{base_url}}/class-teachers/1`

## 22. Class Session

### 22.1 List Class Sessions

**GET** `{{base_url}}/class-session`

### 22.2 Create Class Session

**POST** `{{base_url}}/class-session`

```json
{
  "class_id": {{class_id}},
  "term_id": {{term_id}},
  "teacher_id": {{teacher_id}},
  "subject_id": 1,
  "day_of_week": "Monday",
  "start_time": "08:00",
  "end_time": "10:00",
  "status": "scheduled",
  "created_on": "2026-05-02"
}
```

Allowed `status` values:

- `scheduled`
- `not_started`
- `ongoing`
- `completed`
- `canceled`
- `postponed`

### 22.3 Show Class Session

**GET** `{{base_url}}/class-session/{{class_session_id}}`

### 22.4 Update Class Session

**PUT** `{{base_url}}/class-session/{{class_session_id}}`

```json
{
  "status": "ongoing",
  "start_time": "08:30",
  "end_time": "10:30"
}
```

### 22.5 Delete Class Session

**DELETE** `{{base_url}}/class-session/{{class_session_id}}`

Extra routes:

- `DELETE /class-session/all`
- `DELETE /class-session`

## 23. Attendance Records

### 23.1 Filter Attendance Summary

**GET** `{{base_url}}/attendance-records/filter`

Required query params:

- `date`
- `term_id`
- `class_id`
- `teacher_id`

Optional query params:

- `start_time`
- `end_time`

Example:

```text
{{base_url}}/attendance-records/filter?date=2026-05-02&term_id={{term_id}}&class_id={{class_id}}&teacher_id={{teacher_id}}&start_time=08:00:00&end_time=10:00:00
```

### 23.2 List Attendance Records

**GET** `{{base_url}}/attendance-records`

Optional query params:

- `class_session_id`
- `student_id`
- `status`

Example:

```text
{{base_url}}/attendance-records?class_session_id={{class_session_id}}&status=present
```

### 23.3 Show Attendance Record

**GET** `{{base_url}}/attendance-records/1`

### 23.4 Create Attendance Records in Bulk

**POST** `{{base_url}}/attendance-records`

```json
{
  "class_session_id": {{class_session_id}},
  "records": [
    {
      "student_id": {{student_id}},
      "status": "present",
      "comment": "On time"
    }
  ]
}
```

Allowed `status` values:

- `present`
- `absent`
- `permission`

### 23.5 Update Attendance Records in Bulk

**PUT** `{{base_url}}/attendance-records`

```json
{
  "class_session_id": {{class_session_id}},
  "records": [
    {
      "student_id": {{student_id}},
      "status": "absent",
      "comment": "Updated in Postman"
    }
  ]
}
```

### 23.6 Delete Attendance Records in Bulk

**DELETE** `{{base_url}}/attendance-records`

```json
{
  "class_session_id": {{class_session_id}},
  "student_ids": [{{student_id}}]
}
```

## 24. Blacklists

### 24.1 List Blacklists

**GET** `{{base_url}}/blacklists`

### 24.2 Create Blacklist

**POST** `{{base_url}}/blacklists/create`

```json
{
  "student_id": {{student_id}},
  "term_id": {{term_id}},
  "absence_count": 3,
  "flagged_at": "2026-05-10"
}
```

### 24.3 Show Blacklist

**GET** `{{base_url}}/blacklists/{{blacklist_id}}`

### 24.4 Update Blacklist

**PUT** `{{base_url}}/blacklists/update/{{blacklist_id}}`

```json
{
  "absence_count": 4,
  "flagged_at": "2026-05-11"
}
```

### 24.5 Delete Blacklist

**DELETE** `{{base_url}}/blacklists/{{blacklist_id}}`

## 25. Report Export

This endpoint returns a file download.

### 25.1 Export PDF

**POST** `{{base_url}}/report-export/pdf`

```json
{
  "date_from": "2026-05-01",
  "date_to": "2026-05-31",
  "academic_year_id": {{academic_year_id}},
  "term_id": {{term_id}},
  "class_id": {{class_id}}
}
```

### 25.2 Export Excel

**POST** `{{base_url}}/report-export/xlsx`

Use the same body as the PDF export request.

Important:

- `term_id` must belong to the selected `academic_year_id`.
- Export fails if no attendance records are found for that class and term in the selected date range.

## 26. Useful Postman Test Snippets

### Save Student ID

```javascript
const json = pm.response.json();
if (json.data && json.data.id) {
  pm.environment.set("student_id", json.data.id);
}
```

### Save Class ID

```javascript
const json = pm.response.json();
if (json.data && json.data.id) {
  pm.environment.set("class_id", json.data.id);
}
```

### Save Term ID

```javascript
const json = pm.response.json();
if (json.data && json.data.id) {
  pm.environment.set("term_id", json.data.id);
}
```

### Save Session ID

```javascript
const json = pm.response.json();
if (json.data && json.data.id) {
  pm.environment.set("class_session_id", json.data.id);
}
```

## 27. Common Problems

### 401 Unauthorized

Check:

- login was successful
- `{{token}}` is set
- `Authorization` header uses `Bearer {{token}}`

### 403 Forbidden

This usually means the account or role is not allowed to access the route.

Example:

- `/teachers` requires `role:admin`

### 422 Validation Error

This means the request body or query parameters do not match what Laravel validates.

Common cases:

- missing required IDs
- invalid enum values like `status`
- wrong date format
- duplicate unique fields such as `email`, `key`, or `student_code`

### 500 Server Error

Check:

- database records required by foreign keys exist
- roles like `student` and `teacher` exist
- mail configuration is valid for forgot-password
- storage/public is linked if profile image or exports depend on it

Useful commands:

```bash
php artisan storage:link
php artisan cache:clear
php artisan config:clear
```

## 28. Suggested Postman Collection Folders

Organize the Postman collection like this:

1. Auth
2. User Profile
3. Permissions
4. Roles
5. User Roles
6. Role Permissions
7. Grade Levels
8. Grade Level Subjects
9. Classes
10. Academic Year
11. Term
12. Students
13. Teachers
14. Enrollments
15. Class Teachers
16. Class Session
17. Attendance Records
18. Blacklists
19. Report Export

## 29. Final Note

If you want, the next step can be creating a ready-to-import Postman collection JSON from this guide so you do not need to add every request manually.
