import React, {useState} from 'react';
import Icon from '@/Shared/Icon';
import { InertiaLink } from '@inertiajs/inertia-react';

export default () => {
const [menuOpened, setMenuOpened] = useState(false);
const [notificationOpened, setNotificationOpened] = useState(false);
  return (
    <div className="relative mb-4">
        <div
          className="flex items-center cursor-pointer select-none group"
          onClick={() => setNotificationOpened(true)}>
          <Icon
            className="w-5 h-5 text-gray-800 fill-current group-hover:text-indigo-600 focus:text-indigo-600"
            name="notificacao"
          />
          Notificações
          <div className="absolute bg-indigo-100 text-white whitespace-nowrap group-hover:text-indigo-600 focus:text-indigo-600 mb-6" style={{backgroundColor: 'red', paddingRight: '5px', borderRadius: '10px'}}>
            <span className="ml-1">{localStorage.getItem('notificacao_registo') && localStorage.getItem('notificacao_registo') }</span>
          </div>
        </div>
        <div className={notificationOpened ? '' : 'hidden'}>
          <div className="absolute top-0 right-0 left-auto z-20 py-2 mt-8 text-sm whitespace-nowrap bg-white rounded shadow-xl">
            <InertiaLink
              href={route('contacts.notification', 4)}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setMenuOpened(false)}
            >
              <>
                Registo <span className="ml-1 absolute bg-indigo-100 text-white whitespace-nowrap group-hover:text-indigo-600 focus:text-indigo-600 mb-6" style={{backgroundColor: 'red', paddingRight: '5px', borderRadius: '10px'}}>{localStorage.getItem('notificacao') && localStorage.getItem('notificacao') }</span>
              </>
            </InertiaLink>
            <InertiaLink
              href={route('users')}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setMenuOpened(false)}
            >
              Pagamento
            </InertiaLink>
          </div>
          <div
            onClick={() => {
              setNotificationOpened(false);
            }}
            className="fixed inset-0 z-10 bg-black opacity-25"
          ></div>
        </div>
      </div>
  );
};
