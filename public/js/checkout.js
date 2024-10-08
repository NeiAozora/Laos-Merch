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



var addresses = [];
document.addEventListener("DOMContentLoaded", function() {
    const apiUrl = baseUrl + 'api/user/addresses'; // Replace with your actual API endpoint
    const modalElement = document.getElementById('exampleModalCenter');
    addresses = [];

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
    
        // Clear existing content in modal body
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
            window.location = baseUrl + `user/${uid}/profile?edit=true&tab=address`;
        });
        modalBody.appendChild(addNewAddressButton);
    
        // Create a container for the address cards
        const addressContainer = document.createElement('div');
        modalBody.appendChild(addressContainer);
    
        // Add the prioritized address card first
        if (prioritizedAddress) {
            addressContainer.appendChild(createAddressCard(prioritizedAddress, true));
        }
    
        // Add other address cards with 'Pilih' and 'Jadikan Alamat Utama & Pilih' options
        otherAddresses.forEach(address => {
            addressContainer.appendChild(createAddressCard(address));
        });
    
        // Add search functionality
        const searchInput = searchBar.querySelector('input');
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
            
            const filteredAddresses = addresses.filter(address => {
                return (
                    address.label_name.toLowerCase().includes(query) ||
                    address.street_address.toLowerCase().includes(query) ||
                    address.city.toLowerCase().includes(query) ||
                    address.state.toLowerCase().includes(query) ||
                    address.postal_code.toLowerCase().includes(query)
                );
            });
    
            // Clear the address container and populate it with filtered results
            addressContainer.innerHTML = '';
    
            filteredAddresses.forEach(address => {
                addressContainer.appendChild(createAddressCard(address));
            });
        });
    }
    
    

    function createAddressCard(address) {
        const card = document.createElement('div');
        card.className = 'card mb-3 address-card';
        card.dataset.idShippingAddress = address.id_shipping_address; // Add the id_shipping_address as a data attribute
    
        // Determine if this address is the local selected
        const isSelected = address.id_shipping_address === said;
    
        // Determine card style based on selection
        const cardClass = isSelected ? 'bg-success text-white' : '';
        const checkMark = isSelected ? '<i class="fa-solid fa-check text-white" style="font-size: 24px;"></i>' : '';
    
        // Link color classes based on the theme (text-body adjusts for both light and dark modes)
        const linkClass = isSelected ? 'text-white' : 'text-body';
    
        card.innerHTML = `
            <div class="card-body ${cardClass}">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">${address.label_name} 
                            ${address.is_prioritize ? '<span class="badge bg-warning text-dark">Utama</span>' : ''}
                        </h6>
                        <p class="card-text mb-1">${address.street_address}, ${address.city}, ${address.state}, ${address.postal_code}</p>
                        <p class="card-text">${address.extra_note || ''}</p>
                    </div>
                    <div class="text-end">
                        ${checkMark}
                        ${!isSelected ? `<button type="button" class="btn btn-outline-success mb-2" onclick="selectAddress(${address.id_shipping_address})">Pilih</button>` : ''}
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="#" class="${linkClass}"></a>
                    <div>
                        <a href="${baseUrl}user/${uid}/profile?edit=true&tab=address" class="${linkClass} me-3">Ubah Alamat</a>
                        ${!address.is_prioritize ? `<a href="#" class="${linkClass} me-3" onclick="makePrimaryAddress(${address.id_shipping_address})">Jadikan Alamat Utama & Pilih</a>` : ''}
                    </div>
                </div>
            </div>
        `;
    
        return card;
    }
    

    // Function to select address and update the "Alamat:" field
    window.selectAddress = function(id_shipping_address) {

        const address = addresses.find(addr => addr.id_shipping_address === id_shipping_address); // Find the selected address
        if (!address) return;
        said = id_shipping_address;
        // Update the address info on the page
        const addressField = document.querySelector('.address-details');
        if (addressField) {
            addressField.textContent = `${address.street_address}, Kota ${address.city}, ${address.state}, ${address.postal_code}`;
        }
        
        const extraNoteField = document.querySelector('.extra-note-details span');
        if (extraNoteField) {
            extraNoteField.textContent = address.extra_note ? 
                (address.extra_note.length > 60 ? `${address.extra_note.substring(0, 60)}...` : address.extra_note) 
                : '';
        }
        said = parseInt(id_shipping_address)
        const modalElement = document.getElementById('exampleModalCenter');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
    }

    // Function to make an address primary and select it
    window.makePrimaryAddress = function(id_shipping_address) {

        fetch(apiUrl + '/' + id_shipping_address + '/set-primary', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
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

function ajaxGetToken(transactionData, callback, retryCount = 0) {
    fetch(baseUrl + 'api/prepare-order', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(transactionData)
    })
    .then(response => {
        const contentType = response.headers.get('Content-Type');

        if (response.status === 400) {
            return response.json().then(data => {
                if (data.message && data.message.includes('order_id has already been taken') && retryCount < 3) {
                    return ajaxGetToken(transactionData, callback, retryCount + 1);
                } else {
                    callback(new Error(`Error 400: ${data.message}`), null);
                }
            });
        }

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
        
                    // Prepare data to be sent to finalizeTransferOrder
                    const data = {
                        idOrder: response.idOrder,
                        token: t, // Replace with actual token
                        shippingMethodId: said,
                        cartItems: response.cartItems,
                        products: response.products
                    };
        
                    fetch(baseUrl + 'api/finalize-transfer-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        // Handle success response from finalizeTranferOrder
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
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
