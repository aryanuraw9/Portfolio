# Aryan Uraw — Premium Portfolio Website
### Acting Managing Director · Sovryx Tech Pvt. Ltd.

---

## 📁 Folder Structure

```
portfolio/
├── index.php                 ← Main portfolio page
├── portfolio.sql             ← Database schema + sample data
├── config/
│   └── database.php          ← DB credentials & site config
├── includes/
│   ├── header.php            ← Reusable header (nav, loader, cursor)
│   ├── footer.php            ← Reusable footer + scripts
│   └── contact_handler.php   ← AJAX contact form backend
├── admin/
│   └── index.php             ← Admin dashboard (projects & messages)
└── assets/
    ├── css/
    │   ├── main.css           ← Full design system
    │   └── animations.css     ← Scroll & page animations
    ├── js/
    │   └── main.js            ← All JS (cursor, typing, particles, etc.)
    └── images/
        └── favicon.svg
```

---

## 🚀 Setup on XAMPP

### Step 1 — Install XAMPP
Download from https://www.apachefriends.org and install.

### Step 2 — Place project files
Copy the `portfolio/` folder into:
```
C:\xampp\htdocs\portfolio\        (Windows)
/Applications/XAMPP/htdocs/portfolio/   (macOS)
```

### Step 3 — Start XAMPP
Open XAMPP Control Panel → Start **Apache** and **MySQL**.

### Step 4 — Create the database
1. Go to `http://localhost/phpmyadmin`
2. Click **Import** tab
3. Choose `portfolio.sql` from the project folder
4. Click **Go**

### Step 5 — Configure credentials (if needed)
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // your MySQL password
define('DB_NAME', 'aryan_portfolio');
define('ADMIN_EMAIL', 'aryan@sovryx.com');
```

### Step 6 — Open the site
Visit: `http://localhost/portfolio/`

### Step 7 — Admin panel
Visit: `http://localhost/portfolio/admin/`
- **Username:** `admin`
- **Password:** `password`
> ⚠️ Change the password immediately in phpMyAdmin → `admin_users` table using PHP's `password_hash()`.

---

## 🎨 Customization

### Change your photo
Replace the `.about__image-placeholder` div in `index.php` with:
```html
<img src="assets/images/your-photo.jpg" alt="Aryan Uraw" class="about__photo">
```
Add to `main.css`:
```css
.about__photo { width:340px; height:400px; object-fit:cover; border-radius:var(--radius-xl); }
```

### Add project images
Place images in `assets/images/` and update the `image` field in the DB via the admin panel or SQL.

### Update social links
Edit `includes/footer.php` — replace `href="#"` with your actual profile URLs.

### Change accent color
In `assets/css/main.css`, update:
```css
--accent:   #6c63ff;   /* primary */
--accent-2: #a855f7;   /* secondary gradient */
```

---

## ✨ Features

| Feature | Status |
|---------|--------|
| Dark / Light mode toggle | ✅ |
| Custom animated cursor | ✅ |
| Particle canvas background | ✅ |
| Typing animation | ✅ |
| Scroll-reveal animations | ✅ |
| Animated skill bars | ✅ |
| Project category filter | ✅ |
| Animated number counters | ✅ |
| Glassmorphism contact form | ✅ |
| AJAX form with PHP backend | ✅ |
| Admin dashboard | ✅ |
| MySQL dynamic projects | ✅ |
| Responsive / mobile-first | ✅ |
| Button ripple effects | ✅ |
| Parallax hero | ✅ |
| XSS / injection protection | ✅ |

---

## 🔒 Security Notes
- All user inputs are sanitized with `htmlspecialchars` + `strip_tags`
- Emails validated with `FILTER_VALIDATE_EMAIL`
- Prepared statements used for all DB queries
- Basic rate limiting on contact form (session-based)
- Admin uses `password_verify()` — never stores plaintext passwords

---

*Built with PHP, MySQL, vanilla JS — no heavy frameworks required.*
