<?php
/**
 * index.php — Aryan Uraw Portfolio
 * Acting Managing Director, Sovryx Tech Pvt. Ltd.
 */

require_once 'config/database.php';

// ── Fetch projects from DB (graceful fallback) ─────────────
$projects   = [];
$categories = ['All', 'Web', 'Design', 'Other'];
$conn = getDBConnection();
if ($conn) {
    $stmt = $conn->prepare("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }
    $stmt->close();
    $conn->close();
}

// Fallback sample data when DB is unavailable
if (empty($projects)) {
    $projects = [
        ['id'=>1,'title'=>'Sovryx Tech Platform','description'=>'Enterprise-grade SaaS platform with real-time dashboards and scalable microservice architecture.','image'=>'assets/images/proj1.jpg','link'=>'#','category'=>'Web','featured'=>1],
        ['id'=>2,'title'=>'Executive Brand Identity','description'=>'Complete visual identity system — logo, typography, color system, and brand guidelines for Sovryx Tech.','image'=>'assets/images/proj2.jpg','link'=>'#','category'=>'Design','featured'=>1],
        ['id'=>3,'title'=>'Analytics Intelligence Suite','description'=>'AI-powered analytics dashboard providing predictive insights and automated reporting for enterprise clients.','image'=>'assets/images/proj3.jpg','link'=>'#','category'=>'Web','featured'=>0],
        ['id'=>4,'title'=>'Sovryx Mobile App','description'=>'Cross-platform mobile application with offline-first architecture and seamless sync across iOS and Android.','image'=>'assets/images/proj4.jpg','link'=>'#','category'=>'Web','featured'=>0],
        ['id'=>5,'title'=>'Corporate UX Research','description'=>'In-depth UX research spanning user interviews, journey mapping, and high-fidelity prototyping for B2B products.','image'=>'assets/images/proj5.jpg','link'=>'#','category'=>'Design','featured'=>0],
        ['id'=>6,'title'=>'Venture Pitch Deck System','description'=>'Modular investor presentation framework used for fundraising and strategic partnership communications.','image'=>'assets/images/proj6.jpg','link'=>'#','category'=>'Other','featured'=>0],
    ];
}

include 'includes/header.php';
?>

<!-- ══════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════ -->
<section id="hero" class="hero">
  <!-- Animated background -->
  <div class="hero__bg">
    <div class="hero__orb hero__orb--1"></div>
    <div class="hero__orb hero__orb--2"></div>
    <div class="hero__orb hero__orb--3"></div>
    <canvas id="particleCanvas"></canvas>
  </div>

  <div class="hero__content">
    <div class="hero__eyebrow reveal-up">
      <span class="eyebrow-dot"></span>
      Acting Managing Director · Sovryx Tech Pvt. Ltd.
    </div>

    <h1 class="hero__name reveal-up" style="--delay:0.1s">
      Aryan<br><em>Uraw</em>
    </h1>

    <p class="hero__tagline reveal-up" style="--delay:0.2s">
      <span id="typed-text"></span><span class="typed-cursor">|</span>
    </p>

    <p class="hero__sub reveal-up" style="--delay:0.3s">
      Building the future of technology — one visionary decision at a time.
    </p>

    <div class="hero__cta reveal-up" style="--delay:0.4s">
      <a href="#projects" class="btn btn--primary">
        <span>View Work</span>
        <i class="fas fa-arrow-right"></i>
      </a>
      <a href="#contact" class="btn btn--outline">
        <span>Let's Talk</span>
      </a>
    </div>

    <div class="hero__stats reveal-up" style="--delay:0.5s">
      <div class="stat">
        <span class="stat__num" data-count="3">0</span><span>+</span>
        <span class="stat__label">Years Leading</span>
      </div>
      <div class="stat__divider"></div>
      <div class="stat">
        <span class="stat__num" data-count="20">0</span><span>+</span>
        <span class="stat__label">Projects Shipped</span>
      </div>
      <div class="stat__divider"></div>
      <div class="stat">
        <span class="stat__num" data-count="10">0</span><span>+</span>
        <span class="stat__label">Clients Served</span>
      </div>
    </div>
  </div>

  <div class="hero__scroll-hint">
    <div class="scroll-mouse"><span></span></div>
    <span>Scroll to explore</span>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     ABOUT SECTION
══════════════════════════════════════════════════════ -->
<section id="about" class="section about">
  <div class="container">
    <div class="about__grid">
      <div class="about__visual reveal-left">
        <div class="about__image-wrap">
          <div class="about__image-placeholder">
            <span>AU</span>
          </div>
          <div class="about__badge">
            <i class="fas fa-star"></i>
            <span>MD · Sovryx Tech</span>
          </div>
        </div>
      </div>

      <div class="about__content reveal-right">
        <div class="section-label">About Me</div>
        <h2 class="section-title">Vision-Driven<br><em>Leadership</em></h2>
        <p class="about__body">
          I'm <strong>Aryan Uraw</strong>, Acting Managing Director at <strong>Sovryx Tech Pvt. Ltd.</strong> — 
          a technology company I help lead with a mission to transform how businesses operate through intelligent software solutions.
        </p>
        <p class="about__body">
          My work sits at the intersection of strategic leadership, product design, and engineering. 
          I believe great technology starts with deep empathy for people and a relentless commitment to excellence.
        </p>

        <div class="about__pillars">
          <div class="pillar">
            <i class="fas fa-chess-king"></i>
            <span>Strategic Leadership</span>
          </div>
          <div class="pillar">
            <i class="fas fa-code"></i>
            <span>Technical Depth</span>
          </div>
          <div class="pillar">
            <i class="fas fa-lightbulb"></i>
            <span>Product Innovation</span>
          </div>
          <div class="pillar">
            <i class="fas fa-users"></i>
            <span>Team Building</span>
          </div>
        </div>

        <a href="#contact" class="btn btn--primary">
          <span>Get In Touch</span>
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     EXPERIENCE / SPOTLIGHT SECTION
══════════════════════════════════════════════════════ -->
<section id="experience" class="section experience">
  <div class="container">
    <div class="section-label text-center reveal-up">Experience</div>
    <h2 class="section-title text-center reveal-up">Where I <em>Lead</em></h2>

    <div class="experience__spotlight reveal-up">
      <div class="spotlight__glow"></div>
      <div class="spotlight__content">
        <div class="spotlight__icon">
          <i class="fas fa-building"></i>
        </div>
        <div class="spotlight__info">
          <div class="spotlight__badge">Current Role</div>
          <h3>Acting Managing Director</h3>
          <h4>Sovryx Tech Pvt. Ltd.</h4>
          <p>
            Overseeing company vision, product strategy, team leadership, and client relationships. 
            Driving Sovryx Tech's mission to deliver cutting-edge technology solutions that empower 
            businesses to grow with confidence and clarity.
          </p>
          <blockquote class="vision-quote">
            "Technology should feel effortless — our job is to absorb the complexity so our clients never have to."
          </blockquote>
        </div>
      </div>
    </div>

    <div class="timeline reveal-up">
      <div class="timeline__item">
        <div class="timeline__dot"></div>
        <div class="timeline__content">
          <span class="timeline__date">2022 — Present</span>
          <h4>Acting Managing Director</h4>
          <p>Sovryx Tech Pvt. Ltd.</p>
        </div>
      </div>
      <div class="timeline__item">
        <div class="timeline__dot"></div>
        <div class="timeline__content">
          <span class="timeline__date">2021 — 2022</span>
          <h4>Lead Product Strategist</h4>
          <p>Sovryx Tech Pvt. Ltd.</p>
        </div>
      </div>
      <div class="timeline__item">
        <div class="timeline__dot"></div>
        <div class="timeline__content">
          <span class="timeline__date">2020 — 2021</span>
          <h4>Full-Stack Developer</h4>
          <p>Freelance & Agencies</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     PROJECTS SECTION
══════════════════════════════════════════════════════ -->
<section id="projects" class="section projects">
  <div class="container">
    <div class="section-label text-center reveal-up">Portfolio</div>
    <h2 class="section-title text-center reveal-up">Selected <em>Work</em></h2>

    <!-- Filter Tabs -->
    <div class="filter-tabs reveal-up">
      <?php foreach ($categories as $cat): ?>
        <button class="filter-btn <?php echo $cat === 'All' ? 'active' : ''; ?>"
                data-filter="<?php echo $cat; ?>">
          <?php echo htmlspecialchars($cat); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Projects Grid -->
    <div class="projects__grid" id="projects-grid">
      <?php foreach ($projects as $project): ?>
        <article class="project-card" data-category="<?php echo htmlspecialchars($project['category']); ?>">
          <div class="project-card__image">
            <!-- Gradient placeholder; replace src with actual image -->
            <div class="project-card__img-placeholder proj-bg-<?php echo $project['id'] % 6 + 1; ?>">
              <span><?php echo htmlspecialchars(substr($project['title'], 0, 2)); ?></span>
            </div>
            <div class="project-card__overlay">
              <a href="<?php echo htmlspecialchars($project['link']); ?>" class="project-card__link" target="_blank" rel="noopener">
                <i class="fas fa-arrow-up-right-from-square"></i>
                View Project
              </a>
            </div>
          </div>
          <div class="project-card__body">
            <span class="project-card__cat"><?php echo htmlspecialchars($project['category']); ?></span>
            <h3 class="project-card__title"><?php echo htmlspecialchars($project['title']); ?></h3>
            <p class="project-card__desc"><?php echo htmlspecialchars($project['description']); ?></p>
          </div>
          <?php if ($project['featured']): ?>
            <div class="project-card__featured-badge">Featured</div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     SKILLS SECTION
══════════════════════════════════════════════════════ -->
<section id="skills" class="section skills">
  <div class="container">
    <div class="section-label text-center reveal-up">Expertise</div>
    <h2 class="section-title text-center reveal-up">My <em>Skills</em></h2>

    <div class="skills__grid">
      <!-- Left: bars -->
      <div class="skills__bars reveal-left">
        <?php
        $skills = [
          ['name' => 'Strategic Leadership',   'pct' => 92, 'icon' => 'fa-chess'],
          ['name' => 'Product Management',      'pct' => 88, 'icon' => 'fa-layer-group'],
          ['name' => 'Full-Stack Development',  'pct' => 85, 'icon' => 'fa-code'],
          ['name' => 'UI / UX Design',          'pct' => 80, 'icon' => 'fa-pen-nib'],
          ['name' => 'Business Development',    'pct' => 87, 'icon' => 'fa-chart-line'],
          ['name' => 'Team & Culture Building', 'pct' => 90, 'icon' => 'fa-people-group'],
        ];
        foreach ($skills as $s): ?>
          <div class="skill-bar">
            <div class="skill-bar__header">
              <span><i class="fas <?php echo $s['icon']; ?>"></i> <?php echo $s['name']; ?></span>
              <span class="skill-bar__pct"><?php echo $s['pct']; ?>%</span>
            </div>
            <div class="skill-bar__track">
              <div class="skill-bar__fill" data-width="<?php echo $s['pct']; ?>"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Right: tech tags -->
      <div class="skills__tags reveal-right">
        <h3>Tech Stack</h3>
        <div class="tag-cloud">
          <?php
          $tech = ['PHP','MySQL','JavaScript','React','Node.js','Python','Laravel','Vue.js',
                   'Docker','AWS','Git','REST APIs','Figma','Tailwind CSS','Linux','Redis'];
          foreach ($tech as $t): ?>
            <span class="tech-tag"><?php echo $t; ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     CONTACT SECTION
══════════════════════════════════════════════════════ -->
<section id="contact" class="section contact">
  <div class="container">
    <div class="section-label text-center reveal-up">Let's Connect</div>
    <h2 class="section-title text-center reveal-up">Start a <em>Conversation</em></h2>

    <div class="contact__grid">
      <!-- Info panel -->
      <div class="contact__info reveal-left">
        <h3>Ready to build something remarkable?</h3>
        <p>Whether it's a new venture, a partnership, or just an introduction — I'd love to hear from you.</p>

        <div class="contact__details">
          <a href="mailto:aryan@sovryx.com" class="contact__item">
            <div class="contact__item-icon"><i class="fas fa-envelope"></i></div>
            <div>
              <span>Email</span>
              <strong>aryan@sovryx.com</strong>
            </div>
          </a>
          <a href="#" class="contact__item">
            <div class="contact__item-icon"><i class="fab fa-linkedin-in"></i></div>
            <div>
              <span>LinkedIn</span>
              <strong>linkedin.com/in/aryanuraw</strong>
            </div>
          </a>
          <div class="contact__item">
            <div class="contact__item-icon"><i class="fas fa-location-dot"></i></div>
            <div>
              <span>Location</span>
              <strong>Nepal</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="contact__form-wrap reveal-right">
        <form id="contactForm" class="contact-form" novalidate>
          <div id="form-alert" class="form-alert" style="display:none;"></div>

          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Aryan Uraw" required autocomplete="name">
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="hello@example.com" required autocomplete="email">
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Tell me about your project or idea..." required></textarea>
          </div>

          <button type="submit" class="btn btn--primary btn--full" id="submitBtn">
            <span id="btn-text">Send Message</span>
            <i class="fas fa-paper-plane" id="btn-icon"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
