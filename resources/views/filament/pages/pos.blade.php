<x-filament::page>
    @vite('resources/css/app.css')
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <div class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white p-4">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Menu List -->
            <div class="md:col-span-2 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <input type="text" placeholder="Cari menu ..." id="searchInput" class="w-full p-2 rounded bg-gray-200 dark:bg-gray-700">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4" id="productsContainer">
                    @foreach($products as $item)
                    <div class="product-item p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow" data-product-name="{{$item->name}}" data-product-price="{{$item->sele_price}}" data-product-id="{{$item->id}}" data-product-barcode="{{$item->barcode}}">
                        <img src="burger.jpg" alt="{{$item->name}}" class="w-full h-20 object-cover rounded">
                        <h3 class="mt-2 text-center">{{$item->name}}</h3>
                        <p class="text-center font-bold">Rp. {{$item->sele_price}}</p>
                        <button onclick="addToCart(this)" class="w-full mt-2 p-2 bg-blue-500 text-white rounded">Tambah ke Keranjang</button>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Cart & Checkout -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow relative">
                <h2 class="text-xl font-bold mb-2 flex justify-between items-center">
                    Keranjang
                    <button onclick="clearCart()" class="p-1 bg-red-500 text-white text-xs rounded">Hapus</button>
                </h2>
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-2 bg-gray-100 dark:bg-gray-700 rounded">
                        <div id="cartItems" class="space-y-4">
                            <!-- Item keranjang akan muncul di sini -->
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t pt-4">
                    <div class="relative mt-2">
                        <span class="block font-bold">Member</span>
                        <input 
                            id="memberInput" 
                            type="text" 
                            class="w-full text-right p-2 rounded bg-gray-200 dark:bg-gray-700"
                            onkeyup="filterMembers()"
                            onclick="showMemberList()"
                            autocomplete="off"
                        >
                        <ul id="memberList" class="absolute w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded mt-1 shadow-md hidden max-h-40 overflow-y-auto">
                            <!-- Data akan diisi oleh JavaScript -->
                        </ul>
                    </div>
                                   
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span id="priceCount">0</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="font-bold">Pay Amount</span>
                        <input id="cashInput" type="text" class="w-20 text-right p-1 rounded bg-gray-200 dark:bg-gray-700">
                    </div>
                    <div class="flex justify-between font-bold mt-2">
                        <span>Change</span>
                        <span id="cashReturn">Rp. 0</span>
                    </div>
                    <button onclick="openModal()" class="w-full mt-4 p-2 bg-blue-500 text-white rounded">Submit</button>
                </div>
            </div>
        </div> 
        
        {{-- tampilan proses pop-up --}}
        <div style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <!-- Modal -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 mx-4 md:mx-auto relative">
                <!-- Tombol Close -->
                <button 
                    type="button" 
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    onclick="closeModal()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div id="InvoiceContent">
                    {{-- untuk Content invoicenya --}}
                </div>
                <button 
                    type="button"
                    id="submitTransaction" 
                    class="w-full mt-4 p-2 bg-blue-500 hover:bg-blue-600 focus:ring focus:ring-blue-300 rounded-lg text-white font-medium transition duration-200">
                    PROCEED
                </button>
            </div>
        </div>

        
    </div>
</x-filament::page>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //submitTransactionButton
    $("#submitTransaction").click(function () {
        let totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        let payAmount = parseFloat($("#cashInput").val()) || 0;
        let memberVal = getSelectedMemberId()
        let user_id = @json(Auth::user()->id);
        let transaction_no = "TWPOS-KS-1618104058";
        let paymentMethod ="Cash";

        let requestData = {
            user_id: user_id,
            paymentMethod: paymentMethod,
            transaction_no: transaction_no,
            timestamp: getCurrentTime(),
            total_price: totalPrice,
            pay_amount: payAmount,
            items: cart,
            member_id: memberVal
        };
console.log(requestData);
        $.ajax({
            url: "/transactions/store",
            type: "POST",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: JSON.stringify(requestData),
            success: function (response) {

                alert("Transaction successful: " + response.message);
                window.location.reload();
            },
            error: function (xhr) {
                alert("Transaction failed: " + xhr.responseJSON.error);
            }
        });
    });
