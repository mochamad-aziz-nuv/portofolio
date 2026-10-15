/**
 * assets/js/main.js
 * Interactive logic for Mochamad Aziz Noviyanto - Freelance Portfolio
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Mobile Menu Toggle
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    mobileNavLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
      });
    });
  }

  // 2. Active Navbar Link on Scroll
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.desktop-nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    const scrollY = window.pageYOffset;

    sections.forEach(section => {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - 120;
      const sectionId = section.getAttribute('id');

      if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
        current = sectionId;
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('text-blue-600', 'font-semibold');
      link.classList.add('text-slate-600');
      if (link.getAttribute('href') === `#${current}`) {
        link.classList.remove('text-slate-600');
        link.classList.add('text-blue-600', 'font-semibold');
      }
    });
  });

  // 3. Project Filter Tabs
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        b.classList.add('bg-white', 'text-slate-600', 'hover:bg-slate-100');
      });
      btn.classList.remove('bg-white', 'text-slate-600', 'hover:bg-slate-100');
      btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');

      const filter = btn.getAttribute('data-filter');

      projectCards.forEach(card => {
        const categories = card.getAttribute('data-category').split(' ');
        if (filter === 'all' || categories.includes(filter)) {
          card.classList.remove('hidden');
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 10);
        } else {
          card.classList.add('hidden');
        }
      });
    });
  });

  // 4. Modal Case Study Details
  const modalBackdrop = document.getElementById('project-modal');
  const modalCloseBtn = document.getElementById('modal-close-btn');
  const modalTitle = document.getElementById('modal-title');
  const modalClient = document.getElementById('modal-client');
  const modalRole = document.getElementById('modal-role');
  const modalDesc = document.getElementById('modal-description');
  const modalTech = document.getElementById('modal-tech');
  const modalHighlights = document.getElementById('modal-highlights');
  const detailBtns = document.querySelectorAll('.view-detail-btn');

  // Case Studies Database from CV
  const projectDetails = {
    'pemprov-jatim': {
      title: 'Arsitektur Backend & Sistem Informasi Manajemen Operasional',
      client: 'Pemerintah Provinsi Jawa Timur (Sep 2022 – Sekarang)',
      role: 'Full-Stack Developer (Full-Time)',
      desc: 'Pengembangan arsitektur backend skala enterprise untuk kebutuhan operasional birokrasi pemerintahan daerah dengan standar keandalan tinggi dan pengelolaan multi-versi PHP.',
      tech: ['PHP 7.x & 8.x', 'Laravel', 'CodeIgniter 2', 'MySQL / MariaDB', 'Nginx Server', 'VPS Linux'],
      highlights: [
        'Merancang dan memelihara arsitektur backend aplikasi berbasis Laravel untuk kebutuhan operasional vital.',
        'Optimasi raw query database kompleks secara mandiri untuk penyajian data laporan instan tanpa lemot.',
        'Inisiatif pembaruan UI/UX serta efisiensi menu sistem internal, berhasil meningkatkan produktivitas pegawai.',
        'Konfigurasi server produksi multi-versi PHP tanpa bentrok, mengawinkan aplikasi legacy CI 2 dengan sistem modern Laravel.'
      ]
    },
    'nagrofaqua': {
      title: 'Aplikasi Mobile & Sistem Manajemen Tambak Udang Terpadu',
      client: 'PT. Nagrofaqua (2024 – Awal 2025)',
      role: 'Remote Full-Stack Developer (Project-Based / Repeat Client)',
      desc: 'Penyelesaian tepat waktu maintenance sistem web CodeIgniter berujung kepuasan klien dan repeat order untuk merancang serta meluncurkan aplikasi mobile Flutter dari nol yang terintegrasi dengan RESTful API Laravel.',
      tech: ['Flutter (Dart)', 'Laravel (REST API)', 'CodeIgniter', 'MySQL', 'Mobile & Web Integration'],
      highlights: [
        'Maintenance dan perbaikan fitur core aplikasi manajemen tambak berbasis CI selesai tepat waktu di awal 2025.',
        'Mendapatkan repeat order karena klien puas dengan kecepatan respon dan kualitas kode.',
        'Membangun aplikasi mobile monitoring tambak udang dari nol menggunakan Flutter (iOS & Android).',
        'Integrasi REST API Laravel untuk pemantauan kualitas air kolam, jadwal pakan, dan siklus panen secara real-time.'
      ]
    },
    'java-collaboration': {
      title: 'Pengembangan Arsitektur Komponen Backend Microservice',
      client: 'Project Collaboration Team (3 Bulan)',
      role: 'Remote Back-End Developer',
      desc: 'Kolaborasi pengembangan komponen backend berkecepatan tinggi dengan tim terorganisir berskala remote menggunakan ekosistem Java Spring Boot dan manajemen sistem tiket.',
      tech: ['Java', 'Spring Boot', 'RESTful API', 'Git Flow', 'Jira / Trello Ticketing'],
      highlights: [
        'Berkontribusi dalam pengembangan arsitektur dan komponen backend tangguh menggunakan Java Spring Boot.',
        'Penyelesaian task secara transparan dan disiplin menggunakan alur Agile & Sistem Tiketing.',
        'Sinkronisasi alur kerja teknis lintas developer dalam lingkungan remote terorganisir.'
      ]
    },
    'primavisi': {
      title: 'Enterprise Business Platform & Modul Android Operasional',
      client: 'PT. Primavisi Globalindo (2018 – Agustus 2022)',
      role: 'Web & Android Programmer (Full-Time)',
      desc: 'Membangun fitur aplikasi internal menggunakan framework Angular, penajaman performa query database skala besar, serta memastikan stabilitas modul berbasis Android.',
      tech: ['Angular', 'Android (Java)', 'SQL Server', 'REST API', 'Query Optimization'],
      highlights: [
        'Mengembangkan dan memelihara fitur serta menu dinamis baru pada aplikasi internal menggunakan Angular.',
        'Merancang penulisan raw query database berkala untuk menyusun laporan analitik operasional klien.',
        'Memastikan stabilitas aplikasi web dan modul Android agar sesuai dengan kebutuhan klien perusahaan.'
      ]
    }
  };

  detailBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const projId = btn.getAttribute('data-project');
      const data = projectDetails[projId];

      if (data && modalBackdrop) {
        modalTitle.textContent = data.title;
        modalClient.textContent = data.client;
        modalRole.textContent = data.role;
        modalDesc.textContent = data.desc;

        // Render tech tags
        modalTech.innerHTML = data.tech.map(t => 
          `<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-200">${t}</span>`
        ).join('');

        // Render highlights
        modalHighlights.innerHTML = data.highlights.map(h => 
          `<li class="flex items-start text-sm text-slate-700">
            <svg class="w-4 h-4 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>${h}</span>
          </li>`
        ).join('');

        modalBackdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  const closeModal = () => {
    if (modalBackdrop) {
      modalBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
  if (modalBackdrop) {
    modalBackdrop.addEventListener('click', (e) => {
      if (e.target === modalBackdrop) closeModal();
    });
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // 5. WhatsApp Message Generator (Direct Interactive Form)
  const waForm = document.getElementById('wa-inquiry-form');
  if (waForm) {
    waForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const clientName = document.getElementById('client-name').value.trim() || 'Klien';
      const clientProject = document.getElementById('client-project').value;
      const clientBudget = document.getElementById('client-budget').value || 'Sesuai Diskusi';
      const clientMessage = document.getElementById('client-message').value.trim() || 'Saya ingin mendiskusikan kebutuhan sistem.';

      const waText = `Halo Mas Mochamad Aziz Noviyanto,%0A%0ASaya *${encodeURIComponent(clientName)}* ingin berdiskusi terkait proyek freelance:%0A%0A📌 *Kategori Proyek:* ${encodeURIComponent(clientProject)}%0A💰 *Estimasi Budget / Timeline:* ${encodeURIComponent(clientBudget)}%0A📝 *Kebutuhan / Detail:*%0A${encodeURIComponent(clientMessage)}%0A%0AApakah ada waktu luang di jam kerja malam (18:00 - 22:00 WIB) untuk kita bahas lebih lanjut? Terima kasih!`;

      const waUrl = `https://wa.me/6281335520436?text=${waText}`;
      window.open(waUrl, '_blank');
    });
  }

  // 6. Copy to Clipboard Utility with Toast
  const showToast = (message) => {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-message');
    if (toast && toastMsg) {
      toastMsg.textContent = message;
      toast.classList.add('show');
      setTimeout(() => {
        toast.classList.remove('show');
      }, 2800);
    }
  };
});


