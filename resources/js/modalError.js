import Swal from 'sweetalert2';

export const modalError = (text) => {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: text,
    });
};

export const showModal = (form, text) => {
    setTimeout(function() {
        if (Object.keys(form.errors).length > 0) {
            modalError(text);
        }
    }, 200);
};
