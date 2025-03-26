import {alerts} from "./global.js";

const register = {
    create: async function () {

        const form = document.querySelector('form');
        const submitter = document.querySelector("button[type=button]");

        submitter.addEventListener('click', async (e) => {
            const formData = new FormData(form);

            let stop = false;

            let data = {};
            for (const [key, value] of formData) {

                let field = document.querySelector(`input[name=${key}]`);

                if (!field.checkValidity()) {
                    field.reportValidity();
                    e.preventDefault(); // Prevent form submission
                    break;
                }
                if(value === '') {
                    field.style.border = '1px solid red';
                    stop = true;
                }else{
                    field.style.border = '1px solid green';
                }

                data[`${key}`] = value;
            }
            //check if any field is empty
            if(stop)
                return;


            let res = await fetch(`/rest/api/register/create`, {
                'method': 'POST',
                'content-type': 'application/json',
                'body': JSON.stringify(data)

            }).then(response => {
                return response.json();
            });

            if (res.response?.data) {
                alerts.success("Data save successfully" , () => {
                    window.location.href = '/login'
                });
            }else{
                alerts.error("Something happen");
            }
        })
    }
}

export {register};
