var at = document.documentElement.getAttribute("data-layout");
if ((at = "vertical")) {
  // ==============================================================
  // Auto select left navbar
  // ==============================================================

  document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var isSidebar = document.getElementsByClassName("side-mini-panel");
    if (isSidebar.length > 0) {
      var url = window.location + "";
      var path = url.replace(
        window.location.protocol + "//" + window.location.host + "/",
        "",
      );

      //****************************
      // This is for
      //****************************

      function findMatchingElement() {
        var currentUrl = window.location.href;
        var anchors = document.querySelectorAll("#sidebarnav a");
        let finalUrl = "";

        let urlSplats = currentURL.split("?");
        let refinedUrl = urlSplats[0];

        let isQueryParameter = currentURL.includes("?");
        if (isQueryParameter) {
          finalUrl = refinedUrl;
        } else {
          finalUrl = currentURL;
        }

        for (var i = 0; i < anchors.length; i++) {
          if (anchors[i].href === finalUrl) {
            return anchors[i];
          }
        }

        return null; // Return null if no matching element is found
      }

      var elements = findMatchingElement();

      if (elements) {
        // Do something with the matching element
        elements.classList.add("active");
      }

      //****************************
      // This is for the multilevel menu
      //****************************
      document.querySelectorAll("#sidebarnav a").forEach(function (link) {
        link.addEventListener("click", function (e) {
          const isActive = this.classList.contains("active");
          const parentUl = this.closest("ul");

          if (!isActive) {
            // hide any open menus and remove all other classes
            parentUl.querySelectorAll("ul").forEach(function (submenu) {
              submenu.classList.remove("in");
            });
            parentUl.querySelectorAll("a").forEach(function (navLink) {
              navLink.classList.remove("active");
            });

            // open our new menu and add the open class
            const submenu = this.nextElementSibling;
            if (submenu) {
              submenu.classList.add("in");
            }

            this.classList.add("active");
          } else {
            this.classList.remove("active");
            parentUl.classList.remove("active");
            const submenu = this.nextElementSibling;
            if (submenu) {
              submenu.classList.remove("in");
            }
          }
        });
      });

      document
        .querySelectorAll("#sidebarnav > li > a.has-arrow")
        .forEach(function (link) {
          link.addEventListener("click", function (e) {
            e.preventDefault();
          });
        });

      //****************************
      // This is for show menu
      //****************************

      var closestNav = elements.closest("nav[class^=sidebar-nav]");
      var menuid = (closestNav && closestNav.id) || "menu-right-mini-1";
      var menu = menuid[menuid.length - 1];

      document
        .getElementById("menu-right-mini-" + menu)
        .classList.add("d-block");
      document.getElementById("mini-" + menu).classList.add("selected");

      //****************************
      // This is for mini sidebar
      //****************************
      document
        .querySelectorAll("ul#sidebarnav ul li a.active")
        .forEach(function (link) {
          link.closest("ul").classList.add("in");
          link.closest("ul").parentElement.classList.add("selected");
        });
      document
        .querySelectorAll(".mini-nav .mini-nav-item")
        .forEach(function (item) {
          item.addEventListener("click", function () {
            var id = this.id;
            document
              .querySelectorAll(".mini-nav .mini-nav-item")
              .forEach(function (navItem) {
                navItem.classList.remove("selected");
              });
            this.classList.add("selected");
            document
              .querySelectorAll(".sidebarmenu nav")
              .forEach(function (nav) {
                nav.classList.remove("d-block");
              });
            document
              .getElementById("menu-right-" + id)
              .classList.add("d-block");
            document.body.setAttribute("data-sidebartype", "full");
          });
        });
    }
  });
}

if ((at = "horizontal")) {
  function findMatchingElement() {
    var currentUrl = window.location.href;
    var anchors = document.querySelectorAll("#sidebarnavh ul#sidebarnav a");
    for (var i = 0; i < anchors.length; i++) {
      if (anchors[i].href === currentUrl) {
        return anchors[i];
      }
    }

    return null; // Return null if no matching element is found
  }
  var elements = findMatchingElement();

  if (elements) {
    elements.classList.add("active");
  }
  document
    .querySelectorAll("#sidebarnavh ul#sidebarnav a.active")
    .forEach(function (link) {
      link.closest("a").parentElement.classList.add("selected");
      link.closest("ul").parentElement.classList.add("selected");
    });
}

// ----------------------------------------
// Active 2 file at same time
// ----------------------------------------

var currentURL =
  window.location != window.parent.location
    ? document.referrer
    : document.location.href;

var link = document.getElementById("get-url") || document.createElement("a");

if (currentURL.includes("/main/index.html")) {
  link.setAttribute("href", "../main/index.html");
} else if (currentURL.includes("/index.html")) {
  link.setAttribute("href", "./index.html");
} else {
  link.setAttribute("href", "./");
}
