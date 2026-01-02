 "use strict";

let paymentMethod = document.querySelector('input[name=paymentMethod]:checked').value;
let showStatus = false;

for (let item in gateway) {
    let itemDiv = document.getElementById(item + '_div');
    if (item === paymentMethod) {
        if (gateway[item]) {
            showStatus = true;
            itemDiv.style.display = 'block';
        }
    } else {
        itemDiv.style.display = 'none';
    }
}

let clickGateway = false;
for (let item in onClickGateway) {
    if (item === paymentMethod) {
        showStatus = true;
        clickGateway = true;
        break;
    }
}

let form = document.getElementById('paymentForm');
if (showStatus) {
    document.getElementById('loading-show').classList.add('hidden');
    document.getElementById('confirmBtn').classList.remove('hidden');
    document.getElementById('backBtn').classList.remove('hidden');

    if (clickGateway) {
        document.getElementById('confirmBtn').classList.add('hidden');
        document.getElementById('backBtn').classList.add('hidden');
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        let submit = false;
        for (let item in submitGateway) {
            if (item === paymentMethod) {
                submit = true;
                window[paymentMethod + '_payment']();
                break;
            }
        }

        if (!submit) {
            form.submit();
        }
    });
} else {
    form.submit();
}
