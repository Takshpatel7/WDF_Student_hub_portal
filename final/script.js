/* ==========================================================================
   STUDENT HUB PORTAL - SIMPLE DYNAMIC UI & FORM VALIDATION JAVASCRIPT
   ========================================================================== */

// 1. LIGHT / DARK THEME SWITCHER
function toggleTheme() {
  document.body.classList.toggle('light-theme');
  const isLight = document.body.classList.contains('light-theme');
  localStorage.setItem('theme', isLight ? 'light' : 'dark');
}

// Load saved theme on startup
if (localStorage.getItem('theme') === 'light') {
  document.body.classList.add('light-theme');
}

// 2. HAMBURGER MENU (MOBILE NAVIGATION)
function toggleMenu() {
  const menu = document.getElementById('nav-menu');
  if (menu) {
    menu.classList.toggle('show');
  }
}

// 3. NOTIFICATION BANNER
function closeBanner() {
  const banner = document.getElementById('notification-banner');
  if (banner) {
    banner.style.display = 'none';
  }
}

// 4. COLLAPSIBLE FAQ ACCORDION
function toggleFaq(btn) {
  const answer = btn.nextElementSibling;
  const icon = btn.querySelector('.faq-icon');
  
  if (answer.style.display === 'block') {
    answer.style.display = 'none';
    if (icon) icon.textContent = '+';
  } else {
    answer.style.display = 'block';
    if (icon) icon.textContent = '−';
  }
}

// Live Search Filter for FAQs
function filterFaqs(query) {
  const searchTerm = (query || '').toLowerCase().trim();
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
  });
}

// 5. MODAL POPUP (OPEN / CLOSE)
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'flex';
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'none';
  }
}

// Close modal if user clicks outside the modal box
window.onclick = function(event) {
  if (event.target.classList.contains('modal')) {
    event.target.style.display = 'none';
  }
};

// 6. IMAGE / CONTENT SLIDER
let currentSlide = 0;

function showSlide(index) {
  const slides = document.querySelectorAll('.slide');
  if (slides.length === 0) return;

  if (index >= slides.length) {
    currentSlide = 0;
  } else if (index < 0) {
    currentSlide = slides.length - 1;
  } else {
    currentSlide = index;
  }

  slides.forEach((slide, i) => {
    slide.style.display = (i === currentSlide) ? 'block' : 'none';
  });
}

function changeSlide(step) {
  showSlide(currentSlide + step);
}

// Initialize first slide on page load
document.addEventListener('DOMContentLoaded', () => {
  showSlide(0);
});


// 7. STUDENT REGISTRATION FORM VALIDATION (PRACTICAL 5)
function setError(id, message) {
  const errorElem = document.getElementById(id + 'Error');
  const inputElem = document.getElementById(id);
  if (errorElem) errorElem.textContent = message;
  if (inputElem) {
    inputElem.classList.add('input-error');
    inputElem.classList.remove('input-success');
  }
}

function setSuccess(id) {
  const errorElem = document.getElementById(id + 'Error');
  const inputElem = document.getElementById(id);
  if (errorElem) errorElem.textContent = '';
  if (inputElem) {
    inputElem.classList.remove('input-error');
    inputElem.classList.add('input-success');
  }
}

// Real-time validation helpers
function checkNameRealtime() {
  const elem = document.getElementById('name');
  if (!elem) return false;
  const name = elem.value.trim();
  const nameRegex = /^[A-Za-z\s]{3,30}$/;
  if (!nameRegex.test(name)) {
    setError('name', 'Name must be 3-30 letters without numbers or symbols');
    return false;
  }
  setSuccess('name');
  return true;
}

function checkEmailRealtime() {
  const elem = document.getElementById('email');
  if (!elem) return false;
  const email = elem.value.trim();
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (!emailRegex.test(email)) {
    setError('email', 'Please enter a valid email address (e.g. user@domain.com)');
    return false;
  }
  setSuccess('email');
  return true;
}

