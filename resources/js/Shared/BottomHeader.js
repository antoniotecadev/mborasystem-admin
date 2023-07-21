import React, { useState, useEffect } from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Icon from '@/Shared/Icon';
import logo from '@/img/logotipo-yoga-original.png';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import firebase from '@/firebase';
import { ref, onChildAdded, query, limitToLast } from "firebase/database";
import { useLocalStorage } from 'react-use';
import { isEmpty } from 'lodash';

export default () => {
  const { auth } = usePage().props;
  const [forceupdate, setForceUpdate] = useState(0)
  const [menuOpened, setMenuOpened] = useState(false);
  const [notificationOpened, setNotificationOpened] = useState(false);

  const [value, setValue] = useLocalStorage('imei');

  const zerarNotificacao = () => {
    localStorage.setItem("notificacao_registo", Number.parseInt(0));
    setForceUpdate(forceupdate + 1);
    setNotificationOpened(false);
  }

  useEffect(() => {
    const empresa = query(ref(firebase, 'empresas'), limitToLast(1));
    /* OUVIR EVENTOS DO REALTIME DATABASE */
    onChildAdded(empresa, (snapshot) => {
      if (snapshot.exists()) {
        const imei = snapshot.val().imei;
        const id = snapshot.val().id;

        if (imei != value && isEmpty(id)) {
          toast.success("Empresa: " + imei + " registada.", {
            toastId: imei
          });
          const notsize = Number.parseInt(localStorage.getItem('notificacao_registo'));
          localStorage.setItem("notificacao_registo", (localStorage.getItem('notificacao_registo') ? (notsize + 1) : Number.parseInt(0 + 1)));
          setValue(imei);
        }
      } else {
        toast.warning("Sem dados de notificação");
      }
    });

    /* OUVIR EVENTOS DO LARAVEL WEBSOCKET */
    window.Echo.channel('contact')
      .listen('CreateContactEvent', (e) => {
        toast.success("Empresa de " + e.first_name + " " + e.last_name + " registada pela equipa YOGA " + e.codigo_equipa + "\nIMEI: " + e.imei, {
          toastId: e.id
        });
        const notsize = Number.parseInt(localStorage.getItem('notificacao_registo'));
        localStorage.setItem("notificacao_registo", (localStorage.getItem('notificacao_registo') ? (notsize + 1) : Number.parseInt(0 + 1)));
      });

    return () => ref(firebase, 'empresas').off();
  }, []);

  return (
    <div className="flex items-center justify-between w-full p-4 text-sm bg-white border-b md:py-0 md:px-12 d:text-md">
      <div><img className="text-white fill-current" width="120" height="28" src={`./${logo}`} alt='sem foto' /></div>
      <div className="mt-1 mr-4 font-bold">{auth.user.account.name}</div>
      <ToastContainer />
      <div className="relative">
        <div
          className="flex items-center cursor-pointer select-none group"
          onClick={() => setNotificationOpened(true)}
        >
          <Icon
            className="w-5 h-5 text-gray-800 fill-current group-hover:text-indigo-600 focus:text-indigo-600"
            name="notificacao"
          />
          <div className="absolute bg-indigo-100 text-white whitespace-nowrap group-hover:text-indigo-600 focus:text-indigo-600 mb-6" style={{ backgroundColor: 'red', paddingRight: '5px', borderRadius: '10px' }}>
            <span className="ml-1">{localStorage.getItem('notificacao_registo') && localStorage.getItem('notificacao_registo')}</span>
          </div>
        </div>
        <div className={notificationOpened ? '' : 'hidden'}>
          <div className="absolute top-0 right-0 left-auto z-20 py-2 mt-8 text-sm whitespace-nowrap bg-white rounded shadow-xl">
            <InertiaLink
              href={route('contacts.notification', 4)}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setNotificationOpened(false)}
            >
              Registo <span className="ml-1 absolute bg-indigo-100 text-white whitespace-nowrap group-hover:text-indigo-600 focus:text-indigo-600 mb-6" style={{ backgroundColor: 'red', paddingRight: '5px', borderRadius: '10px' }}>{localStorage.getItem('notificacao') && localStorage.getItem('notificacao')}</span>
            </InertiaLink>
            <InertiaLink
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => zerarNotificacao()}
            >
              Zerar
            </InertiaLink>
            <InertiaLink
              href={route('contacts.notification.firebase')}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setNotificationOpened(false)}
            >
              Firebase
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
      <div className="relative">
        <div
          className="flex items-center cursor-pointer select-none group"
          onClick={() => setMenuOpened(true)}
        >
          <div className="mr-1 text-gray-800 whitespace-nowrap group-hover:text-indigo-600 focus:text-indigo-600">
            <span>{auth.user.first_name}</span>
            <span className="hidden ml-1 md:inline">{auth.user.last_name}</span>
          </div>
          <Icon
            className="w-5 h-5 text-gray-800 fill-current group-hover:text-indigo-600 focus:text-indigo-600"
            name="cheveron-down"
          />
        </div>
        <div className={menuOpened ? '' : 'hidden'}>
          <div className="absolute top-0 right-0 left-auto z-20 py-2 mt-8 text-sm whitespace-nowrap bg-white rounded shadow-xl">
            <InertiaLink
              href={route('users.edit', auth.user.id)}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setMenuOpened(false)}
            >
              Perfil
            </InertiaLink>
            <InertiaLink
              href={route('users')}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              onClick={() => setMenuOpened(false)}
            >
              Gestão de Utilizadores
            </InertiaLink>
            <InertiaLink
              as="button"
              href={route('logout')}
              className="block w-full px-6 py-2 text-left focus:outline-none hover:bg-indigo-600 hover:text-white"
              method="post"
            >
              Sair
            </InertiaLink>
          </div>
          <div
            onClick={() => {
              setMenuOpened(false);
            }}
            className="fixed inset-0 z-10 bg-black opacity-25"
          ></div>
        </div>
      </div>
    </div>
  );
};
