document.addEventListener('DOMContentLoaded', () => {
  initHeaderScroll()
  initSideMenu()
  initSidebarMobile()
  initLoadMorePosts()
  initImagePreviews()
  initAlertMessage()
  initDeleteModal()
})

/* ---------- Header BG ---------- */
function initHeaderScroll() {
  const header = document.querySelector('.header')

  window.addEventListener('scroll', () => {
    header.classList.toggle('header-bg', window.scrollY > 0)
  })
}


/* ---------- SIDE MENU ---------- */
function initSideMenu() {
  const menuBtn = document.querySelector('.header .menu-btn'),
    menuOverlay = document.querySelector('.side-menu-overlay'),
    sideMenu = document.querySelector('.side-menu'),
    closeMenuBtn = document.querySelector('.close-btn');

  // Si les éléments requis ne sont pas là (page signin), on quitte la fonction
  if (!menuBtn || !menuOverlay || !sideMenu || !closeMenuBtn) return;

  const openMenu = () => {
    sideMenu.classList.add('open');
    menuOverlay.classList.add('visible');
    document.body.style.overflow = "hidden";
  }

  const closeMenu = () => {
    sideMenu.classList.remove("open")
    menuOverlay.classList.remove("visible")
    document.body.style.removeProperty("overflow")
  }

  menuBtn.addEventListener('click', openMenu)
  closeMenuBtn.addEventListener('click', closeMenu)
  menuOverlay.addEventListener('click', closeMenu)
}


/* ---------- SHOW & HIDE SIDEBAR FOR MOBILES ---------- */
function initSidebarMobile() {
  const sidebar = document.querySelector('aside');
  const showSidebarBtn = document.querySelector('#show__sidebar-btn');
  const hideSidebarBtn = document.querySelector('#hide__sidebar-btn');

  if (!sidebar || !showSidebarBtn || !hideSidebarBtn) return;

  const showSidebar = () => {
    sidebar.style.left = '0';
    showSidebarBtn.style.display = 'none';
    hideSidebarBtn.style.display = 'inline-block';
  }

  const hideSidebar = () => {
    sidebar.style.left = '-100%';
    showSidebarBtn.style.display = 'inline-block';
    hideSidebarBtn.style.display = 'none';
  }

  showSidebarBtn?.addEventListener('click', showSidebar)
  hideSidebarBtn?.addEventListener('click', hideSidebar)
}

/* ---------- SHOW & HIDE ALERT MESSAGE SUCCESS ---------- */
function initAlertMessage() {
  const successMessages = document.querySelectorAll('.alert__message.success');
  successMessages.forEach((message) => {
    setTimeout(() => {
      message.classList.add('hide')
    }, 3000)
  })
}


/* ---------- LOAD MORE POSTS ---------- */
function initLoadMorePosts() {
  const loadMoreBtn = document.getElementById("load-more");
  const postsContainer = document.querySelector(".posts__container");

  if (!loadMoreBtn || !postsContainer) return;

  loadMoreBtn.addEventListener("click", async () => {
    const offset = parseInt(loadMoreBtn.dataset.offset || "0", 10);
    const token = loadMoreBtn.dataset.token || "";

    try {
      const response = await fetch("/load-more-posts.html", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `offset=${offset}&load_more_posts_token=${encodeURIComponent(token)}`
      });

      if (response.status === 204) {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerText = "Plus d’articles disponibles";
        return;
      }

      const data = await response.text();

      if (data.trim() !== "") {
        postsContainer.insertAdjacentHTML("beforeend", data);
        // Met à jour data-offset (en tant que string)
        loadMoreBtn.dataset.offset = String(offset + 9);
      }
    } catch (error) {
      console.error("Erreur lors du chargement des articles :", error);
    }
  });
}


/* ---------- IMAGE PREVIEW ---------- */
function initImagePreviews() {
  const imagePairs = [
    ['avatar', 'preview-avatar'],
    ['thumbnail', 'preview-thumbnail'],
    ['image_1', 'preview-image_1'],
    ['image_2', 'preview-image_2'],
    ['flag', 'preview-flag'],
  ];

  imagePairs.forEach(([inputId, previewId]) => {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (input && preview) {
      input.addEventListener('change', () => {
        const file = input.files[0];
        if (file) {
          preview.src = URL.createObjectURL(file);
          preview.style.display = 'block';
        }
      });
    }
  });
}

/* ---------- DELETE MODAL ---------- */
function initDeleteModal() {
  const modal = document.getElementById('confirm_modal');
  const modalMessage = document.getElementById('modal_message');
  const confirmBtn = document.getElementById('confirm_delete_btn');
  const cancelBtn = document.getElementById('cancel_delete_btn');

  let currentForm = null;

  document.querySelectorAll('.delete-user-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault()
      currentForm = this
      const fullname = this.querySelector('input[name="fullname"]').value
      modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer ${fullname}?`
      modal.style.display = 'flex';
    });
  })

  document.querySelectorAll('.delete-category-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault()
      currentForm = this
      const title = this.querySelector('input[name="title"]').value
      modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer ${title}?`
      modal.style.display = 'flex';
    });
  })

  document.querySelectorAll('.delete-post-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault()
      currentForm = this
      const title = this.querySelector('input[name="title"]').value
      modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer ${title}?`
      modal.style.display = 'flex';
    });
  })

  confirmBtn.addEventListener('click', function () {
    if (currentForm) {
      currentForm.submit();
    }
    modal.style.display = 'none';
  })

  cancelBtn.addEventListener('click', function () {
    modal.style.display = 'none';
    currentForm = null;
  })
}











