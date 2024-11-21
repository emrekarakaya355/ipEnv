document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('deviceCreateForm');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        let formData = new FormData(form);
        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı mesajını göster
                    document.querySelector('.messages').innerHTML = '<div class="bg-green-500 text-white p-4 rounded-md mb-4">' + data.message + '</div>';
                    setTimeout(() => {
                        //window.location.href = `/devices/${data.id}`;
                    }, 1000); // 2 saniye sonra yönlendirme
                    form.reset();
                } else {
                    // Hata mesajını göster
                    document.querySelector('.messages').innerHTML = '<div class="bg-red-500 text-white p-4 rounded-md mb-4">' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Hata:', error.message);
                document.querySelector('.messages').innerHTML = '<div class="bg-red-500 text-white p-4 rounded-md mb-4">Bir hata oluştu.</div>';
            });
    });
});


document.getElementById('mac_address').addEventListener('input', function (e) {
    let value = e.target.value;

    // Sadece hex karakterleri tut (0-9, A-F, a-f)
    value = value.replace(/[^a-fA-F0-9]/g, '');

    let formattedValue = '';
    for (let i = 0; i < value.length; i += 2) {
        formattedValue += value.slice(i, i + 2);
        if (i + 2 < value.length) {
            formattedValue += ':';
        }
    }

    // Maksimum uzunluğu sınırla (17 karakter - 12 hex, 5 :)
    formattedValue = formattedValue.slice(0, 17);

    e.target.value = formattedValue.toUpperCase(); // Büyük harf formatı
});
document.getElementById('ip_address').addEventListener('input', function (e) {
    let value = e.target.value;

    // Sadece sayıları ve noktayı kabul et
    value = value.replace(/[^0-9]/g, '');

    // 3 hanelik gruplara ayırmak için bir dizi oluştur
    const segments = [];
    for (let i = 0; i < value.length; i += 3) {
        segments.push(value.slice(i, i + 3));
    }
    // Her grup arasına nokta ekleyin
    value = segments.join('.');


    if (value.length > 15) {
        value = value.slice(0, 15);  // Maksimum 15 karakteri geçmesin
    }

    // Güncellenmiş değeri input alanına yaz
    e.target.value = value;
});
