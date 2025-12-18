<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('propertyChart'), {
    type: 'bar',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            data: [{{ $approved }}, {{ $pending }}, {{ $rejected }}],
            backgroundColor: ['#4ade80', '#facc15', '#f87171']
        }]
    }
});
</script>
