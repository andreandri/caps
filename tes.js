fetch('book_seat.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ seat })
})
.then(response => response.json())
.then(data => {
    console.log(data);  // Tambahkan ini untuk melihat respons di console browser
    if (data.success) {
        seatElement.classList.add('booked');
        alert('Seat booked successfully!');
    } else {
        alert(data.message || 'Failed to book seat.');
    }
})
.catch(error => console.error('Error:', error));
