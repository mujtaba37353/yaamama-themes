function formatCardNumber(input) {

    let value = input.value.replace(/\D/g, '');

    let formattedValue = value.match(/.{1,4}/g);

    if (formattedValue) {
        input.value = formattedValue.join(' ');
    } else {
        input.value = "";
    }
}
function switchPaymentMethod(method) {
    const creditOption = document.getElementById('credit-card-option');
    const stcOption = document.getElementById('stc-pay-option');
    const creditForm = document.getElementById('credit-card-form');
    const stcForm = document.getElementById('stc-pay-form');

    const badgeHTML = '<div class="check-badge"><i class="fa-solid fa-check"></i></div>';

    creditOption.classList.remove('active');
    stcOption.classList.remove('active');
    document.querySelectorAll('.payment-option .check-badge').forEach(b => b.remove());

    if (method === 'credit-card') {
        creditOption.classList.add('active');
        creditOption.insertAdjacentHTML('afterbegin', badgeHTML);
        creditForm.style.display = 'block';
        stcForm.style.display = 'none';
    } else if (method === 'stc-pay') {
        stcOption.classList.add('active');
        stcOption.insertAdjacentHTML('afterbegin', badgeHTML);
        creditForm.style.display = 'none';
        stcForm.style.display = 'block';
    }
}