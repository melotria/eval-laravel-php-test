# Chat Helper Feature

This feature adds a simple chat interface where users can ask questions about PHP and web development, and get responses from an AI assistant.

## Features

- Simple chat interface with plain HTML and JavaScript
- No authentication required - all messages are public
- Integration with OpenAI's GPT model for intelligent responses
- Focused on PHP and web development questions

## Setup

1. Make sure you have an OpenAI API key. If you don't have one, you can get it from [OpenAI's website](https://platform.openai.com/).

2. Add your OpenAI API key to the `.env` file:
   ```
   OPENAI_API_KEY=your_api_key_here
   ```

3. The chat helper is available at the `/chat-helper` route.

## How It Works

1. Users can visit the `/chat-helper` page without authentication
2. They can type their PHP or web development questions in the input field
3. The question is sent to the server, which forwards it to OpenAI's API
4. The AI response is displayed in the chat interface

## Technical Implementation

- The feature uses a simple Blade template for the frontend (no React)
- JavaScript fetch API is used to communicate with the backend
- The backend uses Laravel's HTTP client to communicate with OpenAI
- The AI is instructed to only answer PHP and web development questions

## Limitations

- The AI will respond with "I can't help you" for questions not related to PHP or web development
- All messages are public and visible to everyone
- There's no message history persistence (messages are lost on page refresh)
