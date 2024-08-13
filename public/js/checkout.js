document.addEventListener('DOMContentLoaded', function() {
    const shippingMethodSelect = document.getElementById('shipping_method');
    const paymentMethodRadios = document.getElementsByName('payment_method');
    const submitButton = document.getElementById('submitOrderButton');

    function checkSelection() {
        const shippingSelected = shippingMethodSelect.value !== 'disabled';
        const paymentSelected = Array.from(paymentMethodRadios).some(radio => radio.checked);
        
        if (shippingSelected && paymentSelected) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    // Disable the button by default
    submitButton.disabled = true;

    // Attach event listeners to check for changes
    shippingMethodSelect.addEventListener('change', checkSelection);
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', checkSelection);
    });

    // Event listener for the submit button
    submitButton.addEventListener('click', function(event) {
        if (submitButton.disabled) {
            event.preventDefault(); // Prevent the button from doing anything if it's disabled
            return;
        }
        submitOrder(); // Only call submitOrder if the button is not disabled
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const apiUrl = 'YOUR_API_ENDPOINT'; // Replace with your actual API endpoint
    const modalElement = document.getElementById('exampleModalCenter');

    // Fetch and populate modal when 'Ubah Alamat' modal is triggered
    modalElement.addEventListener('show.bs.modal', function () {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const addresses = data.data;
                    const modalBody = modalElement.querySelector('.modal-body');
                    let prioritizedAddress = null;
                    let otherAddresses = [];

                    // Loop through addresses to find the prioritized one and others
                    addresses.forEach(address => {
                        if (address.is_prioritize) {
                            prioritizedAddress = address;
                        } else {
                            otherAddresses.push(address);
                        }
                    });

                    // If there's no prioritized address, select the first one
                    if (!prioritizedAddress && addresses.length > 0) {
                        prioritizedAddress = addresses[0];
                        otherAddresses = addresses.slice(1);
                    }

                    // Function to create address card
                    const createAddressCard = (address, isPrioritized = false) => {
                        const card = document.createElement('div');
                        card.className = 'card mb-3';

                        // Customize card style based on priority
                        card.innerHTML = `
                            <div class="card-body" style="background-color: ${isPrioritized ? '#034d26' : '#1e1e1e'}; color: #fff;">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-1">${address.label_name} ${isPrioritized ? '<span class="badge bg-warning text-dark">Utama</span>' : ''}</h6>
                                        <p class="card-text mb-1">${address.street_address}, ${address.city}, ${address.state}, ${address.postal_code}</p>
                                        <p class="card-text">${address.extra_note || ''}</p>
                                    </div>
                                    <div class="text-end">
                                        ${isPrioritized ? '<i class="fa-solid fa-check text-success" style="font-size: 24px;"></i>' : '<button type="button" class="btn btn-outline-success mb-2">Pilih</button>'}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="#" class="text-light"></a>
                                    <div>
                                        <a href="#" class="text-light me-3">Ubah Alamat</a>
                                        ${!isPrioritized ? '<a href="#" class="text-light me-3">Jadikan Alamat Utama & Pilih</a>' : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                        return card;
                    };

                    // Clear existing cards
                    modalBody.innerHTML = '';

                    // Add the prioritized address card first
                    if (prioritizedAddress) {
                        modalBody.appendChild(createAddressCard(prioritizedAddress, true));
                    }

                    // Add other address cards with 'Pilih' and 'Jadikan Alamat Utama & Pilih' options
                    otherAddresses.forEach(address => {
                        modalBody.appendChild(createAddressCard(address));
                    });

                    // Optionally, add a button or link to add new address
                    const addNewAddressButton = document.createElement('button');
                    addNewAddressButton.className = 'btn btn-outline-success w-100 mb-3';
                    addNewAddressButton.textContent = 'Kelola Alamat';
                    modalBody.prepend(addNewAddressButton);

                    // Add the search bar back
                    const searchBar = document.createElement('div');
                    searchBar.className = 'mb-3 position-relative';
                    searchBar.innerHTML = `
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>
                        <input type="text" class="form-control ps-5" placeholder="Cari alamat yang dimiliki">
                    `;
                    modalBody.prepend(searchBar);
                } else {
                    console.error('Failed to fetch addresses');
                }
            })
            .catch(error => console.error('Error:', error));
    });
});



document.getElementById('shipping_method').addEventListener('change', function() {
    var selectedMethod = this.options[this.selectedIndex].text;
    document.getElementById('selected_shipping_method').textContent = selectedMethod;
});

document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var selectedMethod = this.nextElementSibling.textContent;
        document.getElementById('selected_payment_method').textContent = selectedMethod;
    });
});

function submitOrder() {
    // Get the selected shipment method ID
    const shippingMethodId = document.getElementById('shipping_method').value;

    // Get the selected payment method ID
    const paymentMethodId = Array.from(document.getElementsByName('payment_method'))
        .find(radio => radio.checked).value;

    // Form submission logic here
    console.log('Selected Shipment Method ID:', shippingMethodId);
    console.log('Selected Payment Method ID:', paymentMethodId);
}
