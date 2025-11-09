// Инициализация Supabase - ЗАМЕНИТЕ НА ВАШИ ДАННЫЕ
const supabaseUrl = 'https://rmgiakdzoxkxoinfhdnh.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJtZ2lha2R6b3hreG9pbmZoZG5oIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjIyOTY4NDYsImV4cCI6MjA3Nzg3Mjg0Nn0.QjYN4zfH_lZ3SszAXrUO-PKLPiTol77vNYZlZm0jHEE';
const supabase = supabase.createClient(supabaseUrl, supabaseKey);

// DOM элементы
const loginBtn = document.getElementById('loginBtn');
const logoutBtn = document.getElementById('logoutBtn');
const loginModal = document.getElementById('loginModal');
const registerModal = document.getElementById('registerModal');
const closeButtons = document.querySelectorAll('.close');
const showRegister = document.getElementById('showRegister');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const bookingForm = document.getElementById('bookingForm');
const reviewForm = document.getElementById('reviewForm');
const roomsGrid = document.getElementById('roomsGrid');
const availableRooms = document.getElementById('availableRooms');
const roomsList = document.getElementById('roomsList');
const myBookings = document.getElementById('myBookings');
const bookingsList = document.getElementById('bookingsList');
const reviewsList = document.getElementById('reviewsList');
const addReviewSection = document.getElementById('addReviewSection');

// Текущий пользователь
let currentUser = null;
let selectedRating = 5;

// Инициализация приложения
document.addEventListener('DOMContentLoaded', function() {
    checkAuth();
    loadRooms();
    loadReviews();
    setupEventListeners();
    setupDateInputs();
    setupRatingStars();
});

// Настройка обработчиков событий
function setupEventListeners() {
    // Модальные окна
    loginBtn.addEventListener('click', () => loginModal.style.display = 'block');
    logoutBtn.addEventListener('click', logout);
    
    showRegister.addEventListener('click', (e) => {
        e.preventDefault();
        loginModal.style.display = 'none';
        registerModal.style.display = 'block';
    });
    
    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            loginModal.style.display = 'none';
            registerModal.style.display = 'none';
        });
    });
    
    // Формы
    loginForm.addEventListener('submit', handleLogin);
    registerForm.addEventListener('submit', handleRegister);
    bookingForm.addEventListener('submit', checkAvailability);
    reviewForm.addEventListener('submit', handleReviewSubmit);
    
    // Закрытие модальных окон при клике вне их
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) loginModal.style.display = 'none';
        if (e.target === registerModal) registerModal.style.display = 'none';
    });
}

// Настройка звезд рейтинга
function setupRatingStars() {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.dataset.rating);
            selectedRating = rating;
            document.getElementById('rating').value = rating;
            
            stars.forEach(s => {
                s.classList.toggle('active', parseInt(s.dataset.rating) <= rating);
            });
        });
        
        // Установить начальный рейтинг 5
        if (parseInt(star.dataset.rating) <= 5) {
            star.classList.add('active');
        }
    });
}

// Настройка дат в форме бронирования
function setupDateInputs() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    document.getElementById('checkIn').min = today;
    document.getElementById('checkIn').value = today;
    document.getElementById('checkOut').min = tomorrowStr;
    document.getElementById('checkOut').value = tomorrowStr;
}

// Проверка авторизации
async function checkAuth() {
    const { data: { user } } = await supabase.auth.getUser();
    if (user) {
        currentUser = user;
        updateAuthUI();
        loadUserBookings();
    }
}

// Обновление UI в зависимости от авторизации
function updateAuthUI() {
    if (currentUser) {
        loginBtn.style.display = 'none';
        logoutBtn.style.display = 'inline-block';
        addReviewSection.style.display = 'block';
        myBookings.style.display = 'block';
    } else {
        loginBtn.style.display = 'inline-block';
        logoutBtn.style.display = 'none';
        addReviewSection.style.display = 'none';
        myBookings.style.display = 'none';
    }
}

