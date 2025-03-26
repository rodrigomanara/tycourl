import Chart from "https://cdn.jsdelivr.net/npm/chart.js";


export  function graphic(){

    const barCtx = document.getElementById('barChart').getContext('2d');
    const lineCtx = document.getElementById('lineChart').getContext('2d');

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Bar Chart Example',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4caf50', '#9966cc', '#ff9800'],
            }]
        },
    });

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Line Chart Example',
                data: [3, 10, 5, 8, 2, 6],
                borderColor: '#36a2eb',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true
            }]
        },
    });


}
