(() => {
  const form = document.getElementById("productFilter");
  const results = document.getElementById("productResults");
  const search = form?.querySelector('input[name="q"]');
  const category = form?.querySelector('select[name="category_id"]');
  const clear = document.getElementById("clearProductFilter");

  if (!form || !results || !search || !category) return;

  let timer;
  let request;

  const filterUrl = () => {
    const url = new URL(form.action, window.location.origin);
    const query = search.value.trim();
    if (query) url.searchParams.set("q", query);
    if (category.value) url.searchParams.set("category_id", category.value);
    return url;
  };

  const updateClear = () => {
    if (clear) clear.hidden = !search.value.trim() && !category.value;
  };

  const loadProducts = async (url, updateHistory = true) => {
    request?.abort();
    request = new AbortController();
    results.setAttribute("aria-busy", "true");

    try {
      const response = await fetch(url, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        signal: request.signal,
      });
      if (!response.ok) throw new Error("Unable to load products");

      const page = new DOMParser().parseFromString(await response.text(), "text/html");
      const nextResults = page.getElementById("productResults");
      if (!nextResults) throw new Error("Product results are missing");

      results.innerHTML = nextResults.innerHTML;
      if (updateHistory) window.history.replaceState({}, "", url);
    } catch (error) {
      if (error.name !== "AbortError") form.submit();
    } finally {
      results.removeAttribute("aria-busy");
    }
  };

  const searchNow = () => {
    window.clearTimeout(timer);
    updateClear();
    timer = window.setTimeout(() => loadProducts(filterUrl()), 250);
  };

  search.addEventListener("input", searchNow);
  category.addEventListener("change", () => {
    window.clearTimeout(timer);
    updateClear();
    loadProducts(filterUrl());
  });
  form.addEventListener("submit", event => {
    event.preventDefault();
    window.clearTimeout(timer);
    loadProducts(filterUrl());
  });
  clear?.addEventListener("click", event => {
    event.preventDefault();
    search.value = "";
    category.value = "";
    updateClear();
    loadProducts(new URL(form.action, window.location.origin));
    search.focus();
  });
  results.addEventListener("click", event => {
    const link = event.target.closest('nav[role="navigation"] a');
    if (!link) return;
    event.preventDefault();
    loadProducts(new URL(link.href));
  });
})();
