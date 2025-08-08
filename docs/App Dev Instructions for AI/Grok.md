# Instructions for Developing the Linux Installation Centers (Linux ICs) Web and Mobile App

## Overview
**Linux ICs** is a platform that connects new users seeking software installation (e.g., Linux distros, productivity tools) with local experts offering free or paid offline installation services. The web and mobile app will facilitate location-based matching, distro/software selection, expert-user communication, and payment processing, with a focus on beginner-friendly guidance and offline usability. The app must comply with Pakistan’s regulatory requirements, including **National Tax Number (NTN)** for tax filings and **Sales Tax Registration (STRN)** if annual turnover exceeds PKR 8 million. The development process prioritizes scalability, security, and user experience, targeting deployment within 6 months for an MVP.

## Objectives
- Build a responsive web app using React and Tailwind CSS, with a Node.js backend and MongoDB database.
- Develop a cross-platform mobile app using Flutter for iOS and Android, with offline capabilities.
- Implement core features: user/expert registration, request submission, location-based matching, messaging, payment processing, and rating system.
- Ensure compliance with Pakistan’s tax laws (NTN, STRN) and anti-money laundering regulations.
- Create a scalable, secure platform to support expansion to multiple cities and software types.

## Technical Requirements
### Web App
- **Frontend**: React (18.2.0) with Tailwind CSS for responsive UI, hosted via CDN (cdn.jsdelivr.net).
- **Backend**: Node.js (18.x) with Express for API, MongoDB for database.
- **Hosting**: AWS or DigitalOcean with CDN (e.g., Cloudflare) for scalability.
- **APIs**:
  - Location: OpenStreetMap for geolocation-based expert matching.
  - Payment: Stripe for commissions (5–10% per paid transaction), integrated with Pakistan-compliant gateways (e.g., EasyPaisa, JazzCash).
  - Messaging: WebSocket or Firebase for real-time chat.
- **Security**: HTTPS, JWT for authentication, bcrypt for password hashing, MongoDB encryption for sensitive data (e.g., user locations).

### Mobile App
- **Framework**: Flutter (3.x) for cross-platform iOS/Android development.
- **Offline Capabilities**: Cache guides, user profiles, and request forms using Hive or SQLite for offline access.
- **Push Notifications**: Firebase Cloud Messaging (FCM) for request and message alerts.
- **Integration**: Reuse web app APIs for consistency.

### Compliance
- **Taxation**: Integrate transaction tracking for NTN compliance and sales tax (STRN) if turnover exceeds PKR 8 million annually.
- **Anti-Money Laundering**: Log all transactions (commissions, donations) with audit trails to comply with the **Anti-Money Laundering Act, 2010**.
- **Data Privacy**: Adhere to Pakistan’s **Electronic Transactions Ordinance, 2002**, encrypting user data and obtaining consent for location sharing.

## Development Instructions
### Phase 1: Planning and Setup (1 Month)
1. **Define Project Structure**:
   - Create a monorepo with separate folders for web (`/web`) and mobile (`/mobile`).
   - Initialize Git repository (GitHub/GitLab) for version control.
   - Set up CI/CD pipeline using GitHub Actions or Jenkins for automated testing and deployment.
2. **Environment Setup**:
   - **Web**: Install Node.js (18.x), npm, and MongoDB. Use `create-react-app` for frontend setup and Express for backend.
   - **Mobile**: Install Flutter SDK, Dart, and Android Studio for emulator testing.
   - Configure ESLint, Prettier, and Husky for code quality.
3. **Database Schema**:
   - **Users**: `{ userId, name, email, password (hashed), location (city, coordinates), hardwareSpecs, usageNeeds, role (user/expert), rating }`.
   - **Requests**: `{ requestId, userId, location, hardwareSpecs, usageNeeds, status (open, accepted, completed), expertId, createdAt }`.
   - **Offers**: `{ offerId, requestId, expertId, price (0 for free), timeSlot, status (pending, accepted, rejected) }`.
   - **Messages**: `{ messageId, senderId, receiverId, content, timestamp }`.
   - **Transactions**: `{ transactionId, offerId, amount, commission (5–10%), status, timestamp }`.
   - Use MongoDB for flexible schema and scalability.
4. **API Design**:
   - **POST /api/users/register**: Register user/expert (email, password, location, role).
   - **POST /api/requests**: Submit installation request (location, hardwareSpecs, usageNeeds).
   - **GET /api/requests?location=city**: Fetch requests by city for experts.
   - **POST /api/offers**: Submit offer (price, timeSlot).
   - **POST /api/messages**: Send message between user and expert.
   - **POST /api/transactions**: Process payment and commission.
   - **GET /api/ratings**: Fetch user/expert ratings.

