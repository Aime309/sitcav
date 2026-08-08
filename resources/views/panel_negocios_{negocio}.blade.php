<x-estructura-panel
    titulo="{{ $negocio->nombre }}"
    :crumbs="['Inicio']"
    :usuario="$usuario"
    :negocio="$negocio">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow" id="heroDate">
                Saturday · April 25 · 2026
            </span>
            <h1 class="hero-title">
                Blank
                <span class="accent">canvas</span>
            </h1>
            <p class="hero-sub">
                A starter page with the 2026 shell, theme tokens, and animations all wired in. Drop your content into the empty card below — the rest is taken care of.
            </p>
        </div>
        <div class="hero-actions">
            <button class="btn btn--ghost">
                <svg viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <path d="M14 2v6h6" />
                </svg>
                Duplicate
            </button>
            <button class="btn btn--primary">
                <svg viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add section
            </button>
        </div>
    </section>
    <section
        class="card"
        style="
            min-height: 360px;
            align-items: center;
            justify-content: center
        ">
        <div
            style="
                text-align: center;
                color: var(--t-light);
                padding: 60px 20px
            ">
            <div style="
                width: 56px;
                height: 56px;
                margin: 0 auto 18px;
                border-radius: 14px;
                background: var(--bg-muted);
                color: var(--t-muted);
                display: grid;
                place-items: center
            ">
                <svg
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">
                    <path d="M12 5v14M5 12h14" />
                </svg>
            </div>
            <div style="
                font-family: 'Inter Tight', sans-serif;
                font-weight: 700;
                font-size: 18px;
                color: var(--t-base);
                letter-spacing: -.018em;
                margin-bottom: 6px
            ">
                Empty card
            </div>
            <div style="
                font-size: 13px;
                max-width: 36ch;
                margin: 0 auto
            ">
                Use this as a clean starting point for any new view — KPIs, tables, charts, anything.
            </div>
        </div>
    </section>
</x-estructura-panel>
