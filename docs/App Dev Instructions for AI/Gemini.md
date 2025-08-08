### **Project Directive: Development of "Linux Installation Centers (Linux ICs)" Platform**

**To:** Agentic AI Development System
**From:** Project Lead
**Date:** July 25, 2025
**Subject:** Full-stack development of a hyper-local, community-based tech support marketplace.

#### **1. Mission & Core Principles**

Your primary objective is to build **Linux ICs**, a platform that connects new users seeking help installing Linux with vetted, local experts. Adhere to these core principles:

* **Trust First:** Every feature must be designed to build and maintain trust between users and experts. Security and safety are non-negotiable.
* **Community Focused:** The platform must support both paid professionals and unpaid volunteers. The sense of community is a key asset.
* **Simplicity:** The user journey for both new users and experts must be intuitive, clear, and frictionless.
* **Practicality:** The solution must be tailored to the initial target market (Pakistan), considering local payment methods, languages, and internet conditions.

#### **2. Persona Definitions**

Design the user experience around these primary personas:

* **Naveed (The New User):** A university student in Attock. Tech-curious but intimidated by the Linux installation process. His main concerns are data loss, choosing the right distribution, and cost. He needs guidance and reassurance.
* **Erum (The Expert):** An IT professional in Islamabad. She is passionate about open-source, wants to help the community, and is open to earning side income. Her reputation is important, so she needs a platform to showcase her skills, reviews, and reliability.
* **Admin (Platform Owner):** Needs a comprehensive dashboard to manage users, verify experts, mediate disputes, monitor transactions, and ensure the health of the platform.

#### **3. Core Epics & User Stories**

Develop the platform based on the following feature sets (epics) and user stories.

**EPIC 1: Onboarding & Profiles**
* **User Story:** As Naveed, I want to sign up quickly using my phone number and email so I can post a request for help.
* **User Story:** As Erum, I want to create a detailed expert profile listing my location (city), bio, skills (e.g., Ubuntu, Fedora, Arch), and photos of my workspace to build credibility.
* **User Story:** As Erum, I want to define my service tiers (e.g., "Free Volunteer Service - Weekends Only" or "Paid Professional Service - 3,000 PKR") so users know my terms upfront.
* **User Story:** As Erum, I want to submit my CNIC (ID card) and have my profile marked as "ID Verified" to increase trust and unlock paid transaction capabilities.

**EPIC 2: Service Request & Matching**
* **User Story:** As Naveed, I want to create a service request detailing my device (e.g., "HP Pavilion 15"), my needs ("web development, some light gaming"), and my location (Attock).
* **User Story:** As Erum, I want to see a feed of new requests filtered by my location (e.g., within a 50km radius of Islamabad) so I can find relevant jobs.
* **User Story:** As Erum, I want to submit a clear offer to a request, stating my proposed price (or confirming it's free), what I will do, and my available time slots.
* **User Story:** As Naveed, I want to receive multiple offers and compare expert profiles, reviews, and prices before accepting one.

**EPIC 3: Job Management & Communication**
* **User Story:** As Naveed, once I accept an offer, I want a secure, in-app chat to communicate with Erum to coordinate the device drop-off.
* **User Story:** As both Naveed and Erum, I want to track the job status through a simple workflow (e.g., `Awaiting Drop-off` -> `Service in Progress` -> `Ready for Pickup` -> `Completed`).

**EPIC 4: Payments & Reviews**
* **User Story:** As Naveed, I want to pay for the service securely through the platform using local payment methods like **Easypaisa** or **JazzCash**.
* **User Story:** As the system, I need to hold the payment in escrow and release it to Erum only after Naveed confirms the job is complete.
* **User Story:** As the system, I need to automatically deduct a 15% commission from the paid transaction before payout to the expert.
* **User Story:** As both Naveed and Erum, after the job is complete, I want to leave a rating (1-5 stars) and a written review for the other party to build a reputation system.

**EPIC 5: Admin Dashboard**
* **User Story:** As an Admin, I need a secure dashboard to view and manage all users.
* **User Story:** As an Admin, I need a queue to review and approve/reject expert "ID Verified" applications.
* **User Story:** As an Admin, I need a tool to mediate disputes, view chat histories (with user consent), and process refunds if necessary.

#### **4. Technical Stack & Architecture**

* **Phase 1 (MVP - Web First):**
    * **Frontend:** Next.js (React framework) for a fast, server-rendered web application.
    * **UI Library:** Tailwind CSS for rapid, modern UI development.
* **Phase 2 (Mobile App):**
    * **Mobile:** React Native to build cross-platform (iOS and Android) apps from a single codebase.
* **Backend (for all phases):**
    * **Framework:** Node.js with Express.js for its speed and real-time capabilities (for chat).
    * **Database:** PostgreSQL for its robustness and powerful geospatial features (using PostGIS extension for location queries).
* **Cloud & Deployment:**
    * **Hosting:** Deploy on AWS (EC2/RDS) or Google Cloud Platform. Use a region geographically close to Pakistan for low latency.
    * **DevOps:** Containerize the application using Docker and set up a CI/CD pipeline (e.g., GitHub Actions) for automated testing and deployment.
* **Key APIs:**
    * **Payments:** Integrate with a local Pakistani payment gateway API (e.g., Easypaisa).
    * **Authentication:** Implement OTP-based phone verification using a local SMS gateway provider (e.g., Twilio might be expensive, research local alternatives like EOcean).
    * **Maps/Location:** Google Maps API for location input and distance calculation.

#### **5. Development Roadmap**

Execute development in the following phases:

* **Phase 1: MVP Launch (Target: 3-4 Months)**
    * Focus exclusively on a **web application**.
    * Implement Epics 1-5 with core functionality only.
    * Expert verification will be a manual process for the Admin.
    * Launch marketing targeted only at the **Rawalpindi/Islamabad** region.
    * **Goal:** Prove the core concept and build an initial user base.

* **Phase 2: Mobile App & Scaling (Target: Months 5-9)**
    * Develop the **React Native mobile app** for iOS and Android.
    * Introduce features like push notifications for new offers and messages.
    * Enhance location features with live map views of request areas.
    * Begin expansion to **Lahore and Karachi**.

* **Phase 3: Platform Maturity (Target: Month 10+)**
    * Develop advanced features: subscription tiers for experts ("Pro" profiles), a built-in knowledge base with Linux tutorials, and remote desktop support capabilities.
    * Implement a more sophisticated analytics dashboard for the Admin.

#### **6. Final Output**

The final deliverable for each phase is a fully deployed, functional, and secure application with a complete source code repository and documentation. Begin with Phase 1. Await further instruction before proceeding to subsequent phases.