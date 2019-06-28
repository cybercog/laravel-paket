import Swal from 'sweetalert2';

export default {
    computed: {
        Paket() {
            return window.Paket;
        },
    },

    methods: {
        alertSuccess: async (data) => {
            await Swal.fire({
                type: 'success',
                ...data,
            });
        },

        alertInfo: async (data) => {
            await Swal.fire({
                type: 'info',
                ...data,
            });
        },

        alertWarning: async (data) => {
            await Swal.fire({
                type: 'warning',
                ...data,
            });
        },

        alertError: async (exception) => {
            let errorData = {};

            if (exception.response.status === 422) {
                let error;

                for (let errorKey in exception.response.data.errors) {
                    if (exception.response.data.errors.hasOwnProperty(errorKey)) {
                        error = exception.response.data.errors[errorKey][0];
                        break;
                    }
                }

                errorData = {
                    type: 'error',
                    title: 'Validation Error!',
                    text: error,
                };
            } else {
                errorData = {
                    type: 'error',
                    title: 'Error!',
                    text: exception.message,
                };
            }

            await Swal.fire(errorData);
        },
    },
}
