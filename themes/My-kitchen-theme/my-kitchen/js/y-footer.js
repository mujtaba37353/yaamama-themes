fetch("../../components/layout/y-c-footer.html", { cache: "no-store" })
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="footer"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
    if (window.MYK_CONTACT_SETTINGS) {
      const { address, phone, email, whatsapp } = window.MYK_CONTACT_SETTINGS;
      const addressNode = container.querySelector('[data-y="footer-address"]');
      const phoneNode = container.querySelector('[data-y="footer-phone"]');
      const emailNode = container.querySelector('[data-y="footer-email"]');
      const whatsappLink = container.querySelector('[data-y="floating-whatsapp"]');
      const phoneLink = container.querySelector('[data-y="floating-phone"]');

      if (addressNode && address) {
        addressNode.innerHTML = '<i class="fa-solid fa-location-dot"></i>' + address;
      }
      if (phoneNode && phone) {
        phoneNode.innerHTML = '<i class="fa-solid fa-phone"></i>' + phone;
      }
      if (emailNode && email) {
        emailNode.innerHTML = '<i class="fa-solid fa-envelope"></i> ' + email;
      }
      if (whatsappLink && whatsapp) {
        const clean = whatsapp.replace(/[^\d+]/g, "");
        whatsappLink.setAttribute("href", "https://wa.me/" + clean.replace(/^\+/, ""));
      }
      if (phoneLink && phone) {
        const clean = phone.replace(/[^\d+]/g, "");
        phoneLink.setAttribute("href", "tel:" + clean);
      }
    }
  });
