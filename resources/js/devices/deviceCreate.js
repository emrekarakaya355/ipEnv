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
