import tema from "../globales/tema";

export function tooltip(element: HTMLElement) {
  let div: HTMLDivElement;
  let title: string | null;

  function mouseOver(event: MouseEvent) {
    // N0TE: remove the `title` attribute, to prevent showing the default browser tooltip
    // remember to set it back on `mouseleave`
    title = element.getAttribute("title");
    element.removeAttribute("title");

    div = document.createElement("div");
    div.textContent = title;

    div.style.border = "thin solid #ddd";
    div.style.boxShadow = "1px 1px 1px #ddd";
    div.style.borderRadius = '4px'
    div.style.padding = '4px'
    div.style.position = 'absolute'
    div.style.top = `${event.pageX + 5}px`
    div.style.left = `${event.pageY + 5}px`
    div.style.zIndex = Number.MAX_SAFE_INTEGER.toString()

    tema.subscribe((tema) => {
      div.classList.add(`bg-${tema}`);
    });

    document.body.appendChild(div);
  }

  function mouseMove(event: MouseEvent) {
    div.style.left = `${event.pageX + 5}px`;
    div.style.top = `${event.pageY + 5}px`;
  }

  function mouseLeave() {
    document.body.removeChild(div);
    // N0TE: restore the `title` attribute
    element.setAttribute("title", title || "");
  }

  element.addEventListener("mouseover", mouseOver);
  element.addEventListener("mouseleave", mouseLeave);
  element.addEventListener("mousemove", mouseMove);

  return {
    destroy() {
      element.removeEventListener("mouseover", mouseOver);
      element.removeEventListener("mouseleave", mouseLeave);
      element.removeEventListener("mousemove", mouseMove);
    },
  };
}
