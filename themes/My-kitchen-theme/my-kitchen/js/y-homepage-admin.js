(function () {
  function updatePreview(previewId, url) {
    var preview = document.getElementById(previewId);
    if (!preview) return;
    if (url) {
      preview.innerHTML =
        '<img src="' + url + '" style="max-width:220px;height:auto;" alt="" />';
    } else {
      preview.innerHTML = "<span>لا توجد صورة</span>";
    }
  }

  function getSelectedContainer() {
    return document.getElementById("homepage_last_chance_selected");
  }

  function addLastChanceProduct(id, label) {
    var container = getSelectedContainer();
    if (!container || !id) {
      return;
    }
    if (container.querySelector('[data-product-id="' + id + '"]')) {
      return;
    }
    var item = document.createElement("li");
    item.className = "myk-selected-item";
    item.setAttribute("data-product-id", id);
    var text = document.createElement("span");
    text.className = "myk-selected-label";
    text.textContent = label || "#" + id;
    var removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "button myk-remove-selected";
    removeBtn.textContent = "إزالة";
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "homepage_last_chance_products[]";
    input.value = id;
    item.appendChild(text);
    item.appendChild(removeBtn);
    item.appendChild(input);
    container.appendChild(item);
  }

  document.addEventListener("click", function (event) {
    var target = event.target;
    if (target && target.nodeType !== 1) {
      target = target.parentElement;
    }

    var uploadBtn = target ? target.closest(".myk-upload-btn") : null;
    if (uploadBtn) {
      event.preventDefault();
      var targetId = uploadBtn.getAttribute("data-target-id");
      var targetUrl = uploadBtn.getAttribute("data-target-url");
      var previewId = uploadBtn.getAttribute("data-preview");
      var frame = wp.media({
        title: "اختيار صورة",
        button: { text: "استخدام الصورة" },
        multiple: false,
      });
      frame.on("select", function () {
        var attachment = frame.state().get("selection").first().toJSON();
        var idField = document.getElementById(targetId);
        var urlField = document.getElementById(targetUrl);
        if (idField) idField.value = attachment.id || "";
        if (urlField) urlField.value = attachment.url || "";
        updatePreview(previewId, attachment.url || "");
      });
      frame.open();
      return;
    }

    var removeBtn = target ? target.closest(".myk-remove-btn") : null;
    if (removeBtn) {
      event.preventDefault();
      var removeTargetId = removeBtn.getAttribute("data-target-id");
      var removeTargetUrl = removeBtn.getAttribute("data-target-url");
      var removePreviewId = removeBtn.getAttribute("data-preview");
      var removeIdField = document.getElementById(removeTargetId);
      var removeUrlField = document.getElementById(removeTargetUrl);
      if (removeIdField) removeIdField.value = "";
      if (removeUrlField) removeUrlField.value = "";
      updatePreview(removePreviewId, "");
    }

    var addBtn = target ? target.closest(".myk-add-last-chance") : null;
    if (addBtn) {
      event.preventDefault();
      var select = document.getElementById("homepage_last_chance_picker");
      if (!select || !select.value) {
        return;
      }
      var label = select.options[select.selectedIndex].text;
      addLastChanceProduct(select.value, label);
    }

    var removeSelected = target ? target.closest(".myk-remove-selected") : null;
    if (removeSelected) {
      event.preventDefault();
      var item = removeSelected.closest(".myk-selected-item");
      if (item) {
        item.remove();
      }
    }
  });
})();
