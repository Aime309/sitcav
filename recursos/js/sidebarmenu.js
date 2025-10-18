/*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ==============================================================
// Auto select left navbar
// ==============================================================
$(() => {
  function findMatchingElement() {
    const currentUrl = window.location.href;
    const anchors = document.querySelectorAll("#sidebarnav a");
    for (let i = 0; i < anchors.length; i++) {
      const anchor = anchors[i];

      if (anchor instanceof HTMLAnchorElement && anchor.href === currentUrl) {
        return anchors[i];
      }
    }

    return null; // Return null if no matching element is found
  }
  const elements = findMatchingElement();

  // Do something with the matching element
  if (elements) {
    elements.classList.add("active");
  }

  for (const link of document.querySelectorAll(
    "ul#sidebarnav ul li a.active",
  )) {
    link.closest("ul")?.classList.add("in");
    link.closest("ul")?.parentElement?.classList.add("selected");
  }

  for (const li of document.querySelectorAll("#sidebarnav li")) {
    const isActive = li.classList.contains("selected");

    if (isActive) {
      const anchor = li.querySelector("a");

      if (anchor) {
        anchor.classList.add("active");
      }
    }
  }

  for (const link of document.querySelectorAll("#sidebarnav a")) {
    link.addEventListener("click", () => {
      const isActive = link.classList.contains("active");
      const parentUl = link.closest("ul");

      if (!isActive) {
        // hide any open menus and remove all other classes
        for (const submenu of parentUl?.querySelectorAll("ul") || []) {
          submenu.classList.remove("in");
        }

        for (const navLink of parentUl?.querySelectorAll("a") || []) {
          navLink.classList.remove("active");
        }

        // open our new menu and add the open class
        const submenu = link.nextElementSibling;
        if (submenu) {
          submenu.classList.add("in");
        }

        link.classList.add("active");
      } else {
        link.classList.remove("active");
        parentUl?.classList.remove("active");

        const submenu = link.nextElementSibling;

        if (submenu) {
          submenu.classList.remove("in");
        }
      }
    });
  }
});
