export function onLogoClick() {
    document.querySelector('.logo').addEventListener('click', () => {
        window.location.href = './';
    })
}

/**
 *
 * @type {{
 *    __show: alerts.__show
 *  , __hide: alerts.__hide
 *  , success: alerts.success
 *  , warning: alerts.warning
 *  , error: alerts.error
 *  , info: alerts.info}}
 */
export const alerts = {
    error: function (message, call) {
        alerts.__show('error', message, call);
    },
    info: function (message, call) {
        alerts.__show('info', message, call)
    },
    success: function (message, call) {
        alerts.__show('success', message, call)
    },
    warning: function (message, call) {
        alerts.__show('warning', message, call)
    },
    /**
     *
     * @param role
     * @param message
     * @private
     */
    __show: function (role, message, call) {
        let dom = document.querySelector(`[role=${role}]`);
        dom.classList.remove('hidden');
        dom.innerText = `${message}`;

        dom.addEventListener('click', (e) => {
            e.preventDefault();
            alerts.__hide(role);
        })

        //callback
        if (call !== null || call !== undefined)
            setTimeout(() => {
                call()
            }, 2000);

    },
    __hide: function (role) {
        let dom = document.querySelector(`[role=${role}]`);
        dom.classList.add('hidden');
        dom.innerText = ``
    },
}

/**
 *
 * @param element
 * @returns {Promise<void>}
 */
export async function generateShortUrl(element) {

    document.querySelector(element)?.addEventListener('click', async  (e) => {
        e.preventDefault();

        const longUrl = document.getElementById('longUrl').value;
        if (longUrl) {
            let url = window.location.hostname
            // Simple encoding for demonstration

            try {
                //apply logic
                await fetch(`/rest/api/hash/create`, {
                    'method': 'POST',
                    'content-type': 'application/json',
                    'body': JSON.stringify({
                        url: longUrl
                    })
                }).then(data => {
                    document.getElementById('shortUrl').textContent = '';
                    document.getElementById('result').style.display = 'none';
                    document.querySelector('[id=error]').textContent = '';
                    return data.json();
                }).then(res => {
                    let response = res.response;
                    if (response?.data?.hash) {
                        let shortUrl = `http://${url}/` + response.data.hash;
                        document.getElementById('shortUrl').textContent = shortUrl;
                        document.getElementById('shortUrl').href = shortUrl;
                        document.getElementById('result').style.display = 'block';
                    } else {
                        document.querySelector('[id=error]').textContent = response?.errorMessage?.error;
                    }
                }).catch(error => {
                    console.log(error);
                })
            } catch (error) {
                console.log(error)

            }

        } else {

        }

    })
}
