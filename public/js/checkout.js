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
    const apiUrl = baseUrl + 'api/user/addresses'; // Replace with your actual API endpoint
    const modalElement = document.getElementById('exampleModalCenter');
    let addresses = [];

    // Fetch and populate modal when 'Ubah Alamat' modal is triggered
    modalElement.addEventListener('show.bs.modal', function () {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    addresses = data.data; // Store the addresses globally
                    populateModal(addresses); // Populate modal with initial data
                } else {
                    console.error('Failed to fetch addresses');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function populateModal(addresses) {
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

        // Clear existing cards
        modalBody.innerHTML = '';

        // Add the search bar
        const searchBar = document.createElement('div');
        searchBar.className = 'mb-3 position-relative';
        searchBar.innerHTML = `
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                <i class="fa-solid fa-magnifying-glass text-muted"></i>
            </span>
            <input type="text" class="form-control ps-5" placeholder="Cari alamat yang dimiliki">
        `;
        modalBody.appendChild(searchBar);

        // Add the "Kelola Alamat" button at the top, just below the search bar
        const addNewAddressButton = document.createElement('button');
        addNewAddressButton.className = 'btn btn-outline-success w-100 mb-3';
        addNewAddressButton.textContent = 'Kelola Alamat';
        addNewAddressButton.addEventListener('click', function(){
            window.location = baseUrl + `user/${uid}/profile?edit=true&tab=address`
        });
        modalBody.appendChild(addNewAddressButton);

        // Add the prioritized address card first
        if (prioritizedAddress) {
            modalBody.appendChild(createAddressCard(prioritizedAddress, true));
        }

        // Add other address cards with 'Pilih' and 'Jadikan Alamat Utama & Pilih' options
        otherAddresses.forEach(address => {
            modalBody.appendChild(createAddressCard(address));
        });
    }

    function createAddressCard(address, isPrioritized = false) {
        const card = document.createElement('div');
        card.className = 'card mb-3 address-card';
        card.dataset.idShippingAddress = address.id_shipping_address; // Add the id_shipping_address as a data attribute

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
                        ${isPrioritized ? '<i class="fa-solid fa-check text-success" style="font-size: 24px;"></i>' : `<button type="button" class="btn btn-outline-success mb-2" onclick="selectAddress(${address.id_shipping_address})">Pilih</button>`}
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="#" class="text-light"></a>
                    <div>
                        <a href="#" class="text-light me-3">Ubah Alamat</a>
                        ${!isPrioritized ? '<a href="#" class="text-light me-3" onclick="makePrimaryAddress(${address.id_shipping_address})">Jadikan Alamat Utama & Pilih</a>' : ''}
                    </div>
                </div>
            </div>
        `;
        return card;
    }

    function updateModalContent(filteredAddresses) {
        const modalBody = modalElement.querySelector('.modal-body');
        modalBody.innerHTML = '';

        let prioritizedAddress = filteredAddresses.find(addr => addr.is_prioritize);
        if (!prioritizedAddress && filteredAddresses.length > 0) {
            prioritizedAddress = filteredAddresses[0];
        }

        // Add the search bar back
        const searchBar = document.createElement('div');
        searchBar.className = 'mb-3 position-relative';
        searchBar.innerHTML = `
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                <i class="fa-solid fa-magnifying-glass text-muted"></i>
            </span>
            <input type="text" class="form-control ps-5" placeholder="Cari alamat yang dimiliki">
        `;
        modalBody.appendChild(searchBar);

        // Add the "Kelola Alamat" button back at the top
        const addNewAddressButton = document.createElement('button');
        addNewAddressButton.className = 'btn btn-outline-success w-100 mb-3';
        addNewAddressButton.textContent = 'Kelola Alamat';
        modalBody.appendChild(addNewAddressButton);

        if (prioritizedAddress) {
            modalBody.appendChild(createAddressCard(prioritizedAddress, true));
        }

        filteredAddresses
            .filter(addr => addr !== prioritizedAddress)
            .forEach(address => {
                modalBody.appendChild(createAddressCard(address));
            });
    }

    // Function to select address and update the "Alamat:" field
    window.selectAddress = function(id_shipping_address) {
        const address = addresses.find(addr => addr.id_shipping_address === id_shipping_address); // Find the selected address
        if (!address) return;

        // Update the address info on the page
        const alamatField = document.querySelector('.card-body h6[style*="font-family: nunito;"][text^="Alamat:"]');
        if (alamatField) {
            alamatField.textContent = `${address.street_address}, ${address.city}, ${address.state}, ${address.postal_code}`;
        }
        const extraNoteField = document.querySelector('.card-body h6[style*="font-family: nunito;"][text^="Catatan:"]');
        if (extraNoteField && address.extra_note) {
            extraNoteField.textContent = address.extra_note;
        }
        const modalElement = document.getElementById('exampleModalCenter');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
    }

    // Function to make an address primary and select it
    window.makePrimaryAddress = function(id_shipping_address) {
        fetch(apiUrl + '/' + id_shipping_address + '/setPrimary', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                selectAddress(id_shipping_address); // Call selectAddress to update the display
                // Optionally, refetch addresses and update the modal
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            addresses = data.data;
                            populateModal(addresses); // Re-populate modal with updated data
                        }
                    });
            } else {
                console.error('Failed to make address primary');
            }
        })
        .catch(error => console.error('Error:', error));
    }
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

function ajaxGetToken(transactionData, callback) {
    fetch(baseUrl + 'api/prepare-order', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(transactionData)
    })
    .then(response => {
        const contentType = response.headers.get('Content-Type');

        if (contentType.includes('application/json')) {
            return response.json();
        } else if (contentType.includes('text/html')) {
            return response.text().then(htmlContent => {
                openHtmlContentToNewPage(htmlContent);
                callback(new Error('Received HTML instead of JSON'), null);
            });
        } else {
            return response.text().then(text => {
                callback(new Error(`Unexpected content type: ${contentType}. Response: ${text}`), null);
            });
        }
    })
    .then(data => {
        if (data) {
            if (data.snapToken) {
                callback(null, { snapToken: data.snapToken, redirect: '' });
            } else if (data.redirect) {
                callback(null, { snapToken: '', redirect: data.redirect });
            } else {
                callback(new Error('Unexpected response structure'), null);
            }
        }
    })
    .catch(error => {
        callback(error, null);
    });
}

function revertButton() {
    removeLoader('submitOrderButton');
    document.getElementById('submitOrderButton').innerHTML = 'Lanjut Bayar';
}

function submitOrder() {
    injectLoaderWithoutText('submitOrderButton');

    const shippingMethodId = document.getElementById('shipping_method').value;
    const paymentMethodId = Array.from(document.getElementsByName('payment_method'))
        .find(radio => radio.checked).value;

    const idShippingAddress = said;
    const selectedProductsParameters = selected;

    const transactionData = {
        shippingMethodId: shippingMethodId,
        paymentMethodId: paymentMethodId,
        idShippingAddress: idShippingAddress,
        token: t,
        selectedProductsParameters: selectedProductsParameters
    };

    ajaxGetToken(transactionData, function(error, response) {
        if (error) {
            console.log(error);
            openHtmlContentToNewPage(error.message);
            revertButton();
            return;
        }

        if (response.snapToken) {
            snap.pay(response.snapToken, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    revertButton();
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    revertButton();
                },
                onError: function(result) {
                    console.error('Payment Error:', result);
                    revertButton();
                }
            });
        } else if (response.redirect) {
            window.location.href = response.redirect;
        }

        revertButton();
    });
}
