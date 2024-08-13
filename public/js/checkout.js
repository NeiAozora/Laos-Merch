document.addEventListener('DOMContentLoaded', function() {
    const shippingMethodSelect = document.getElementById('shipping_method');
    const paymentMethodRadios = document.getElementsByName('payment_method');
    const submitButton = document.querySelector('button[onclick="submitOrder()"]');

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

    function submitOrder() {
        // Form submission logic here
    }

});
