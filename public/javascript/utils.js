//ping check if user was already logged in


class Utils {

    getUserId() {
        return window.localStorage.getItem('user_id');

    }

    getToken() {
        return window.localStorage.getItem('token');
    }

    /**
     *
     * @returns {boolean}
     */
    isLoggedIn() {
        fetch('/rest/api/ping', {
            method: 'get',
            headers: {
                'content-type': 'application/json',
                'Authorization': `Bearer ${this.getToken()}`
            },
        }).then(response => response.json()).then(data => {
            if (data.response.success === true) (window.location.assign('/dashboard'));
        });
        return false;
    }

    isLoggedOut() {
        fetch('/rest/api/ping', {
            method: 'get',
            headers: {
                'content-type': 'application/json',
                'Authorization': `Bearer ${this.getToken()}`
            },
        }).then(response => response.json()).then(data => {
            if (data.response.success === false) (window.location.assign('/'));
        });
        return false;
    }

}


/**
 *
 * @param url
 */
function ask(url , hash){

    let refUrl = encodeURIComponent(window.location.href);
    if(confirm('Are you sure?'))
        window.location.assign(`${url}?ref=${refUrl}&hash=${hash}`);
}