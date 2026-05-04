<?php
/**
 * Header Component
 * Reusable site-wide header with meta, fonts, and CSS
 */
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Aryan Uraw — Acting Managing Director at Sovryx Tech Pvt. Ltd. Portfolio showcasing vision, leadership, and innovation.">
  <meta name="keywords" content="Aryan Uraw, Sovryx Tech, Managing Director, Portfolio, Web Development">
  <meta property="og:title" content="Aryan Uraw · Sovryx Tech">
  <meta property="og:description" content="Acting Managing Director at Sovryx Tech Pvt. Ltd.">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Aryan Uraw — Portfolio'; ?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/animations.css">

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml">
</head>
<body>

<!-- ── Loading Screen ─────────────────────────────────────── -->
<div id="loader" class="loader">
  <div class="loader__inner">
    <div class="loader__logo">AU</div>
    <div class="loader__bar"><span></span></div>
    <p class="loader__text">Loading Experience</p>
  </div>
</div>

<!-- ── Custom Cursor ──────────────────────────────────────── -->
<div id="cursor" class="cursor"></div>
<div id="cursor-follower" class="cursor-follower"></div>

<!-- ── Navbar ─────────────────────────────────────────────── -->
<nav id="navbar" class="navbar">
  <div class="nav__container">
    <a href="index.php" class="nav__logo">
      <span class="logo-text">Aryan<em>.</em></span>
    </a>
    <ul class="nav__links">
      <li><a href="#about"    class="nav__link">About</a></li>
      <li><a href="#projects" class="nav__link">Work</a></li>
      <li><a href="#skills"   class="nav__link">Skills</a></li>
      <li><a href="#contact"  class="nav__link">Contact</a></li>
    </ul>
    <div class="nav__actions">
      <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">
        <i class="fas fa-sun" id="theme-icon"></i>
      </button>
      <button class="nav__cta" onclick="document.getElementById('contact').scrollIntoView({behavior:'smooth'})">
        Hire Me
      </button>
      <button class="nav__hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
  <ul>
    <li><a href="#about"    onclick="closeMobileMenu()">About</a></li>
    <li><a href="#projects" onclick="closeMobileMenu()">Work</a></li>
    <li><a href="#skills"   onclick="closeMobileMenu()">Skills</a></li>
    <li><a href="#contact"  onclick="closeMobileMenu()">Contact</a></li>
  </ul>
</div>