### Phase 2: Web App Development (2–3 Months)
1. **Frontend (React, Tailwind CSS)**:
   - **Homepage**: Showcase Linux ICs’s value (e.g., “Connect with local experts for software installation”). Include CTA for request submission and expert signup.
   - **User Dashboard**: Display active requests, offers, and messages. Allow users to submit requests with location, hardware specs, and usage needs.
   - **Expert Dashboard**: List nearby requests, allow offer submission (free/paid, time slot), and show earnings (commissions tracked).
   - **Request Form**: Input fields for city, hardware specs (CPU, RAM, storage), and usage needs (e.g., gaming, office).
   - **Messaging**: Real-time chat interface using WebSocket or Firebase.
   - **Payment Page**: Integrate Stripe for secure payments, supporting EasyPaisa/JazzCash for Pakistan.
   - Use Tailwind CSS for responsive design (mobile-first, breakpoints for tablet/desktop).
2. **Backend (Node.js, Express, MongoDB)**:
   - Implement REST API endpoints (see API Design).
   - Use JWT for user authentication, ensuring secure access to dashboards.
   - Integrate OpenStreetMap for location-based matching (filter experts within 50km radius).
   - Set up MongoDB with indexes for fast queries (e.g., location, requestId).
   - Implement commission calculation (5–10% of paid transactions) and store in Transactions collection.
3. **Testing**:
   - Unit tests: Jest for React, Mocha/Chai for Node.js.
   - Integration tests: Test API endpoints with Postman or Supertest.
   - UI tests: Cypress for end-to-end testing.
4. **Deployment**:
   - Deploy frontend to Netlify or Vercel (CDN-hosted).
   - Deploy backend to AWS EC2 or DigitalOcean Droplet with PM2 for process management.
   - Use MongoDB Atlas for managed database hosting.
   - Configure Cloudflare for CDN and DDoS protection.

### Phase 3: Mobile App Development (2–3 Months)
1. **Flutter Setup**:
   - Initialize Flutter project: `flutter create LIC_mobile`.
   - Configure dependencies: `http` for API calls, `hive` for offline storage, `firebase_messaging` for notifications.
2. **UI Development**:
   - **Homepage**: Mirror web homepage with CTA buttons.
   - **User/Expert Dashboards**: Reuse web layouts, optimized for mobile (Flutter’s `ResponsiveBuilder`).
   - **Request Form**: Form with text fields and dropdowns for location/software selection.
   - **Messaging**: Real-time chat using Firebase.
   - **Offline Mode**: Cache guides (e.g., Linux distro selection) and user data using Hive.
3. **API Integration**:
   - Connect to web app’s REST APIs for request submission, offer management, and payments.
   - Use `http` package for API calls, with retry logic for unstable connections.
4. **Push Notifications**:
   - Implement FCM for alerts (e.g., new request, offer accepted).
   - Test notifications on iOS/Android emulators.
