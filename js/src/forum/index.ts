import app from 'flarum/forum/app';

app.initializers.add('nodeloc/flarum-telegram-notification', () => {
  console.log('[nodeloc/flarum-telegram-notification] Hello, forum!');
});
