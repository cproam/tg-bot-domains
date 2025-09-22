# Telegram Domain Expiry Bot

This is a Telegram bot that checks domain registration expiry dates using Timeweb API.

## Environment Variables

To run this bot, you need to set up the following environment variables:

1. `TELEGRAM_BOT_TOKEN`: Your Telegram bot token, which you can obtain by talking to the [BotFather](https://core.telegram.org/bots#botfather) on Telegram.
2. `TIMEWEB_LOGIN`: Your Timeweb login.
3. `TIMEWEB_PASSWORD`: Your Timeweb password.
4. `TIMEWEB_APPKEY`: Your Timeweb app key.
5. `LIST_DOMAINS`: A comma-separated list of domains you want to check, for example, `example.com,google.com,test.ru`.

## Setup

1. Clone this repository.
2. Create a `.env` file in the root directory with the following content:

   ```
   TELEGRAM_BOT_TOKEN=your_telegram_bot_token_here
   TIMEWEB_LOGIN=your_timeweb_login_here
   TIMEWEB_PASSWORD=your_timeweb_password_here
   TIMEWEB_APPKEY=your_timeweb_appkey_here
   LIST_DOMAINS=example.com,google.com,test.ru
   ```