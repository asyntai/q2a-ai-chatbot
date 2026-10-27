# Asyntai AI Chatbot for Question2Answer

[Asyntai](https://asyntai.com) adds an AI chatbot to your Question2Answer (Q2A) site. It answers visitor questions from your own pages, 24/7, in more than 80 languages, so visitors get help before they post a new question.

Full guide: [Asyntai AI chatbot for Question2Answer](https://asyntai.com/documentation/installation/question2answer/)

## Install

1. Download this repository (Code > Download ZIP) and copy its `asyntai-ai-chatbot` folder into the `qa-plugin` folder of your Q2A site.
2. Log in as the super administrator and open **Admin > Plugins**.
3. Find **Asyntai AI Chatbot**, tick **Enabled** and click **Save Options**.
4. Click **options** in the Asyntai AI Chatbot box.
5. Create a free account at [asyntai.com](https://asyntai.com), open the Install page of your [Asyntai dashboard](https://asyntai.com/dashboard) and copy your Widget ID, for example `asyntai_1a2b3c4d5e6f`.
6. Paste the Widget ID (or the whole embed code) and click **Save Changes**.

The chat bubble now shows on every page of your site.

## Settings

- **Widget ID**: your Asyntai Widget ID. Nothing loads without a valid ID.
- **Show the chatbot on the site**: switch the chatbot on or off without disabling the plugin.
- **Pages without the chatbot**: page types or paths, separated by commas, for example `ask, login, register`. Leave this empty to show the chatbot everywhere. Admin pages never show it.

## How it works

The plugin adds one small script tag before the closing `</body>` tag of each public page through a Q2A theme layer, so your theme does not change. If the Asyntai script is already in your Admin > Layout custom HTML, the plugin does not add it twice.

Works with Question2Answer 1.8 (tested on 1.8.6 and 1.8.8).

## License

MIT
