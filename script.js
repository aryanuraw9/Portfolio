/* ======== AOS ANIMATION INITIALIZATION ======== */
AOS.init({
    duration: 1000, // Animation duration in ms
    once: true,     // Whether animation should happen only once
    offset: 50,     // Offset (in px) from the original trigger point
});

/* ======== EMAILJS INITIALIZATION ======== */
(function () {
    if (typeof emailjs !== 'undefined') {
        emailjs.init("unXcyDrFoTYPVa3JN");
    } else {
        console.error("EmailJS library not loaded");
    }
})();

/* ======== MOBILE NAVIGATION MENU ======== */
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

// Show Menu
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

// Hide Menu
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

// Hide Menu when a link is clicked
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
});

/* ======== ALL DOM CONTENT LOADED FUNCTIONS ======== */
document.addEventListener('DOMContentLoaded', () => {

    /* --- HERO TYPING ANIMATION --- */
    const roles = ["Frontend Developer", "UI/UX Designer", "Web Developer", "Creative Coder", "Video Editor"];
    let roleIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const typingElement = document.querySelector('.typing-text');
    const typeSpeed = 100;
    const deleteSpeed = 50;
    const delayBetweenRoles = 2000;

    function type() {
        const currentRole = roles[roleIndex];
        
        if (isDeleting) {
            // Deleting text
            typingElement.textContent = currentRole.substring(0, charIndex - 1);
            charIndex--;
        } else {
            // Typing text
            typingElement.textContent = currentRole.substring(0, charIndex + 1);
            charIndex++;
        }

        // Check if word is fully typed or deleted
        if (!isDeleting && charIndex === currentRole.length) {
            // Pause, then start deleting
            setTimeout(() => isDeleting = true, delayBetweenRoles);
        } else if (isDeleting && charIndex === 0) {
            // Move to the next role
            isDeleting = false;
            roleIndex = (roleIndex + 1) % roles.length;
        }

        // Set typing speed
        const speed = isDeleting ? deleteSpeed : typeSpeed;
        setTimeout(type, speed);
    }

    // Start the typing animation
    if (typingElement) {
        setTimeout(type, 500); // Start typing after a short delay
    }

    /* --- 3D HERO TILT EFFECT (NEW) --- */
    const homeSection = document.querySelector('.home');
    const homeContent = document.querySelector('.home-content');

    // Only run on non-touch devices (desktops)
    if (homeSection && homeContent && !('ontouchstart' in window)) {
        
        homeSection.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const { offsetWidth, offsetHeight } = homeSection;

            // Get center of the home section
            const centerX = offsetWidth / 2;
            const centerY = offsetHeight / 2;

            // Calculate deviation from center (relative to section)
            // We use e.pageX/Y and section.offsetLeft/Top to get position relative to the element
            const rect = homeSection.getBoundingClientRect();
            const x = clientX - rect.left;
            const y = clientY - rect.top;

            const percentX = (x - centerX) / centerX;
            const percentY = (y - centerY) / centerY;

            // Define max tilt (e.g., 8 degrees)
            const maxTilt = 8;
            const rotateY = percentX * maxTilt;
            const rotateX = percentY * maxTilt * -1; // Invert X rotation for natural feel

            // Apply the 3D transform (maintaining the translateZ)
            homeContent.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        homeSection.addEventListener('mouseleave', () => {
            // Reset tilt when mouse leaves the section
            homeContent.style.transform = 'rotateX(0deg) rotateY(0deg)';
        });
    }
});


/* ======== ADD SHADOW TO HEADER ON SCROLL ======== */
const header = document.querySelector('.header');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('header-scrolled');
    } else {
        header.classList.remove('header-scrolled');
    }
});

/* ======== SHOW SCROLL-TO-TOP BUTTON ======== */
const scrollUp = document.getElementById('scroll-up');
window.addEventListener('scroll', () => {
    if (window.scrollY >= 350) {
        scrollUp.classList.add('show-scroll');
    } else {
        scrollUp.classList.remove('show-scroll');
    }
});

/* ======== ACTIVE LINK HIGHLIGHT ON SCROLL ======== */
const sections = document.querySelectorAll('section[id]');

window.addEventListener('scroll', () => {
    const scrollY = window.pageYOffset;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 58; // 58px = header height + 10px
        const sectionId = current.getAttribute('id');

        const navLink = document.querySelector('.nav-link[href*=' + sectionId + ']');
        if (navLink) {
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                navLink.classList.add('active-link');
            } else {
                navLink.classList.remove('active-link');
            }
        }
    });
});

/* ======== CONTACT FORM (PREVENT DEFAULT) ======== */
const contactForm = document.getElementById('contactForm');

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault(); 
        
        if (typeof emailjs === 'undefined') {
            alert("Email service is not available. Please try again later.");
            return;
        }
        
        emailjs.send("service_wgqprg9", "template_2w03ial", {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            message: document.getElementById("message").value,
        })
        .then(function (response) {
            console.log("Email sent successfully:", response);
            // Show success modal
            document.getElementById('successModal').style.display = 'block';
            contactForm.reset();
        }, function (error) {
            console.error("EmailJS error:", error);
            alert("Failed to send message. Try again. Check console for details.");
        });
    });
}

/* ======== MODAL FUNCTIONALITY ======== */
const modal = document.getElementById('successModal');
const closeBtn = document.querySelector('.close');

if (closeBtn) {
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
