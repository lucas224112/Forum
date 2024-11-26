const messageInput = document.getElementById('message');
const sendButton = document.getElementById('send');
const refrestButton = document.getElementById('refrest');
const chatDiv = document.getElementById('chat');

async function loadMessages() {
    const response = await fetch('chat.php');
    const data = await response.json();
    const messages = data.messages;

    chatDiv.innerHTML = '';

    messages.forEach(msg => {
        const messageElement = document.createElement('p');
        messageElement.classList.add(msg.self);

        const linkRegex = /(https?:\/\/[^\s]+)/g;
        const messageContent = msg.message;

        if (linkRegex.test(messageContent)) {
            const link = document.createElement('a');
            link.href = messageContent.match(linkRegex)[0];
            link.target = '_blank';
            link.textContent = messageContent;
            link.classList.add('link-style');

            messageElement.innerHTML = `<span>${msg.username}:</span> `;
            messageElement.appendChild(link);
        } else {
            messageElement.innerHTML = `<span>${msg.username}:</span> ${messageContent}`;
        }

        chatDiv.appendChild(messageElement);
    });

    chatDiv.scrollTop = chatDiv.scrollHeight;
}

async function sendMessage() {
    const message = messageInput.value.trim();
    if (!message) return;

    const formData = new FormData();
    formData.append('message', message);

    await fetch('chat.php', {
        method: 'POST',
        body: formData
    });

    messageInput.value = '';
    loadMessages();
}

sendButton.addEventListener('click', sendMessage);
refrestButton.addEventListener('click', loadMessages);

messageInput.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});

loadMessages();