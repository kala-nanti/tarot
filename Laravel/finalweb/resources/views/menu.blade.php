@extends('admin.layouts.store')

@section('content')
<main class="flex gap-8 p-6 lg:p-10">
    <!-- Kolom Kiri: Kategori & Logo -->
    <div class="hidden lg:block w-72 flex-shrink-0">
        <a href="{{ route('menu') }}" class="font-extrabold text-3xl">
            <span class="text-red-500">Tao</span>Bun🍜
        </a>
        <aside class="bg-white p-6 mt-6 rounded-xl shadow-sm" style="height: calc(100vh - 120px);">
            <p class="font-bold text-lg">Kategori</p>
            <div id="category-buttons" class="flex flex-col mt-4 gap-y-2">
                <button onclick="filterProducts('all', this)" class="category-btn flex justify-between items-center w-full py-3 px-4 rounded font-bold transition-colors">
                    <div><i class="ri-apps-2-line mr-3"></i><span>Semua</span></div>
                </button>
                @foreach($categories as $category)
                <button onclick="filterProducts('{{ $category->name }}', this)" class="category-btn flex justify-between items-center w-full py-3 px-4 rounded font-bold transition-colors">
                    <div><i class="ri-{{ $category->name == 'Food' ? 'restaurant' : 'goblet' }}-line mr-3"></i><span>{{ $category->name }}</span></div>
                    <span class="text-gray-500">{{ $category->products_count }}</span>
                </button>
                @endforeach
            </div>
        </aside>
    </div>

    <!-- Kolom Tengah: Menu Produk -->
    <div class="w-full">
        {{-- ... Notifikasi ... --}}
        <p class="font-bold text-3xl">Menu</p>
        <section id="product-grid" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($products as $product)
            <div class="product-card bg-white rounded-xl shadow-sm overflow-hidden flex flex-col" data-category="{{ $product->category->name ?? '' }}">
                <div class="h-48 overflow-hidden"><img src="{{ asset('storage/images/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" /></div>
                <div class="p-5 flex flex-col flex-grow">
                    <p class="font-bold text-lg">{{ $product->name }}</p>
                    <p class="font-bold text-red-500 mt-1">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="mt-auto pt-4">
                        <button onclick="addToCart('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->price }}')" class="w-full py-2 px-4 bg-red-100 rounded-lg font-bold text-red-600 hover:bg-red-500 hover:text-white transition-colors">Pesan</button>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </div>

    <!-- Kolom Kanan: Keranjang Belanja -->
    <div class="hidden xl:block w-96 flex-shrink-0">
         <p class="font-bold text-3xl">Keranjang</p>
         <div id="cart-container" class="bg-white p-6 mt-6 rounded-xl shadow-sm flex flex-col" style="height: calc(100vh - 120px);">
            <div id="cart-items" class="flex-grow overflow-y-auto custom-scrollbar pr-2">
                <p class="text-gray-400 text-center mt-10">Keranjang Anda masih kosong.</p>
            </div>
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span id="cart-total">Rp. 0</span>
                </div>
                 {{-- Form ini sekarang mengarah ke halaman pembayaran --}}
                 <form id="checkout-form" action="{{ route('checkout.page') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="cart" id="cart-input">
                    <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600 transition-colors">Checkout</button>
                </form>
            </div>
         </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let cart = {}; 

    function addToCart(id, name, price) {
        if (cart[id]) {
            cart[id].quantity++;
        } else {
            cart[id] = { name: name, price: price, quantity: 1 };
        }
        updateCartDisplay();
    }
    
    function removeFromCart(id) {
        if (cart[id] && cart[id].quantity > 0) {
            cart[id].quantity--;
            if (cart[id].quantity === 0) {
                delete cart[id];
            }
        }
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotalElement = document.getElementById('cart-total');
        const cartInputElement = document.getElementById('cart-input');
        cartItemsContainer.innerHTML = '';
        let total = 0;

        if (Object.keys(cart).length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-gray-400 text-center mt-10">Keranjang Anda masih kosong.</p>';
        } else {
            for (const id in cart) {
                const item = cart[id];
                total += item.price * item.quantity;
                const itemElement = document.createElement('div');
                itemElement.className = 'flex justify-between items-center mb-4';

                const infoDiv = document.createElement('div');
                infoDiv.className = 'flex-grow';
                infoDiv.innerHTML = `<p class="font-bold">${item.name}</p><p class="text-sm text-gray-500">Rp. ${item.price.toLocaleString('id-ID')}</p>`;
                
                const actionDiv = document.createElement('div');
                actionDiv.className = 'flex items-center gap-2';

                const minusButton = document.createElement('button');
                minusButton.className = 'text-red-500 font-bold w-6 h-6 rounded-full hover:bg-red-100';
                minusButton.textContent = '-';
                minusButton.onclick = () => removeFromCart(id);
                
                const quantityText = document.createElement('p');
                quantityText.className = 'font-bold w-6 text-center';
                quantityText.textContent = item.quantity;

                const plusButton = document.createElement('button');
                plusButton.className = 'text-green-500 font-bold w-6 h-6 rounded-full hover:bg-green-100';
                plusButton.textContent = '+';
                plusButton.onclick = () => addToCart(id, item.name, item.price); // Perbaikan di sini

                actionDiv.appendChild(minusButton);
                actionDiv.appendChild(quantityText);
                actionDiv.appendChild(plusButton);
                
                itemElement.appendChild(infoDiv);
                itemElement.appendChild(actionDiv);

                cartItemsContainer.appendChild(itemElement);
            }
        }
        cartTotalElement.innerText = `Rp. ${total.toLocaleString('id-ID')}`;
        cartInputElement.value = JSON.stringify(cart);
    }
    
    function filterProducts(category, clickedButton) {
        const products = document.getElementsByClassName('product-card');
        for (let product of products) {
            product.style.display = (category === 'all' || product.dataset.category === category) ? 'flex' : 'none';
        }
        const buttons = document.getElementsByClassName('category-btn');
        for (let button of buttons) {
            button.classList.remove('active', 'bg-red-500', 'text-white');
            button.classList.add('bg-red-500/10', 'text-red-600');
        }
        clickedButton.classList.add('active', 'bg-red-500', 'text-white');
        clickedButton.classList.remove('bg-red-500/10', 'text-red-600');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const allButton = document.querySelector('#category-buttons .category-btn');
        if (allButton) filterProducts('all', allButton);
    });
</script>
@endpush