document.addEventListener("DOMContentLoaded", function () {
  // Progressive enhancement: utilizing a no-js class
  // document.documentElement.classList.add("js");
  // document.documentElement.classList.remove("no-js");
  // in css: .hidden.no-js { display: block }

  // Now set up the event listeners
  // Look for all buttons that are inside a correct level heading inside the accordion container
  document.querySelectorAll(".toggle-container .toggle-item__header button").forEach(function (button) {
      button.addEventListener("click", function () {
          if (this.getAttribute("aria-expanded") === "true") {
              hideAccordion(this);
          } else {
              showAccordion(this);
          }
      });
  });

  function showAccordion(button) {
      var thisId = button.getAttribute("data-id");
      var wrapper = document.getElementById("toggle-item-" + thisId);
      var panel = document.getElementById("toggle__content-" + thisId);

      // document.querySelectorAll(".toggle-item").forEach(function (item) {
      //     item.classList.remove("toggle-open");
      //     item.querySelector(".toggle-item__content").classList.add("toggle-hidden");
      //     item.querySelector(".toggle-item__header button").setAttribute("aria-expanded", "false");
      // });

      wrapper.classList.add("toggle-open");
      panel.classList.remove("toggle-hidden");
      button.setAttribute("aria-expanded", "true");
      button.focus();
  }

  function hideAccordion(button) {
      var thisId = button.getAttribute("data-id");
      var wrapper = document.getElementById("toggle-item-" + thisId);
      var panel = document.getElementById("toggle__content-" + thisId);

      wrapper.classList.remove("toggle-open");
      panel.classList.add("toggle-hidden");
      button.setAttribute("aria-expanded", "false");
      button.focus();
  }
});