5. **Testing**:
   - Unit tests: Dart’s `test` package for Flutter widgets and logic.
   - Device testing: Use Android Studio emulator and iOS simulator (via MacStadium or virtualized macOS on Linux, as Xcode is macOS-only).[](https://www.iphonedevelopers.co.uk/2023/08/developing-ios-apps-on-linux-your.html)
6. **Deployment**:
   - Build Android APK: `flutter build apk --release`.
   - Build iOS IPA: Use MacStadium or virtualized macOS for Xcode build.
   - Publish to Google Play Store and Apple App Store (requires developer accounts: $25 one-time for Google, $99/year for Apple).

### Phase 4: Compliance and Scalability (Ongoing)
1. **Tax Compliance**:
   - Integrate transaction logging to track commissions/donations for NTN filings.
   - Monitor turnover; if >PKR 8 million, register for STRN via FBR’s IRIS portal and implement GST collection (17% or provincial rate).
   - For donations, pursue Section 42 non-profit registration to secure tax exemptions.
2. **Security**:
   - Encrypt sensitive data (e.g., user locations, payment details) in MongoDB.
   - Implement rate limiting and CAPTCHA to prevent abuse.
   - Conduct security audits using tools like OWASP ZAP.
3. **Scalability**:
   - Use AWS Auto Scaling or DigitalOcean’s Kubernetes for traffic spikes.
   - Optimize database queries with MongoDB sharding for multi-city expansion.
   - Localize app (e.g., Urdu, Hindi) using Flutter’s `intl` package and React’s `react-i18next`.

### Key Features Implementation
1. **User Registration**:
   - Form: Email, password, name, city, role (user/expert).
   - Validation: Email format, password strength (min 8 chars, 1 number, 1 special char).
   - Expert-specific: Add fields for expertise (e.g., Linux distros, Windows software) and service type (free/paid).
2. **Request Submission**:
   - Form: City (OpenStreetMap dropdown), hardware specs (text), usage needs (dropdown: gaming, office, development).
   - Store in Requests collection with status: `open`.
3. **Location-Based Matching**:
   - Use OpenStreetMap to geocode user city and filter experts within 50km.
   - Display matching requests on expert dashboard with sorting by distance/date.
4. **Offer System**:
   - Experts submit offers with price (0 for free) and time slot.
   - Users accept/reject offers; update status in Offers collection.
5. **Messaging**:
   - Real-time chat using WebSocket (Node.js `ws` library) or Firebase.
   - Store messages in Messages collection with timestamps.
6. **Payment Processing**:
   - Integrate Stripe for card payments, EasyPaisa/JazzCash for local options.
   - Calculate 5–10% commission, store in Transactions collection.
   - Support donations via separate button (Stripe’s donation feature).
7. **Rating System**:
   - After installation, users rate experts (1–5 stars) and vice versa.
   - Store in Users collection, display average rating on profiles.
8. **Distro/Software Guides**:
   - Create markdown guides for 5 beginner-friendly Linux distros (Ubuntu, Linux Mint, Zorin OS, Pop!_OS, Fedora) and common software (e.g., LibreOffice).
   - Store guides in MongoDB or as static assets, cached offline in mobile app.

### Development Timeline
- **Month 1**: Planning, environment setup, database schema, API design.
- **Months 2–3**: Web app frontend/backend, testing, deployment.
- **Months 4–5**: Mobile app UI, API integration, offline mode, testing.
- **Month 6**: Compliance setup, security audits, beta launch in 5 cities (e.g., Lahore, Karachi, Islamabad, Faisalabad, Rawalpindi).

### Budget
- **Development**: $10,000–$15,000 (freelance developer or AI-driven coding).
- **Hosting**: $100/month (AWS/DigitalOcean, MongoDB Atlas, Cloudflare).
- **Licenses**: $25 (Google Play), $99/year (Apple App Store).
- **Compliance**: PKR 15,000–30,000 (SECP registration), PKR 1,000–2,000 (DSC), free (NTN, STRN if applicable).
- **Marketing**: $5,000 for initial campaigns (X, Reddit, YouTube).

### Testing and Quality Assurance
- **Unit Tests**: Cover 80% of codebase (React, Node.js, Flutter).
- **Integration Tests**: Verify API endpoints and payment flows.
- **User Testing**: Beta test with 100 users and 50 experts in 5 cities, collecting feedback via surveys.
- **Security Tests**: Use OWASP ZAP to identify vulnerabilities.

### Deployment and Launch
- **Web**: Deploy to Netlify/Vercel (frontend) and AWS/DigitalOcean (backend).
- **Mobile**: Publish to Google Play Store and Apple App Store.
- **Beta Launch**: Target 5 Pakistani cities, recruit experts via Reddit (r/Linux4Noobs), X, and Linux User Groups.
- **Post-Launch**: Monitor turnover for STRN compliance (PKR 8 million threshold), pursue PSEB registration for tax exemptions.

### Success Metrics
- **MVP Launch**: 100 users, 50 experts, 200 requests within 6 months.
- **Revenue**: $5,000/year 1 (commissions, donations), $50,000/year 2.
- **User Satisfaction**: 4+ star average rating for experts.
- **Compliance**: NTN obtained, STRN registered if turnover > PKR 8 million.

## AI-Specific Instructions
1. **Code Generation**:
   - Use TypeScript for React/Node.js to ensure type safety.
   - Generate Flutter widgets with responsive layouts using `ResponsiveBuilder`.
   - Follow REST API best practices (e.g., HATEOAS, proper status codes).
2. **Error Handling**:
   - Implement retry logic for API calls and payment processing.
   - Handle offline scenarios with graceful degradation (e.g., cached guides).
3. **Optimization**:
   - Minify React/Flutter assets for faster load times.
   - Use MongoDB indexes for location-based queries.
4. **Documentation**:
   - Generate API docs using Swagger/OpenAPI.
   - Create user guides for registration, request submission, and expert onboarding.
5. **Monitoring**:
   - Integrate logging (Winston for Node.js, Firebase Analytics for Flutter).
   - Monitor uptime and performance with New Relic or AWS CloudWatch.

## References
- Flutter for cross-platform mobile apps:,,[](https://www.spaceotechnologies.com/blog/ios-development-on-linux/)[](https://www.iphonedevelopers.co.uk/2023/08/developing-ios-apps-on-linux-your.html)[](https://www.tecmint.com/create-android-ios-apps-using-livecode-linux/)
- React and Tauri for Linux app development:[](https://medium.com/better-programming/pinephone-app-development-a-quick-start-guide-97f2d90a334c)
- Pakistan tax compliance: FBR IRIS portal, **Sales Tax Act, 1990**, **Income Tax Ordinance, 2001**