function checkMobileRealtime() {
  const elem = document.getElementById('mobile');
  if (!elem) return false;
  const mobile = elem.value.trim();
  const mobileRegex = /^[6-9]\d{9}$/;
  if (!mobileRegex.test(mobile)) {
    setError('mobile', 'Enter a valid 10-digit mobile number starting with 6-9');
    return false;
  }
  setSuccess('mobile');
  return true;
}

function checkCourseRealtime() {
  const elem = document.getElementById('course');
  if (!elem) return false;
  if (!elem.value) {
    setError('course', 'Please select your academic course');
    return false;
  }
  setSuccess('course');
  return true;
}

function checkYearRealtime() {
  const elem = document.getElementById('year');
  if (!elem) return false;
  const year = elem.value.trim();
  const yearRegex = /^[1-4]$/;
  if (!yearRegex.test(year)) {
    setError('year', 'Enter a valid academic year (1 to 4)');
    return false;
  }
  setSuccess('year');
  return true;
}

function checkGenderRealtime() {
  const checked = document.querySelector('input[name="gender"]:checked');
  const genderError = document.getElementById('genderError');
  if (!checked) {
    if (genderError) genderError.textContent = 'Please select your gender';
    return false;
  }
  if (genderError) genderError.textContent = '';
  return true;
}

