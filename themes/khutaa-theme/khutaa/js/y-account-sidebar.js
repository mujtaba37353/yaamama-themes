fetch("../../components/account/y-c-account-sidebar.html")
  .then((response) => response.text())
  .then((data) => {
    const sidebar = document.querySelector('[data-y="account-sidebar"]');
    const contentContainer = document.getElementById('account-content');
    if (sidebar && contentContainer) {
      sidebar.innerHTML = data;

      const sidebarLinks = sidebar.querySelectorAll('.sidebar-item a');
      sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          const parentItem = link.closest('.sidebar-item');

          sidebar.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('active');
          });

          parentItem.classList.add('active');

          const title = parentItem.getAttribute('data-title');
          const titleElement = sidebar.querySelector('#sidebar-title');
          if (titleElement && title) {
            titleElement.textContent = title;
          }

          let componentPath = '';
          switch (title) {
            case 'تفاصيل الحساب':
              componentPath = '../../components/account/y-c-account-details.html';
              break;
            case 'الطلبات':
              componentPath = '../../components/account/y-c-orders.html';
              break;
            case 'العنوان':
              componentPath = '../../components/account/y-c-address.html';
              break;
            case 'تسجيل الخروج':
              window.location.href = '../../templates/login/layout.html';
              return; 
            default:
              componentPath = '../../components/account/y-c-account-details.html';
          }

          fetch(componentPath)
            .then(response => response.text())
            .then(html => {
              contentContainer.innerHTML = html;
            })
            .catch(err => {
              console.error('Error loading content component:', err);
              contentContainer.innerHTML = '<p>فشل تحميل المحتوى.</p>';
            });
        });
      });

      const sidebarItems = sidebar.querySelectorAll('.sidebar-item');
      let activeItem = sidebarItems[0];
      if (activeItem) {
        activeItem.classList.add('active');
        const title = activeItem.getAttribute('data-title');
        const titleElement = sidebar.querySelector('#sidebar-title');
        if (titleElement && title) {
          titleElement.textContent = title;
        }
        let componentPath = '';
        switch (title) {
          case 'تفاصيل الحساب':
            componentPath = '../../components/account/y-c-account-details.html';
            break;
          case 'الطلبات':
            componentPath = '../../components/account/y-c-orders.html';
            break;
          case 'العنوان':
            componentPath = '../../components/account/y-c-address.html';
            break;
          case 'تسجيل الخروج':
            window.location.href = '../../templates/login/layout.html';
            return; 
          default:
            componentPath = '../../components/account/y-c-account-details.html';
        }
        fetch(componentPath)
          .then(response => response.text())
          .then(html => {
            contentContainer.innerHTML = html;
          })
          .catch(err => {
            console.error('Error loading content component:', err);
            contentContainer.innerHTML = '<p>فشل تحميل المحتوى.</p>';
          });
      }
    }
  })
  .catch((err) => console.error("Error loading account sidebar:", err));
