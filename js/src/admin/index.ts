import app from 'flarum/admin/app';

app.initializers.add('nodeloc-telegram-notification', () => {
  app.extensionData
    .for('nodeloc-telegram-notification')
    .registerSetting({
      setting: 'telegram.bot_token',
      label: 'Telegram Bot Token',
      type: 'text',
    })
    .registerSetting({
      setting: 'telegram.channel_id',
      label: 'Telegram Channel ID',
      type: 'text',
    })
    .registerSetting({
    setting: 'telegram.excluded_tags',
    label: 'Exclude tags',
    help: 'Input exclude tabs：1,2,3',
    type: 'text',
  });
});
