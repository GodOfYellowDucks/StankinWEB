// Корзина товаров
class Cart {
    constructor() {
        this.products = [];
        this.loadFromServer();
        this.init();
    }

    init() {
        this.createCartButton();
        this.createCartModal();
        this.bindEvents();
        this.updateCartCounter();
    }

    // Создание кнопки корзины
    createCartButton() {
        // Проверяем, авторизован ли пользователь
        if (!this.isUserLoggedIn()) {
            return;
        }

        const cartButton = document.createElement('button');
        cartButton.className = 'cart-button';
        cartButton.id = 'cart-button';
        cartButton.innerHTML = `
            <img src="images/cart.png" alt="Корзина" class="cart-icon" />
            <div class="cart-counter" id="cart-counter">0</div>
        `;
        
        document.body.appendChild(cartButton);
    }

    // Создание модального окна корзины
    createCartModal() {
        if (!this.isUserLoggedIn()) {
            return;
        }

        const modal = document.createElement('div');
        modal.className = 'cart-modal';
        modal.id = 'cart-modal';
        modal.innerHTML = `
            <div class="cart-modal-content">
                <div class="cart-header">
                    <h2>Корзина</h2>
                    <span class="cart-close" id="cart-close">&times;</span>
                </div>
                <div class="cart-body" id="cart-body">
                    <p>Корзина пуста</p>
                </div>
                <div class="cart-footer">
                    <div class="cart-total">
                        <strong>Итого: <span id="cart-total">0</span> руб.</strong>
                    </div>
                    <div class="cart-search">
                        <input type="text" id="cart-search" placeholder="Поиск в корзине...">
                        <button onclick="myCart.searchInCart()">Найти</button>
                    </div>
                    <button class="cart-checkout-btn">Оформить заказ</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    // Привязка событий
    bindEvents() {
        if (!this.isUserLoggedIn()) {
            return;
        }

        // Открытие корзины
        const cartButton = document.getElementById('cart-button');
        if (cartButton) {
            cartButton.addEventListener('click', () => this.openCart());
        }

        // Закрытие корзины
        const cartClose = document.getElementById('cart-close');
        if (cartClose) {
            cartClose.addEventListener('click', () => this.closeCart());
        }

        const cartModal = document.getElementById('cart-modal');
        if (cartModal) {
            cartModal.addEventListener('click', (e) => {
                if (e.target === cartModal) {
                    this.closeCart();
                }
            });
        }

        // Добавление товаров в корзину
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart-button') || e.target.closest('.add-to-cart-button')) {
                e.preventDefault();
                this.addToCartFromButton(e.target);
            }
        });
    }

    // Проверка авторизации пользователя
    isUserLoggedIn() {
        // Проверяем наличие элементов, которые указывают на авторизованного пользователя
        const loginForm = document.querySelector('.login-form form');
        return !loginForm; // Если нет формы логина, значит пользователь авторизован
    }

    // Добавление товара в корзину
    addToCart(productData) {
        if (!this.isUserLoggedIn()) {
            alert('Для добавления товаров в корзину необходимо войти в систему');
            return;
        }

        // Проверяем, есть ли уже такой товар в корзине (сравниваем только ID, игнорируем свойства для каталога)
        const existingProductIndex = this.products.findIndex(p => {
            // Если товар добавляется с каталога (без свойств), сравниваем только ID
            if (!productData.properties || Object.keys(productData.properties).length === 0) {
                return p.id === productData.id && (!p.properties || Object.keys(p.properties).length === 0);
            }
            // Если товар добавляется со страницы товара (с свойствами), сравниваем ID и свойства
            return p.id === productData.id && JSON.stringify(p.properties) === JSON.stringify(productData.properties);
        });

        if (existingProductIndex >= 0) {
            this.products[existingProductIndex].quantity += productData.quantity || 1;
        } else {
            this.products.push({
                ...productData,
                quantity: productData.quantity || 1,
                dateAdded: new Date().toISOString()
            });
        }

        this.saveToServer();
        this.updateCartCounter();
        this.updateCartDisplay();
        
        // Показываем уведомление
        this.showNotification('Товар добавлен в корзину');
    }

    // Добавление товара из кнопки
    addToCartFromButton(button) {
        const productItem = button.closest('.catalog-item, .main-content');
        if (!productItem) return;

        const productData = this.extractProductData(productItem);
        if (productData) {
            this.addToCart(productData);
        }
    }

    // Извлечение данных товара из DOM
    extractProductData(element) {
        const img = element.querySelector('img');
        const link = element.querySelector('a[href*="product"]');
        const nameElement = element.querySelector('h1, a');
        const priceElement = element.querySelector('.price, .product-price, #product-price');
        const descriptionElement = element.querySelector('p:not(.price):not(.product-price)');

        if (!img || !nameElement || !priceElement) {
            console.error('Не удалось найти необходимые элементы товара');
            return null;
        }

        // Извлекаем ID товара из ссылки
        let productId = null;
        if (link && link.href.includes('id=')) {
            const urlParams = new URLSearchParams(link.href.split('?')[1]);
            productId = urlParams.get('id');
        }

        // Извлекаем цену
        let price = 0;
        const priceText = priceElement.textContent || priceElement.innerText;
        const priceMatch = priceText.match(/[\d\s]+/);
        if (priceMatch) {
            price = parseInt(priceMatch[0].replace(/\s/g, ''));
        }

        // Извлекаем свойства товара (если на странице товара)
        const properties = {};
        const propertySelects = element.querySelectorAll('.property-select');
        propertySelects.forEach(select => {
            const label = select.previousElementSibling;
            if (label && select.value !== '') {
                const propertyName = label.textContent.replace(':', '');
                const selectedOption = select.options[select.selectedIndex];
                properties[propertyName] = {
                    value: selectedOption.text.split(' (+')[0].split(' (')[0],
                    price: parseFloat(select.value) || 0
                };
            }
        });

        return {
            id: productId || Date.now().toString(),
            name: nameElement.textContent || nameElement.innerText,
            price: price,
            image: img.src,
            description: descriptionElement ? (descriptionElement.textContent || descriptionElement.innerText) : '',
            properties: properties,
            link: link ? link.href : window.location.href
        };
    }

    // Удаление товара из корзины
    removeFromCart(index) {
        this.products.splice(index, 1);
        this.saveToServer();
        this.updateCartCounter();
        this.updateCartDisplay();
    }

    // Изменение количества товара
    updateQuantity(index, quantity) {
        if (quantity <= 0) {
            this.removeFromCart(index);
        } else {
            this.products[index].quantity = quantity;
            this.saveToServer();
            this.updateCartCounter();
            this.updateCartDisplay();
        }
    }

    // Обновление счетчика корзины
    updateCartCounter() {
        const counter = document.getElementById('cart-counter');
        if (counter) {
            const totalItems = this.products.reduce((sum, product) => sum + product.quantity, 0);
            counter.textContent = totalItems;
            counter.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    // Обновление отображения корзины
    updateCartDisplay() {
        const cartBody = document.getElementById('cart-body');
        const cartTotal = document.getElementById('cart-total');
        
        if (!cartBody || !cartTotal) return;

        if (this.products.length === 0) {
            cartBody.innerHTML = '<p>Корзина пуста</p>';
            cartTotal.textContent = '0';
            return;
        }

        let totalPrice = 0;
        const cartItems = this.products.map((product, index) => {
            const productPrice = product.price + this.calculatePropertiesPrice(product.properties);
            const itemTotal = productPrice * product.quantity;
            totalPrice += itemTotal;

            const propertiesText = Object.keys(product.properties).length > 0 
                ? Object.entries(product.properties).map(([key, value]) => `${key}: ${value.value}`).join(', ')
                : '';

            return `
                <div class="cart-item" data-index="${index}">
                    <div class="cart-item-image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="cart-item-details">
                        <h4><a href="${product.link}">${product.name}</a></h4>
                        ${propertiesText ? `<p class="cart-item-properties">${propertiesText}</p>` : ''}
                        <p class="cart-item-price">${this.formatPrice(productPrice)} руб.</p>
                    </div>
                    <div class="cart-item-controls">
                        <div class="quantity-controls">
                            <button onclick="myCart.updateQuantity(${index}, ${product.quantity - 1})">-</button>
                            <input type="number" value="${product.quantity}" min="1" 
                                   onchange="myCart.updateQuantity(${index}, parseInt(this.value))">
                            <button onclick="myCart.updateQuantity(${index}, ${product.quantity + 1})">+</button>
                        </div>
                        <p class="cart-item-total">${this.formatPrice(itemTotal)} руб.</p>
                        <button class="cart-item-remove" onclick="myCart.removeFromCart(${index})">Удалить</button>
                    </div>
                </div>
            `;
        }).join('');

        cartBody.innerHTML = cartItems;
        cartTotal.textContent = this.formatPrice(totalPrice);
    }

    // Вычисление цены свойств
    calculatePropertiesPrice(properties) {
        return Object.values(properties).reduce((sum, prop) => sum + (prop.price || 0), 0);
    }

    // Форматирование цены
    formatPrice(price) {
        return price.toLocaleString('ru-RU');
    }

    // Поиск в корзине
    searchInCart() {
        const searchTerm = document.getElementById('cart-search').value.toLowerCase();
        const cartItems = document.querySelectorAll('.cart-item');
        
        cartItems.forEach(item => {
            const productName = item.querySelector('h4').textContent.toLowerCase();
            const productProperties = item.querySelector('.cart-item-properties');
            const propertiesText = productProperties ? productProperties.textContent.toLowerCase() : '';
            
            if (productName.includes(searchTerm) || propertiesText.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Открытие корзины
    openCart() {
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            this.updateCartDisplay();
        }
    }

    // Закрытие корзины
    closeCart() {
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Показ уведомления
    showNotification(message) {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.textContent = message;
        document.body.appendChild(notification);

        // Показываем уведомление
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Скрываем через 3 секунды
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Сохранение корзины на сервер
    saveToServer() {
        if (!this.isUserLoggedIn()) return;

        fetch('cart_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'save',
                products: this.products
            })
        })
        .catch(error => {
            console.error('Ошибка сохранения корзины:', error);
        });
    }

    // Загрузка корзины с сервера
    loadFromServer() {
        if (!this.isUserLoggedIn()) return;

        fetch('cart_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'load'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products) {
                this.products = data.products;
                this.updateCartCounter();
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки корзины:', error);
        });
    }
}

// Инициализация корзины
let myCart;
document.addEventListener('DOMContentLoaded', function() {
    myCart = new Cart();
});

// Утилиты для форматирования
function toNum(str) {
    const num = Number(str.replace(/\s/g, ""));
    return num;
}

function toCurrency(num) {
    const format = new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB",
        minimumFractionDigits: 0,
    }).format(num);
    return format;
}