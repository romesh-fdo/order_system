function makeAjaxRequest(formData, url, buttonProps = null, configs = {}) {
    return new Promise((resolve, reject) => {
        const defaultConfigs = {
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            xhrFields: {
                withCredentials: true
            },
            headers: {
                'Accept': 'application/json'
            }
        };

        const ajaxConfigs = {
            ...defaultConfigs,
            ...configs,
            url,
            data: formData,
            dataType: 'JSON'
        };

        if (buttonProps) {
            const { id, process_text, text } = buttonProps;
            const button = $(`#${id}`);
            button.prop("disabled", true).html(process_text);
        }

        $.ajax(ajaxConfigs)
            .done((response) => {
                if (buttonProps) {
                    const { id, text } = buttonProps;
                    const button = $(`#${id}`);
                    button.prop("disabled", false).html(text);
                }

                handleAjaxResponse(response);
                resolve(response);
            })
            .fail((jqXHR) => {
                if (buttonProps) {
                    const { id, text } = buttonProps;
                    const button = $(`#${id}`);
                    button.prop("disabled", false).html(text);
                }

                showNotification('error', 'Something went wrong when processing your request. Please try again');
                reject(jqXHR);
            });
    });
}

function showNotification(status, message) {
    new Notify({
        status,
        title: status.charAt(0).toUpperCase() + status.slice(1),
        text: message,
        effect: 'fade',
        speed: 300,
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 5000,
        gap: 20,
        distance: 20,
        position: 'right bottom',
    });
}

function handleAjaxResponse(response) {
    //console.log(response);
    if (response.success) {
        if (response.notify) {
            showNotification('success', response.message);
        }
        if (response.redirect) {
            setTimeout(() => { $(location).prop('href', response.redirect); }, 2000);
        }
        if (response.reload) {
            setTimeout(() => { location.reload(); }, 2000);
        }
    } else {
        if (response.notify) {
            showNotification('warning', response.message);
        }

        if(response.validate_errors)
        {
            displayValidationErrors(response.validate_errors);
        }
    }
}

function displayValidationErrors(errors) {
    Object.keys(errors).forEach(field => {
        const warning = errors[field].join("\n");
        $(`#error-add-${field}`).text(warning);
        $(`#error-edit-${field}`).text(warning);
    });
}

function handleChange(field_name) {
    $('#error-' + field_name).text('');
}
