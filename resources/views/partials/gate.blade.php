<div id="category-gate" class="category-gate" role="dialog" aria-modal="true" aria-label="Pilih kategori peta" hidden>
    <img class="category-gate__scenery" src="{{ asset('images/category-gate-scenery.svg') }}" alt="" aria-hidden="true">
    <span class="category-gate__petal category-gate__petal--one" aria-hidden="true">✿</span>
    <span class="category-gate__petal category-gate__petal--two" aria-hidden="true">✿</span>
    <span class="category-gate__petal category-gate__petal--three" aria-hidden="true">✿</span>
    <span class="category-gate__petal category-gate__petal--four" aria-hidden="true">✿</span>

    <div class="category-gate__stage">
        <button
            id="category-gate-logo"
            class="category-gate__logo"
            type="button"
            aria-expanded="false"
            aria-controls="category-gate-categories"
            aria-label="Tampilkan kategori"
        >
            <img src="{{ asset('images/nihonlink-logo.png.png') }}" alt="NihonLink" width="220" height="220">
        </button>

        <p class="category-gate__hint" id="category-gate-hint">Ketuk logo untuk melihat kategori</p>
        <div id="category-gate-categories" class="category-gate__categories" role="group" aria-label="Kategori tempat" aria-hidden="true"></div>

        <button id="category-gate-enter" class="category-gate__enter" type="button">
            Masuk ke Peta <span aria-hidden="true">→</span>
        </button>
    </div>

    <div class="category-gate__curtain category-gate__curtain--left" aria-hidden="true"></div>
    <div class="category-gate__curtain category-gate__curtain--right" aria-hidden="true"></div>
</div>
