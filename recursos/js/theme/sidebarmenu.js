const at = document.documentElement.getAttribute("data-layout");

if (at === "vertical") {
  document.addEventListener("DOMContentLoaded", () => {
    const isSidebar = document.getElementsByClassName("side-mini-panel");

    if (isSidebar.length > 0) {
      const url = `${window.location}`;

      url.replace(`${window.location.protocol}//${window.location.host}/`, "");

      //****************************
      // This is for
      //****************************
      function findMatchingElement() {
        const anchors = document.querySelectorAll("#sidebarnav a");
        let finalUrl = "";

        const urlSplats = currentURL.split("?");
        const refinedUrl = urlSplats[0];
        const isQueryParameter = currentURL.includes("?");

        if (isQueryParameter) {
          finalUrl = refinedUrl;
        } else {
          finalUrl = currentURL;
        }

        for (let i = 0; i < anchors.length; i++) {
          const anchor = anchors[i];

          if (!(anchor instanceof HTMLAnchorElement)) {
            continue;
          }

          if (anchor.href === finalUrl) {
            return anchor;
          }
        }

        return null; // Return null if no matching element is found
      }

      const elements = findMatchingElement();

      if (elements) {
        // Do something with the matching element
        elements.classList.add("active");
      }

      //****************************
      // This is for the multilevel menu
      //****************************
      for (const link of document.querySelectorAll("#sidebarnav a")) {
        if (!(link instanceof HTMLAnchorElement)) {
          return;
        }

        link.addEventListener("click", () => {
          const isActive = link.classList.contains("active");
          const parentUl = link.closest("ul");

          if (!isActive) {
            // hide any open menus and remove all other classes
            for (const submenu of parentUl?.querySelectorAll("ul") ||
              new HTMLCollection()) {
              submenu.classList.remove("in");
            }

            for (const navLink of parentUl?.querySelectorAll("a") ||
              new HTMLCollection()) {
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

      for (const link of document.querySelectorAll(
        "#sidebarnav > li > a.has-arrow",
      )) {
        if (!(link instanceof HTMLAnchorElement)) {
          return;
        }

        link.addEventListener("click", (e) => {
          e.preventDefault();
        });
      }

      //****************************
      // This is for show menu
      //****************************
      const closestNav = elements?.closest("nav[class^=sidebar-nav]");
      const menuid = closestNav?.id || "menu-right-mini-1";
      const menu = menuid[menuid.length - 1];

      document
        .getElementById(`menu-right-mini-${menu}`)
        ?.classList.add("d-block");

      document.getElementById(`mini-${menu}`)?.classList.add("selected");

      //****************************
      // This is for mini sidebar
      //****************************
      for (const link of document.querySelectorAll(
        "ul#sidebarnav ul li a.active",
      )) {
        link.closest("ul")?.classList.add("in");
        link.closest("ul")?.parentElement?.classList.add("selected");
      }

      for (const item of document.querySelectorAll(
        ".mini-nav .mini-nav-item",
      )) {
        if (!(item instanceof HTMLLIElement)) {
          continue;
        }

        item.querySelector('a[href="javascript:"]') &&
          item.addEventListener("click", () => {
            const id = item.id;

            for (const navItem of document.querySelectorAll(
              ".mini-nav .mini-nav-item",
            )) {
              navItem.classList.remove("selected");
            }

            item.classList.add("selected");

            for (const nav of document.querySelectorAll(".sidebarmenu nav")) {
              nav.classList.remove("d-block");
            }

            document
              .getElementById(`menu-right-${id}`)
              ?.classList.add("d-block");
            document.body.setAttribute("data-sidebartype", "full");
          });
      }
    }
  });
}

if (at === "horizontal") {
  function findMatchingElement() {
    const currentUrl = window.location.href;
    const anchors = document.querySelectorAll("#sidebarnavh ul#sidebarnav a");
    for (let i = 0; i < anchors.length; i++) {
      const anchor = anchors[i];

      if (!(anchor instanceof HTMLAnchorElement)) {
        continue;
      }

      if (anchor.href === currentUrl) {
        return anchor;
      }
    }

    return null; // Return null if no matching element is found
  }

  const elements = findMatchingElement();

  if (elements) {
    elements.classList.add("active");
  }

  for (const link of document.querySelectorAll(
    "#sidebarnavh ul#sidebarnav a.active",
  )) {
    link.closest("a")?.parentElement?.classList.add("selected");
    link.closest("ul")?.parentElement?.classList.add("selected");
  }
}

// ----------------------------------------
// Active 2 file at same time
// ----------------------------------------
const currentURL =
  window.location !== window.parent.location
    ? document.referrer
    : document.location.href;

const link = document.getElementById("get-url") || document.createElement("a");

if (currentURL.includes("/main/index.html")) {
  link.setAttribute("href", "../main/index.html");
} else if (currentURL.includes("/index.html")) {
  link.setAttribute("href", "./index.html");
} else {
  link.setAttribute("href", "./");
}
