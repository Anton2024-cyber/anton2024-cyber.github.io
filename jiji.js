document.getElementById('userForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы

    const formData = new FormData(this);
    const jsonData = {};

    formData.forEach((value, key) => {
        jsonData[key] = value;
    });

    fetch('log.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        // Обработка ответа от сервера
        if (data.error) {
            document.getElementById('response').innerText = data.error;
        } else {
            // Перенаправление на конкретный URL после успешной отправки
            window.location.href = project.html; // Указываем конкретный URL
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
});