// Вход пользователя
async function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    const { data, error } = await supabase.auth.signInWithPassword({
        email,
        password
    });
    
    if (error) {
        alert('Ошибка входа: ' + error.message);
    } else {
        currentUser = data.user;
        loginModal.style.display = 'none';
        updateAuthUI();
        loadUserBookings();
        alert('Добро пожаловать!');
    }
}

// Регистрация пользователя
async function handleRegister(e) {
    e.preventDefault();
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    const firstName = document.getElementById('regFirstName').value;
    const lastName = document.getElementById('regLastName').value;
    const phone = document.getElementById('regPhone').value;
    
    if (password.length < 6) {
        alert('Пароль должен содержать не менее 6 символов');
        return;
    }
    
    // Регистрация в Supabase Auth
    const { data: authData, error: authError } = await supabase.auth.signUp({
        email,
        password
    });
    
    if (authError) {
        alert('Ошибка регистрации: ' + authError.message);
        return;
    }
    
    // Создание записи в таблице users
    const { error: userError } = await supabase
        .from('users')
        .insert([
            {
                id: authData.user.id,
                email: email,
                first_name: firstName,
                last_name: lastName,
                phone: phone
            }
        ]);
    
    if (userError) {
        alert('Ошибка создания профиля: ' + userError.message);
    } else {
        registerModal.style.display = 'none';
        alert('Регистрация успешна! Проверьте вашу почту для подтверждения.');
    }
}

// Выход из системы
async function logout() {
    const { error } = await supabase.auth.signOut();
    if (error) {
        alert('Ошибка выхода: ' + error.message);
    } else {
        currentUser = null;
        updateAuthUI();
        alert('Вы вышли из системы');
    }
}

// Загрузка номеров
async function loadRooms() {
    const { data, error } = await supabase
        .from('rooms')
        .select('*')
        .order('room_number');
    
    if (error) {
        console.error('Ошибка загрузки номеров:', error);
        showError('Не удалось загрузить номера');
        return;
    }
    
    roomsGrid.innerHTML = '';
    data.forEach(room => {
        const statusClass = room.status === 'Доступен' ? 'available' : 
                           room.status === 'На ремонте' ? 'maintenance' : 'occupied';
        
        const roomCard = document.createElement('div');
        roomCard.className = 'room-card fade-in';
        roomCard.innerHTML = `
            <div class="room-image">Номер ${room.room_number}</div>
            <div class="room-info">
                <h3>${room.room_type}</h3>
                <p>💤 До ${room.max_guests} гостей</p>
                <p>📶 ${room.amenities}</p>
                <p>🏢 Этаж: ${room.floor || 'Не указан'}</p>
                <div class="room-price">${room.price_per_night} руб./ночь</div>
                <div class="room-status ${statusClass}">
                    ${room.status}
                </div>
            </div>
        `;
        roomsGrid.appendChild(roomCard);
    });
}

// Проверка доступности номеров
async function checkAvailability(e) {
    e.preventDefault();
    
    if (!currentUser) {
        alert('Пожалуйста, войдите в систему для бронирования');
        loginModal.style.display = 'block';
        return;
    }
    
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    const guests = parseInt(document.getElementById('guests').value);
    const roomType = document.getElementById('roomType').value;
    const specialRequests = document.getElementById('specialRequests').value;
    
    if (new Date(checkIn) >= new Date(checkOut)) {
        alert('Дата выезда должна быть позже даты заезда');
        return;
    }
    
    // Проверяем доступность номеров
    const { data: availableRoomsData, error } = await supabase
        .from('rooms')
        .select('*')
        .eq('room_type', roomType)
        .eq('status', 'Доступен')
        .gte('max_guests', guests);
    
    if (error) {
        console.error('Ошибка проверки доступности:', error);
        alert('Ошибка при проверке доступности номеров');
        return;
    }
    
    // Проверяем, нет ли пересекающихся бронирований
    const availableRooms = await checkBookingConflicts(availableRoomsData, checkIn, checkOut);
    displayAvailableRooms(availableRooms, checkIn, checkOut, guests, specialRequests);
}

