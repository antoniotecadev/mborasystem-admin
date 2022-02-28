import React from 'react';
import { render } from 'react-dom';
import { InertiaProgress } from '@inertiajs/progress';
import * as Sentry from '@sentry/browser';
import { createInertiaApp } from '@inertiajs/inertia-react';
import Echo from 'laravel-echo';

InertiaProgress.init({
  color: '#ED8936',
  showSpinner: true
});

Sentry.init({
  dsn: process.env.MIX_SENTRY_LARAVEL_DSN
});

// inicializar a estrutura do lado do cliente com o componente base da Inércia.
createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props }) {
    render(
      <App {...props} />
    , el)
  },
})
// criar uma nova instância Echo
window.Pusher = require('pusher-js');
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
});


