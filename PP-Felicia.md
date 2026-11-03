# PP‑Felicia Project Log

*File created on 2026‑10‑31.*

## Decisions Made
- **Project scope**: The portal is for supplementary examinations only. (Confirmed 2026-11-01)
- **Staff access**: Chief Examiner and Administrator use staff access separate from student access. Staff can sign in but cannot self-register. Chief Examiner registration is removed. (Confirmed 2026-11-03)
- **Administrator account creation**: Bootstrap the first Administrator through a distinct, private setup credential/invitation code; do not expose an open public Administrator sign-up. Administrators can then create and assign staff accounts. Exact setup-code handling and recovery remain to be designed before implementation. (Decision recorded 2026-11-03)
- **Staff invitation flow**: Staff sign in through the staff portal. They do not self-register. An invited staff member enters the invited email and one-time invitation code, then sets their name and password; the invitation determines their role. Each invitation is single-use. (Agreed 2026-11-03)
- **Student identity and access**: Students may sign in with either email or a positive, exactly 10-digit integer index number. No school-specific email domain is required. Student account creation and registration are available only during the supplementary registration window. (Confirmed 2026-11-03)
- **Supplementary registration window enforcement**: A student's account can be created when at least one active supplementary course has both dates configured and is open. Each course opens at its start time (inclusive) and closes at its deadline time (exclusive); missing dates mean closed. Application times use Ghana time (`Africa/Accra`). (Implemented 2026-11-03)
- **Role responsibilities**: Chief Examiner manages supplementary courses, fees, registration dates/deadlines, and timetables. Administrator manages accounts, role assignment, account removal/disablement, and password-reset support. Students manage their own supplementary registrations and view their payment and exam information. (Confirmed 2026-11-03)
- **Password recovery**: Password reset should use a verified email address; email delivery and verification details remain to be decided before implementation. (Confirmed 2026-11-03)
- **Staff invitation email delivery**: Invitation codes must be delivered to the specified real recipient email address; local log-only delivery is insufficient. Select an institution-approved SMTP or transactional mail provider before production delivery. (Confirmed 2026-11-03)
- **Email configuration for development**: Use the dedicated project Gmail account through Gmail SMTP for development once its App Password is available; keep credentials in the local `.env`. Confirm an institution-approved production mail provider before deployment. (Confirmed 2026-11-03)
- **Learning and implementation workflow**: Explain existing code and proposed logic in chat first. Felicia reviews and may choose to implement it herself, request a core structure, or explicitly authorize Codex to implement it. Work incrementally and explain unfamiliar concepts in plain language. Do not implement authentication logic until Felicia reviews the proposed approach and authorizes the next step. (Confirmed 2026-11-03)
- **Change reporting**: For every project file created, edited, or deleted, report its path, exact added/updated/deleted line range, and a concise summary. (Confirmed 2026-11-03)

## Work Completed
- Reviewed current authentication, role, route, and registration-deadline implementation. (2026-11-03)
- Added front-end-only staff access and Administrator/Chief Examiner dashboard previews; no authentication or database wiring was added. (2026-11-03)
- Added a front-end invitation acceptance/account-setup flow with a blurred staff-page backdrop, plus revised staff and dashboard copy to use production-style wording. These screens remain unconnected prototypes. (2026-11-03)
- Added the `staff_invitations` table migration for role-bound, expiring, single-use staff invitations; account creation and invitation verification logic are still pending. (2026-11-03)
- Added reusable role-check middleware and protected student, Administrator, and Chief Examiner dashboard routes. Public design previews remain read-only. Staff sign-in routing and invitation authorization are still pending. (2026-11-03)
- Restricted student sign-in to the `student` role; student sign-in now accepts either email or an exactly 10-digit positive index number. Registration validates the same index format, and demo student records use numeric 10-digit indexes. (2026-11-03)
- Reviewed current route and record ownership checks: student pages require the student role and student-owned registration/payment records are checked; Administrator invitation actions require the Administrator role. First-Administrator bootstrap remains undecided. (2026-11-03)
- Enforced registration-window checks on student account creation and course application; closed or incomplete course windows are rejected, including at the exact deadline. The login page reflects whether any active course window is open. (2026-11-03)
- Connected staff sign-in to a POST route; successful staff authentication now routes by the role stored on the account, and student accounts are rejected from staff sign-in. (2026-11-03)
- Connected staff invitation verification and account setup to the invitation table. The invitee’s email, code, expiry and unused status are checked; password confirmation is enforced; account creation and invitation consumption happen together. (2026-11-03)
- Added Administrator-only staff invitation issuance code and email flow. Felicia has not reviewed or understood this code yet; real email setup remains paused and no real invitation email has been sent. (2026-11-03)

## Upcoming Work
- Member 1 (Authentication, Users & Course Management) – start date: 2026‑10‑31
- Member 2 (Student Registration & Portal) – TBD
- Member 3 (Mobile Money Payment Integration) – TBD
- Member 4 (Chief Examiner, Timetable & Email Notifications) – TBD
- **Integration Order**: 1️⃣ Member 1 → 2️⃣ Member 2 → 3️⃣ Member 3 → 4️⃣ Member 4
- **Integration Order Details**:
  1. **Member 1 – Authentication, Users & Course Management**
     - Implement user registration, login, and role‑based access control.
     - Define database tables for users, roles, and courses that will be shared.
     - Expose REST endpoints (`/api/auth/*`, `/api/courses/*`) for other modules.
  2. **Member 2 – Student Registration & Portal**
     - Build the registration UI and student dashboard.
     - Consume Member 1’s authentication APIs.
     - Store registration records that reference the user and course IDs defined by Member 1.
  3. **Member 3 – Mobile Money Payment Integration**
     - Integrate payment gateway and link payments to registration records from Member 2.
     - Update payment status fields in the shared schema.
     - Provide webhook handlers that emit events used by Member 4.
  4. **Member 4 – Chief Examiner, Timetable & Email Notifications**
     - Create examiner dashboard to view paid registrations.
     - Generate and publish examination timetables.
     - Send email notifications using the data populated by Members 1‑3.
  5. **Integration Testing**
     - After each module merges, run end‑to‑end tests covering authentication → registration → payment → timetable → email flow.

## Open Items / To‑Do
- Walk through the staff invitation and email-delivery code with Felicia before continuing email work; she wants to understand each part first.
- Define detailed authentication flow.
- Extend and verify role-based access control for all actions, including account management, course management, and invitation issuance.
- Create course management UI.
- Decide secure first-Administrator bootstrap credential and staff-account provisioning/recovery flow.
- Remove public Chief Examiner registration; staff access page should offer sign-in only, with Administrator account setup handled through the approved secure process.
- Run and review the staff invitation issuance flow after configuring mail delivery; current Gmail SMTP remains disabled until the project App Password is set in local `.env`.
- Run and review the new `staff_invitations` migration in the local development database.
- Add staff login rate limiting and verified-email password reset after Felicia reviews the proposed logic.
- Add distinct dashboards and role-protected routes for Student, Chief Examiner, and Administrator.
- Implement verified-email password reset and define email verification/delivery behavior.
- Build Chief Examiner course management for supplementary courses, fees, dates/deadlines, and timetables.
- Build Administrator account management, role assignment, disable/removal policy, and password-reset support.
