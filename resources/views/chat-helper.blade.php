<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Helper - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .chat-container {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .user-message {
            background-color: #e3f2fd;
            margin-left: 20px;
            border-left: 4px solid #2196F3;
        }
        .ai-message {
            background-color: #f1f8e9;
            margin-right: 20px;
            border-left: 4px solid #8bc34a;
        }
        .message-form {
            display: flex;
            gap: 10px;
        }
        .message-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .send-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .send-button:hover {
            background-color: #45a049;
        }
        .message-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .loading {
            text-align: center;
            padding: 10px;
            font-style: italic;
            color: #666;
            display: none;
        }
        .error {
            color: #f44336;
            margin-top: 10px;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
            display: none;
        }
        .home-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2196F3;
            text-decoration: none;
        }
        .home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chat with PHP Assistant</h1>

        <div class="chat-container" id="chatContainer">
            <div class="message ai-message">
                <div class="message-header">PHP Assistant:</div>
                <div>Hello! I'm your PHP development assistant. Ask me any questions about PHP or web development.</div>
            </div>
        </div>

        <div class="loading" id="loading">Thinking...</div>
        <div class="error" id="errorMessage"></div>

        <form class="message-form" id="messageForm">
            <input type="text" class="message-input" id="messageInput" placeholder="Type your question here..." required>
            <button type="submit" class="send-button">Send</button>
        </form>

        <a href="{{ route('home') }}" class="home-link">Back to Home</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const chatContainer = document.getElementById('chatContainer');
            const loading = document.getElementById('loading');
            const errorMessage = document.getElementById('errorMessage');

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message) return;

                // Add user message to chat
                addMessage('You', message, 'user-message');

                // Clear input
                messageInput.value = '';

                // Show loading indicator
                loading.style.display = 'block';
                errorMessage.style.display = 'none';

                // Send message to server
                fetch('{{ route("chat-helper.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading indicator
                    loading.style.display = 'none';

                    // Add AI response to chat
                    if (data.response) {
                        addMessage('PHP Assistant', data.response, 'ai-message');
                    } else if (data.error) {
                        showError(data.error);
                    }
                })
                .catch(error => {
                    // Hide loading indicator
                    loading.style.display = 'none';

                    // Show error
                    showError('Failed to send message: ' + error.message);
                });
            });

            function addMessage(sender, content, className) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message ' + className;

                const headerDiv = document.createElement('div');
                headerDiv.className = 'message-header';
                headerDiv.textContent = sender + ':';

                const contentDiv = document.createElement('div');
                contentDiv.textContent = content;

                messageDiv.appendChild(headerDiv);
                messageDiv.appendChild(contentDiv);

                chatContainer.appendChild(messageDiv);

                // Scroll to bottom
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
