class AccountSidebar {
  constructor() {
    this.currentComponent = null; 
    this.contentContainer = null;
    this.sidebar = null;
    this.isLoading = false;
    this.componentCache = new Map();

    this.init();
  }

  async init() {
    try {
      console.log('Initializing AccountSidebar...');

      const sidebarHTML = await this.fetchHTML('../../components/account/y-c-account-sidebar.html');
      this.sidebar = document.querySelector('[data-y="account-sidebar"]');
      this.contentContainer = document.getElementById('account-content');

      console.log('Sidebar element found:', !!this.sidebar);
      console.log('Content container found:', !!this.contentContainer);

      if (this.sidebar && this.contentContainer) {
        this.sidebar.innerHTML = sidebarHTML;
        console.log('Sidebar HTML set');

        this.setupEventListeners();
        console.log('Event listeners set up');

        this.loadInitialComponent();
        console.log('Initial component loading triggered');
      } else {
        console.error('Required DOM elements not found:', {
          sidebar: !!this.sidebar,
          contentContainer: !!this.contentContainer
        });
      }
    } catch (error) {
      console.error('Error initializing account sidebar:', error);
    }
  }

  async fetchHTML(url) {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return await response.text();
    } catch (error) {
      console.error(`Error fetching ${url}:`, error);
      throw error;
    }
  }

  setupEventListeners() {
    const sidebarItems = this.sidebar.querySelectorAll('.sidebar-item');

    sidebarItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        this.handleSidebarClick(item);
      });

      item.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.handleSidebarClick(item);
        }
      });

      item.setAttribute('tabindex', '0');
      item.setAttribute('role', 'button');
    });

    window.addEventListener('popstate', (e) => {
      if (e.state && e.state.component) {
        this.loadComponent(e.state.component, false);
      }
    });
  }

  async handleSidebarClick(item) {
    if (this.isLoading) return;

    const component = item.dataset.component;
    const action = item.dataset.action;

    if (action === 'logout') {
      this.handleLogout();
      return;
    }

    await this.loadComponent(component, true);
  }
  async loadComponent(component, updateHistory = true) {
    if (this.isLoading) return;

    if (this.currentComponent === component && this.contentContainer.innerHTML.trim() !== '') {
      console.log('Component already loaded:', component);
      return;
    }

    console.log('loadComponent called with:', component, 'updateHistory:', updateHistory);

    this.isLoading = true;
    this.showLoading();

    try {
      let html;
      if (this.componentCache.has(component)) {
        html = this.componentCache.get(component);
        console.log('Using cached HTML for:', component);
      } else {
        const componentPath = this.getComponentPath(component);
        console.log('Fetching component from:', componentPath);
        html = await this.fetchHTML(componentPath);
        this.componentCache.set(component, html);
        console.log('Cached HTML for:', component);
      }

      this.setActiveItem(component);
      this.updateTitle(component);
      await this.setContent(html);

      this.currentComponent = component;
      console.log('Component loaded successfully:', component);

      if (updateHistory) {
        this.updateURL(component);
      }

      this.dispatchComponentChange(component);

    } catch (error) {
      console.error(`Error loading component ${component}:`, error);
      this.showError();
    } finally {
      this.isLoading = false;
      this.hideLoading();
    }
  }

  getComponentPath(component) {
    const paths = {
      'account-details': '../../components/account/y-c-account-details.html',
      'orders': '../../components/account/y-c-orders.html',
      'notifications': '../../components/account/y-c-notifications.html'
    };
    return paths[component] || paths['account-details'];
  }

  setActiveItem(component) {
    this.sidebar.querySelectorAll('.sidebar-item').forEach(item => {
      item.classList.remove('active');
      item.setAttribute('aria-selected', 'false');
    });

    const activeItem = this.sidebar.querySelector(`[data-component="${component}"]`);
    if (activeItem) {
      activeItem.classList.add('active');
      activeItem.setAttribute('aria-selected', 'true');
    }
  }

  updateTitle(component) {
    const titleElement = this.sidebar.querySelector('#sidebar-title');
    const activeItem = this.sidebar.querySelector(`[data-component="${component}"]`);

    if (titleElement && activeItem) {
      const title = activeItem.dataset.title;
      titleElement.textContent = title || 'حسابي الشخصي';
    }
  }

  async setContent(html) {
    return new Promise((resolve) => {
      console.log('Setting content, HTML length:', html.length);

      this.contentContainer.style.opacity = '0';
      this.contentContainer.style.transform = 'translateY(20px)';

      setTimeout(() => {
        this.contentContainer.innerHTML = html;
        this.contentContainer.classList.add('account-content-loaded');

        console.log('Content set, container innerHTML length:', this.contentContainer.innerHTML.length);

        this.contentContainer.style.opacity = '1';
        this.contentContainer.style.transform = 'translateY(0)';

        resolve();
      }, 200);
    });
  }

  showLoading() {
    this.contentContainer.classList.add('account-content-loading');

    if (!this.contentContainer.querySelector('.loading-spinner')) {
      const spinner = document.createElement('div');
      spinner.className = 'loading-spinner';
      spinner.innerHTML = `
        <div class="spinner-border" role="status">
          <span class="sr-only">جاري التحميل...</span>
        </div>
        <p>جاري تحميل المحتوى...</p>
      `;
      this.contentContainer.appendChild(spinner);
    }
  }

  hideLoading() {
    this.contentContainer.classList.remove('account-content-loading');
    const spinner = this.contentContainer.querySelector('.loading-spinner');
    if (spinner) {
      spinner.remove();
    }
  }

  showError() {
    this.contentContainer.innerHTML = `
      <div class="error-message">
        <i class="fa-solid fa-exclamation-triangle"></i>
        <h3>خطأ في تحميل المحتوى</h3>
        <p>عذراً، حدث خطأ أثناء تحميل المحتوى. يرجى المحاولة مرة أخرى.</p>
        <button class="y-enhanced-btn" onclick="location.reload()">إعادة تحميل الصفحة</button>
      </div>
    `;
  }

  handleLogout() {
    if (confirm('هل أنت متأكد من رغبتك في تسجيل الخروج؟')) {
      this.clearUserData();

      window.location.href = '../../templates/login/layout.html';
    }
  }

  clearUserData() {
    localStorage.removeItem('userToken');
    sessionStorage.clear();
    this.componentCache.clear();
  }

  updateURL(component = this.currentComponent) {
    const url = new URL(window.location);
    url.searchParams.set('section', component);

    history.pushState(
      { component: component },
      '',
      url.toString()
    );
  }

  loadInitialComponent() {
    const urlParams = new URLSearchParams(window.location.search);
    const urlComponent = urlParams.get('section');

    const initialComponent = urlComponent || 'account-details';

    console.log('Loading initial component:', initialComponent);

    setTimeout(() => {
      this.loadComponent(initialComponent, false);
    }, 100);
  }

  dispatchComponentChange(component) {
    const event = new CustomEvent('accountSectionChange', {
      detail: {
        component: component,
        title: this.sidebar.querySelector(`[data-component="${component}"]`)?.dataset.title
      }
    });
    document.dispatchEvent(event);
  }

  navigateTo(component) {
    this.loadComponent(component, true);
  }

  getCurrentComponent() {
    return this.currentComponent;
  }

  refresh() {
    this.componentCache.delete(this.currentComponent);
    this.loadComponent(this.currentComponent, false);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM Content Loaded - Initializing AccountSidebar');
  window.accountSidebar = new AccountSidebar();
});

if (document.readyState === 'loading') {
  console.log('DOM is still loading, waiting for DOMContentLoaded');
} else {
  console.log('DOM already loaded, initializing AccountSidebar immediately');
  if (!window.accountSidebar) {
    window.accountSidebar = new AccountSidebar();
  }
}

document.addEventListener('accountSectionChange', (e) => {
  console.log('Account section changed to:', e.detail.component);
});
