class User {

    getId() {
        return window.localStorage.getItem('user_id');

    }

    getToken() {
        return window.localStorage.getItem('token');
    }

    logout() {
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user_id');
        window.location.assign('/login');
    }
}