</script>
{{-- script untuk keranjang --}}
<script>
    let cart = []; // Array untuk menyimpan item keranjang
    //Fungsi untuk minyimpan data ke cart
    function addToCart(button) {
        const productElement = button.closest('div');
        const productName = productElement.dataset.productName;
        const productPrice = parseInt(productElement.dataset.productPrice);
        const productId = parseInt(productElement.dataset.productId);

        // Cek apakah produk sudah ada di keranjang
        const existingProduct = cart.find(item => item.name === productName);

        if (existingProduct) {
            existingProduct.quantity++; // Tambah quantity jika produk sudah ada
        } else {
            cart.push({ // Tambah produk baru jika belum ada
                id:productId,
                name: productName,
                price: productPrice,
                quantity: 1
            });
        }

        updateCartDisplay(); // Update tampilan keranjang
        renderCartItems();
    }

    //Fungsi untuk render cart item
    function renderCartItems() {
            const cartContainer = document.getElementById('cartItems');
            cartContainer.innerHTML = ''; // Kosongkan kontainer
            
            cart.forEach(item => {
                // Buat elemen HTML untuk setiap item
                const itemElement = document.createElement('div');
                itemElement.className = 'p-4 bg-white dark:bg-gray-800 rounded-lg shadow';
                itemElement.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <span>${item.name}</span>
                            <div class="flex items-center mt-1">
                                <button onclick="updateQuantity('${item.name}', -1)" 
                                        class="p-1 bg-gray-300 dark:bg-gray-600 text-black dark:text-white rounded">-</button>
                                <span class="mx-2">${item.quantity}</span>
                                <button onclick="updateQuantity('${item.name}', 1)" 
                                        class="p-1 bg-gray-300 dark:bg-gray-600 text-black dark:text-white rounded">+</button>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold">Rp. ${(item.price * item.quantity).toLocaleString()}</span>
                        </div>
                    </div>
                `;
                
                cartContainer.appendChild(itemElement);
            });
        }

    //Fngsi untuk update quantity
    function updateQuantity(productName, change) {
            const productIndex = cart.findIndex(item => item.name === productName);
            
            if (productIndex > -1) {
                cart[productIndex].quantity += change;
                
                // Hapus item jika quantity <= 0
                if (cart[productIndex].quantity <= 0) {
                    cart.splice(productIndex, 1);
                }
                
                updateCartDisplay();
                renderCartItems(); // Render ulang tampilan
            }
        }

    // Fungsi untuk update tampilan jumlah item
    function updateCartDisplay() {
        const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);

        const totalPrice = cart.reduce((sum, item) => {
            return sum + (item.price * item.quantity);
        }, 0);

        document.getElementById('priceCount').textContent = 'Rp. ' + totalPrice.toLocaleString();

        const cashInput = document.getElementById('cashInput');
            cashInput.value = totalPrice;
            
            function updateChange() {
                const cashValue = parseInt(cashInput.value.replace(/\./g, '')) || 0;
                const change = cashValue - totalPrice;
                document.getElementById('cashReturn').textContent = 'Rp. ' + (change > 0 ? change.toLocaleString() : '0');
            }
            
            // Pastikan event listener hanya ditambahkan sekali
            cashInput.removeEventListener('input', updateChange);
            cashInput.addEventListener('input', updateChange);

            updateChange();

        // Optional: untuk testing
        console.log('Keranjang:', cart);
        console.log('Total Item:', totalQty);
        console.log('Total Harga:', totalPrice.toLocaleString());
    }

    //Fungsi Untuk Hapus data di cart
    function clearCart() {
        cart = [];
        updateCartDisplay();
        renderCartItems();
    }

    //Fungsi Untuk Search Item
    const searchInput = document.getElementById('searchInput');
    const productsContainer = document.getElementById('productsContainer');
    const productItems = document.querySelectorAll('.product-item');
    
    function searchProducts(query) {
        productItems.forEach(item => {
            const productName = item.dataset.productName.toLowerCase();
            const productBarcode = item.dataset.productBarcode.toLowerCase();   
            // atur display
            if (productName.includes(query.toLowerCase()) || productBarcode.includes(query.toLowerCase())) {
                item.style.display = 'block'; 
            } else {
                item.style.display = 'none';
            }
        });
    }

    function addItemByName(productName) {
        productItems.forEach(item => {
            if (item.dataset.productName.toLowerCase() === productName.toLowerCase()) {
                addToCart(item.querySelector('button'));
            }
        });
    }

    function addItemByBarcode(productBarcode) {
        productItems.forEach(item => {
            if (item.dataset.productBarcode.toLowerCase() === productBarcode.toLowerCase()) {
                addToCart(item.querySelector('button'));
            }
        });
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        searchProducts(query);
    });

    searchInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                addItemByName(query);
                addItemByBarcode(query);
                this.value = ''; 
                searchProducts('');
            }
        }
    });
</script>

    {{-- script untuk pop-up --}}
<script>
    //Fungsi Untuk Menutup Modal Invoce
    function closeModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) {
            modal.style.display = 'none'; 
        }
    }

    //Fungsi untuk Memunculkan Modal Invoce
    function openModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) {
            modal.style.display = 'flex'; 
            renderReceipt();
        }

    }

    //Fungsi Untuk Format penulisan Currency
    function formatCurrency(amount) {
        return `Rp. ${amount.toLocaleString()}`;
    }

    //Fungsi Untuk Mendapatkan Waktu Sekarang
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleString("id-ID", { day: "2-digit", month: "2-digit", year: "2-digit", hour: "2-digit", minute: "2-digit" });
    }

    //Fungsi Untuk Merender Invoice
    function renderReceipt() {
        let totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const cashInput = document.getElementById('cashInput');
            
        let payAmount = parseFloat(cashInput.value) || 0;
        let changeAmount = payAmount - totalPrice;
        let TransactionIdOld = {{$transactionsIdLast}} + 1;

        document.getElementById("InvoiceContent").innerHTML = `
            <h2 class="text-center text-lg font-bold text-gray-900 dark:text-white">Management GYM</h2>
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">CABANG KONOHA SELATAN</p>
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                No: TWPOS-KS-${TransactionIdOld} 
                <span class="float-right">${getCurrentTime()}</span>
            </p>
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left font-bold text-gray-900 dark:text-white">
                        <th class="py-1">#</th>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    ${cart.map((item, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}<br><span class="text-xs text-gray-500 dark:text-gray-400">${formatCurrency(item.price)}</span></td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-right">${formatCurrency(item.price * item.quantity)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <div class="flex justify-between font-bold text-sm text-gray-900 dark:text-white">
                <span>Total</span>
                <span>${formatCurrency(totalPrice)}</span>
            </div>
            <div class="flex justify-between text-sm mt-1 text-gray-700 dark:text-gray-300">
                <span>PAY AMOUNT</span>
                <span>${formatCurrency(payAmount)}</span>
            </div>
            <div class="flex justify-between font-bold text-sm mt-1 text-gray-900 dark:text-white">
                <span>CHANGE</span>
                <span>${formatCurrency(changeAmount)}</span>
            </div>
        `;
    }
  
</script>

<script> 
    const memberData = @json($members->map(fn($member) => [
        'name' => $member->name,
        'id' => $member->id,
    ])->toArray());

    const members = Array.isArray(memberData) ? memberData : [];
    const memberInput = document.getElementById("memberInput");
    const memberList = document.getElementById("memberList");

    function populateMemberList() {
        memberList.innerHTML = ""; 
        members.forEach(member => {
            let li = document.createElement("li");
            li.textContent = `${member.name} - ${member.id}`;
            li.className = "p-2 cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600";
            li.onclick = () => {
                memberInput.value = member.name;
                memberInput.dataset.selectedId = member.id;
                // console.log("ID yang dipilih:", memberInput.dataset.selectedId);
                memberList.classList.add("hidden");
            };
            memberList.appendChild(li);
        });
    }

    // Filter berdasarkan input
    function filterMembers() {
        const query = memberInput.value.toLowerCase();
        const items = memberList.getElementsByTagName("li");
        for (let item of items) {
            if (item.textContent.toLowerCase().includes(query)) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        }
    }

    // Tampilkan daftar saat input diklik
    function showMemberList() {
        populateMemberList();
        memberList.classList.remove("hidden");
    }

    // Sembunyikan daftar jika klik di luar
    document.addEventListener("click", (e) => {
        if (!memberInput.contains(e.target) && !memberList.contains(e.target)) {
            memberList.classList.add("hidden");
        }
    });

    // Cara mengambil ID yang dipilih
    function getSelectedMemberId() {
        return memberInput.dataset.selectedId || null;
    }
</script>







