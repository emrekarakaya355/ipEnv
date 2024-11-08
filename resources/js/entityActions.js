window.handleSave= function handleSave(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) {
        form.reportValidity();
        return; // Stop the form submission if validation fails
    }
    const formData = new FormData(form);

    let url = form.action;
    // CSRF token is already included in the formData
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Girdiğiniz verileri kontrol ediniz.');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('saved successfully:', data);
            closeLocationModal();
            toastr.success(data.message || 'Başarı ile kaydedildi.');
            setTimeout(() =>
                location.reload(),1500);
        })
        .catch(error => {
            // Log error to console
            console.error('Error saving: ', error);
            // Show error message to user
            toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
        });
}

window.handleDelete = function handleDelete(url,redirectUrl = null) {
    if (confirm('Silmek istediğinizden emin misiniz?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    setTimeout(() => {
                        if(redirectUrl){
                            window.location.href = redirectUrl;
                        }
                        else
                            window.location.reload();
                    }, 2000);
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                toastr.error('Silme işlemi sırasında bir hata oluştu.');
            });
    }
}
