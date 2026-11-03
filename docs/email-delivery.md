# Email delivery setup

The portal sends staff invitations through Laravel's configured mail transport. The invitation email contains the invited role, one-time code, acceptance link, and expiry. The database stores only a SHA-256 hash of the code.

## Gmail SMTP for development

Creating a Gmail account does not create an App Password. If Google offers App Passwords for the project account, enable 2-Step Verification first, then create an App Password from the account's Security settings. Some managed or specially protected accounts may not offer this option.

Add these values to the local `.env` file, using the dedicated project Gmail address and its App Password:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=project-sender@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=project-sender@gmail.com
MAIL_FROM_NAME="Campus Resit Portal"
```

Replace the example address and password with the project's actual values. Do not commit `.env`, share the App Password, or put it in source code. The repository's `.env.example` contains no real credentials.

Leave `MAIL_SCHEME` unset (or `null`) for port 587. Laravel selects regular SMTP for that port, and the SMTP connection negotiates STARTTLS; `tls` is not a valid Laravel mail scheme here.

After changing mail settings, clear Laravel's cached configuration if the application has cached it, then try an invitation addressed to an inbox you control. A successful SMTP send confirms that the transport accepted the message; checking that inbox (and spam folder) confirms delivery to that mailbox. A valid-looking email address alone does not prove that its owner can receive mail.

## Invitation links and deployment

The email link is built from `APP_URL`. On a local XAMPP server, it points to the local machine and generally cannot be opened by a recipient on another device or network. For invitations to work remotely, deploy the portal at a reachable HTTPS address and set `APP_URL` to that address. Gmail sends the message; it does not host or expose the Laravel application.

## Later production setup

Use an institution-approved mail provider and verified sender identity for production. Student email verification and password-reset messages are separate flows and still need implementation; sending an invitation does not verify student email addresses.
