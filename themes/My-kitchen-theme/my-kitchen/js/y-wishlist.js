document.addEventListener("DOMContentLoaded", () => {
  if (typeof window.mykitchenSyncFavorites === "function") {
    window.mykitchenSyncFavorites(document);
  }
});