// Password Strength Meter & Realtime Checker
function updatePasswordStrength() {
  const elem = document.getElementById('password');
  const bar = document.getElementById('passwordMeterBar');
  const text = document.getElementById('passwordMeterText');
  if (!elem) return false;

  const val = elem.value;
  if (!bar || !text) return;

  if (val.length === 0) {
    bar.className = 'meter-fill';
    bar.style.width = '0%';
    text.textContent = 'Enter password';
    text.style.color = '#94A3B8';
    return false;
  }

  let score = 0;
  if (val.length >= 6) score++;
  if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
  if (/\d/.test(val)) score++;
  if (/[@$!%*#?&]/.test(val)) score++;

  if (score <= 1) {
    bar.className = 'meter-fill strength-weak';
    text.textContent = 'Weak (Needs min 6 chars & numbers)';
    text.style.color = '#EF4444';
    setError('password', 'Password must have at least 6 characters (letters & numbers)');
    return false;
  } else if (score === 2 || score === 3) {
    bar.className = 'meter-fill strength-medium';
    text.textContent = 'Medium (Good combination)';
    text.style.color = '#F59E0B';
    setSuccess('password');
    return true;
  } else {
    bar.className = 'meter-fill strength-strong';
    text.textContent = 'Strong (Excellent security!)';
    text.style.color = '#10B981';
    setSuccess('password');
    return true;
  }
}

function checkConfirmPasswordRealtime() {
  const passElem = document.getElementById('password');
  const confirmElem = document.getElementById('confirmPassword');
  if (!confirmElem || !passElem) return false;

  if (confirmElem.value === '' || confirmElem.value !== passElem.value) {
    setError('confirmPassword', 'Passwords do not match');
    return false;
  }
  setSuccess('confirmPassword');
  return true;
}

function checkTermsRealtime() {
  const terms = document.getElementById('terms');
  const termsError = document.getElementById('termsError');
  if (!terms || !terms.checked) {
    if (termsError) termsError.textContent = 'You must accept the Terms & Conditions';
    return false;
  }
  if (termsError) termsError.textContent = '';
  return true;
}

function validateRegistration(event) {
  let isV1 = checkNameRealtime();
  let isV2 = checkEmailRealtime();
  let isV3 = checkMobileRealtime();
  let isV4 = checkCourseRealtime();
  let isV5 = checkYearRealtime();
  let isV6 = checkGenderRealtime();
  let isV7 = updatePasswordStrength();
  let isV8 = checkConfirmPasswordRealtime();
  let isV9 = checkTermsRealtime();

  const isFormValid = isV1 && isV2 && isV3 && isV4 && isV5 && isV6 && isV7 && isV8 && isV9;

  if (!isFormValid) {
    if (event) event.preventDefault();
    return false;
  }

  // If page is hosted on HTTP server (e.g. XAMPP localhost or PHP built-in), let POST form submit to process_register.php (Practical 7)
  if (window.location.protocol.startsWith('http')) {
    // Normal form submission continues to process_register.php
    return true;
  }

  // If opened directly from file:// (browser static preview), alert and redirect
  if (event) event.preventDefault();
  alert('🎉 Student Registration Successful! Client-side validation passed. Redirecting to Dashboard...');
  window.location.href = 'dashboard.html';
  return false;
}

// ==========================================================================
// 8. PRACTICAL 6: FETCH API, DYNAMIC RENDERING, SEARCH, FILTER, SORT & PAGINATION
// ==========================================================================
let allEvents = [];
let filteredEvents = [];
let currentPage = 1;
const itemsPerPage = 4;
let activeEventTitle = '';

const fallbackEvents = [
  { "id": 1, "title": "SPOURAL – 2026", "category": "SPORTS", "date": "2026-10-25", "displayDate": "October 25, 2026", "venue": "College Main Ground", "description": "Annual Inter-College Sports & Athletics Championship" },
  { "id": 2, "title": "Cyber Security Workshop", "category": "TECH", "date": "2026-11-10", "displayDate": "November 10, 2026", "venue": "Auditorium B", "description": "Hands-on Ethical Hacking & Network Defense Bootcamp" },
  { "id": 3, "title": "AI & Robotics Workshop", "category": "WORKSHOP", "date": "2026-11-22", "displayDate": "November 22, 2026", "venue": "Computer Lab 3", "description": "Practical Machine Learning, Arduino & Autonomous Bots Session" },
  { "id": 4, "title": "Tarang Cultural Fest", "category": "CULTURAL", "date": "2026-12-05", "displayDate": "December 05, 2026", "venue": "Open Air Amphitheater", "description": "Annual Youth Festival featuring Music, Drama, Dance & Art" },
  { "id": 5, "title": "WDF Web Development Hackathon", "category": "TECH", "date": "2026-12-15", "displayDate": "December 15, 2026", "venue": "IT Lab 2", "description": "24-hour Full-Stack Web App Development Challenge" },
  { "id": 6, "title": "DSA CodeSprint Contest", "category": "ACADEMIC", "date": "2026-12-20", "displayDate": "December 20, 2026", "venue": "Online Portal & Lab 1", "description": "Algorithmic Problem Solving & Competitive Coding Battle" },
  { "id": 7, "title": "Java OOP Project Exhibition", "category": "ACADEMIC", "date": "2026-12-28", "displayDate": "December 28, 2026", "venue": "Seminar Hall A", "description": "Semester 3 Java Object-Oriented Software Projects Showcase" },
  { "id": 8, "title": "Networking & FCN Hands-on Lab", "category": "WORKSHOP", "date": "2027-01-08", "displayDate": "January 08, 2027", "venue": "Cisco Networking Lab", "description": "Practical Packet Tracer, Subnetting & Network Simulation" },
  { "id": 9, "title": "Cloud Computing & DevOps Summit", "category": "TECH", "date": "2027-01-18", "displayDate": "January 18, 2027", "venue": "Conference Hall C", "description": "Docker, Kubernetes, AWS infrastructure deployment and CI/CD pipelines" },
  { "id": 10, "title": "Inter-Department Badminton League", "category": "SPORTS", "date": "2027-01-25", "displayDate": "January 25, 2027", "venue": "Indoor Sports Complex", "description": "Men's and Women's singles and doubles tournament" },
  { "id": 11, "title": "National Technical Paper Presentation", "category": "ACADEMIC", "date": "2027-02-05", "displayDate": "February 05, 2027", "venue": "Auditorium A", "description": "Original research papers across AI, IoT, Blockchain, and Cybersecurity" },
  { "id": 12, "title": "UI/UX Design Sprint Bootcamp", "category": "WORKSHOP", "date": "2027-02-14", "displayDate": "February 14, 2027", "venue": "Design Studio Lab", "description": "Figma design systems, wireframing, and interactive prototyping" },
  { "id": 13, "title": "Battle of the Bands & Music Night", "category": "CULTURAL", "date": "2027-02-22", "displayDate": "February 22, 2027", "venue": "Open Air Amphitheater", "description": "Live rock, acoustic, and fusion performances with inter-college bands" },
  { "id": 14, "title": "Annual Chess Championship", "category": "SPORTS", "date": "2027-03-02", "displayDate": "March 02, 2027", "venue": "Activity Hall 2", "description": "FIDE rapid rules Swiss-system chess tournament" },
  { "id": 15, "title": "Data Science & Python Masterclass", "category": "WORKSHOP", "date": "2027-03-12", "displayDate": "March 12, 2027", "venue": "Computer Center 4", "description": "Pandas, NumPy, Scikit-learn, and predictive analytics" },
  { "id": 16, "title": "Alumni Career Mentorship Conclave", "category": "ACADEMIC", "date": "2027-03-20", "displayDate": "March 20, 2027", "venue": "Main Auditorium", "description": "Industry alumni networking and career roadmap advice" }
];

// Load external JSON data via Fetch API
function loadEventsData() {
  const container = document.getElementById('events-list');
  if (!container) return; // Not on events page

  fetch('events.json')
    .then(response => {
      if (!response.ok) throw new Error('Network error loading events.json');
      return response.json();
    })
    .then(data => {
      allEvents = data;
      applyEventFilters();
    })
    .catch(err => {
      console.warn('Fetch fallback activated (e.g. file:// protocol):', err);
      allEvents = fallbackEvents;
      applyEventFilters();
    });
}

// Combined Search, Filter & Sort Logic
function applyEventFilters() {
  const searchInput = document.getElementById('search');
  const categorySelect = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortOrder');

  const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
  const selectedCategory = categorySelect ? categorySelect.value : 'ALL';
  const sortOrder = sortSelect ? sortSelect.value : 'date-asc';

  // 1. Filter by Search Query & Category
  filteredEvents = allEvents.filter(event => {
    const matchesSearch =
      event.title.toLowerCase().includes(searchTerm) ||
      event.venue.toLowerCase().includes(searchTerm) ||
      event.description.toLowerCase().includes(searchTerm) ||
      event.category.toLowerCase().includes(searchTerm);

    const matchesCategory =
      selectedCategory === 'ALL' || event.category === selectedCategory;

    return matchesSearch && matchesCategory;
  });

  // 2. Sort Events
  filteredEvents.sort((a, b) => {
    if (sortOrder === 'name-asc') {
      return a.title.localeCompare(b.title);
    } else if (sortOrder === 'name-desc') {
      return b.title.localeCompare(a.title);
    } else if (sortOrder === 'date-desc') {
      return new Date(b.date) - new Date(a.date);
    } else {
      // date-asc (default)
      return new Date(a.date) - new Date(b.date);
    }
  });

  // Reset to first page whenever filter changes
  currentPage = 1;
  renderEventsPage();
}

// Dynamic Rendering with Pagination
function renderEventsPage() {
  const container = document.getElementById('events-list');
  const paginationContainer = document.getElementById('pagination-controls');
  if (!container) return;

  // Handle empty state
  if (filteredEvents.length === 0) {
    container.innerHTML = `
      <div class="table1" style="text-align: center; padding: 36px 20px;">
        <h3 style="color: #EC9F05; margin-bottom: 8px; font-size: 20px;">No events found</h3>
        <p style="color: #CBD5E1; font-size: 15px;">No campus events match your search query or selected filter.</p>
        <button onclick="resetEventFilters()" style="margin-top: 14px; padding: 8px 20px;">Reset Filters</button>
      </div>
    `;
    if (paginationContainer) paginationContainer.innerHTML = '';
    return;
  }

  // Calculate slice for current page
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const pageItems = filteredEvents.slice(startIndex, endIndex);

  // Generate HTML for events
  let html = '';
  pageItems.forEach(event => {
    html += `
      <div class="table1 event-card-item">
        <div class="event-badge">${event.category}</div>
        <div style="flex: 1; min-width: 260px;">
          <h3 style="font-size: 22px; color: #EC9F05; margin-bottom: 6px;">${event.title}</h3>
          <p style="font-size: 15px; margin-bottom: 4px;">
            <strong>📅 Date:</strong> ${event.displayDate} &nbsp;|&nbsp; <strong>📍 Venue:</strong> ${event.venue}
          </p>
          <p style="font-size: 14px; color: #CBD5E1;">${event.description}</p>
        </div>
        <div style="flex-shrink: 0;">
          <button onclick="openRegisterModal('${event.title.replace(/'/g, "\\'")}', '${event.displayDate}', '${event.venue}')" style="padding: 10px 24px;">Register</button>
        </div>
      </div>
    `;
  });

  container.innerHTML = html;
  renderPaginationButtons();
}

// Render Pagination Controls
function renderPaginationButtons() {
  const paginationContainer = document.getElementById('pagination-controls');
  if (!paginationContainer) return;

  const totalPages = Math.ceil(filteredEvents.length / itemsPerPage);
  if (totalPages <= 1) {
    paginationContainer.innerHTML = '';
    return;
  }

  let paginationHtml = '';

  // Previous Button
  paginationHtml += `
    <button class="pagination-btn" onclick="changeEventsPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
      &laquo; Prev
    </button>
  `;

  // Page Numbers
  for (let i = 1; i <= totalPages; i++) {
    paginationHtml += `
      <button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="changeEventsPage(${i})">
        ${i}
      </button>
    `;
  }

  // Next Button
  paginationHtml += `
    <button class="pagination-btn" onclick="changeEventsPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
      Next &raquo;
    </button>
  `;

  paginationContainer.innerHTML = paginationHtml;
}

// Change Page Handler
function changeEventsPage(pageNumber) {
  const totalPages = Math.ceil(filteredEvents.length / itemsPerPage);
  if (pageNumber < 1 || pageNumber > totalPages) return;
  currentPage = pageNumber;
  renderEventsPage();
  // Smooth scroll back to top of events list
  const container = document.getElementById('events-toolbar-card');
  if (container) {
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// Reset Search & Filters
function resetEventFilters() {
  const searchInput = document.getElementById('search');
  const categorySelect = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortOrder');

  if (searchInput) searchInput.value = '';
  if (categorySelect) categorySelect.value = 'ALL';
  if (sortSelect) sortSelect.value = 'date-asc';

  applyEventFilters();
}

// Universal Registration Modal Handlers
function openRegisterModal(title, date, venue) {
  activeEventTitle = title;
  const titleEl = document.getElementById('modalEventTitle');
  const detailsEl = document.getElementById('modalEventDetails');

  if (titleEl) titleEl.textContent = 'Register for ' + title;
  if (detailsEl) detailsEl.innerHTML = `<strong>Date:</strong> ${date} &bull; <strong>Venue:</strong> ${venue}<br><br>Confirm your participation to secure your registration slot.`;

  openModal('dynamicEventModal');
}

function confirmEventRegistration() {
  alert('🎉 Successfully registered for ' + (activeEventTitle || 'the event') + '!');
  closeModal('dynamicEventModal');
}

// Automatically load events if on events page
document.addEventListener('DOMContentLoaded', () => {
  loadEventsData();
});

// ==========================================================================
// 9. AUTHENTICATION & ACCESS CONTROL (STUDENT & ADMIN CREDENTIALS)
// ==========================================================================
const PORTAL_CREDENTIALS = {
  student: {
    identifiers: ['student@studenthub.com', '25dcs200', 'john.doe@studenthub.edu'],
    password: 'student123',
    role: 'student',
    name: 'John Doe',
    studentId: '25DCS200',
    course: 'B.Tech CSE - 3rd Sem'
  },
  admin: {
    identifiers: ['admin@studenthub.com', 'admin'],
    password: 'admin123',
    role: 'admin',
    name: 'Portal Administrator',
    studentId: 'ADMIN-01',
    course: 'Faculty / Administration'
  }
};

// Handle Login Form Submission
function handlePortalLogin(event) {
  if (event) event.preventDefault();

  const idInput = document.getElementById('email');
  const passInput = document.getElementById('password');
  const errorMsg = document.getElementById('login-error-msg');

  if (!idInput || !passInput) return;

  const enteredId = idInput.value.trim().toLowerCase();
  const enteredPass = passInput.value.trim();

  // 1. Check Student Credentials
  if (PORTAL_CREDENTIALS.student.identifiers.includes(enteredId) && enteredPass === PORTAL_CREDENTIALS.student.password) {
    sessionStorage.setItem('currentUser', JSON.stringify(PORTAL_CREDENTIALS.student));
    alert('✅ Welcome, John Doe! Logged in as Student.');
    window.location.href = 'dashboard.html';
    return;
  }

  // 2. Check Admin Credentials
  if (PORTAL_CREDENTIALS.admin.identifiers.includes(enteredId) && enteredPass === PORTAL_CREDENTIALS.admin.password) {
    sessionStorage.setItem('currentUser', JSON.stringify(PORTAL_CREDENTIALS.admin));
    alert('🛡️ Welcome, Administrator! Full Dashboard and Admin Access Granted.');
    window.location.href = 'dashboard.html';
    return;
  }

  // 3. Failed Login
  if (errorMsg) {
    errorMsg.textContent = '❌ Invalid Credentials! Use 25DCS200 / student123 or admin@studenthub.com / admin123.';
    errorMsg.style.display = 'block';
  } else {
    alert('❌ Invalid Credentials!\n\n• Student: 25DCS200 / student123\n• Admin: admin@studenthub.com / admin123');
  }
}

// Quick Fill Demo Credentials
function fillCredentials(id, pass) {
  const idInput = document.getElementById('email');
  const passInput = document.getElementById('password');
  const errorMsg = document.getElementById('login-error-msg');

  if (idInput) idInput.value = id;
  if (passInput) passInput.value = pass;
  if (errorMsg) errorMsg.style.display = 'none';
}

// Protect Dashboard (Redirect if not logged in)
function checkDashboardAuth() {
  const sessionUser = sessionStorage.getItem('currentUser');
  if (!sessionUser) {
    alert('🔒 Access Denied! Please login with Student or Admin credentials to view the dashboard.');
    window.location.href = 'login.html';
    return false;
  }

  const user = JSON.parse(sessionUser);

  // If Admin is viewing, show special admin access badge
  const adminBadge = document.getElementById('admin-access-indicator');
  if (adminBadge && user.role === 'admin') {
    adminBadge.style.display = 'block';
  }

  return true;
}

// Logout Handler
function handleLogout() {
  sessionStorage.removeItem('currentUser');
  alert('👋 You have been logged out successfully.');
  window.location.href = 'login.html';
}

// Protect Admin Panel (Only Admin Allowed)
function checkAdminAuth() {
  const sessionUser = sessionStorage.getItem('currentUser');
  if (!sessionUser) {
    alert('🔒 Admin Access Only! Please login with Administrator credentials.');
    window.location.href = 'login.html';
    return false;
  }

  const user = JSON.parse(sessionUser);
  if (user.role !== 'admin') {
    alert('⛔ Access Restricted! Only Administrators can access the Admin Panel.');
    window.location.href = 'dashboard.html';
    return false;
  }

  return true;
}