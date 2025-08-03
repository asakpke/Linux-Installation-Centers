### **1. Core System Architecture**
**Tech Stack:**
- **Frontend**: React.js (Web) + React Native (Mobile)
- **Backend**: Node.js/Express or Django
- **Database**: PostgreSQL (relational) + Firebase (realtime features)
- **Maps/Location**: Google Maps API or Mapbox
- **Auth**: Firebase Auth (Email/OAuth)
- **Payments**: Stripe/PayPal + JazzCash for Pakistan

---

### **2. AI Development Instructions**

#### **A. User Flow Implementation**
1. **User Registration Module**
   - Implement JWT-based auth with 3 roles: 
     - `User` (requesters)
     - `Expert` (installers)
     - `Admin`
   - Include hardware spec capture form (CPU/RAM/GPU dropdowns)

2. **Request Matching Engine**
   ```python
   def match_experts(user_request):
       # Geospatial query (50km radius)
       experts = Expert.objects.filter(
           location__near=user_request.location,
           distros__contains=user_request.preferred_distros,
           availability=True
       ).order_by('-rating')
       return experts
   ```

3. **Distro Recommendation AI**
   - Train a lightweight ML model (TensorFlow.js) using:
     ```json
     // Training data example
     {
       "ram": 8,
       "use_case": "gaming",
       "experience": "beginner",
       "recommended_distro": "Pop!_OS"
     }
     ```

#### **B. Key Features to Implement**
1. **Real-time Booking System**
   - WebSocket-based calendar integration (use `socket.io`)
   - Conflict detection for expert availability

2. **Payment Gateway**
   - Commission handling (10% auto-deduction logic):
     ```javascript
     const finalPayout = amount * 0.9; // 10% platform fee
     ```

3. **Reputation System**
   - Implement Elo-like rating algorithm:
     ```python
     def update_ratings(expert, user_rating):
         expected = 1 / (1 + 10**((user_rating - expert.rating)/400))
         expert.rating += 32 * (score - expected) 
         expert.save()
     ```

#### **C. Admin Dashboard**
1. **Fraud Prevention**
   - Computer vision model to verify expert IDs (PyTorch)
   - Transaction anomaly detection (Isolation Forest)

2. **Analytics**
   - Visualization of:
     - Top requested distros (Pie chart)
     - Geographic demand heatmap

---

### **3. DevOps Setup**
1. **CI/CD Pipeline**
   - GitHub Actions config:
     ```yaml
     jobs:
       deploy:
         runs-on: ubuntu-latest
         steps:
           - uses: actions/checkout@v3
           - run: docker-compose up --build -d
     ```

2. **Monitoring**
   - Prometheus + Grafana dashboard tracking:
     - Matching success rate
     - Average installation time

---

### **4. Compliance Requirements**
1. **Data Protection**
   - GDPR/PDPA compliance:
     - Auto-delete user data after 180 days of inactivity
     - End-to-end encryption for messages

2. **Pakistan-Specific**
   - Integrate FBR's tax calculator API for commission reporting
   - Urdu localization (RTL layout support)

---

### **5. Testing Protocol**
1. **Automated Tests**
   - Jest/Pytest for:
     - Matching algorithm accuracy
     - Payment failure edge cases

2. **Real-World Testing**
   - Deploy beta in 3 cities:
     - Islamabad (Pakistan)
     - Berlin (Germany) 
     - Austin (USA)

---

### **6. Launch Checklist**
1. **Phase 1 (MVP)**
   - Basic request/offer matching
   - In-person payment only

2. **Phase 2**
   - Remote support (Zoom integration)
   - Hardware compatibility scanner

3. **Phase 3**
   - AI troubleshooting bot (Fine-tune Llama 3)
   - Distro performance benchmarking

---

**AI Agent Note**: Prioritize modular development - start with core matching system, then incrementally add ML features. Use feature flags for gradual rollout. Monitor GPU usage when training distro recommendation models.