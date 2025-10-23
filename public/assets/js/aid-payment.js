// Payment Modal UI Interactions (UI Only - No actual payment processing)

document.addEventListener('DOMContentLoaded', function() {
  const paymentModal = document.getElementById('paymentModal');
  const approvePayButtons = document.querySelectorAll('.approve-pay-btn');
  const closeModalBtn = document.querySelector('.payment-modal-close');
  const cancelPaymentBtn = document.querySelector('.cancel-payment-btn');
  const proceedPaymentBtn = document.querySelector('.proceed-payment-btn');

  // Open payment modal when "Approve & Pay" is clicked
  approvePayButtons.forEach(button => {
    button.addEventListener('click', function() {
      const studentName = this.getAttribute('data-student');
      const requestType = this.getAttribute('data-type');
      const amount = this.getAttribute('data-amount');

      // Populate modal with request details
      document.getElementById('paymentStudentName').textContent = studentName;
      document.getElementById('paymentRequestType').textContent = requestType;
      document.getElementById('paymentAmount').textContent = amount;

      // Show modal
      paymentModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  // Close modal functions
  function closeModal() {
    paymentModal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', closeModal);
  }

  if (cancelPaymentBtn) {
    cancelPaymentBtn.addEventListener('click', closeModal);
  }

  // Close modal when clicking outside
  paymentModal.addEventListener('click', function(e) {
    if (e.target === paymentModal) {
      closeModal();
    }
  });

  // Proceed to payment button (UI feedback only)
  if (proceedPaymentBtn) {
    proceedPaymentBtn.addEventListener('click', function() {
      const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
      const amount = document.getElementById('paymentAmount').textContent;
      const studentName = document.getElementById('paymentStudentName').textContent;
      
      // Show demo success message
      proceedPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
      proceedPaymentBtn.disabled = true;
      
      setTimeout(() => {
        alert(`✅ Demo Payment Successful!\n\nAmount: ${amount}\nStudent: ${studentName}\nMethod: ${selectedMethod.toUpperCase()}\n\n(Backend integration required for actual payment processing)`);
        closeModal();
        
        // Reset button
        proceedPaymentBtn.innerHTML = '<i class="fas fa-lock"></i> Proceed to Pay';
        proceedPaymentBtn.disabled = false;
      }, 1500);
    });
  }

  // Payment method card number formatting
  const cardNumberInput = document.querySelector('.card-input-wrapper input');
  if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\s/g, '');
      let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
      e.target.value = formattedValue;
    });
  }

  // Expiry date formatting (MM/YY)
  const expiryInput = document.querySelector('input[placeholder="MM/YY"]');
  if (expiryInput) {
    expiryInput.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
      }
      e.target.value = value;
    });
  }

  // CVV - numbers only
  const cvvInput = document.querySelector('input[placeholder="123"]');
  if (cvvInput) {
    cvvInput.addEventListener('input', function(e) {
      e.target.value = e.target.value.replace(/\D/g, '');
    });
  }
});