// Проверка конфликтов бронирований
async function checkBookingConflicts(rooms, checkIn, checkOut) {
    const availableRooms = [];
    
    for (const room of rooms) {
        const { data: conflicts, error } = await supabase
            .from('bookings')
            .select('*')
            .eq('room_id', room.id)
            .neq('status', 'Отменено')
            .or(`and(check_in_date.lte.${checkOut},check_out_date.gte.${checkIn})`);
        
        if (!error && conflicts.length === 0) {
            availableRooms.push(room);
        }
    }
    
    return availableRooms;
}

// Отображение доступных номеров
function displayAvailableRooms(rooms, checkIn, checkOut, guests, specialRequests) {
    if (rooms.length === 0) {
        roomsList.innerHTML = `
            <div class="text-center">
                <p>К сожалению, нет доступных номеров на выбранные даты.</p>
                <p>Попробуйте изменить даты или тип номера.</p>
            </div>
        `;
    } else {
        roomsList.innerHTML = '';
        const nights = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
        
        rooms.forEach(room => {
            const totalPrice = nights * room.price_per_night;
            
            const roomElement = document.createElement('div');
            roomElement.className = 'room-option fade-in';
            roomElement.innerHTML = `
                <div>
                    <h4>Номер ${room.room_number} (${room.room_type})</h4>
                    <p>💤 До ${room.max_guests} гостей | 🏢 Этаж ${room.floor}</p>
                    <p>📶 ${room.amenities}</p>
                    <p><strong>${nights} ночей × ${room.price_per_night} руб. = ${totalPrice} руб.</strong></p>
                </div>
                <button class="btn-primary" onclick="bookRoom(${room.id}, '${checkIn}', '${checkOut}', ${guests}, ${totalPrice}, '${specialRequests}')">
                    Забронировать
                </button>
            `;
            roomsList.appendChild(roomElement);
        });
    }
    
    availableRooms.style.display = 'block';
    availableRooms.scrollIntoView({ behavior: 'smooth' });
}

// Бронирование номера
async function bookRoom(roomId, checkIn, checkOut, guests, totalAmount, specialRequests) {
    if (!currentUser) {
        alert('Пожалуйста, войдите в систему');
        return;
    }
    
    try {
        const { data, error } = await supabase
            .from('bookings')
            .insert([
                {
                    user_id: currentUser.id,
                    room_id: roomId,
                    check_in_date: checkIn,
                    check_out_date: checkOut,
                    guests_count: guests,
                    total_amount: totalAmount,
                    special_requests: specialRequests,
                    status: 'Ожидание'
                }
            ])
            .select();
        
        if (error) throw error;
        
        // Обновляем статус номера на "Занят"
        await supabase
            .from('rooms')
            .update({ status: 'Занят' })
            .eq('id', roomId);
        
        alert('Бронирование успешно создано! Ожидайте подтверждения от администратора.');
        availableRooms.style.display = 'none';
        bookingForm.reset();
        setupDateInputs();
        loadRooms(); // Обновляем список номеров
        loadUserBookings(); // Обновляем список бронирований
        
    } catch (error) {
        console.error('Ошибка бронирования:', error);
        alert('Ошибка бронирования: ' + error.message);
    }
}

