


(function () {
    let user = new User();
    let user_id = user.getId();
    let token = user.getToken();
    function setWelComeMessage(data) {
        let showWelcomeBox = document.querySelector('#userName')
        showWelcomeBox.classList.remove('hidden');
        showWelcomeBox.innerHTML = showWelcomeBox.innerHTML.replace('userName', data.full_name);
    }

    fetch(`/rest/api/users/retrieve/${user_id}`, {
        headers: {
            'content-type': 'application/json', 'Authorization': `Bearer ${token}`
        }, method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            if (data.response.success === false) user.logout();
            setWelComeMessage(data.response.data);
        })
        .catch(error => {
            //logout();
        });
})();



// Devices Chart (Doughnut)
const ctx2 = document.getElementById('devicesChart').getContext('2d');
const devicesChart = new Chart(ctx2, {
    type: 'doughnut', data: {
        labels: ['Mobile', 'Desktop', 'Tablet'], datasets: [{
            data: [58, 35, 7],
            backgroundColor: ['rgb(59, 130, 246)', 'rgb(168, 85, 247)', 'rgb(249, 115, 22)'],
            borderWidth: 0
        }]
    }, options: {
        responsive: true, maintainAspectRatio: true, plugins: {
            legend: {
                position: 'bottom', labels: {
                    padding: 20, font: {
                        size: 12
                    }
                }
            }
        }
    }
});
