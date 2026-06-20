<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Книжная Галерея</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/welcome.css?v={{ time() }}">
</head>
<body>

    
    <header>
        <div class="container">
            <a href="/" class="logo-link">
                <span class="brand-title">Книжная Галерея</span>
            </a>

            
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="global-search" class="search-input" placeholder="Поиск книг по названию...">
            </div>

            
            <div class="btns" id="header-auth-actions">
                
            </div>
        </div>
    </header>

    
    <div class="main-container">
        
        <aside class="filters-sidebar" id="filters-sidebar">
            <div class="filter-section">
                <div class="filter-title">Жанры</div>
                <div class="filter-options" id="filter-genres">
                    
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Авторы</div>
                <div class="filter-options" id="filter-authors">
                    
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Издательства</div>
                <div class="filter-options" id="filter-publishers">
                    
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Тип обложки</div>
                <div class="filter-options" id="filter-covers">
                    
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Возрастные лимиты</div>
                <div class="filter-options" id="filter-age-limits">
                    
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Цена</div>
                <div class="range-inputs">
                    <input type="number" id="price-from" class="range-field" placeholder="от" min="0">
                    <input type="number" id="price-to" class="range-field" placeholder="до" min="0">
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Год издания</div>
                <div class="range-inputs">
                    <input type="number" id="year-from" class="range-field" placeholder="от" min="0">
                    <input type="number" id="year-to" class="range-field" placeholder="до" min="0">
                </div>
            </div>
            
            <div class="filter-section" style="border: none;">
                <button class="request-btn w-full" id="btn-apply-filters">Применить фильтры</button>
            </div>
        </aside>

        
        <main class="catalog-container">
            <div class="catalog-header">
                <div class="catalog-title" id="catalog-title-text">Все книги</div>
                
                <div class="catalog-controls">
                    <select class="sort-select" id="sort-control">
                        <option value="">Сортировка по умолчанию</option>
                        <option value="year_desc">Сначала новые (год)</option>
                        <option value="year_asc">Сначала старые (год)</option>
                        <option value="price_asc">Сначала дешевые</option>
                        <option value="price_desc">Сначала дорогие</option>
                        <option value="reviews_count_desc">По количеству отзывов</option>
                    </select>
                </div>
            </div>

            
            <div class="books-grid" id="books-grid">
                
            </div>

            
            <div class="pagination-container" id="pagination-controls">
                
            </div>
        </main>
    </div>

    
    <footer>
        <div class="container">
            <div class="logo-footer">
                <span>Книжная Галерея</span>
            </div>
            <div class="footer-info">
                &copy; 2026 Книжная Галерея. Все права защищены.
            </div>
            <div class="btns">
                <a href="/" class="btn">Каталог</a>
                <a href="#" onclick="openModal('auth-modal'); return false;" class="btn">Личный кабинет</a>
            </div>
        </div>
    </footer>

    

    
    <div class="modal-overlay" id="book-detail-modal">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('book-detail-modal')">✕</button>
            <div id="book-detail-content">
                
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="auth-modal">
        <div class="modal-container auth-modal">
            <button class="modal-close" onclick="closeModal('auth-modal')">✕</button>
            <div class="auth-title">Вход в кабинет</div>
            
            <div class="auth-form">
                
                <div class="auth-step active" id="auth-step-1">
                    <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 5px;">Введите ваш Email или номер телефона:</div>
                    <input type="text" id="auth-credential" class="form-input" placeholder="email@example.com или +7...">
                    <button class="request-btn" id="btn-auth-request" style="justify-content: center; margin-top: 10px;">Получить код</button>
                </div>

                
                <div class="auth-step" id="auth-step-2">
                    <div class="debug-code-box" id="auth-debug-box">
                        Код отправлен! Тестовый код: <span id="debug-code-value">xxxx</span>
                    </div>
                    <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 5px; margin-top: 10px;">Введите 4-значный код подтверждения:</div>
                    <input type="text" id="auth-code" class="form-input text-center" placeholder="0000" maxlength="4" style="font-size: 20px; letter-spacing: 4px;">
                    <button class="request-btn" id="btn-auth-verify" style="justify-content: center; margin-top: 10px;">Войти</button>
                    <button class="btn" id="btn-auth-back" style="justify-content: center; margin-top: 5px; border: none; font-size: 13px;">Вернуться назад</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="profile-modal">
        <div class="modal-container profile-modal">
            <button class="modal-close" onclick="closeModal('profile-modal')">✕</button>
            <div class="auth-title" style="margin-bottom: 25px;">Редактирование профиля</div>
            <div class="auth-form" id="profile-form">
                <div>
                    <label style="font-size: 13px; color: var(--text-muted); display: block; margin-bottom: 5px;">Имя</label>
                    <input type="text" id="profile-first-name" class="form-input">
                </div>
                <div>
                    <label style="font-size: 13px; color: var(--text-muted); display: block; margin-bottom: 5px;">Фамилия</label>
                    <input type="text" id="profile-last-name" class="form-input">
                </div>
                <div>
                    <label style="font-size: 13px; color: var(--text-muted); display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" id="profile-email" class="form-input">
                </div>
                <div>
                    <label style="font-size: 13px; color: var(--text-muted); display: block; margin-bottom: 5px;">Телефон</label>
                    <input type="text" id="profile-phone" class="form-input">
                </div>
                <button class="request-btn" id="btn-save-profile" style="margin-top: 20px; justify-content: center;">Сохранить профиль</button>
            </div>
        </div>
    </div>

    
    <div class="toast-container" id="toast-container"></div>

    
    <script>

        let authToken = localStorage.getItem('auth_token') || null;
        let currentUser = null;
        let currentBookPage = 1;
        let activeFilters = {
            genres: [],
            authors: [],
            publishers: [],
            covers: [],
            ageLimits: [],
            priceFrom: null,
            priceTo: null,
            yearFrom: null,
            yearTo: null,
            sort: null,
            search: '',
            favoritesOnly: false
        };

        let availableFilters = {};

        document.addEventListener('DOMContentLoaded', () => {
            initApp();
        });

        async function initApp() {
            updateHeaderAuth();
            if (authToken) {
                await fetchProfile();
            }
            await fetchSidebarFilters();
            await fetchBooks();
            setupEventListeners();
        }

        function updateHeaderAuth() {
            const container = document.getElementById('header-auth-actions');
            if (authToken) {
                container.innerHTML = `
                    <button class="btn" id="btn-favorites-toggle" onclick="toggleFavoritesOnlyView()">
                        ❤️ Избранное
                    </button>
                    <button class="btn" onclick="openProfileModal()">
                        👤 ${currentUser ? (currentUser.first_name || currentUser.email || currentUser.phone) : 'Кабинет'}
                    </button>
                    <button class="request" onclick="handleLogout()">Выйти</button>
                `;
                
                const favBtn = document.getElementById('btn-favorites-toggle');
                if (favBtn && activeFilters.favoritesOnly) {
                    favBtn.style.color = '#fbbf24';
                    favBtn.style.fontWeight = 'bold';
                }
            } else {
                container.innerHTML = `
                    <button class="request" onclick="openModal('auth-modal')">Войти</button>
                `;
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <span>${type === 'success' ? '🟢' : '🔴'}</span>
                <div>${message}</div>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 50);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        async function apiFetch(endpoint, options = {}) {
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...options.headers
            };

            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`;
            }

            const response = await fetch(endpoint, {
                ...options,
                headers
            });

            if (response.status === 401) {
                authToken = null;
                localStorage.removeItem('auth_token');
                currentUser = null;
                updateHeaderAuth();
                showToast('Сессия истекла. Войдите заново.', 'error');
                return null;
            }

            if (!response.ok) {
                const errData = await response.json().catch(() => ({}));
                throw new Error(errData.message || `API Error: ${response.status}`);
            }

            return response.json();
        }

        async function fetchProfile() {
            try {
                currentUser = await apiFetch('/api/profile');
                updateHeaderAuth();
            } catch (err) {
                console.error(err);
                authToken = null;
                localStorage.removeItem('auth_token');
                currentUser = null;
                updateHeaderAuth();
            }
        }

        function toggleFavoritesOnlyView() {
            activeFilters.favoritesOnly = !activeFilters.favoritesOnly;
            currentBookPage = 1;
            
            const titleText = document.getElementById('catalog-title-text');
            if (activeFilters.favoritesOnly) {
                titleText.innerText = 'Мои избранные книги';
            } else {
                titleText.innerText = 'Все книги';
            }
            
            updateHeaderAuth();
            fetchBooks();
        }

        async function fetchBooks() {
            try {
                let url = '/api/books';
                if (activeFilters.favoritesOnly) {
                    url = '/api/favorites';
                }

                const params = new URLSearchParams();
                params.append('page', currentBookPage);

                if (activeFilters.sort) params.append('sort', activeFilters.sort);
                if (activeFilters.search) params.append('search', activeFilters.search);
                
                if (!activeFilters.favoritesOnly) {
                    activeFilters.genres.forEach(id => params.append('genre_id[]', id));
                    activeFilters.authors.forEach(id => params.append('author_id[]', id));
                    activeFilters.publishers.forEach(p => params.append('publisher[]', p));
                    activeFilters.covers.forEach(c => params.append('cover_type[]', c));
                    activeFilters.ageLimits.forEach(a => params.append('age_limit[]', a));

                    if (activeFilters.priceFrom) params.append('price_from', activeFilters.priceFrom);
                    if (activeFilters.priceTo) params.append('price_to', activeFilters.priceTo);
                    if (activeFilters.yearFrom) params.append('year_from', activeFilters.yearFrom);
                    if (activeFilters.yearTo) params.append('year_to', activeFilters.yearTo);
                }

                const queryStr = params.toString();
                const response = await apiFetch(url + (queryStr ? '?' + queryStr : ''));

                if (response) {
                    renderBooksGrid(response.data || []);
                    renderPagination(response);
                }
            } catch (err) {
                showToast('Ошибка при загрузке книг: ' + err.message, 'error');
            }
        }

        function renderBooksGrid(books) {
            const grid = document.getElementById('books-grid');
            if (books.length === 0) {
                grid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--text-muted);">
                        Книги не найдены
                    </div>
                `;
                return;
            }

            grid.innerHTML = books.map(book => {
                const coverUrl = book.images && book.images[0] ? book.images[0] : 'https://placehold.co/400x600/181824/FFF?text=' + encodeURIComponent(book.title);
                const isFav = currentUser && currentUser.favorite_book_ids && currentUser.favorite_book_ids.includes(book.id);
                const rating = book.reviews_avg_rating ? parseFloat(book.reviews_avg_rating).toFixed(1) : 'Нет';

                return `
                    <div class="book-card" onclick="openBookDetails(${book.id})">
                        <div class="book-cover-container">
                            <img src="${coverUrl}" class="book-cover" alt="${book.title}" loading="lazy">
                            ${book.year ? `<span class="book-badge-year">${book.year}</span>` : ''}
                            ${authToken ? `
                                <button class="book-favorite-btn ${isFav ? 'active' : ''}" 
                                        onclick="event.stopPropagation(); handleFavoriteToggle(${book.id}, this)">
                                    ❤️
                                </button>
                            ` : ''}
                        </div>
                        <div class="book-info">
                            <div class="book-title" title="${book.title}">${book.title}</div>
                            <div class="book-author">
                                ${book.author ? `${book.author.first_name || ''} ${book.author.last_name || ''}`.trim() : 'Неизвестный автор'}
                            </div>
                            <div class="book-rating">
                                <span class="star-icon">★</span>
                                <span>${rating} ${book.reviews_count ? `(${book.reviews_count})` : ''}</span>
                            </div>
                            <div class="book-footer">
                                <span class="book-price">${book.price} ₽</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-controls');
            if (!data.last_page || data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            html += `<button class="btn" ${data.current_page === 1 ? 'disabled' : ''} onclick="changePage(${data.current_page - 1})">Назад</button>`;
            
            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    html += `<button class="request-btn" style="border-radius: 8px;">${i}</button>`;
                } else {
                    html += `<button class="btn" onclick="changePage(${i})">${i}</button>`;
                }
            }

            html += `<button class="btn" ${data.current_page === data.last_page ? 'disabled' : ''} onclick="changePage(${data.current_page + 1})">Вперед</button>`;

            container.innerHTML = html;
        }

        function changePage(page) {
            currentBookPage = page;
            fetchBooks();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function fetchSidebarFilters() {
            try {
                const params = new URLSearchParams();
                activeFilters.genres.forEach(id => params.append('genre_id[]', id));
                activeFilters.authors.forEach(id => params.append('author_id[]', id));
                activeFilters.publishers.forEach(p => params.append('publisher[]', p));
                activeFilters.covers.forEach(c => params.append('cover_type[]', c));
                activeFilters.ageLimits.forEach(a => params.append('age_limit[]', a));
                
                const response = await apiFetch('/api/books/filters' + (params.toString() ? '?' + params.toString() : ''));
                if (response) {
                    availableFilters = response;
                    renderSidebarFilters();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderSidebarFilters() {
            const genresContainer = document.getElementById('filter-genres');
            const authorsContainer = document.getElementById('filter-authors');
            const publishersContainer = document.getElementById('filter-publishers');
            const coversContainer = document.getElementById('filter-covers');
            const ageLimitsContainer = document.getElementById('filter-age-limits');

            if (availableFilters.genres) {
                genresContainer.innerHTML = availableFilters.genres.map(genre => `
                    <label class="filter-checkbox-label">
                        <input type="checkbox" class="filter-checkbox" value="${genre.id}" data-type="genres" 
                               ${activeFilters.genres.includes(genre.id.toString()) ? 'checked' : ''}>
                        <span>${genre.name}</span>
                    </label>
                `).join('');
            }

            if (availableFilters.authors) {
                authorsContainer.innerHTML = availableFilters.authors.map(author => {
                    const fullName = `${author.first_name || ''} ${author.last_name || ''}`.trim();
                    return `
                        <label class="filter-checkbox-label">
                            <input type="checkbox" class="filter-checkbox" value="${author.id}" data-type="authors"
                                   ${activeFilters.authors.includes(author.id.toString()) ? 'checked' : ''}>
                            <span>${fullName}</span>
                        </label>
                    `;
                }).join('');
            }

            if (availableFilters.publishers) {
                publishersContainer.innerHTML = availableFilters.publishers.map(pub => `
                    <label class="filter-checkbox-label">
                        <input type="checkbox" class="filter-checkbox" value="${pub}" data-type="publishers"
                               ${activeFilters.publishers.includes(pub) ? 'checked' : ''}>
                        <span>${pub}</span>
                    </label>
                `).join('');
            }

            if (availableFilters.cover_types) {
                coversContainer.innerHTML = availableFilters.cover_types.map(cover => `
                    <label class="filter-checkbox-label">
                        <input type="checkbox" class="filter-checkbox" value="${cover}" data-type="covers"
                               ${activeFilters.covers.includes(cover) ? 'checked' : ''}>
                        <span>${cover}</span>
                    </label>
                `).join('');
            }

            if (availableFilters.age_limits) {
                ageLimitsContainer.innerHTML = availableFilters.age_limits.map(age => `
                    <label class="filter-checkbox-label">
                        <input type="checkbox" class="filter-checkbox" value="${age}" data-type="ageLimits"
                               ${activeFilters.ageLimits.includes(age) ? 'checked' : ''}>
                        <span>${age}</span>
                    </label>
                `).join('');
            }
        }

        async function handleFavoriteToggle(bookId, element) {
            try {
                const isActive = element.classList.contains('active');
                if (isActive) {
                    await apiFetch(`/api/favorites/${bookId}`, { method: 'DELETE' });
                    element.classList.remove('active');
                    showToast('Книга удалена из избранного');
                    if (currentUser && currentUser.favorite_book_ids) {
                        currentUser.favorite_book_ids = currentUser.favorite_book_ids.filter(id => id !== bookId);
                    }
                } else {
                    await apiFetch('/api/favorites', {
                        method: 'POST',
                        body: JSON.stringify({ book_id: bookId })
                    });
                    element.classList.add('active');
                    showToast('Книга добавлена в избранное');
                    if (currentUser) {
                        if (!currentUser.favorite_book_ids) currentUser.favorite_book_ids = [];
                        currentUser.favorite_book_ids.push(bookId);
                    }
                }
                
                if (activeFilters.favoritesOnly) {
                    fetchBooks();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        let activeBookId = null;
        async function openBookDetails(bookId) {
            try {
                activeBookId = bookId;
                const bookRecord = await fetchSingleBookData(bookId);
                if (!bookRecord) return;

                renderBookDetails(bookRecord);
                openModal('book-detail-modal');
            } catch (err) {
                showToast('Ошибка: ' + err.message, 'error');
            }
        }

        async function fetchSingleBookData(bookId) {
            const response = await apiFetch(`/api/books/${bookId}`);
            return response || null;
        }

        function renderBookDetails(book) {
            const content = document.getElementById('book-detail-content');
            const coverUrl = book.images && book.images[0] ? book.images[0] : 'https://placehold.co/400x600/181824/FFF?text=' + encodeURIComponent(book.title);
            const rating = book.reviews_avg_rating ? parseFloat(book.reviews_avg_rating).toFixed(1) : 'Нет оценки';

            content.innerHTML = `
                <div class="detail-layout">
                    <div class="detail-cover-container">
                        <img src="${coverUrl}" class="detail-cover" alt="${book.title}">
                    </div>
                    <div class="detail-content">
                        <div class="detail-title">${book.title}</div>
                        <div class="detail-author">
                            ${book.author ? `${book.author.first_name || ''} ${book.author.last_name || ''}`.trim() : 'Неизвестный автор'}
                        </div>
                        <div class="detail-meta-grid">
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Жанр</span>
                                <span>${book.genre ? book.genre.name : 'Без жанра'}</span>
                            </div>
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Издательство</span>
                                <span>${book.publisher || '—'}</span>
                            </div>
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Количество страниц</span>
                                <span>${book.pages || '—'}</span>
                            </div>
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Тип обложки</span>
                                <span>${book.cover_type || '—'}</span>
                            </div>
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Размер</span>
                                <span>${book.size || '—'}</span>
                            </div>
                            <div class="detail-meta-item">
                                <span class="detail-meta-label">Возрастные ограничения</span>
                                <span>${book.age_limit || '—'}</span>
                            </div>
                        </div>
                        <div class="detail-description">
                            ${book.description || 'Описание отсутствует.'}
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: white; margin-top: 15px;">
                            Цена: ${book.price} ₽
                        </div>
                    </div>
                </div>

                
                <div class="reviews-section">
                    <div class="reviews-header">
                        <h3 style="font-size: 20px; font-weight: 700;">Отзывы и оценки</h3>
                        <div style="font-size: 16px; font-weight: 700; color: #fbbf24;">★ ${rating} (${book.reviews_count || 0} отз.)</div>
                    </div>

                    ${authToken ? `
                        <form class="review-form" id="add-review-form" onsubmit="submitReview(event, ${book.id})">
                            <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 5px;">Оставить отзыв</h4>
                            <div class="form-row">
                                <div>
                                    <label style="font-size: 13px; color: var(--text-muted); display: block; margin-bottom: 5px;">Оценка</label>
                                    <select class="form-input" id="review-rating-input" required>
                                        <option value="5">★★★★★ (5)</option>
                                        <option value="4">★★★★☆ (4)</option>
                                        <option value="3">★★★☆☆ (3)</option>
                                        <option value="2">★★☆☆☆ (2)</option>
                                        <option value="1">★☆☆☆☆ (1)</option>
                                    </select>
                                </div>
                                <div style="display: flex; align-items: flex-end; padding-bottom: 5px;">
                                    <label class="filter-checkbox-label">
                                        <input type="checkbox" id="review-anonymous-input" class="filter-checkbox">
                                        <span>Оставить отзыв анонимно</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-row">
                                <input type="text" id="review-pros-input" class="form-input" placeholder="Плюсы">
                                <input type="text" id="review-cons-input" class="form-input" placeholder="Минусы">
                            </div>
                            <textarea id="review-comment-input" class="form-input" rows="3" placeholder="Ваш комментарий..." required></textarea>
                            <button type="submit" class="request-btn" style="align-self: flex-end;">Отправить отзыв</button>
                        </form>
                    ` : `
                        <div style="text-align: center; padding: 15px; background: rgba(255,255,255,0.02); border: 2px dashed var(--border-color); border-radius: 12px; font-size: 14px; color: var(--text-muted);">
                            <a href="#" onclick="openModal('auth-modal'); return false;" style="color: var(--primary-hover); text-decoration: underline;">Войдите</a>, чтобы оставить отзыв.
                        </div>
                    `}

                    
                    <div id="reviews-list-box" style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                        ${book.reviews && book.reviews.length > 0 ? renderReviewsListHtml(book.reviews) : `
                            <div style="text-align: center; color: var(--text-muted); font-size: 14px; padding: 30px;">
                                Отзывов еще нет. Будьте первыми!
                            </div>
                        `}
                    </div>
                </div>
            `;
        }

        function renderReviewsListHtml(reviews) {
            return reviews.map(review => {
                const userDisplayName = review.is_anonymous ? 'Анонимный читатель' : (review.author_name || 'Пользователь');
                const ratingStars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                
                let comment = review.comment || '';
                let pros = '';
                let cons = '';
                
                const prosMatch = comment.match(/\[Плюсы\]:\s*(.*?)(?=\n\[Минусы\]:|$)/is);
                if (prosMatch) {
                    pros = prosMatch[1].trim();
                    comment = comment.replace(/\[Плюсы\]:\s*.*?(?=\n\[Минусы\]:|$)/is, '');
                }
                
                const consMatch = comment.match(/\[Минусы\]:\s*(.*?)$/is);
                if (consMatch) {
                    cons = consMatch[1].trim();
                    comment = comment.replace(/\[Минусы\]:\s*.*?$/is, '');
                }
                
                comment = comment.trim();
                
                const isLiked = review.user_rating === 1;
                const isDisliked = review.user_rating === -1;
                
                return `
                    <div class="review-card">
                        <div class="review-user-row">
                            <span class="review-user">${userDisplayName}</span>
                            <span class="review-rating">${ratingStars}</span>
                        </div>
                        
                        ${(pros || cons) ? `
                            <div class="review-pros-cons">
                                ${pros ? `<div class="review-pro"><strong>Плюсы:</strong> ${pros}</div>` : ''}
                                ${cons ? `<div class="review-con"><strong>Минусы:</strong> ${cons}</div>` : ''}
                            </div>
                        ` : ''}

                        <div class="review-comment">${comment || ''}</div>
                        
                        <div class="review-actions">
                            <button class="review-like-btn ${isLiked ? 'active-like' : ''}" onclick="handleReviewLike(${review.id}, 1, this)">
                                👍 <span class="like-count">${review.likes_count || 0}</span>
                            </button>
                            <button class="review-like-btn ${isDisliked ? 'active-dislike' : ''}" onclick="handleReviewLike(${review.id}, -1, this)">
                                👎 <span class="dislike-count">${review.dislikes_count || 0}</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function setupEventListeners() {
            const searchInput = document.getElementById('global-search');
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    activeFilters.search = e.target.value;
                    currentBookPage = 1;
                    fetchBooks();
                }, 300);
            });

            document.getElementById('sort-control').addEventListener('change', (e) => {
                activeFilters.sort = e.target.value;
                currentBookPage = 1;
                fetchBooks();
            });

            document.getElementById('btn-apply-filters').addEventListener('click', () => {
                activeFilters.priceFrom = document.getElementById('price-from').value || null;
                activeFilters.priceTo = document.getElementById('price-to').value || null;
                activeFilters.yearFrom = document.getElementById('year-from').value || null;
                activeFilters.yearTo = document.getElementById('year-to').value || null;
                
                activeFilters.genres = Array.from(document.querySelectorAll('.filter-checkbox[data-type="genres"]:checked')).map(el => el.value);
                activeFilters.authors = Array.from(document.querySelectorAll('.filter-checkbox[data-type="authors"]:checked')).map(el => el.value);
                activeFilters.publishers = Array.from(document.querySelectorAll('.filter-checkbox[data-type="publishers"]:checked')).map(el => el.value);
                activeFilters.covers = Array.from(document.querySelectorAll('.filter-checkbox[data-type="covers"]:checked')).map(el => el.value);
                activeFilters.ageLimits = Array.from(document.querySelectorAll('.filter-checkbox[data-type="ageLimits"]:checked')).map(el => el.value);

                currentBookPage = 1;
                fetchSidebarFilters();
                fetchBooks();
                showToast('Фильтры успешно применены');
            });

            document.getElementById('btn-auth-request').addEventListener('click', async () => {
                const credField = document.getElementById('auth-credential');
                authCredential = credField.value.trim();
                if (!authCredential) {
                    showToast('Введите Email или Телефон', 'error');
                    return;
                }

                try {
                    const body = {};
                    if (authCredential.includes('@')) {
                        body.email = authCredential;
                    } else {
                        body.phone = authCredential;
                    }

                    const response = await apiFetch('/api/login', {
                        method: 'POST',
                        body: JSON.stringify(body)
                    });

                    if (response) {
                        showToast(response.message || 'Код авторизации отправлен');
                        
                        document.getElementById('auth-step-1').classList.remove('active');
                        document.getElementById('auth-step-2').classList.add('active');
                        
                        if (response.debug_code) {
                            document.getElementById('debug-code-value').innerText = response.debug_code;
                            document.getElementById('auth-debug-box').style.display = 'block';
                        } else {
                            document.getElementById('auth-debug-box').style.display = 'none';
                        }
                    }
                } catch (err) {
                    showToast(err.message, 'error');
                }
            });

            document.getElementById('btn-auth-back').addEventListener('click', () => {
                document.getElementById('auth-step-2').classList.remove('active');
                document.getElementById('auth-step-1').classList.add('active');
            });

            document.getElementById('btn-auth-verify').addEventListener('click', async () => {
                const codeField = document.getElementById('auth-code');
                const code = codeField.value.trim();
                if (!code || code.length !== 4) {
                    showToast('Введите 4-значный код', 'error');
                    return;
                }

                try {
                    const body = { code };
                    if (authCredential.includes('@')) {
                        body.email = authCredential;
                    } else {
                        body.phone = authCredential;
                    }

                    const response = await apiFetch('/api/login/verify', {
                        method: 'POST',
                        body: JSON.stringify(body)
                    });

                    if (response && response.access_token) {
                        authToken = response.access_token;
                        localStorage.setItem('auth_token', authToken);
                        
                        codeField.value = '';
                        document.getElementById('auth-credential').value = '';
                        document.getElementById('auth-step-2').classList.remove('active');
                        document.getElementById('auth-step-1').classList.add('active');
                        
                        closeModal('auth-modal');
                        showToast('Вы успешно вошли в систему');

                        await fetchProfile();
                        await fetchBooks();
                    }
                } catch (err) {
                    showToast(err.message, 'error');
                }
            });

            document.getElementById('btn-save-profile').addEventListener('click', async () => {
                const firstName = document.getElementById('profile-first-name').value.trim();
                const lastName = document.getElementById('profile-last-name').value.trim();
                const email = document.getElementById('profile-email').value.trim();
                const phone = document.getElementById('profile-phone').value.trim();

                try {
                    const response = await apiFetch('/api/profile', {
                        method: 'PUT',
                        body: JSON.stringify({
                            first_name: firstName,
                            last_name: lastName,
                            email,
                            phone
                        })
                    });

                    if (response) {
                        showToast(response.message || 'Профиль изменен');
                        currentUser = response.user;
                        updateHeaderAuth();
                        closeModal('profile-modal');
                    }
                } catch (err) {
                    showToast(err.message, 'error');
                }
            });
        }

        function openProfileModal() {
            if (!currentUser) return;
            document.getElementById('profile-first-name').value = currentUser.first_name || '';
            document.getElementById('profile-last-name').value = currentUser.last_name || '';
            document.getElementById('profile-email').value = currentUser.email || '';
            document.getElementById('profile-phone').value = currentUser.phone || '';
            openModal('profile-modal');
        }

        async function submitReview(event, bookId) {
            event.preventDefault();
            const rating = document.getElementById('review-rating-input').value;
            const pros = document.getElementById('review-pros-input').value.trim();
            const cons = document.getElementById('review-cons-input').value.trim();
            const comment = document.getElementById('review-comment-input').value.trim();
            const isAnonymous = document.getElementById('review-anonymous-input').checked;

            try {
                const response = await apiFetch('/api/reviews', {
                    method: 'POST',
                    body: JSON.stringify({
                        book_id: bookId,
                        rating,
                        pros,
                        cons,
                        comment,
                        is_anonymous: isAnonymous ? 1 : 0
                    })
                });

                if (response) {
                    showToast('Отзыв успешно добавлен!');
                    openBookDetails(bookId);
                    fetchBooks();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function handleReviewLike(reviewId, direction, element) {
            if (!authToken) {
                showToast('Войдите, чтобы оценивать отзывы', 'error');
                return;
            }

            try {
                const response = await apiFetch(`/api/reviews/${reviewId}/rate`, {
                    method: 'POST',
                    body: JSON.stringify({
                        rating: direction
                    })
                });

                if (response) {
                    showToast(response.message || 'Оценка принята');
                    if (activeBookId) {
                        openBookDetails(activeBookId);
                    }
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function handleLogout() {
            try {
                await apiFetch('/api/logout', { method: 'POST' });
            } catch (err) {
                console.error(err);
            }
            
            authToken = null;
            localStorage.removeItem('auth_token');
            currentUser = null;
            activeFilters.favoritesOnly = false;
            
            const titleText = document.getElementById('catalog-title-text');
            titleText.innerText = 'Все книги';

            updateHeaderAuth();
            fetchBooks();
            showToast('Вы вышли из системы');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
    </script>
</body>
</html>