// Загрузка бронирований пользователя
async function loadUserBookings() {
    if (!currentUser) return;
    
    const { data, error } = await supabase
        .from('bookings')
        .select(`
            *,
            rooms (room_number, room_type, price_per_night)
        `)
        .eq('user_id', currentUser.id)
        .order('created_at', { ascending: false });
    
    if (error) {
        console.error('Ошибка загрузки бронирований:', error);
        return;
    }
    
    if (data.length === 0) {
        bookingsList.innerHTML = '<p>У вас пока нет бронирований.</p>';
        return;
    }
    
    bookingsList.innerHTML = '';
    data.forEach(booking => {
        const nights = Math.ceil((new Date(booking.check_out_date) - new Date(booking.check_in_date)) / (1000 * 60 * 60 * 24));
        const statusClass = `status-${booking.status.toLowerCase().replace(' ', '_')}`;
        
        const bookingElement = document.createElement('div');
        bookingElement.className = 'booking-card fade-in';
        bookingElement.innerHTML = `
            <div style="display: flex; justify-content: between; align-items: start;">
                <div style="flex: 1;">
                    <h4>Номер ${booking.rooms.room_number} (${booking.rooms.room_type})</h4>
                    <p>📅 ${new Date(booking.check_in_date).toLocaleDateString('ru-RU')} - ${new Date(booking.check_out_date).toLocaleDateString('ru-RU')} (${nights} ночей)</p>
                    <p>👥 ${booking.guests_count} гостей</p>
                    <p>💰 ${booking.total_amount} руб.</p>
                    ${booking.special_requests ? `<p>💬 ${booking.special_requests}</p>` : ''}
                </div>
                <div>
                    <span class="booking-status ${statusClass}">${booking.status}</span>
                </div>
            </div>
            <div style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                Создано: ${new Date(booking.created_at).toLocaleDateString('ru-RU')}
            </div>
        `;
        bookingsList.appendChild(bookingElement);
    });
}

// Загрузка отзывов
async function loadReviews() {
    const { data, error } = await supabase
        .from('reviews')
        .select(`
            *,
            users (first_name, last_name)
        `)
        .order('created_at', { ascending: false })
        .limit(8);
    
    if (error) {
        console.error('Ошибка загрузки отзывов:', error);
        showError('Не удалось загрузить отзывы');
        return;
    }
    
    reviewsList.innerHTML = '';
    if (data.length === 0) {
        reviewsList.innerHTML = '<p class="text-center">Пока нет отзывов. Будьте первым!</p>';
        return;
    }
    
    data.forEach(review => {
        const reviewCard = document.createElement('div');
        reviewCard.className = 'review-card fade-in';
        
        const stars = '⭐'.repeat(review.rating);
        const userName = review.users ? `${review.users.first_name} ${review.users.last_name}` : 'Аноним';
        
        reviewCard.innerHTML = `
            <div class="review-rating">${stars}</div>
            <p style="font-style: italic; line-height: 1.6;">"${review.comment}"</p>
            <div class="review-author">
                <strong>${userName}</strong>
                <small>${new Date(review.created_at).toLocaleDateString('ru-RU')}</small>
            </div>
        `;
        reviewsList.appendChild(reviewCard);
    });
}

// Обработка отправки отзыва
async function handleReviewSubmit(e) {
    e.preventDefault();
    
    if (!currentUser) {
        alert('Пожалуйста, войдите в систему чтобы оставить отзыв');
        loginModal.style.display = 'block';
        return;
    }
    
    const comment = document.getElementById('reviewComment').value.trim();
    const rating = selectedRating;
    
    if (!comment) {
        alert('Пожалуйста, напишите отзыв');
        return;
    }
    
    try {
        const { data, error } = await supabase
            .from('reviews')
            .insert([
                {
                    user_id: currentUser.id,
                    rating: rating,
                    comment: comment
                }
            ])
            .select();
        
        if (error) throw error;
        
        alert('Спасибо за ваш отзыв!');
        reviewForm.reset();
        setupRatingStars(); // Сбросить звезды к значению по умолчанию
        loadReviews(); // Обновить список отзывов
        
    } catch (error) {
        console.error('Ошибка отправки отзыва:', error);
        alert('Ошибка отправки отзыва: ' + error.message);
    }
}

// Вспомогательные функции
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = `
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 5px;
        margin: 1rem 0;
        text-align: center;
    `;
    errorDiv.textContent = message;
    document.querySelector('main').prepend(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Показать уведомление
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 3000;
        animation: slideIn 0.3s ease-out;
        ${type === 'success' ? 'background: #28a745;' : 'background: #dc3545;'}
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

// Добавляем стили для анимации уведомления
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;

document.head.appendChild(style);
