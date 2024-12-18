document.getElementById('command').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();

        var command = this.value;
        var outputDiv = document.getElementById('output');
        var promptDiv = document.createElement('div');
        promptDiv.innerHTML = '<span class="prompt">> </span>' + command;
        outputDiv.appendChild(promptDiv);

        // Kirim perintah ke server untuk dieksekusi
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'command=' + encodeURIComponent(command)
        })
        .then(response => response.text())
        .then(result => {
            var resultDiv = document.createElement('div');
            var preElement = document.createElement('pre');
            preElement.textContent = result; // Menggunakan <pre> untuk menjaga format
            resultDiv.appendChild(preElement);
            resultDiv.classList.add('text-light');
            outputDiv.appendChild(resultDiv);

            // Scroll otomatis ke bawah setelah output ditambahkan
            outputDiv.scrollTop = outputDiv.scrollHeight;
        });

        this.value = ''; // Reset input
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const fileName = this.getAttribute('data-item');
            const fileContent = this.getAttribute('data-content');
            document.getElementById('modal-file-name').value = fileName;
            document.getElementById('modal-file-content').value = fileContent;
        });
    });
});  