<x-app-layout>
    <div class="review-page">
        <div class="review-page__inner">
            <a href="{{ route('home') }}" class="review-back"><span aria-hidden="true">←</span> Kembali ke peta</a>

            <header class="review-hero">
                <div class="review-hero__copy">
                    <span class="review-eyebrow">PENGALAMAN KOMUNITAS</span>
                    <h1>Ulasan tempat</h1>
                    <h2>{{ $place->name }}</h2>
                    <p>{{ $place->description ?: 'Bagikan pengalamanmu dan bantu pengunjung lain menemukan tempat menarik.' }}</p>
                    <span class="review-category">{{ ucfirst($place->category) }}</span>
                </div>
                <div class="review-score" aria-label="{{ number_format((float) $reviews->avg('rating'), 1) }} dari 5 dari {{ $reviews->count() }} ulasan">
                    <span class="review-score__number">{{ $reviews->count() ? number_format((float) $reviews->avg('rating'), 1) : '—' }}</span>
                    <span class="review-score__stars" aria-hidden="true">★★★★★</span>
                    <span class="review-score__caption">{{ $reviews->count() }} ulasan</span>
                </div>
            </header>

            @if (session('status'))
                <div class="review-notice" role="status"><span aria-hidden="true">✓</span> {{ session('status') }}</div>
            @endif

            <div class="review-columns">
                <section class="review-panel review-form-panel">
                    <div class="review-panel__heading">
                        <span class="review-panel__icon" aria-hidden="true">✎</span>
                        <div>
                            <h2>{{ $myReview ? 'Perbarui ulasanmu' : 'Bagikan pengalaman' }}</h2>
                            <p>Rating dan ulasanmu membantu komunitas.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('places.reviews.store', $place) }}" class="review-form">
                        @csrf
                        <fieldset>
                            <legend>Seberapa kamu merekomendasikan tempat ini?</legend>
                            <div class="review-rating" role="radiogroup" aria-label="Pilih rating bintang">
                                @for ($star = 1; $star <= 5; $star++)
                                    <label class="review-rating__option">
                                        <input type="radio" name="rating" value="{{ $star }}" @checked((string) old('rating', $myReview?->rating) === (string) $star) required>
                                        <span aria-hidden="true">★</span>
                                        <span class="sr-only">{{ $star }} bintang</span>
                                    </label>
                                @endfor
                                <span class="review-rating__hint" id="rating-hint">Pilih 1–5 bintang</span>
                            </div>
                            @error('rating')<p class="review-error">{{ $message }}</p>@enderror
                        </fieldset>

                        <div class="review-field">
                            <label for="review">Ceritakan pengalamanmu</label>
                            <textarea id="review" name="review" rows="5" maxlength="2000" required placeholder="Apa yang kamu sukai? Bagaimana suasana atau pelayanannya?">{{ old('review', $myReview?->review) }}</textarea>
                            <div class="review-field__meta"><span>Ulasan akan terlihat oleh pengunjung lain.</span><span id="review-char-count">0 / 2000</span></div>
                            @error('review')<p class="review-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="review-submit"><span aria-hidden="true">➤</span> {{ $myReview ? 'Simpan perubahan' : 'Kirim ulasan' }}</button>
                    </form>
                </section>

                <section class="review-panel review-list-panel">
                    <div class="review-panel__heading review-list-panel__heading">
                        <span class="review-panel__icon" aria-hidden="true">☆</span>
                        <div>
                            <h2>Ulasan pengunjung</h2>
                            <p>{{ $reviews->count() }} {{ $reviews->count() === 1 ? 'pengalaman' : 'pengalaman' }} dibagikan</p>
                        </div>
                    </div>

                    <div class="review-list">
                        @forelse ($reviews as $review)
                            <article class="review-item">
                                <div class="review-item__top">
                                    <div class="review-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($review->user->name, 0, 1)) }}</div>
                                    <div class="review-item__identity">
                                        <h3>{{ $review->user->name }}</h3>
                                        <time datetime="{{ $review->updated_at->toDateString() }}">{{ $review->updated_at->format('d M Y') }}</time>
                                    </div>
                                    <span class="review-item__rating" aria-label="{{ $review->rating }} dari 5 bintang">★ {{ $review->rating }}.0</span>
                                </div>
                                <p>{{ $review->review }}</p>
                            </article>
                        @empty
                            <div class="review-empty">
                                <span aria-hidden="true">✧</span>
                                <h3>Belum ada ulasan</h3>
                                <p>Jadilah orang pertama yang berbagi pengalaman di tempat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ratingInputs = [...document.querySelectorAll('.review-rating input')];
                const ratingHint = document.getElementById('rating-hint');
                const updateRating = () => {
                    const selected = Number(document.querySelector('.review-rating input:checked')?.value || 0);
                    ratingInputs.forEach((input) => input.closest('label').classList.toggle('is-active', Number(input.value) <= selected));
                    if (ratingHint && selected) ratingHint.textContent = `${selected} dari 5 bintang`;
                };
                ratingInputs.forEach((input) => input.addEventListener('change', updateRating));
                updateRating();

                const textarea = document.getElementById('review');
                const counter = document.getElementById('review-char-count');
                const updateCount = () => { if (textarea && counter) counter.textContent = `${textarea.value.length} / 2000`; };
                textarea?.addEventListener('input', updateCount);
                updateCount();
            });
        </script>
    @endpush
</x-app-layout>
